<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
     * Form distribute stok ke toko
     */
    public function create(Product $product)
    {
        $product->load([
            'variants' => function($query) {
                $query->with('children')->whereNull('parent_id');
            }
        ]);

        // Get all active tokos (exclude Head Office 999)
        $tokos = Toko::where('status', 'aktif')
            ->where('id', '!=', 999)
            ->orderBy('nama_toko')
            ->get();

        // Get leaf variants (yang bisa didistribute)
        $leafVariants = $product->variants->flatMap(function($color) {
            if ($color->hasChildren()) {
                // Jika punya children, ambil children-nya
                return $color->children;
            }
            // Jika tidak punya children, ambil color itu sendiri
            return [$color];
        });

        return view('superadmin.products.stock.distribute', compact('product', 'tokos', 'leafVariants'));
    }

    /**
     * Execute distribution (direct)
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

                // 1. Kurangi stock_pusat (warehouse)
                $variant->decrement('stock_pusat', $quantity);

                // 2. Tambah atau update stock di toko
                $variantStock = ProductVariantStock::firstOrCreate(
                    [
                        'variant_id' => $variant->id,
                        'toko_id' => $toko->id,
                    ],
                    [
                        'stock' => 0
                    ]
                );

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
                    'stock_before' => $variantStock->stock - $quantity,
                    'stock_after' => $variantStock->stock,
                ];
            }

            // 4. Create distribution log
            StockDistributionLog::create([
                'product_id' => $product->id,
                'toko_id' => $toko->id,
                'distributed_by' => auth()->id(),
                'items' => $distributedItems,
                'notes' => $request->notes,
                'type' => 'direct', // direct distribution (bukan dari request)
            ]);

            DB::commit();

            // 🔥 KIRIM NOTIFIKASI ke toko
            NotificationHelper::notifyTokoRoles(
                $toko->id,
                ['kepala_toko', 'staff_toko'],
                NotificationHelper::stockDistributed($product, $toko, $distributedItems, auth()->user())
            );

            return redirect()
                ->route('superadmin.products.stock.overview', $product)
                ->with('success', 'Stok berhasil didistribusikan ke ' . $toko->nama_toko);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal mendistribusikan stok: ' . $e->getMessage()]);
        }
    }

    /**
     * Stock breakdown (warehouse + tokos)
     */
    public function overview(Product $product)
    {
        $product->load([
            'variants' => function($query) {
                $query->with(['children.stocks.toko', 'stocks.toko'])
                    ->whereNull('parent_id');
            }
        ]);

        // Get all tokos
        $tokos = Toko::where('id', '!=', 999)
            ->where('status', 'aktif')
            ->orderBy('nama_toko')
            ->get();

        // Build stock overview per variant per toko
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

        return view('superadmin.products.stock.overview', compact('product', 'tokos', 'stockOverview'));
    }

    /**
     * Distribution history logs
     */
    public function history(Product $product)
    {
        $logs = StockDistributionLog::with(['toko', 'distributedBy'])
            ->where('product_id', $product->id)
            ->latest()
            ->paginate(20);

        return view('superadmin.products.stock.history', compact('product', 'logs'));
    }

    /**
     * Show single distribution log detail
     */
    public function showLog(StockDistributionLog $log)
    {
        $log->load(['product', 'toko', 'distributedBy']);

        return view('superadmin.products.stock.log-detail', compact('log'));
    }

    /**
     * AJAX: Get available stock for variant
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
     * AJAX: Get stock overview for specific toko
     */
    public function getTokoStock(Product $product, Toko $toko)
    {
        $product->load([
            'variants' => function($query) use ($toko) {
                $query->with([
                    'children.stocks' => function($q) use ($toko) {
                        $q->where('toko_id', $toko->id);
                    },
                    'stocks' => function($q) use ($toko) {
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