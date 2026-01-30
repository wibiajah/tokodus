<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantStock;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    const CENTRAL_STORE_ID = 999;

    /**
     * Tampilkan halaman cart
     */
    public function index()
    {
        $customer = auth('customer')->user();

        // ✅ Load cart dengan relasi lengkap
        $cartItems = Cart::with([
            'product.categories',
            'variant' => function ($query) {
                $query->with('parent'); // Load parent variant jika size
            },
            'toko'
        ])
            ->where('customer_id', $customer->id)
            ->get();

        // Group by toko
        $groupedCart = $cartItems->groupBy('toko_id');

        $subtotal = $cartItems->sum('final_subtotal');

        return view('frontend.cart.index', compact('groupedCart', 'subtotal'));
    }

    /**
     * Apply Voucher (AJAX)
     */
    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'cart_ids' => 'required|array',
            'cart_ids.*' => 'exists:carts,id'
        ]);

        $customer = auth('customer')->user();
        $code = strtoupper(trim($request->voucher_code));

        $voucher = Voucher::where('code', $code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak valid atau sudah kadaluarsa!'
            ], 400);
        }

        $cartItems = Cart::with(['product.categories'])
            ->whereIn('id', $request->cart_ids)
            ->where('customer_id', $customer->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada item yang dipilih!'
            ], 400);
        }

        $subtotal = $cartItems->sum('final_subtotal');

        // Validate voucher
        $validation = $this->validateVoucher($voucher, $customer, $subtotal, $cartItems);

        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message']
            ], 400);
        }

        // Calculate discount
        $discount = $this->calculateDiscount($voucher, $subtotal, $cartItems);

        // Save voucher to session
        session([
            'applied_voucher' => [
                'code' => $voucher->code,
                'name' => $voucher->name,
                'discount' => $discount,
                'cart_ids' => $request->cart_ids
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diterapkan!',
            'voucher' => [
                'code' => $voucher->code,
                'name' => $voucher->name,
                'discount' => $discount,
                'discount_formatted' => number_format($discount, 0, ',', '.')
            ]
        ]);
    }

    /**
     * Remove applied voucher
     */
    public function removeVoucher()
    {
        session()->forget('applied_voucher');

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil dihapus'
        ]);
    }

    /**
     * Get applied voucher from session
     */
    public function getAppliedVoucher(Request $request)
    {
        $appliedVoucher = session('applied_voucher');

        if (!$appliedVoucher) {
            return response()->json([
                'success' => false,
                'voucher' => null
            ]);
        }

        // Validate cart_ids masih sama
        $selectedItems = $request->input('cart_ids', []);
        $savedCartIds = $appliedVoucher['cart_ids'] ?? [];

        // Jika cart berubah, hapus voucher
        if (array_diff($selectedItems, $savedCartIds) || array_diff($savedCartIds, $selectedItems)) {
            session()->forget('applied_voucher');
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak valid untuk item yang dipilih',
                'voucher' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'voucher' => $appliedVoucher
        ]);
    }

    /**
     * Get available vouchers for selected cart items
     */
    public function getAvailableVouchers(Request $request)
    {
        $request->validate([
            'cart_ids' => 'required|array',
            'cart_ids.*' => 'exists:carts,id'
        ]);

        $customer = auth('customer')->user();

        // Get selected cart items
        $cartItems = Cart::with(['product.categories'])
            ->whereIn('id', $request->cart_ids)
            ->where('customer_id', $customer->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => true,
                'vouchers' => []
            ]);
        }

        $subtotal = $cartItems->sum('final_subtotal');

        // Get all vouchers
        $allVouchers = Voucher::all();

        $categorizedVouchers = [
            'available' => [],
            'unavailable' => [],
            'expired' => []
        ];

        foreach ($allVouchers as $voucher) {
            $voucherData = [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'name' => $voucher->name,
                'description' => $voucher->description,
                'discount_type' => $voucher->discount_type,
                'discount_value' => $voucher->discount_value,
                'discount_display' => $voucher->discount_display,
                'min_purchase' => $voucher->min_purchase,
                'max_discount' => $voucher->max_discount,
                'end_date' => $voucher->end_date->format('d M Y'),
                'start_date' => $voucher->start_date->format('d M Y'),
                'is_active' => $voucher->is_active,
            ];

            // Check if expired
            if ($voucher->end_date < now()) {
                $voucherData['reason'] = 'Voucher sudah kadaluarsa sejak ' . $voucher->end_date->format('d M Y');
                $categorizedVouchers['expired'][] = $voucherData;
                continue;
            }

            // Check if not started yet
            if ($voucher->start_date > now()) {
                $voucherData['reason'] = 'Voucher belum aktif. Berlaku mulai ' . $voucher->start_date->format('d M Y');
                $categorizedVouchers['unavailable'][] = $voucherData;
                continue;
            }

            // Check if inactive
            if (!$voucher->is_active) {
                $voucherData['reason'] = 'Voucher sedang tidak aktif';
                $categorizedVouchers['unavailable'][] = $voucherData;
                continue;
            }

            // Check total usage limit
            if ($voucher->usage_limit_total && $voucher->usage_count >= $voucher->usage_limit_total) {
                $voucherData['reason'] = 'Voucher sudah mencapai batas penggunaan';
                $categorizedVouchers['unavailable'][] = $voucherData;
                continue;
            }

            // Check min purchase
            if ($voucher->min_purchase > $subtotal) {
                $voucherData['reason'] = 'Minimal belanja Rp' . number_format($voucher->min_purchase, 0, ',', '.') . ' (kurang Rp' . number_format($voucher->min_purchase - $subtotal, 0, ',', '.') . ')';
                $categorizedVouchers['unavailable'][] = $voucherData;
                continue;
            }

            // Check per customer usage limit
            if ($voucher->usage_limit_per_customer) {
                $customerUsage = \App\Models\VoucherUsage::where('voucher_id', $voucher->id)
                    ->where('customer_id', $customer->id)
                    ->count();

                if ($customerUsage >= $voucher->usage_limit_per_customer) {
                    $voucherData['reason'] = 'Anda sudah mencapai batas penggunaan voucher ini (' . $customerUsage . 'x)';
                    $categorizedVouchers['unavailable'][] = $voucherData;
                    continue;
                }
            }

            // Check customer access
            if ($voucher->distribution_type === 'private') {
                $hasAccess = $voucher->customers()
                    ->where('customer_id', $customer->id)
                    ->exists();

                if (!$hasAccess) {
                    $voucherData['reason'] = 'Voucher ini khusus untuk customer tertentu';
                    $categorizedVouchers['unavailable'][] = $voucherData;
                    continue;
                }
            } elseif ($voucher->distribution_type === 'public') {
                $hasCustomerRestriction = $voucher->customers()->exists();

                if ($hasCustomerRestriction) {
                    $hasAccess = $voucher->customers()
                        ->where('customer_id', $customer->id)
                        ->exists();

                    if (!$hasAccess) {
                        $voucherData['reason'] = 'Voucher ini tidak tersedia untuk Anda';
                        $categorizedVouchers['unavailable'][] = $voucherData;
                        continue;
                    }
                }
            }

            // Check voucher scope
            if ($voucher->scope !== 'all_products') {
                $hasApplicableProduct = false;

                if ($voucher->scope === 'specific_products') {
                    $voucherProductIds = $voucher->products()->pluck('product_id')->toArray();
                    foreach ($cartItems as $item) {
                        if (in_array($item->product_id, $voucherProductIds)) {
                            $hasApplicableProduct = true;
                            break;
                        }
                    }

                    if (!$hasApplicableProduct) {
                        $voucherData['reason'] = 'Voucher tidak berlaku untuk produk yang Anda pilih';
                        $categorizedVouchers['unavailable'][] = $voucherData;
                        continue;
                    }
                } elseif ($voucher->scope === 'specific_categories') {
                    $voucherCategoryIds = $voucher->categories()->pluck('category_id')->toArray();
                    foreach ($cartItems as $item) {
                        $productCategoryIds = $item->product->categories->pluck('id')->toArray();
                        if (array_intersect($productCategoryIds, $voucherCategoryIds)) {
                            $hasApplicableProduct = true;
                            break;
                        }
                    }

                    if (!$hasApplicableProduct) {
                        $voucherData['reason'] = 'Voucher tidak berlaku untuk kategori produk yang Anda pilih';
                        $categorizedVouchers['unavailable'][] = $voucherData;
                        continue;
                    }
                }
            }

            // If passed all checks, it's available
            $categorizedVouchers['available'][] = $voucherData;
        }

        return response()->json([
            'success' => true,
            'vouchers' => $categorizedVouchers
        ]);
    }

    /**
     * Validate voucher
     */
    private function validateVoucher($voucher, $customer, $subtotal, $cartItems)
    {
        // Check minimum purchase
        if ($voucher->min_purchase > $subtotal) {
            return [
                'valid' => false,
                'message' => 'Minimal pembelian Rp ' . number_format($voucher->min_purchase, 0, ',', '.') . ' untuk menggunakan voucher ini!'
            ];
        }

        // Check total usage limit
        if ($voucher->usage_limit_total && $voucher->usage_count >= $voucher->usage_limit_total) {
            return [
                'valid' => false,
                'message' => 'Voucher sudah mencapai batas penggunaan!'
            ];
        }

        // Check per customer limit
        if ($voucher->usage_limit_per_customer) {
            $customerUsage = \App\Models\VoucherUsage::where('voucher_id', $voucher->id)
                ->where('customer_id', $customer->id)
                ->count();

            if ($customerUsage >= $voucher->usage_limit_per_customer) {
                return [
                    'valid' => false,
                    'message' => 'Anda sudah mencapai batas penggunaan voucher ini!'
                ];
            }
        }

        // Check customer access
        $hasCustomerRestriction = $voucher->customers()->count() > 0;

        if ($voucher->distribution_type === 'private' || $hasCustomerRestriction) {
            $hasAccess = $voucher->customers()
                ->where('customer_id', $customer->id)
                ->exists();

            if (!$hasAccess) {
                return [
                    'valid' => false,
                    'message' => 'Voucher ini tidak tersedia untuk Anda!'
                ];
            }
        }

        // Check voucher scope
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
                return [
                    'valid' => false,
                    'message' => 'Voucher ini tidak berlaku untuk produk yang dipilih!'
                ];
            }
        }

        return ['valid' => true];
    }

    /**
     * Calculate discount amount
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

    /**
     * ✅ FIXED: Tambah produk ke cart - Handle Central Store (999)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
            'toko_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        if (!auth()->guard('customer')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login sebagai customer!'
            ], 401);
        }

        $customer = auth('customer')->user();
        $product = Product::findOrFail($validated['product_id']);
        $variant = ProductVariant::with('parent')->findOrFail($validated['variant_id']);

        $tokoId = (int) $validated['toko_id'];

        // ✅ Validate stock based on store type
        if ($tokoId === self::CENTRAL_STORE_ID) {
            $availableStock = $variant->stock_pusat;

            if ($availableStock < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi! Tersedia: ' . $availableStock . ' item.'
                ], 400);
            }
        } else {
            $variantStock = ProductVariantStock::where('variant_id', $validated['variant_id'])
                ->where('toko_id', $tokoId)
                ->first();

            if (!$variantStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Varian produk tidak tersedia di toko ini!'
                ], 400);
            }

            $availableStock = $variantStock->stock;

            if ($availableStock < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi! Tersedia: ' . $availableStock . ' item.'
                ], 400);
            }
        }

        try {
            DB::beginTransaction();

            // ✅ Check if item already exists in cart
            $cart = Cart::where('customer_id', $customer->id)
                ->where('product_id', $validated['product_id'])
                ->where('variant_id', $validated['variant_id'])
                ->where('toko_id', $tokoId)
                ->first();

            if ($cart) {
                $newQuantity = $cart->quantity + $validated['quantity'];

                if ($availableStock < $newQuantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi! Maksimal ' . $availableStock . ' item.'
                    ], 400);
                }

                $cart->update(['quantity' => $newQuantity]);
            } else {
                // ✅ Get price from variant or product
                $price = $variant->price ?? $product->final_price;

                $cart = Cart::create([
                    'customer_id' => $customer->id,
                    'product_id' => $validated['product_id'],
                    'variant_id' => $validated['variant_id'],
                    'toko_id' => $tokoId,
                    'quantity' => $validated['quantity'],
                    'price' => $price,
                ]);
            }

            DB::commit();

            $cartCount = Cart::where('customer_id', $customer->id)->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => $cartCount,
                'cart_item' => [
                    'id' => $cart->id,
                    'product_title' => $product->title,
                    'variant_name' => $variant->display_name,
                    'quantity' => $cart->quantity
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cart Store Error:', [
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
     * ✅ FIXED: Update cart quantity - Handle Central Store
     */
    public function update(Request $request, Cart $cart)
    {
        if ($cart->customer_id !== auth('customer')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ((int) $cart->toko_id === self::CENTRAL_STORE_ID) {
            $variant = ProductVariant::find($cart->variant_id);
            $availableStock = $variant ? $variant->stock_pusat : 0;
        } else {
            $variantStock = ProductVariantStock::where('variant_id', $cart->variant_id)
                ->where('toko_id', $cart->toko_id)
                ->first();

            $availableStock = $variantStock ? $variantStock->stock : 0;
        }

        if ($availableStock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi! Maksimal ' . $availableStock . ' item.'
            ], 400);
        }

        $cart->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diupdate!',
            'subtotal' => number_format($cart->final_subtotal, 0, ',', '.')
        ]);
    }

    /**
     * Delete cart item
     */
    public function destroy(Cart $cart)
    {
        if ($cart->customer_id !== auth('customer')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $cart->delete();

        $cartCount = Cart::where('customer_id', auth('customer')->id())->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang!',
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        Cart::where('customer_id', auth('customer')->id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan!'
        ]);
    }

    /**
     * Get cart item count
     */
    public function count()
    {
        $count = Cart::where('customer_id', auth('customer')->id())->sum('quantity');

        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * ✅ FIXED: Get stock for specific variant at toko - Handle Central Store
     */
    public function getStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
            'toko_id' => 'required',
        ]);

        $tokoId = (int) $request->toko_id;

        // ✅ FIX: Handle Central Store vs Regular Store
        if ($tokoId === self::CENTRAL_STORE_ID) {
            // Central Store: Get stock from ProductVariant
            $variant = ProductVariant::find($request->variant_id);
            $stock = $variant ? $variant->stock : 0;
        } else {
            // Regular Store: Get stock from ProductVariantStock
            $variantStock = ProductVariantStock::where('variant_id', $request->variant_id)
                ->where('toko_id', $tokoId)
                ->first();

            $stock = $variantStock ? $variantStock->stock : 0;
        }

        return response()->json([
            'stock' => $stock
        ]);
    }
}
