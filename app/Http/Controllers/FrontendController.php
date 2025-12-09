<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class FrontendController extends Controller
{
    /**
     * ✅ STEP 1: Tampilkan halaman home dengan kategori dan produk dari database
     */
    public function index()
    {
        // 🔥 Ambil kategori aktif dari database
        $categories = Category::where('is_active', true)
            ->select('id', 'name', 'slug', 'photo')
            ->orderBy('name', 'asc')
            ->get();

        // 🔥 Ambil produk untuk "Our Recommended Product" (8 produk terbaru)
        $recommendedProducts = Product::where('is_active', true)
            ->with('categories:id,name,slug')
            ->withCount('reviews')
            ->latest()
            ->limit(8)
            ->get();

        // 🔥 Ambil produk untuk "Special Edition" (8 produk dengan rating tertinggi atau featured)
        $specialProducts = Product::where('is_active', true)
            ->with('categories:id,name,slug')
            ->withCount('reviews')
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // 🔥 Kirim data ke view
        return view('frontend.index', [
            'page_title' => 'Tokodus | Solusi Packaging Anda',
            'categories' => $categories,
            'recommendedProducts' => $recommendedProducts,
            'specialProducts' => $specialProducts,
        ]);
    }

    /**
     * ✅ Tampilkan halaman catalog
     */
    public function catalog()
    {
        $products = Product::where('is_active', true)
            ->with('categories')
            ->withCount('reviews')
            ->paginate(12);
        
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();
        
        return view('frontend.catalog', [
            'page_title' => 'Catalog | Tokodus',
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * ✅ Filter produk by category
     */
    public function category($slug)
    {
        // Cari kategori berdasarkan slug
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Ambil produk yang ada di kategori ini
        $products = $category->products()
            ->where('is_active', true)
            ->withCount('reviews')
            ->paginate(12);
        
        return view('frontend.category', [
            'page_title' => $category->name . ' | Tokodus',
            'category' => $category,
            'products' => $products,
        ]);
    }

    /**
     * ✅ Search produk
     */
    public function search(Request $request)
    {
        $keyword = $request->input('q');
        
        $products = Product::where('is_active', true)
            ->where(function($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                      ->orWhere('sku', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%");
            })
            ->with('categories')
            ->withCount('reviews')
            ->paginate(12);
        
        return view('frontend.search', [
            'page_title' => 'Search: ' . $keyword . ' | Tokodus',
            'keyword' => $keyword,
            'products' => $products,
        ]);
    }

    /**
     * ✅ Detail produk
     */
    public function productDetail($id)
    {
        $product = Product::where('is_active', true)
            ->with(['categories', 'stocks.toko', 'reviews.user'])
            ->findOrFail($id);
        
        // Produk terkait (same category)
        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->withCount('reviews')
            ->limit(4)
            ->get();
        
        return view('frontend.product-detail', [
            'page_title' => $product->title . ' | Tokodus',
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}