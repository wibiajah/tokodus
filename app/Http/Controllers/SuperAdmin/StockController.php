<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Toko;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // Overview stok real time semua produk di semua toko
    public function index()
    {
        $products = Product::with(['stocks.toko'])->get();
        $tokos = Toko::all(); // 🔥 HAPUS filter is_active

        return view('superadmin.stocks.index', compact('products', 'tokos'));
    }

    // Detail stok per produk
    public function show(Product $product)
    {
        $product->load(['stocks.toko']);
        $tokos = Toko::all(); // 🔥 HAPUS filter is_active

        return view('superadmin.stocks.show', compact('product', 'tokos'));
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

    // 🔥 TAMBAHAN: Form set stok ke toko tertentu
    public function create(Product $product)
    {
        $tokos = Toko::all(); // 🔥 HAPUS filter is_active
        
        return view('superadmin.stocks.create', compact('product', 'tokos'));
    }

    // 🔥 TAMBAHAN: Set/update stok ke toko tertentu
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

    // 🔥 TAMBAHAN: Form edit stok toko tertentu
    public function edit(Product $product, ProductStock $stock)
    {
        $tokos = Toko::all(); // 🔥 HAPUS filter is_active
        
        return view('superadmin.stocks.edit', compact('product', 'stock', 'tokos'));
    }

    // 🔥 TAMBAHAN: Update stok toko tertentu
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