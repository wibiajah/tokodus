<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Toko;

class FrontendController extends Controller
{
    /**
     * ✅ Tampilkan halaman home
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->select('id', 'name', 'slug', 'photo')
            ->orderBy('name', 'asc')
            ->get();

        $recommendedProducts = Product::where('is_active', true)
            ->with('categories:id,name,slug')
            ->withCount('reviews')
            ->latest()
            ->limit(8)
            ->get();

        $specialProducts = Product::where('is_active', true)
            ->with('categories:id,name,slug')
            ->withCount('reviews')
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $tokos = Toko::where('status', 'aktif')
            ->where('id', '!=', 999)
            ->select('id', 'nama_toko', 'alamat', 'telepon', 'email', 'googlemap_iframe')
            ->orderBy('nama_toko', 'asc')
            ->get();

        return view('frontend.index', [
            'page_title' => 'Tokodus | Solusi Packaging Anda',
            'categories' => $categories,
            'recommendedProducts' => $recommendedProducts,
            'specialProducts' => $specialProducts,
            'tokos' => $tokos,
        ]);
    }

    /**
     * ✅ Tampilkan halaman catalog dengan filter
     */
    public function catalog(Request $request)
    {
        // Query dasar produk
// Query dasar produk
        $query = Product::where('is_active', true)
            ->with(['categories:id,name,slug', 'stocks.toko', 'tokos:id,nama_toko'])
            ->withCount('reviews');

        // Filter by search keyword
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('sku', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Filter by categories (multiple)
        if ($request->filled('categories')) {
            $categoryIds = is_array($request->categories) 
                ? $request->categories 
                : explode(',', $request->categories);
            
            $query->whereHas('categories', function($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        // Filter by toko (multiple)
        if ($request->filled('tokos')) {
            $tokoIds = is_array($request->tokos) 
                ? $request->tokos 
                : explode(',', $request->tokos);
            
            $query->whereHas('stocks', function($q) use ($tokoIds) {
                $q->whereIn('toko_id', $tokoIds)
                  ->where('stock', '>', 0);
            });
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by availability
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->whereHas('stocks', function($q) {
                    $q->where('stock', '>', 0);
                });
            } elseif ($request->availability === 'out-of-stock') {
                $query->whereDoesntHave('stocks')
                      ->orWhereHas('stocks', function($q) {
                          $q->havingRaw('SUM(stock) = 0');
                      });
            }
        }

        // Filter by stock range
        if ($request->filled('stock_range') && $request->stock_range !== 'all') {
            $stockRange = $request->stock_range;
            
            switch ($stockRange) {
                case '1-50':
                    $query->whereHas('stocks', function($q) {
                        $q->havingRaw('SUM(stock) BETWEEN 1 AND 50');
                    });
                    break;
                case '51-100':
                    $query->whereHas('stocks', function($q) {
                        $q->havingRaw('SUM(stock) BETWEEN 51 AND 100');
                    });
                    break;
                case '101-500':
                    $query->whereHas('stocks', function($q) {
                        $q->havingRaw('SUM(stock) BETWEEN 101 AND 500');
                    });
                    break;
                case '500+':
                    $query->whereHas('stocks', function($q) {
                        $q->havingRaw('SUM(stock) > 500');
                    });
                    break;
            }
        }

        // Filter by discount
        if ($request->filled('discount') && $request->discount === 'true') {
            $query->whereNotNull('discount_price')
                  ->whereColumn('discount_price', '<', 'price');
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name-asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name-desc':
                $query->orderBy('title', 'desc');
                break;
            case 'price-low':
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;
            case 'price-high':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        // Paginate results
        $products = $query->paginate(12)->withQueryString();
        
        // Get categories with product count (only active products)
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name', 'asc')
            ->get();
        
        // Get active tokos
        $tokos = Toko::where('status', 'aktif')
            ->where('id', '!=', 999)
            ->select('id', 'nama_toko')
            ->orderBy('nama_toko', 'asc')
            ->get();

        // Pass filters to view for active filter display
        $activeFilters = [
            'search' => $request->search,
            'categories' => $request->categories,
            'tokos' => $request->tokos,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'availability' => $request->availability,
            'discount' => $request->discount,
            'sort' => $sort
        ];
        
        return view('frontend.catalog', [
            'page_title' => 'Katalog Produk | Tokodus',
            'products' => $products,
            'categories' => $categories,
            'tokos' => $tokos,
            'activeFilters' => $activeFilters
        ]);
    }

    /**
     * ✅ Filter produk by category
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = $category->products()
            ->where('is_active', true)
            ->with(['categories:id,name,slug', 'stocks.toko'])
            ->withCount('reviews')
            ->latest()
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
        
        if (empty($keyword)) {
            return redirect()->route('catalog');
        }

        $products = Product::where('is_active', true)
            ->where(function($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                      ->orWhere('sku', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%");
            })
            ->with(['categories:id,name,slug', 'stocks.toko'])
            ->withCount('reviews')
            ->latest()
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
        ->with(['categories:id,name,slug', 'stocks.toko'])
        ->withCount('reviews')
        ->inRandomOrder()
        ->limit(4)
        ->get();
    
    return view('frontend.product-detail', [
        'page_title' => $product->title . ' | Tokodus',
        'product' => $product,
        'relatedProducts' => $relatedProducts,
    ]);
}
}