<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\ProductVariantStock;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\ShippingHelper;

class CheckoutController extends Controller
{
    const CENTRAL_STORE_ID = 999;

    /**
     * Display checkout page
     */
    public function index(Request $request)
    {
        $customer = auth('customer')->user();

        // Validasi koordinat customer
        if (!$customer->latitude || !$customer->longitude) {
            return redirect()->route('customer.profile')
                ->with('error', 'Mohon set lokasi Anda di peta terlebih dahulu!');
        }

        // Get selected cart items
        $cartIds = explode(',', $request->input('items', ''));

        // 🔥 IMPORTANT: Eager load relationships untuk prevent N+1 query
        $cartItems = Cart::with([
            'product.categories',
            'variant' => function ($query) {
                $query->with('parent'); // Load parent untuk size variants
            },
            'toko'
        ])
            ->whereIn('id', $cartIds)
            ->where('customer_id', $customer->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Tidak ada item yang dipilih');
        }

        // Group by toko
        $groupedItems = $cartItems->groupBy('toko_id');

        // Get available tokos for pickup
        $availableTokos = \App\Models\Toko::where('status', 'aktif')
            ->orderBy('nama_toko')
            ->get();

        // Calculate totals
        $subtotal = $cartItems->sum('final_subtotal');
        $discount = 0;
        $appliedVoucher = null;

        // Apply voucher if exists
        $sessionVoucher = session('applied_voucher');
        if ($sessionVoucher && $request->has('voucher')) {
            $voucher = Voucher::where('code', $sessionVoucher['code'])
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($voucher) {
                $validation = $this->validateVoucher($voucher, $customer, $subtotal, $cartItems);

                if ($validation['valid']) {
                    $discount = $this->calculateDiscount($voucher, $subtotal, $cartItems);
                    $appliedVoucher = $voucher;
                }
            }
        }

        $grandTotal = $subtotal - $discount;

        return view('frontend.checkout.index', compact(
            'customer',
            'groupedItems',
            'cartItems',
            'subtotal',
            'discount',
            'grandTotal',
            'appliedVoucher',
            'availableTokos'
        ));
    }


