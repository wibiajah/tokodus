<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantStock;
use App\Models\StockDistributionLog;
use App\Models\Toko;
use App\Http\Requests\SuperAdmin\StoreStockDistributionRequest;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;

class StockDistributionController extends Controller
{
    /**
     * ✅ VIEW 1: INDEX - Overview semua produk dengan stock
     * File: resources/views/superadmin/stocks/index.blade.php
     * Route: superadmin.stocks.index
     */
    public function index()
    {
        $products = Product::with(['variants.children', 'variants.stocks'])
            ->whereNull('deleted_at')
            ->orderBy('title', 'asc')
            ->get();

        $tokos = Toko::where('status', 'aktif')
            ->where('id', '!=', 999)
            ->get();

        return view('superadmin.stocks.index', compact('products', 'tokos'));
    }

    /**
     * ✅ VIEW 2: DETAIL - Stock breakdown + Form distribusi (2 TABS dalam 1 halaman)
     * File: resources/views/superadmin/stocks/detail.blade.php
     * Route: superadmin.stocks.detail
     * 
     * TAB 1: Overview Stok (breakdown per varian per toko)
     * TAB 2: Form Distribusi Stok
     */
    public function detail(Product $product)
    {
        $product->load([
            'variants' => function ($query) {
                $query->with(['children.stocks.toko', 'stocks.toko'])
                    ->whereNull('parent_id');
            }
        ]);

        // Get all active tokos (exclude Head Office 999)
        $tokos = Toko::where('status', 'aktif')
            ->where('id', '!=', 999)
            ->orderBy('nama_toko')
            ->get();

        // Build stock overview per variant per toko (untuk TAB 1)
        $stockOverview = [];
        foreach ($product->variants as $color) {
            if ($color->hasChildren()) {
                // Color dengan sizes
                foreach ($color->children as $size) {
                    $stockOverview[] = [
                        'variant' => $size,
                        'display_name' => $color->name . ' - ' . $size->name,
                        'stock_pusat' => $size->stock_pusat,
                        'stocks_per_toko' => $size->stocks->keyBy('toko_id'),
                    ];
                }
            } else {
                // Color tanpa sizes
                $stockOverview[] = [
                    'variant' => $color,
                    'display_name' => $color->name,
                    'stock_pusat' => $color->stock_pusat,
                    'stocks_per_toko' => $color->stocks->keyBy('toko_id'),
                ];
            }
        }

        // Get leaf variants (untuk TAB 2 - Form Distribusi)
        $leafVariants = $product->variants->flatMap(function ($color) {
            if ($color->hasChildren()) {
                return $color->children;
            }
            return [$color];
        });

        return view('superadmin.stocks.detail', compact('product', 'tokos', 'stockOverview', 'leafVariants'));
    }

    /**
     * ✅ STORE - Execute distribution (POST dari form di TAB 2)
     * Redirect ke: superadmin.stocks.detail dengan success message
     */
    public function store(StoreStockDistributionRequest $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $toko = Toko::findOrFail($request->toko_id);
            $distributedItems = [];

            foreach ($request->variants as $item) {
                $variant = ProductVariant::findOrFail($item['variant_id']);
                $quantity = (int) $item['quantity'];

                // Validasi: stok pusat cukup
                if ($variant->stock_pusat < $quantity) {
                    throw new \Exception("Stok pusat untuk {$variant->display_name} tidak mencukupi. Tersedia: {$variant->stock_pusat}, Diminta: {$quantity}");
                }

                // 1. Kurangi stock_pusat (warehouse)
                $variant->decrement('stock_pusat', $quantity);

                // 2. Tambah atau update stock di toko
                $variantStock = ProductVariantStock::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'variant_id' => $variant->id,
                        'toko_id' => $toko->id,
                    ],
                    [
                        'stock' => 0
                    ]
                );

                $stockBefore = $variantStock->stock;
                $variantStock->increment('stock', $quantity);

                // 3. Update parent color stock (jika ini size)
                if ($variant->parent_id) {
                    $parent = $variant->parent;
                    $parent->updateParentStock();
                }

