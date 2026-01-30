<?php

namespace App\Http\Controllers\KepalaToko;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $toko = auth()->user()->toko;
        
        if (!$toko) {
            abort(403, 'Anda tidak memiliki toko.');
        }

        $query = Order::with(['customer', 'items', 'toko'])
            ->forToko($toko->id)
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by order number or customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20);

        // ✅ FIXED: Stats untuk cards - ganti 'processing' jadi 'paid'
        $stats = [
            'pending' => Order::forToko($toko->id)->pending()->count(),
            'paid' => Order::forToko($toko->id)->paid()->count(), // ✅ FIXED: processing → paid
            'shipped' => Order::forToko($toko->id)->shipped()->count(),
            'completed' => Order::forToko($toko->id)->completed()->count(),
        ];

        return view('kepala-toko.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $toko = auth()->user()->toko;

        // Validasi ownership
        if ($order->toko_id !== $toko->id) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        $order->load(['customer', 'items.product', 'toko', 'statusLogs.changedBy']);

        return view('kepala-toko.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $toko = auth()->user()->toko;

        // Validasi ownership
        if ($order->toko_id !== $toko->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke order ini.');
        }

        // ✅ FIXED: Validasi status - ganti 'processing' jadi 'paid'
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled', // ✅ FIXED
            'resi_number' => 'required_if:status,shipped',
            'courier_name' => 'nullable|string|max:255',
            'courier_phone' => 'nullable|string|max:20',
            'shipping_notes' => 'nullable|string',
        ]);

        $additionalData = [];

        // Jika status shipped, wajib isi resi
        if ($request->status === 'shipped') {
            $additionalData['resi_number'] = $request->resi_number;
            $additionalData['courier_name'] = $request->courier_name;
            $additionalData['courier_phone'] = $request->courier_phone;
            $additionalData['shipping_notes'] = $request->shipping_notes;
        }

        $order->updateStatus(
            $request->status,
            auth()->id(),
            $request->notes,
            $additionalData
        );

        return back()->with('success', 'Status order berhasil diupdate!');
    }

    // 🆕 Konfirmasi Pembayaran Manual
    public function confirmPayment(Order $order)
    {
        $toko = auth()->user()->toko;

        // Validasi ownership
        if ($order->toko_id !== $toko->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke order ini.');
        }

        if ($order->payment_status !== 'unpaid') {
            return back()->with('error', 'Order sudah dibayar atau tidak valid!');
        }

        $order->payment_status = 'paid';
        
        // ✅ FIXED: Auto update status ke 'paid' jika masih pending
        if ($order->status === 'pending') {
            $order->updateStatus('paid', auth()->id(), 'Pembayaran dikonfirmasi oleh Kepala Toko'); // ✅ FIXED
        } else {
            $order->save();
        }

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }
}