    /**
 * Process checkout and create order
 * 🆕 REVISED: Only allow checkout from 1 store
 */
public function process(Request $request)
{
    $validated = $request->validate([
        'cart_ids' => 'required|array',
        'cart_ids.*' => 'exists:carts,id',
        'shipping_address' => 'nullable|string|max:500',
        'customer_phone' => 'required|string|max:20',
        'notes' => 'nullable|string|max:500',
        'voucher_code' => 'nullable|string',
        'shipping_type' => 'nullable|in:reguler,instant', // 🆕 Single shipping type (no array)
        'delivery_method' => 'nullable|in:delivery,pickup', // 🆕 Single delivery method
        'pickup_toko_id' => 'nullable|exists:tokos,id', // 🆕 Single pickup toko
    ]);

    $customer = auth('customer')->user();

    // Get cart items with variants
    $cartItems = Cart::with([
        'product',
        'variant' => function ($query) {
            $query->with('parent');
        },
        'toko'
    ])
        ->whereIn('id', $validated['cart_ids'])
        ->where('customer_id', $customer->id)
        ->get();

    if ($cartItems->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Keranjang belanja kosong!'
        ], 400);
    }

    // 🆕 VALIDASI: Semua item harus dari toko yang sama
    $tokoIds = $cartItems->pluck('toko_id')->unique();
    if ($tokoIds->count() > 1) {
        return response()->json([
            'success' => false,
            'message' => '❌ Checkout hanya bisa dilakukan dari 1 toko! Anda memilih produk dari ' . $tokoIds->count() . ' toko berbeda.'
        ], 400);
    }

    // Get single toko ID
    $tokoId = (int) $cartItems->first()->toko_id;

    // ✅ Check stock availability for each variant
    foreach ($cartItems as $item) {
        if ($tokoId === self::CENTRAL_STORE_ID) {
            $variant = ProductVariant::find($item->variant_id);
            $availableStock = $variant ? $variant->stock_pusat : 0;
        } else {
            $variantStock = ProductVariantStock::where('variant_id', $item->variant_id)
                ->where('toko_id', $tokoId)
                ->first();

            $availableStock = $variantStock ? $variantStock->stock : 0;
        }

        if ($availableStock < $item->quantity) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak mencukupi untuk {$item->product->title} - {$item->variant_name}. Tersedia: {$availableStock}"
            ], 400);
        }
    }

    $subtotal = $cartItems->sum('final_subtotal');
    $discount = 0;
    $voucherModel = null;

    // Validate and apply voucher if provided
    if (!empty($validated['voucher_code'])) {
        $voucherModel = Voucher::where('code', $validated['voucher_code'])
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if ($voucherModel) {
            $validation = $this->validateVoucher($voucherModel, $customer, $subtotal, $cartItems);

            if ($validation['valid']) {
                $discount = $this->calculateDiscount($voucherModel, $subtotal, $cartItems);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher tidak valid: ' . $validation['message']
                ], 400);
            }
        }
    }

    $total = $subtotal - $discount;

    try {
        DB::beginTransaction();

        // 🆕 SINGLE STORE - No grouping needed
        $deliveryMethod = $validated['delivery_method'] ?? 'delivery';
        $isCentralStore = $tokoId === self::CENTRAL_STORE_ID;

        // Calculate shipping cost
        $shippingCost = 0;
        $shippingType = $validated['shipping_type'] ?? 'reguler';
        $shippingDistance = 0;
        $customerLat = $customer->latitude;
        $customerLng = $customer->longitude;
        $tokoLat = null;
        $tokoLng = null;

        if ($deliveryMethod === 'delivery') {
            $toko = \App\Models\Toko::find($tokoId);

            if ($toko && ShippingHelper::hasCoordinates($customer, $toko)) {
                $tokoLat = $toko->latitude;
                $tokoLng = $toko->longitude;

                $shippingDistance = ShippingHelper::calculateDistance(
                    $customerLat,
                    $customerLng,
                    $tokoLat,
                    $tokoLng
                );

                $result = ShippingHelper::calculateShippingCost($shippingDistance, $shippingType);

                if ($result['error']) {
                    return response()->json([
                        'success' => false,
                        'message' => $result['error']
                    ], 400);
                }

                $shippingCost = $result['cost'];
                $total += $shippingCost;
            }
        }

        // Determine shipping address and pickup toko
        $shippingAddress = $validated['shipping_address'] ?? '';
        $pickupTokoId = null;

        if ($deliveryMethod === 'pickup') {
            if ($isCentralStore) {
                $pickupTokoId = $validated['pickup_toko_id'] ?? null;

                if (!$pickupTokoId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pilih toko untuk pengambilan produk dari Central Store'
                    ], 400);
                }

                $pickupToko = \App\Models\Toko::find($pickupTokoId);
                $shippingAddress = $pickupToko ? "Pickup di {$pickupToko->nama_toko}" : 'Pickup di Toko';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Toko cabang tidak menyediakan opsi pickup'
                ], 400);
            }
        } else {
            if (!$shippingAddress) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alamat pengiriman harus diisi'
                ], 400);
            }
        }

        // 🆕 Create single order
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'payment_reference' => null, // 🆕 No payment reference for single toko
            'customer_id' => $customer->id,
            'toko_id' => $tokoId,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'total' => $total,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'customer_name' => $customer->firstname . ' ' . $customer->lastname,
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $customer->email,
            'shipping_address' => $shippingAddress,
            'shipping_cost' => $shippingCost,
            'shipping_type' => $deliveryMethod === 'delivery' ? $shippingType : null,
            'shipping_distance' => $shippingDistance,
            'customer_latitude' => $customerLat,
            'customer_longitude' => $customerLng,
            'toko_latitude' => $tokoLat,
            'toko_longitude' => $tokoLng,
            'notes' => $validated['notes'] ?? null,
            'delivery_method' => $deliveryMethod,
            'pickup_toko_id' => $pickupTokoId,
        ]);

        // Create order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'variant_id' => $cartItem->variant_id,
                'product_title' => $cartItem->product->title,
                'product_sku' => $cartItem->product->sku,
                'variant_name' => $cartItem->variant_name,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->final_price,
                'discount_price' => $cartItem->product->discount_price,
                'subtotal' => $cartItem->final_subtotal,
            ]);

            // ✅ Reduce stock
            if ($tokoId === self::CENTRAL_STORE_ID) {
                $variant = ProductVariant::find($cartItem->variant_id);
                if ($variant) {
                    $variant->decrement('stock_pusat', $cartItem->quantity);
                }
            } else {
                $variantStock = ProductVariantStock::where('variant_id', $cartItem->variant_id)
                    ->where('toko_id', $tokoId)
                    ->first();

                if ($variantStock) {
                    $variantStock->decrement('stock', $cartItem->quantity);
                }
            }
        }

        // Record voucher usage
        if ($voucherModel) {
            VoucherUsage::create([
                'voucher_id' => $voucherModel->id,
                'customer_id' => $customer->id,
                'order_id' => $order->id,
                'discount_amount' => $discount,
                'order_total' => $total,
                'used_at' => now(),
            ]);

            $voucherModel->increment('usage_count');
        }

        // Clear cart items
        Cart::whereIn('id', $validated['cart_ids'])->delete();

        // Clear voucher session
        session()->forget('applied_voucher');

        DB::commit();

        // 🆕 Return single order response
        return response()->json([
            'success' => true,
            'message' => 'Checkout berhasil! Order Anda telah dibuat.',
            'data' => [
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total' => $order->total
                ],
                'redirect_url' => route('customer.orders.show', $order->id)
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Checkout error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}


    /**
     * Calculate shipping cost (AJAX)
     */
    public function calculateShipping(Request $request)
    {
        $validated = $request->validate([
            'toko_id' => 'required|exists:tokos,id',
            'shipping_type' => 'required|in:reguler,instant',
        ]);

        $customer = auth('customer')->user();
        $toko = \App\Models\Toko::find($validated['toko_id']);

        if (!ShippingHelper::hasCoordinates($customer, $toko)) {
            return response()->json([
                'success' => false,
                'message' => 'Koordinat belum lengkap'
            ], 400);
        }

        $distance = ShippingHelper::calculateDistance(
            $customer->latitude,
            $customer->longitude,
            $toko->latitude,
            $toko->longitude
        );

        $result = ShippingHelper::calculateShippingCost($distance, $validated['shipping_type']);

        if ($result['error']) {
            return response()->json([
                'success' => false,
                'message' => $result['error']
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'distance' => $distance,
                'cost' => $result['cost'],
                'formatted_cost' => 'Rp ' . number_format($result['cost'], 0, ',', '.'),
                'shipping_type' => $validated['shipping_type'],
                'estimate_time' => ShippingHelper::getEstimatedTime($validated['shipping_type']),
                'customer_coords' => [
                    'lat' => $customer->latitude,
                    'lng' => $customer->longitude
                ],
                'toko_coords' => [
                    'lat' => $toko->latitude,
                    'lng' => $toko->longitude
                ]
            ]
        ]);
    }

    /**
     * Validate Voucher
     */
    private function validateVoucher($voucher, $customer, $subtotal, $cartItems)
    {
        if ($voucher->min_purchase > $subtotal) {
            return [
                'valid' => false,
                'message' => 'Minimal pembelian Rp ' . number_format($voucher->min_purchase, 0, ',', '.')
            ];
        }

        if ($voucher->usage_limit_total && $voucher->usage_count >= $voucher->usage_limit_total) {
            return ['valid' => false, 'message' => 'Voucher sudah mencapai batas penggunaan!'];
        }

        if ($voucher->usage_limit_per_customer) {
            $customerUsage = VoucherUsage::where('voucher_id', $voucher->id)
                ->where('customer_id', $customer->id)
                ->count();

            if ($customerUsage >= $voucher->usage_limit_per_customer) {
                return ['valid' => false, 'message' => 'Anda sudah mencapai batas penggunaan!'];
            }
        }

        if ($voucher->scope !== 'all_products') {
            $eligible = false;

            if ($voucher->scope === 'specific_products') {
                $voucherProductIds = $voucher->products()->pluck('product_id')->toArray();
                foreach ($cartItems as $item) {
                    if (in_array($item->product_id, $voucherProductIds)) {
                        $eligible = true;
                        break;
                    }
                }
            } elseif ($voucher->scope === 'specific_categories') {
                $voucherCategoryIds = $voucher->categories()->pluck('category_id')->toArray();
                foreach ($cartItems as $item) {
                    $productCategoryIds = $item->product->categories->pluck('id')->toArray();
                    if (array_intersect($productCategoryIds, $voucherCategoryIds)) {
                        $eligible = true;
                        break;
                    }
                }
            }

            if (!$eligible) {
                return ['valid' => false, 'message' => 'Voucher tidak berlaku untuk produk ini!'];
            }
        }

        if ($voucher->distribution_type === 'private') {
            $hasAccess = $voucher->customers()->where('customer_id', $customer->id)->exists();
            if (!$hasAccess) {
                return ['valid' => false, 'message' => 'Voucher tidak tersedia untuk Anda!'];
            }
        }

        return ['valid' => true];
    }

    /**
     * Calculate Discount
     */
    private function calculateDiscount($voucher, $subtotal, $cartItems)
    {
        $discount = 0;

        if ($voucher->scope === 'all_products') {
            if ($voucher->discount_type === 'fixed') {
                $discount = $voucher->discount_value;
            } else {
                $discount = ($subtotal * $voucher->discount_value) / 100;
                if ($voucher->max_discount && $discount > $voucher->max_discount) {
                    $discount = $voucher->max_discount;
                }
            }
        } else {
            $eligibleSubtotal = 0;

            if ($voucher->scope === 'specific_products') {
                $voucherProductIds = $voucher->products()->pluck('product_id')->toArray();
                foreach ($cartItems as $item) {
                    if (in_array($item->product_id, $voucherProductIds)) {
                        $eligibleSubtotal += $item->final_subtotal;
                    }
                }
            } elseif ($voucher->scope === 'specific_categories') {
                $voucherCategoryIds = $voucher->categories()->pluck('category_id')->toArray();
                foreach ($cartItems as $item) {
                    $productCategoryIds = $item->product->categories->pluck('id')->toArray();
                    if (array_intersect($productCategoryIds, $voucherCategoryIds)) {
                        $eligibleSubtotal += $item->final_subtotal;
                    }
                }
            }

            if ($voucher->discount_type === 'fixed') {
                $discount = min($voucher->discount_value, $eligibleSubtotal);
            } else {
                $discount = ($eligibleSubtotal * $voucher->discount_value) / 100;
                if ($voucher->max_discount && $discount > $voucher->max_discount) {
                    $discount = $voucher->max_discount;
                }
            }
        }

        return min($discount, $subtotal);
    }
}
