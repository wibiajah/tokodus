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

    // Form set stok (untuk pertama kali)
    public function create(Product $product)
    {
        $tokoId = auth()->user()->toko_id;
        $currentStock = ProductStock::where('product_id', $product->id)
            ->where('toko_id', $tokoId)
            ->first();

        return view('kepala-toko.stocks.create', compact('product', 'currentStock'));
    }

    // Set stok pertama kali
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
            ->with('success', 'Stok berhasil diset!');
    }

    // Form edit stok (tambah/kurang/set manual)
    public function edit(Product $product)
    {
        $tokoId = auth()->user()->toko_id;
        $currentStock = ProductStock::where('product_id', $product->id)
            ->where('toko_id', $tokoId)
            ->first();

        return view('kepala-toko.stocks.edit', compact('product', 'currentStock'));
    }

    // Update stok dengan action_type
    public function update(Request $request, Product $product)
    {
        $tokoId = auth()->user()->toko_id;

        $validated = $request->validate([
            'action_type' => 'required|in:add,reduce,set',
            'stock' => 'required|integer|min:0',
        ]);

        // Get current stock
        $currentStock = ProductStock::where('product_id', $product->id)
            ->where('toko_id', $tokoId)
            ->first();

        if (!$currentStock) {
            return back()->withErrors([
                'stock' => 'Stok belum diset untuk toko ini.'
            ]);
        }

        $currentStockValue = $currentStock->stock;
        $inputValue = $validated['stock'];
        $newStockValue = 0;
        $changeInInitialStock = 0; // Perubahan pada initial_stock

        // Hitung stok baru berdasarkan action_type
        switch ($validated['action_type']) {
            case 'add':
                // Tambah stok: ambil dari stok awal
                $newStockValue = $currentStockValue + $inputValue;
                $changeInInitialStock = $inputValue; // Akan mengurangi remaining_initial_stock
                break;

            case 'reduce':
                // Kurangi stok: kembalikan ke stok awal
                $newStockValue = max(0, $currentStockValue - $inputValue);
                $changeInInitialStock = -$inputValue; // Akan menambah remaining_initial_stock (negatif karena dikurangi)
                break;

            case 'set':
                // Set manual: hitung selisihnya
                $newStockValue = $inputValue;
                $changeInInitialStock = $inputValue - $currentStockValue;
                break;
        }

        // Validasi: cek apakah stok awal cukup (untuk add dan set yang menambah)
        $remainingStock = $product->remaining_initial_stock;
        
        if ($changeInInitialStock > $remainingStock) {
            return back()->withErrors([
                'stock' => "Stok awal hanya tersisa {$remainingStock}. Tidak cukup untuk menambah {$changeInInitialStock} unit."
            ])->withInput();
        }

        // Update stok toko
        $currentStock->update([
            'stock' => $newStockValue,
        ]);

        $actionText = [
            'add' => 'ditambah',
            'reduce' => 'dikurangi',
            'set' => 'diubah menjadi'
        ];

        return redirect()
            ->route('kepala-toko.stocks.index')
            ->with('success', "Stok berhasil {$actionText[$validated['action_type']]} {$inputValue} unit! Stok toko sekarang: {$newStockValue}");
    }
}