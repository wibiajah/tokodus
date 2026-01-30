<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $customer = auth('customer')->user();

        $query = Order::with([
            'items.product',
            'items.variant.parent',
            'toko',
            'statusLogs'
        ])
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhere('payment_reference', 'like', '%' . $request->search . '%');
            });
        }

        $allOrders = $query->get();

        // Group by payment_reference
        $groupedOrders = $allOrders->groupBy(function ($order) {
            return $order->payment_reference ?: 'single_' . $order->id;
        });

        // Transform to pagination-friendly format
        $orderGroups = $groupedOrders->map(function ($orders, $reference) {
            $firstOrder = $orders->first();

            return (object) [
                'reference' => $reference,
                'is_combined' => !str_starts_with($reference, 'single_'),
                'orders' => $orders,
                'order_count' => $orders->count(),
                'total_items' => $orders->sum(function ($order) {
                    return $order->items->sum('quantity');
                }),
                'total_amount' => $orders->sum('total'),
                'status' => $firstOrder->status,
                'payment_status' => $firstOrder->payment_status,
                'created_at' => $firstOrder->created_at,
                'formatted_date' => $firstOrder->formatted_created_at,
                'first_order_id' => $firstOrder->id,
            ];
        })->sortByDesc('created_at')->values();

        // Manual pagination
        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $total = $orderGroups->count();

        $paginatedGroups = $orderGroups->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedGroups,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('frontend.orders.index', compact('orders'));
    }

    public function show(Request $request, $identifier)
    {
        $customer = auth('customer')->user();

        // Check if identifier is payment_reference or order_id
        if (str_starts_with($identifier, 'PAY-')) {
            $orders = Order::with([
                'items.product',
                'items.variant.parent',
                'toko',
                'pickupToko',
                'voucherUsages.voucher',
                'statusLogs.changedBy',
                'reviews' // 🆕 Load reviews
            ])
                ->where('payment_reference', $identifier)
                ->where('customer_id', $customer->id)
                ->get();

            if ($orders->isEmpty()) {
                abort(404, 'Pesanan tidak ditemukan');
            }

            $isCombined = true;
            $totalPayment = $orders->sum('total');
            $paymentReference = $identifier;
        } else {
            $order = Order::with([
                'items.product',
                'items.variant.parent',
                'toko',
                'pickupToko',
                'voucherUsages.voucher',
                'statusLogs.changedBy',
                'reviews' // 🆕 Load reviews
            ])
                ->where('id', $identifier)
                ->where('customer_id', $customer->id)
                ->first();

            if (!$order) {
                abort(404, 'Pesanan tidak ditemukan');
            }

            $orders = collect([$order]);
            $isCombined = false;
            $totalPayment = $order->total;
            $paymentReference = $order->payment_reference;
        }

        return view('frontend.orders.show', compact('orders', 'isCombined', 'totalPayment', 'paymentReference'));
    }

    // 🆕 UPDATE: Cancel hanya untuk status PENDING
    public function cancel(Request $request, Order $order)
    {
        // Validasi 1: Order milik customer yang login
        if ($order->customer_id !== auth('customer')->id()) {
            abort(403, 'Unauthorized');
        }

        // Validasi 2: Hanya bisa cancel jika status PENDING
        if (!$order->canBeCancelledByCustomer()) {
            return back()->with(
                'error',
                'Order tidak dapat dibatalkan. Status saat ini: ' . $order->status_label .
                    '. Jika sudah melakukan pembayaran dan ingin membatalkan, silakan gunakan fitur "Laporkan Masalah".'
            );
        }

        // Validasi alasan (opsional - boleh kosong)
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $oldStatus = $order->status;

        // Update status
        $order->update(['status' => Order::STATUS_CANCELLED]);

        // Log status change
        $order->statusLogs()->create([
            'status_from' => $oldStatus,
            'status_to' => Order::STATUS_CANCELLED,
            'changed_by' => null, // customer action (bukan admin)
            'notes' => $request->reason
                ? 'Dibatalkan oleh customer. Alasan: ' . $request->reason
                : 'Dibatalkan oleh customer'
        ]);

        return redirect()
            ->route('customer.orders.index')
            ->with('success', 'Order berhasil dibatalkan');
    }

    // 🆕 NEW: Complete order (SHIPPED → COMPLETED)
    public function complete(Order $order)
    {
        $customer = auth('customer')->user();

        // Validasi 1: Order milik customer yang login
        if ($order->customer_id !== $customer->id) {
            abort(403, 'Unauthorized');
        }

        // Validasi 2: Harus status SHIPPED
        if (!$order->canBeCompletedByCustomer()) {
            return back()->with(
                'error',
                'Order tidak dapat diselesaikan. Status saat ini: ' . $order->status_label
            );
        }

        try {
            DB::beginTransaction();

            // Update status ke COMPLETED
            $order->update(['status' => Order::STATUS_COMPLETED]);

            // Log status change
            $order->statusLogs()->create([
                'status_from' => Order::STATUS_SHIPPED,
                'status_to' => Order::STATUS_COMPLETED,
                'changed_by' => null,
                'notes' => 'Order diterima dan diselesaikan oleh customer'
            ]);

            DB::commit();

            return back()->with(
                'success',
                'Terima kasih! Order telah diselesaikan. Silakan beri ulasan untuk produk Anda.'
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 🆕 NEW: Report problem (placeholder)
    // 🆕 UPDATED: Report problem - redirect ke chat
    public function reportProblem(Order $order)
    {
        // Validasi 1: Order milik customer yang login
        if ($order->customer_id !== auth('customer')->id()) {
            abort(403, 'Unauthorized');
        }
        
        // Validasi 2: Hanya bisa report jika sudah PAID/SHIPPED/COMPLETED
        if (!$order->canReportProblem()) {
            return back()->with('error', 
                'Fitur laporkan masalah hanya tersedia setelah pembayaran dikonfirmasi. ' .
                'Status order saat ini: ' . $order->status_label
            );
        }
        
        // Redirect ke create chat from order
        return app(\App\Http\Controllers\Customer\CustomerChatController::class)
            ->createFromOrder($order);
    }
}
