<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items', 'toko'])
            ->orderBy('created_at', 'desc');

        // Filter by toko
        if ($request->filled('toko_id')) {
            $query->where('toko_id', $request->toko_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20);

        // Get all tokos for filter
        $tokos = Toko::orderBy('nama_toko')->get();

        // 🆕 Stats updated (pakai status baru)
        $stats = [
    // Order Status
    'total' => Order::count(),
    'pending' => Order::pending()->count(),        // Menunggu pembayaran
    'paid' => Order::paid()->count(),              // Sudah dibayar
    'shipped' => Order::shipped()->count(),        // Dikirim
    'completed' => Order::completed()->count(),    // Selesai
    'cancelled' => Order::cancelled()->count(),    // Dibatalkan
    
    // Payment Status (tambahan insight)
    'unpaid' => Order::where('payment_status', 'unpaid')->count(),
    'payment_paid' => Order::where('payment_status', 'paid')->count(),
];

        return view('superadmin.orders.index', compact('orders', 'tokos', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'items.product', 'toko', 'statusLogs.changedBy']);

        return view('superadmin.orders.show', compact('order'));
    }

    // 🆕 UPDATE: Confirm Payment (PENDING → PAID) - MANUAL
    public function confirmPayment(Request $request, Order $order)
    {
        // Validasi: Hanya bisa confirm jika status PENDING dan unpaid
        if (!$order->canBeConfirmedPayment()) {
            return back()->with('error', 
                'Pembayaran tidak dapat dikonfirmasi. Status order: ' . $order->status_label
            );
        }

        try {
            DB::beginTransaction();

            $oldStatus = $order->status;

            // Update payment_status dan status order
            $order->update([
                'payment_status' => Order::PAYMENT_PAID,
                'status' => Order::STATUS_PAID
            ]);

            // Log status change
            $order->statusLogs()->create([
                'status_from' => $oldStatus,
                'status_to' => Order::STATUS_PAID,
                'changed_by' => auth()->id(),
                'notes' => $request->notes ?? 'Pembayaran dikonfirmasi oleh admin'
            ]);

            DB::commit();

            return back()->with('success', 'Pembayaran berhasil dikonfirmasi!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 🆕 UPDATE: Update Status dengan validasi transition
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
            'resi_number' => 'required_if:status,shipped|nullable|string|max:255',
            'courier_name' => 'nullable|string|max:255',
            'courier_phone' => 'nullable|string|max:20',
            'shipping_notes' => 'nullable|string',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Validasi status transition
        if (!$order->isValidStatusTransition($newStatus)) {
            return back()->with('error', 
                'Perubahan status dari ' . $order->status_label . 
                ' ke status baru tidak diizinkan.'
            );
        }

        try {
            DB::beginTransaction();

            $additionalData = [];

            // Jika status SHIPPED, update data pengiriman
            if ($newStatus === Order::STATUS_SHIPPED) {
                $additionalData['resi_number'] = $request->resi_number;
                $additionalData['courier_name'] = $request->courier_name;
                $additionalData['courier_phone'] = $request->courier_phone;
                $additionalData['shipping_notes'] = $request->shipping_notes;
                $additionalData['shipped_at'] = now();
            }

            // Update status
            $order->update(array_merge(['status' => $newStatus], $additionalData));

            // Log status change
            $order->statusLogs()->create([
                'status_from' => $oldStatus,
                'status_to' => $newStatus,
                'changed_by' => auth()->id(),
                'notes' => $request->notes ?? 'Status diubah oleh admin'
            ]);

            DB::commit();

            return back()->with('success', 'Status order berhasil diubah!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 🆕 NEW: Cancel order dengan reason
    public function cancel(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        // Validasi: Admin tidak bisa cancel order yang sudah COMPLETED
        if (!$order->canBeCancelledByAdmin()) {
            return back()->with('error', 
                'Order yang sudah selesai tidak dapat dibatalkan.'
            );
        }

        try {
            DB::beginTransaction();

            $oldStatus = $order->status;

            // Update status ke CANCELLED
            $order->update(['status' => Order::STATUS_CANCELLED]);

            // Log status change
            $order->statusLogs()->create([
                'status_from' => $oldStatus,
                'status_to' => Order::STATUS_CANCELLED,
                'changed_by' => auth()->id(),
                'notes' => 'Dibatalkan oleh admin. Alasan: ' . $request->reason
            ]);

            // TODO: Notifikasi ke customer (via live chat - menyusul)
            // TODO: Proses refund jika status PAID

            DB::commit();

            return back()->with('success', 'Order berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}