<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    // Overview stok real time semua produk di semua toko
    public function index()
    {
        $products = Product::with(['stocks.toko'])->get();
        $tokos = Toko::all();

        return view('superadmin.stocks.index', compact('products', 'tokos'));
    }

    // Detail stok per produk (untuk halaman detail lengkap)
    public function show(Product $product)
    {
        $product->load(['stocks.toko']);
        $tokos = Toko::all();

        return view('superadmin.stocks.show', compact('product', 'tokos'));
    }

    // 🆕 AJAX: Get detail stok untuk modal
    public function getDetail(Product $product)
    {
        try {
            $product->load(['stocks.toko']);
            
            $totalAllocated = $product->stocks->sum('stock');
            $remainingStock = $product->remaining_initial_stock;
            
            // Format data untuk response
            $stocks = $product->stocks->map(function($stock) use ($totalAllocated) {
                return [
                    'toko_id' => $stock->toko_id,
                    'toko_name' => $stock->toko->nama_toko ?? 'Unknown',
                    'toko_status' => $stock->toko->status ?? 'unknown',
                    'stock' => (int) $stock->stock,
                    'percentage' => $totalAllocated > 0 ? round(($stock->stock / $totalAllocated) * 100, 1) : 0
                ];
            })->sortByDesc('stock')->values();
            
            $response = [
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'title' => $product->title,
                    'sku' => $product->sku,
                    'photo' => $product->photos && count($product->photos) > 0 
                        ? asset('storage/' . $product->photos[0]) 
                        : null,
                ],
                'initial_stock' => (int) $product->initial_stock,
                'total_allocated' => (int) $totalAllocated,
                'remaining_stock' => (int) $remainingStock,
                'stocks' => $stocks,
            ];
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('Error in getDetail: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // Update stok awal produk (hanya superadmin)
    public function updateInitialStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'initial_stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'initial_stock' => $validated['initial_stock'],
        ]);

        return back()->with('success', 'Stok awal berhasil diupdate!');
    }

    // Form set stok ke toko tertentu
    public function create(Product $product)
    {
        $tokos = Toko::all();
        
        return view('superadmin.stocks.create', compact('product', 'tokos'));
    }

    // Set/update stok ke toko tertentu
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'toko_id' => 'required|exists:tokos,id',
            'stock' => 'required|integer|min:0',
        ]);

        $remainingStock = $product->remaining_initial_stock;
        
        $currentStock = ProductStock::where('product_id', $product->id)
            ->where('toko_id', $validated['toko_id'])
            ->first();

        $currentlyAllocated = $currentStock ? $currentStock->stock : 0;
        $difference = $validated['stock'] - $currentlyAllocated;

        if ($difference > $remainingStock) {
            return back()->withErrors([
                'stock' => "Stok awal hanya tersisa {$remainingStock}. Tidak bisa menambahkan {$difference} stok."
            ])->withInput();
        }

        ProductStock::updateOrCreate(
            [
                'product_id' => $product->id,
                'toko_id' => $validated['toko_id'],
            ],
            [
                'stock' => $validated['stock'],
            ]
        );

        return redirect()
            ->route('superadmin.stocks.show', $product)
            ->with('success', 'Stok toko berhasil diupdate!');
    }

    // Form edit stok toko tertentu
    public function edit(Product $product, ProductStock $stock)
    {
        $tokos = Toko::all();
        
        return view('superadmin.stocks.edit', compact('product', 'stock', 'tokos'));
    }

    // Update stok toko tertentu
    public function update(Request $request, Product $product, ProductStock $stock)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $remainingStock = $product->remaining_initial_stock;
        $currentlyAllocated = $stock->stock;
        $difference = $validated['stock'] - $currentlyAllocated;

        if ($difference > $remainingStock) {
            return back()->withErrors([
                'stock' => "Stok awal hanya tersisa {$remainingStock}. Tidak bisa menambahkan {$difference} stok."
            ])->withInput();
        }

        $stock->update([
            'stock' => $validated['stock'],
        ]);

        return redirect()
            ->route('superadmin.stocks.show', $product)
            ->with('success', 'Stok toko berhasil diupdate!');
    }
}