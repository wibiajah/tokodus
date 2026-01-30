<?php

namespace App\Http\Controllers\KepalaToko;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductVariantStock;
use App\Models\StockDistributionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $tokoId = auth()->user()->toko_id;
        $search = $request->get('search');
        $filter = $request->get('filter', 'all');
        
        // Query SEMUA produk dari warehouse
        $query = Product::with([
            'categories:id,name',
            'variants' => function($q) {
                $q->whereNull('parent_id')->with('children');
            }
        ])
        ->whereNull('deleted_at');
        
        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        $products = $query->orderBy('title', 'asc')->get();
        
        // Untuk setiap produk, cek status di toko ini
        foreach ($products as $product) {
            // Cek apakah produk sudah ada di toko
            $variantStocks = ProductVariantStock::whereHas('variant', function($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->where('toko_id', $tokoId)
            ->get();
            
            $product->is_in_my_toko = $variantStocks->count() > 0;
            $product->my_stock = $variantStocks->sum('stock');
            $product->is_active_in_toko = $variantStocks->where('is_active', true)->count() > 0;
            
            // Hitung total stok warehouse (stock_pusat)
            $product->warehouse_stock = $product->variants->sum(function($variant) {
                if ($variant->hasChildren()) {
                    return $variant->children->sum('stock_pusat');
                }
                return $variant->stock_pusat;
            });
            
            // 🆕 Flag untuk sorting: produk tidak aktif dari superadmin
            $product->is_product_inactive_by_admin = !$product->is_active;
        }
        
        // 🆕 Sort: Produk tidak aktif di paling bawah
        $products = $products->sortBy([
            ['is_product_inactive_by_admin', 'asc'], // false (active) dulu
            ['is_in_my_toko', 'desc'], // yang sudah di toko dulu
            ['title', 'asc']
        ])->values();
        
        // Filter berdasarkan status
        $products = $products->filter(function($product) use ($filter) {
            if ($filter === 'in_toko') {
                return $product->is_in_my_toko && $product->is_active_in_toko && $product->is_active;
            } elseif ($filter === 'not_in_toko') {
                return !$product->is_in_my_toko && $product->is_active;
            } elseif ($filter === 'inactive') {
                return $product->is_in_my_toko && !$product->is_active_in_toko;
            } elseif ($filter === 'inactive_by_admin') {
                return !$product->is_active; // Produk dinonaktifkan superadmin
            }
            return true; // 'all'
        });
        
        return view('kepala-toko.stocks.index', compact('products', 'search', 'filter'));
    }

    public function create(Product $product)
    {
        // 🆕 Validasi: Cek apakah produk aktif
        if (!$product->is_active) {
            return redirect()
                ->route('kepala-toko.stocks.index')
                ->withErrors(['error' => 'Produk ini telah dinonaktifkan oleh SuperAdmin. Anda tidak dapat menambahkan stok.']);
        }
        
        $tokoId = auth()->user()->toko_id;
        
        $product->load([
            'variants' => function($q) use ($tokoId) {
                $q->whereNull('parent_id')
                  ->with([
                      'children.stocks' => function($stockQuery) use ($tokoId) {
                          $stockQuery->where('toko_id', $tokoId);
                      },
                      'stocks' => function($stockQuery) use ($tokoId) {
                          $stockQuery->where('toko_id', $tokoId);
                      }
                  ]);
            }
        ]);

        return view('kepala-toko.stocks.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        // 🆕 Validasi: Cek apakah produk aktif
        if (!$product->is_active) {
            return back()->withErrors([
                'error' => 'Produk ini telah dinonaktifkan oleh SuperAdmin. Anda tidak dapat menambahkan stok.'
            ])->withInput();
        }
        
        $tokoId = auth()->user()->toko_id;

        $validated = $request->validate([
            'variants' => 'required|array',
            'variants.*.variant_id' => 'required|exists:product_variants,id',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        foreach ($validated['variants'] as $variantData) {
            $variantId = $variantData['variant_id'];
            $stock = $variantData['stock'];

            $variant = \App\Models\ProductVariant::find($variantId);
            
            if ($stock > $variant->stock_pusat) {
                return back()->withErrors([
                    'stock' => "Stok warehouse untuk {$variant->display_name} hanya tersisa {$variant->stock_pusat}."
                ])->withInput();
            }

            ProductVariantStock::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'variant_id' => $variantId,
                    'toko_id' => $tokoId,
                ],
                [
                    'stock' => $stock,
                ]
            );
        }

        return redirect()
            ->route('kepala-toko.stocks.index')
            ->with('success', 'Stok berhasil diset!');
    }

    public function edit(Product $product)
    {
        // 🆕 Validasi: Cek apakah produk aktif
        if (!$product->is_active) {
            return redirect()
                ->route('kepala-toko.stocks.index')
                ->withErrors(['error' => 'Produk ini telah dinonaktifkan oleh SuperAdmin. Anda tidak dapat mengedit stok.']);
        }
        
        $tokoId = auth()->user()->toko_id;
        
        $product->load([
            'variants' => function($q) use ($tokoId) {
                $q->whereNull('parent_id')
                  ->with([
                      'children.stocks' => function($stockQuery) use ($tokoId) {
                          $stockQuery->where('toko_id', $tokoId);
                      },
                      'stocks' => function($stockQuery) use ($tokoId) {
                          $stockQuery->where('toko_id', $tokoId);
                      }
                  ]);
            }
        ]);

        return view('kepala-toko.stocks.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // 🆕 Validasi: Cek apakah produk aktif
        if (!$product->is_active) {
            return back()->withErrors([
                'error' => 'Produk ini telah dinonaktifkan oleh SuperAdmin. Anda tidak dapat mengedit stok.'
            ])->withInput();
        }
        
        $tokoId = auth()->user()->toko_id;

        $validated = $request->validate([
            'variants' => 'required|array',
            'variants.*.variant_id' => 'required|exists:product_variants,id',
            'variants.*.action_type' => 'required|in:add,reduce,set',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            $editedItems = [];

            foreach ($validated['variants'] as $variantData) {
                $variantId = $variantData['variant_id'];
                $actionType = $variantData['action_type'];
                $inputValue = $variantData['stock'];

                $currentStock = ProductVariantStock::where('product_id', $product->id)
                    ->where('variant_id', $variantId)
                    ->where('toko_id', $tokoId)
                    ->first();

                if (!$currentStock) {
                    throw new \Exception('Stok belum diset untuk varian ini.');
                }

                $currentStockValue = $currentStock->stock;
                $newStockValue = 0;

                switch ($actionType) {
                    case 'add':
                        $newStockValue = $currentStockValue + $inputValue;
                        break;

                    case 'reduce':
                        $newStockValue = max(0, $currentStockValue - $inputValue);
                        break;

                    case 'set':
                        $newStockValue = $inputValue;
                        break;
                }

                $variant = \App\Models\ProductVariant::find($variantId);
                $changeInStock = $newStockValue - $currentStockValue;

                if ($changeInStock > 0 && $changeInStock > $variant->stock_pusat) {
                    throw new \Exception("Stok warehouse untuk {$variant->display_name} hanya tersisa {$variant->stock_pusat}.");
                }

                $currentStock->update([
                    'stock' => $newStockValue,
                ]);

                if ($changeInStock != 0) {
                    try {
                        $logData = [
                            'product_id' => (int) $product->id,
                            'variant_id' => (int) $variantId,
                            'toko_id' => (int) $tokoId,
                            'quantity' => (int) $changeInStock,
                            'type' => 'manual_adjustment',
                            'source_type' => 'manual_edit',
                            'source_id' => null,
                            'performed_by' => (int) auth()->id(),
                            'stock_before' => (int) $currentStockValue,
                            'stock_after' => (int) $newStockValue,
                            'notes' => "Edit manual - {$actionType}: {$inputValue} unit",
                        ];
                        
                        Log::info('Attempting Eloquent create:', $logData);
                        $createdLog = StockDistributionLog::create($logData);
                        
                        $freshLog = StockDistributionLog::find($createdLog->id);
                        
                        if (empty($freshLog->type) || empty($freshLog->source_type)) {
                            Log::warning('Eloquent saved empty type/source_type, using raw query');
                            
                            DB::table('stock_distribution_logs')
                                ->where('id', $createdLog->id)
                                ->update([
                                    'type' => 'manual_adjustment',
                                    'source_type' => 'manual_edit',
                                ]);
                            
                            $freshLog = StockDistributionLog::find($createdLog->id);
                            Log::info('After raw update:', [
                                'type' => $freshLog->type,
                                'source_type' => $freshLog->source_type,
                            ]);
                        } else {
                            Log::info('Eloquent create successful:', [
                                'id' => $createdLog->id,
                                'type' => $freshLog->type,
                                'source_type' => $freshLog->source_type,
                            ]);
                        }
                        
                    } catch (\Exception $e) {
                        Log::error('Failed to create log:', [
                            'error' => $e->getMessage(),
                            'data' => $logData ?? null,
                        ]);
                    }

                    $editedItems[] = [
                        'variant_id' => $variantId,
                        'variant_name' => $variant->display_name,
                        'stock_before' => $currentStockValue,
                        'stock_after' => $newStockValue,
                        'change' => $changeInStock,
                        'action' => $actionType,
                    ];
                }
            }

            DB::commit();

            if (!empty($editedItems)) {
                try {
                    $toko = auth()->user()->toko;
                    \App\Helpers\NotificationHelper::stockManuallyEdited($product, $toko, $editedItems, auth()->user());
                } catch (\Exception $e) {
                    Log::warning('Failed to send notification: ' . $e->getMessage());
                }
            }

            return redirect()
                ->route('kepala-toko.stocks.index')
                ->with('success', "Stok berhasil diupdate!");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Stock update failed: ' . $e->getMessage());
            
            return back()->withErrors([
                'stock' => $e->getMessage()
            ])->withInput();
        }
    }

    public function addProductToToko(Product $product)
    {
        // 🆕 Validasi: Cek apakah produk aktif
        if (!$product->is_active) {
            return back()->withErrors(['error' => 'Produk ini telah dinonaktifkan oleh SuperAdmin. Anda tidak dapat menambahkannya ke toko.']);
        }
        
        $tokoId = auth()->user()->toko_id;
        
        $existingStocks = ProductVariantStock::whereHas('variant', function($q) use ($product) {
            $q->where('product_id', $product->id);
        })
        ->where('toko_id', $tokoId)
        ->exists();
        
        if ($existingStocks) {
            return back()->withErrors(['error' => 'Produk sudah ada di toko Anda!']);
        }
        
        $product->load(['variants' => function($q) {
            $q->whereNull('parent_id')->with('children');
        }]);
        
        $leafVariants = $product->variants->flatMap(function($color) {
            if ($color->hasChildren()) {
                return $color->children;
            }
            return [$color];
        });
        
        foreach ($leafVariants as $variant) {
            ProductVariantStock::create([
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'toko_id' => $tokoId,
                'stock' => 0,
                'is_active' => true,
            ]);
        }
        
        $toko = auth()->user()->toko;
        \App\Helpers\NotificationHelper::productAddedToToko($product, $toko, auth()->user());
        
        return redirect()
            ->route('kepala-toko.stocks.edit', $product)
            ->with('success', "Produk '{$product->title}' berhasil ditambahkan ke toko Anda! Silakan set stok awal.");
    }

    public function toggleProductStatus(Product $product)
    {
        try {
            // 🆕 Validasi: Tidak bisa toggle jika produk dinonaktifkan superadmin
            if (!$product->is_active) {
                return back()->withErrors(['error' => 'Produk ini telah dinonaktifkan oleh SuperAdmin. Anda tidak dapat mengubah statusnya.']);
            }
            
            $tokoId = auth()->user()->toko_id;
            
            $variantStocks = ProductVariantStock::whereHas('variant', function($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->where('toko_id', $tokoId)
            ->get();
            
            if ($variantStocks->isEmpty()) {
                return back()->withErrors(['error' => 'Produk tidak ditemukan di toko Anda!']);
            }
            
            $hasActive = $variantStocks->where('is_active', 1)->count() > 0;
            $newStatus = !$hasActive;
            
            ProductVariantStock::whereHas('variant', function($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->where('toko_id', $tokoId)
            ->update(['is_active' => $newStatus]);
            
            $toko = auth()->user()->toko;
            \App\Helpers\NotificationHelper::productStatusToggledInToko($product, $toko, $newStatus, auth()->user());
            
            $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
            
            return back()->with('success', "Produk '{$product->title}' berhasil {$statusText}!");
            
        } catch (\Exception $e) {
            Log::error('Toggle Product Status Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengubah status produk.']);
        }
    }
}