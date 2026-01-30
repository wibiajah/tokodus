<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create payment (get snap token)
     */
    public function createPayment(Order $order)
    {
        $customer = auth('customer')->user();

        // Validate ownership
        if ($order->customer_id !== $customer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Check if order can be paid
        if (!$order->isUnpaid() || $order->isCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dibayar'
            ], 400);
        }

        try {
            // 🆕 Check existing transaction status from Midtrans
            $existingStatus = $this->checkTransactionStatus($order->order_number);

            // If transaction exists and still pending, return existing snap token
            if ($existingStatus && in_array($existingStatus['status'], ['pending', 'challenge'])) {
                // Get snap token from transaction
                if (isset($existingStatus['snap_token']) && $existingStatus['snap_token']) {
                    return response()->json([
                        'success' => true,
                        'snap_token' => $existingStatus['snap_token'],
                        'order_number' => $order->order_number,
                        'is_existing' => true
                    ]);
                }
            }

            // If transaction expired, settlement, deny, or cancel - create new transaction with updated order_id
            if ($existingStatus && in_array($existingStatus['status'], ['expire', 'deny', 'cancel'])) {
                // Update order number to avoid duplicate
                $newOrderNumber = $order->order_number . '-R' . now()->format('His');
                $order->update(['order_number' => $newOrderNumber]);
            }
            // Prepare transaction details
            $transactionDetails = [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total,
            ];

            // Prepare item details
            $itemDetails = [];
            foreach ($order->items as $item) {
                $itemDetails[] = [
                    'id' => $item->product_id,
                    'price' => (int) $item->final_price,
                    'quantity' => $item->quantity,
                    'name' => substr($item->product_title, 0, 50), // Max 50 chars
                ];
            }

            // Add discount if exists
            if ($order->discount_amount > 0) {
                $itemDetails[] = [
                    'id' => 'DISCOUNT',
                    'price' => -(int) $order->discount_amount,
                    'quantity' => 1,
                    'name' => 'Diskon Voucher',
                ];
            }

            // Customer details
            $customerDetails = [
                'first_name' => $customer->firstname,
                'last_name' => $customer->lastname,
                'email' => $customer->email,
                'phone' => $order->customer_phone,
                'billing_address' => [
                    'address' => $order->shipping_address,
                    'city' => $customer->city ?? 'Jakarta',
                    'postal_code' => $customer->postal_code ?? '12345',
                    'country_code' => 'IDN'
                ],
                'shipping_address' => [
                    'address' => $order->shipping_address,
                    'city' => $customer->city ?? 'Jakarta',
                    'postal_code' => $customer->postal_code ?? '12345',
                    'country_code' => 'IDN'
                ]
            ];

            // Prepare transaction data
            $transactionData = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'enabled_payments' => [
                    'gopay', 'shopeepay', 'bank_transfer', 'echannel', 
                    'bca_va', 'bni_va', 'bri_va', 'permata_va',
                    'other_va', 'indomaret', 'alfamart'
                ],
                'callbacks' => [
                    'finish' => route('customer.payment.finish', $order->id)
                ]
            ];

            // Get Snap Token
            $snapToken = Snap::getSnapToken($transactionData);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_number' => $order->order_number,
                'is_existing' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🆕 Check transaction status from Midtrans
     */
    private function checkTransactionStatus($orderNumber)
    {
        try {
            $status = \Midtrans\Transaction::status($orderNumber);
            
            return [
                'status' => $status->transaction_status,
                'fraud_status' => $status->fraud_status ?? null,
                'snap_token' => $status->snap_token ?? null,
                'payment_type' => $status->payment_type ?? null,
            ];
        } catch (\Exception $e) {
            // Transaction not found or error
            Log::info('Transaction not found in Midtrans', [
                'order_number' => $orderNumber,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Handle Midtrans notification (webhook)
     */
    public function notification(Request $request)
    {
        try {
            $notification = new Notification();

            $orderNumber = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;

            Log::info('Midtrans Notification', [
                'order_number' => $orderNumber,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'notification' => $notification
            ]);

            // 🆕 Check if it's a combined payment (starts with PAY-)
            if (str_starts_with($orderNumber, 'PAY-')) {
                // Multi-order payment
                $orders = Order::where('payment_reference', $orderNumber)->get();

                if ($orders->isEmpty()) {
                    Log::error('Orders not found for payment reference', ['order_number' => $orderNumber]);
                    return response()->json(['message' => 'Orders not found'], 404);
                }

                DB::beginTransaction();

                // Update all orders with same status
                foreach ($orders as $order) {
                    if ($transactionStatus == 'capture') {
                        if ($fraudStatus == 'accept') {
                            $order->update([
                                'payment_status' => 'paid',
                                'status' => 'processing'
                            ]);
                        }
                    } elseif ($transactionStatus == 'settlement') {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'processing'
                        ]);
                    } elseif ($transactionStatus == 'pending') {
                        $order->update([
                            'payment_status' => 'unpaid',
                            'status' => 'pending'
                        ]);
                    } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                        $order->update([
                            'payment_status' => 'unpaid',
                            'status' => 'cancelled'
                        ]);
                    }
                }

                DB::commit();

                return response()->json(['message' => 'Multi-order notification handled']);
            }

            // Single order payment (legacy)
            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                Log::error('Order not found', ['order_number' => $orderNumber]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            DB::beginTransaction();

            // Handle transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing'
                    ]);
                }
            } elseif ($transactionStatus == 'settlement') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);
            } elseif ($transactionStatus == 'pending') {
                $order->update([
                    'payment_status' => 'unpaid',
                    'status' => 'pending'
                ]);
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $order->update([
                    'payment_status' => 'unpaid',
                    'status' => 'cancelled'
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Notification handled']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Midtrans Notification Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    /**
     * 🆕 Show multi-order payment page
     */
    public function checkout(Request $request)
    {
        $customer = auth('customer')->user();
        
        // 🆕 Support both 'orders' param and 'reference' param
        if ($request->has('reference')) {
            // Get orders by payment reference
            $orders = Order::with(['items.product', 'toko'])
                ->where('payment_reference', $request->reference)
                ->where('customer_id', $customer->id)
                ->get();
        } else {
            // Get orders by IDs (legacy)
            $orderIds = explode(',', $request->input('orders', ''));
            
            $orders = Order::with(['items.product', 'toko'])
                ->whereIn('id', $orderIds)
                ->where('customer_id', $customer->id)
                ->get();
        }

        if ($orders->isEmpty()) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Pesanan tidak ditemukan');
        }

        $totalPayment = $orders->sum('total');
        $orderIds = $orders->pluck('id')->toArray();

        return view('frontend.payment.checkout', compact('orders', 'totalPayment', 'orderIds'));
    }

    /**
     * 🆕 Create payment for multiple orders
     */
    public function createMultiplePayment(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $customer = auth('customer')->user();

        // Get all orders
        $orders = Order::with(['items.product', 'toko'])
            ->whereIn('id', $validated['order_ids'])
            ->where('customer_id', $customer->id)
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        // Check if all orders are unpaid
        $unpaidOrders = $orders->where('payment_status', 'unpaid');
        if ($unpaidOrders->count() !== $orders->count()) {
            return response()->json([
                'success' => false,
                'message' => 'Beberapa pesanan sudah dibayar'
            ], 400);
        }

        try {
            // Generate combined order number for Midtrans
            $combinedOrderNumber = 'PAY-' . now()->format('YmdHis') . '-' . $orders->count();
            
            // Check existing transaction
            $existingStatus = $this->checkTransactionStatus($combinedOrderNumber);

            if ($existingStatus && in_array($existingStatus['status'], ['pending', 'challenge'])) {
                if (isset($existingStatus['snap_token']) && $existingStatus['snap_token']) {
                    return response()->json([
                        'success' => true,
                        'snap_token' => $existingStatus['snap_token'],
                        'order_number' => $combinedOrderNumber,
                        'is_existing' => true
                    ]);
                }
            }

            if ($existingStatus && in_array($existingStatus['status'], ['expire', 'deny', 'cancel'])) {
                $combinedOrderNumber = $combinedOrderNumber . '-R' . now()->format('His');
            }

            // Calculate total
            $totalAmount = $orders->sum('total');

            // Prepare transaction details
            $transactionDetails = [
                'order_id' => $combinedOrderNumber,
                'gross_amount' => (int) $totalAmount,
            ];

            // Prepare item details (combine all orders)
            $itemDetails = [];
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    $itemDetails[] = [
                        'id' => $item->product_id,
                        'price' => (int) $item->final_price,
                        'quantity' => $item->quantity,
                        'name' => substr($item->product_title, 0, 50),
                    ];
                }
            }

            // Add total discount if exists
            $totalDiscount = $orders->sum('discount_amount');
            if ($totalDiscount > 0) {
                $itemDetails[] = [
                    'id' => 'DISCOUNT',
                    'price' => -(int) $totalDiscount,
                    'quantity' => 1,
                    'name' => 'Diskon Total',
                ];
            }

            // Customer details
            $firstOrder = $orders->first();
            $customerDetails = [
                'first_name' => $customer->firstname,
                'last_name' => $customer->lastname,
                'email' => $customer->email,
                'phone' => $firstOrder->customer_phone,
                'billing_address' => [
                    'address' => $firstOrder->shipping_address,
                    'city' => $customer->city ?? 'Jakarta',
                    'postal_code' => $customer->postal_code ?? '12345',
                    'country_code' => 'IDN'
                ],
                'shipping_address' => [
                    'address' => $firstOrder->shipping_address,
                    'city' => $customer->city ?? 'Jakarta',
                    'postal_code' => $customer->postal_code ?? '12345',
                    'country_code' => 'IDN'
                ]
            ];

            // Prepare transaction data
            $transactionData = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'enabled_payments' => [
                    'gopay', 'shopeepay', 'bank_transfer', 'echannel', 
                    'bca_va', 'bni_va', 'bri_va', 'permata_va',
                    'other_va', 'indomaret', 'alfamart'
                ],
                'callbacks' => [
                    'finish' => route('customer.payment.finish-multiple') . '?orders=' . implode(',', $validated['order_ids'])
                ]
            ];

            // Get Snap Token
            $snapToken = Snap::getSnapToken($transactionData);

            // Store combined order number in each order for tracking
            foreach ($orders as $order) {
                $order->update(['payment_reference' => $combinedOrderNumber]);
            }

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_number' => $combinedOrderNumber,
                'is_existing' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Multi-Order Payment Error', [
                'order_ids' => $validated['order_ids'],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🆕 Payment finish page for multiple orders
     */
    public function finishMultiple(Request $request)
    {
        $customer = auth('customer')->user();
        
        $orderIds = explode(',', $request->input('orders', ''));
        
        $orders = Order::with(['items.product', 'toko'])
            ->whereIn('id', $orderIds)
            ->where('customer_id', $customer->id)
            ->get();

        if ($orders->isEmpty()) {
            abort(404, 'Pesanan tidak ditemukan');
        }

        $totalPayment = $orders->sum('total');

        return view('frontend.payment.finish-multiple', compact('orders', 'totalPayment'));
    }
}