                // Simpan untuk log
                $distributedItems[] = [
                    'variant_id' => $variant->id,
                    'variant_name' => $variant->display_name,
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $variantStock->stock,
                ];
            }

            // 4. Create distribution log
            $dummyRequest = \App\Models\StockRequest::create([
                'product_id' => $product->id,
                'toko_id' => $toko->id,
                'type' => 'direct',
                'requested_by' => auth()->id(),
                'approved_by' => auth()->id(),
                'status' => 'approved',
                'items' => json_encode($distributedItems),
                'notes' => $request->notes,
                'requested_at' => now(),
                'approved_at' => now(),
            ]);

            // Create logs
            foreach ($distributedItems as $item) {
                StockDistributionLog::create([
                    'product_id' => $product->id,
                    'variant_id' => $item['variant_id'],
                    'toko_id' => $toko->id,
                    'quantity' => $item['quantity'],
                    'type' => 'in',
                    'source_type' => 'direct',
                    'source_id' => $dummyRequest->id,
                    'performed_by' => auth()->id(),
                    'stock_before' => $item['stock_before'],
                    'stock_after' => $item['stock_after'],
                    'notes' => $request->notes,
                ]);
            }

            DB::commit();

            // Notifikasi
            NotificationHelper::stockDistributed($product, $toko, $distributedItems, auth()->user());
            NotificationHelper::stockReceived($product, $toko, $distributedItems, auth()->user());

            return redirect()
                ->route('superadmin.stocks.detail', $product)
                ->with('success', 'Stok berhasil didistribusikan ke ' . $toko->nama_toko . '! 🎉');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal mendistribusikan stok: ' . $e->getMessage()]);
        }
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'variants' => 'required|array|min:1',
            'variants.*.variant_id' => 'required|exists:product_variants,id',
            'variants.*.action' => 'required|in:add,reduce,set',
            'variants.*.quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $editedItems = [];

            foreach ($request->variants as $item) {
                $variant = ProductVariant::findOrFail($item['variant_id']);
                $action = $item['action'];
                $quantity = (int) $item['quantity'];
                $stockBefore = $variant->stock_pusat;

                // Validasi: tidak boleh reduce lebih dari stok yang ada
                if ($action === 'reduce' && $quantity > $stockBefore) {
                    throw new \Exception("Stok varian '{$variant->display_name}' tidak cukup untuk dikurangi. Tersedia: {$stockBefore}, Diminta: {$quantity}");
                }

                // Execute action
                if ($action === 'add') {
                    $variant->increment('stock_pusat', $quantity);
                } elseif ($action === 'reduce') {
                    $variant->decrement('stock_pusat', $quantity);
                } elseif ($action === 'set') {
                    $variant->update(['stock_pusat' => $quantity]);
                }

                $stockAfter = $variant->fresh()->stock_pusat;
                $change = $stockAfter - $stockBefore;

                // Update parent color stock (jika ini size)
                if ($variant->parent_id) {
                    $parent = $variant->parent;
                    $parent->updateParentStock();
                }

                // Simpan untuk log
                $editedItems[] = [
                    'variant_id' => $variant->id,
                    'variant_name' => $variant->display_name,
                    'action' => $action,
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'change' => $change,
                ];
            }

            // Create logs
            foreach ($editedItems as $item) {
                StockDistributionLog::create([
                    'product_id' => $product->id,
                    'variant_id' => $item['variant_id'],
                    'toko_id' => 999, // Head Office
                    'quantity' => abs($item['change']),
                    'type' => $item['change'] >= 0 ? 'in' : 'out',
                    'source_type' => 'stock_adjustment',
                    'source_id' => null,
                    'performed_by' => auth()->id(),
                    'stock_before' => $item['stock_before'],
                    'stock_after' => $item['stock_after'],
                    'notes' => $request->notes ?? "Adjustment: {$item['action']} {$item['quantity']} unit",
                ]);
            }

            DB::commit();

            return redirect()
                ->route('superadmin.stocks.detail', $product)
                ->with('success', 'Stok pusat berhasil diperbarui! 🎉');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui stok: ' . $e->getMessage()]);
        }
    }

    /**
     * ✅ VIEW 3: HISTORY - Distribution history logs per product
     * File: resources/views/superadmin/stocks/history.blade.php
     * Route: superadmin.stocks.history
     */
    public function history(Product $product)
    {
        // TAB 1: Aktivitas Super Admin (distribusi + edit stok pusat)
        $superAdminLogs = StockDistributionLog::with(['variant', 'toko', 'performedBy'])
            ->where('product_id', $product->id)
            ->where(function ($query) {
                $query->where(function ($q) {
                    // Distribusi warehouse ke toko
                    $q->where('type', '=', 'in')
                        ->whereIn('source_type', ['direct', 'request']);
                })
                    ->orWhere(function ($q) {
                        // Edit stok pusat (toko_id = 999)
                        $q->where('source_type', '=', 'stock_adjustment')
                            ->where('toko_id', '=', 999);
                    });
            })
            ->latest()
            ->paginate(20, ['*'], 'super_page');

        // TAB 2: Edit manual oleh Kepala Toko (toko_id != 999)
        $manualEditLogs = StockDistributionLog::with(['variant', 'toko', 'performedBy'])
            ->where('product_id', $product->id)
            ->where('source_type', '=', 'manual_edit')
            ->where('toko_id', '!=', 999)
            ->latest()
            ->paginate(20, ['*'], 'edit_page');

        return view('superadmin.stocks.history', compact('product', 'superAdminLogs', 'manualEditLogs'));
    }

    /**
     * ✅ AJAX - Get available stock for variant
     */
    public function getVariantStock(ProductVariant $variant)
    {
        // Validasi: harus leaf node
        if ($variant->isColor() && $variant->hasChildren()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa mendistribusikan parent color yang memiliki ukuran'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'variant' => [
                'id' => $variant->id,
                'name' => $variant->display_name,
                'stock_pusat' => $variant->stock_pusat,
                'price' => $variant->price,
            ]
        ]);
    }

    /**
     * ✅ AJAX - Get stock overview for specific toko
     */
    public function getTokoStock(Product $product, Toko $toko)
    {
        $product->load([
            'variants' => function ($query) use ($toko) {
                $query->with([
                    'children.stocks' => function ($q) use ($toko) {
                        $q->where('toko_id', $toko->id);
                    },
                    'stocks' => function ($q) use ($toko) {
                        $q->where('toko_id', $toko->id);
                    }
                ])->whereNull('parent_id');
            }
        ]);

        $stockData = [];

        foreach ($product->variants as $color) {
            if ($color->hasChildren()) {
                foreach ($color->children as $size) {
                    $stock = $size->stocks->first();
                    $stockData[] = [
                        'variant_id' => $size->id,
                        'display_name' => $color->name . ' - ' . $size->name,
                        'stock' => $stock ? $stock->stock : 0,
                    ];
                }
            } else {
                $stock = $color->stocks->first();
                $stockData[] = [
                    'variant_id' => $color->id,
                    'display_name' => $color->name,
                    'stock' => $stock ? $stock->stock : 0,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'toko' => [
                'id' => $toko->id,
                'nama' => $toko->nama_toko,
            ],
            'stocks' => $stockData
        ]);
    }
}
