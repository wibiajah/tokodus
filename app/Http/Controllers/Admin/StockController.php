<?php

namespace App\Http\Controllers\admin;

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
        $tokos = Toko::where('is_active', true)->get();

        return view('admin.stocks.index', compact('products', 'tokos'));
    }

    // Detail stok per produk
    public function show(Product $product)
    {
        $product->load(['stocks.toko']);
        $tokos = Toko::where('is_active', true)->get();

        return view('admin.stocks.show', compact('product', 'tokos'));
    }

    // Update stok awal produk (hanya super admin)
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
}