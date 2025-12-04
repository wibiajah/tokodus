<?php

namespace App\Http\Controllers\KepalaToko;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $tokoId = auth()->user()->toko_id;
        
        $products = Product::with(['stocks' => function($q) use ($tokoId) {
            $q->where('toko_id', $tokoId);
        }])->get();

        return view('kepala-toko.stocks.index', compact('products'));
    }

    // Form set stok
    public function create(Product $product)
    {
        $tokoId = auth()->user()->toko_id;
        $currentStock = ProductStock::where('product_id', $product->id)
            ->where('toko_id', $tokoId)
            ->first();

        // 🔥 UBAH INI: dari 'set' jadi 'create'
        return view('kepala-toko.stocks.create', compact('product', 'currentStock'));
    }

    // Set stok
    public function store(Request $request, Product $product)
    {
        $tokoId = auth()->user()->toko_id;

        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $remainingStock = $product->remaining_initial_stock;
        
        $currentStock = ProductStock::where('product_id', $product->id)
            ->where('toko_id', $tokoId)
            ->first();

        $currentlyAllocated = $currentStock ? $currentStock->stock : 0;
        $difference = $validated['stock'] - $currentlyAllocated;

        if ($difference > $remainingStock) {
            return back()->withErrors([
                'stock' => "Stok awal hanya tersisa {$remainingStock}."
            ]);
        }

        ProductStock::updateOrCreate(
            [
                'product_id' => $product->id,
                'toko_id' => $tokoId,
            ],
            [
                'stock' => $validated['stock'],
            ]
        );

        return redirect()
            ->route('kepala-toko.stocks.index')
            ->with('success', 'Stok berhasil diupdate!');
    }

    // Edit stok
    public function edit(Product $product)
    {
        $tokoId = auth()->user()->toko_id;
        $currentStock = ProductStock::where('product_id', $product->id)
            ->where('toko_id', $tokoId)
            ->first();

        // 🔥 UBAH INI: dari 'set' jadi 'edit'
        return view('kepala-toko.stocks.edit', compact('product', 'currentStock'));
    }

    // Update stok
    public function update(Request $request, Product $product)
    {
        return $this->store($request, $product);
    }
}