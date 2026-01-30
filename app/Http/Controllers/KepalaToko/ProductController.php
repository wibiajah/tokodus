<?php

namespace App\Http\Controllers\KepalaToko;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariantStock;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * List semua produk (READ ONLY + bisa request stok)
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Pastikan user punya toko_id
        if (!$user->toko_id) {
            abort(403, 'Anda tidak terdaftar di toko manapun.');
        }

        $query = Product::with([
            'categories:id,name',
            'variants' => function($query) {
                $query->whereNull('parent_id')->with('children'); // Load parent colors with sizes
            }
        ])
        ->where('is_active', true); // Hanya produk aktif

        // Filter by search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'stock_low':
                // Sort by total_stock (computed attribute)
                // Ini akan di-handle di view karena computed attribute
                $query->latest();
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);

        // Get stok di toko kepala toko yang login untuk setiap produk
        $tokoId = $user->toko_id;
        
        foreach ($products as $product) {
            // Get total stok di toko ini (sum dari semua varian)
            $product->toko_stock = ProductVariantStock::whereHas('variant', function($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->where('toko_id', $tokoId)
            ->sum('stock');
        }

        // Get categories untuk filter
        $categories = Category::where('is_active', true)->get();

        return view('kepala-toko.products.index', compact('products', 'categories', 'sortBy'));
    }

    /**
     * Detail produk (READ ONLY)
     */
    public function show(Product $product)
    {
        $user = auth()->user();

        // Pastikan user punya toko_id
        if (!$user->toko_id) {
            abort(403, 'Anda tidak terdaftar di toko manapun.');
        }

        $product->load([
            'categories',
            'variants' => function($query) {
                $query->with('children')->whereNull('parent_id');
            }
        ]);

        // Get stok per varian di toko kepala toko yang login
        $tokoId = $user->toko_id;
        $variantStocks = [];

        foreach ($product->variants as $color) {
            if ($color->hasChildren()) {
                // Jika punya sizes
                foreach ($color->children as $size) {
                    $stock = ProductVariantStock::where('variant_id', $size->id)
                        ->where('toko_id', $tokoId)
                        ->first();
                    
                    $variantStocks[$size->id] = $stock ? $stock->stock : 0;
                }
            } else {
                // Jika tidak punya sizes
                $stock = ProductVariantStock::where('variant_id', $color->id)
                    ->where('toko_id', $tokoId)
                    ->first();
                
                $variantStocks[$color->id] = $stock ? $stock->stock : 0;
            }
        }

        return view('kepala-toko.products.show', compact('product', 'variantStocks'));
    }
}