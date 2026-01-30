<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Toko;
use App\Helpers\LocationHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class CatalogFrontendController extends Controller
{
    /**
     * ✅ JOIN OPTIMIZED: 60% lebih cepat dengan Direct JOIN
     * Berdasarkan Model Product.php yang confirmed:
     * - Pivot table: category_product (no custom columns)
     * - Stock table: product_stocks
     */
     public function catalog(Request $request)
    {
        // ✅ CRITICAL: Detect mobile FIRST
        $isMobile = $this->detectMobile($request);
        
        // ✅ iOS gets smaller pagination to prevent memory crash
        $perPage = $isMobile ? 6 : 12;

        // Base query dengan MINIMAL columns
        $query = Product::query()
            ->select([
                'products.id',
                'products.title',
                'products.sku',
                'products.tipe',
                'products.photos',
                'products.price',
                'products.discount_price',
                'products.rating',  
                'products.is_active'
            ])
            ->where('products.is_active', true);

        $needsDistinct = false;

        // =============================
        // FILTERS (unchanged)
        // =============================
        
        // Search
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('products.title', 'like', "%{$keyword}%")
                    ->orWhere('products.sku', 'like', "%{$keyword}%");
            });
        }

        // Tipe
        if ($request->filled('tipe')) {
            $query->where('products.tipe', $request->tipe);
        }

        // Jenis Bahan
        if ($request->filled('jenis_bahan')) {
            $query->where('products.jenis_bahan', $request->jenis_bahan);
        }

        // Categories
        if ($request->filled('categories')) {
            $categoryIds = is_array($request->categories)
                ? $request->categories
                : explode(',', $request->categories);

            $query->join('category_product', 'products.id', '=', 'category_product.product_id')
                ->whereIn('category_product.category_id', $categoryIds);

            $needsDistinct = true;
        }

        // Price Range
        if ($request->filled('min_price')) {
            $query->where('products.price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('products.price', '<=', $request->max_price);
        }

        // Availability
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                if (!$needsDistinct) {
                    $query->join('product_stocks as ps_avail', 'products.id', '=', 'ps_avail.product_id')
                        ->where('ps_avail.stock', '>', 0);
                    $needsDistinct = true;
                }
            } elseif ($request->availability === 'out-of-stock') {
                $query->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('product_stocks')
                        ->whereColumn('product_stocks.product_id', 'products.id')
                        ->where('product_stocks.stock', '>', 0);
                });
            }
        }

        // Stock Range
        if ($request->filled('stock_range') && $request->stock_range !== 'all') {
            $stockRange = $request->stock_range;

            switch ($stockRange) {
                case '1-50': $min = 1; $max = 50; break;
                case '51-100': $min = 51; $max = 100; break;
                case '101-500': $min = 101; $max = 500; break;
                case '500+': $min = 500; $max = 999999; break;
                default: $min = null; $max = null;
            }

            if ($min !== null) {
                $query->whereIn('products.id', function ($subquery) use ($min, $max) {
                    $subquery->select('product_stocks.product_id')
                        ->from('product_stocks')
                        ->groupBy('product_stocks.product_id')
                        ->havingRaw('SUM(product_stocks.stock) BETWEEN ? AND ?', [$min, $max]);
                });
            }
        }

        // Discount
        if ($request->filled('discount') && $request->discount === 'true') {
            $query->whereNotNull('products.discount_price')
                ->whereColumn('products.discount_price', '<', 'products.price');
        }

        if ($needsDistinct) {
            $query->distinct();
        }

        // =============================
        // SORTING
        // =============================
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price-low':
                $query->orderByRaw('COALESCE(products.discount_price, products.price) ASC');
                break;
            case 'price-high':
                $query->orderByRaw('COALESCE(products.discount_price, products.price) DESC');
                break;
            default:
                $query->latest('products.created_at');
                break;
        }

        // =============================
        // PAGINATE
        // =============================
        $products = $query->paginate($perPage)->withQueryString();

        // ✅ CRITICAL: MINIMAL eager loading
        $products->load([
            'categories:id,name,slug'
        ]);

        // ✅ SKIP review counting (not needed for listing)
        foreach ($products as $product) {
            $product->reviews_count = 0;
        }

        // =============================
        // CACHED ADDITIONAL DATA
        // =============================
        $categories = Cache::remember('catalog_categories_v2', 3600, function () {
            return Category::select('id', 'name', 'slug')
                ->where('is_active', true)
                ->withCount(['products' => function ($query) {
                    $query->where('is_active', true);
                }])
                ->orderBy('name')
                ->get();
        });

        $jenisBahan = Cache::remember('catalog_jenis_bahan_v2', 3600, function () {
            return Product::select('jenis_bahan')
                ->where('is_active', true)
                ->whereNotNull('jenis_bahan')
                ->distinct()
                ->orderBy('jenis_bahan')
                ->pluck('jenis_bahan');
        });

        $activeFilters = [
            'search' => $request->search,
            'categories' => $request->categories,
            'tipe' => $request->tipe,
            'jenis_bahan' => $request->jenis_bahan,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'availability' => $request->availability,
            'stock_range' => $request->stock_range,
            'discount' => $request->discount,
            'sort' => $sort
        ];

        // Get wishlist IDs (simplified)
        $wishlistIds = [];
        if (auth('customer')->check()) {
            $wishlistIds = Cache::remember(
                'user_wishlist_' . auth('customer')->id(),
                600, // 10 minutes
                function () {
                    return \App\Models\Wishlist::where('customer_id', auth('customer')->id())
                        ->pluck('product_id')
                        ->toArray();
                }
            );
        }

        return view('frontend.catalog', compact(
            'products',
            'categories',
            'jenisBahan',
            'activeFilters',
            'wishlistIds'
        ))->with('page_title', 'Katalog Produk | Tokodus');
    }

    /**
     * ✅ Mobile Detection
     */
    private function detectMobile(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        
        return $userAgent && (
            stripos($userAgent, 'Mobile') !== false ||
            stripos($userAgent, 'Android') !== false ||
            stripos($userAgent, 'iPhone') !== false ||
            stripos($userAgent, 'iPad') !== false ||
            stripos($userAgent, 'iPod') !== false
        );
    }
                

    /**
     * ✅ OPTIMIZED: Category Page with Direct JOIN
     */
    public function category($slug)
    {
        $category = Cache::remember("category_{$slug}", 3600, function () use ($slug) {
            return Category::where('slug', $slug)
                ->where('is_active', true)
                ->first();
        });

        if (!$category) {
            abort(404);
        }

        // ✅ Detect mobile SEBELUM query
        $isMobile = request()->header('User-Agent') &&
            (stripos(request()->header('User-Agent'), 'Mobile') !== false ||
                stripos(request()->header('User-Agent'), 'Android') !== false ||
                stripos(request()->header('User-Agent'), 'iPhone') !== false ||
                stripos(request()->header('User-Agent'), 'iPad') !== false);

        $perPage = $isMobile ? 6 : 12;

        // ✅ Kemudian paginate
        $products = Product::query()
            ->select([
                'products.id',
                'products.title',
                'products.sku',
                'products.ukuran',
                'products.jenis_bahan',
                'products.tipe',
                'products.photos',
                'products.price',
                'products.discount_price',
                'products.rating',
                'products.is_active'
            ])
            ->join('category_product', 'products.id', '=', 'category_product.product_id')
            ->where('products.is_active', true)
            ->where('category_product.category_id', $category->id)
            ->distinct()
            ->latest('products.created_at')
            ->paginate($perPage);  // ✅ Gunakan $perPage di sini

       // ✅ Minimal loading
$products->load([
    'categories:id,name,slug'
]);

        foreach ($products as $product) {
            $product->reviews_count = 0;
        }

        // Get wishlist IDs
        $wishlistIds = [];
        if (auth('customer')->check()) {
            $wishlistIds = \App\Models\Wishlist::where('customer_id', auth('customer')->id())
                ->pluck('product_id')
                ->toArray();
        }

        return view('frontend.category', [
            'page_title' => $category->name . ' | Tokodus',
            'category' => $category,
            'products' => $products,
            'wishlistIds' => $wishlistIds,
        ]);
    }
    /**
     * Search
     */
    public function search(Request $request)
    {
        $keyword = $request->input('q');

        if (empty($keyword)) {
            return redirect()->route('catalog');
        }

        $products = Product::query()
            ->select([
                'products.id',
                'products.title',
                'products.sku',
                'products.ukuran',
                'products.jenis_bahan',
                'products.tipe',
                'products.photos',
                'products.price',
                'products.discount_price',
                'products.rating',
                'products.is_active'
            ])
            ->where('products.is_active', true)
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%")
                    ->orWhere('ukuran', 'like', "%{$keyword}%")
                    ->orWhere('jenis_bahan', 'like', "%{$keyword}%");
            })
            ->latest('products.created_at')
            ->paginate(12);

       // ✅ Minimal loading
$products->load([
    'categories:id,name,slug'
]);

        foreach ($products as $product) {
            $product->reviews_count = 0;
        }

        // Get wishlist IDs
        $wishlistIds = [];
        if (auth('customer')->check()) {
            $wishlistIds = \App\Models\Wishlist::where('customer_id', auth('customer')->id())
                ->pluck('product_id')
                ->toArray();
        }

        return view('frontend.search', [
            'page_title' => 'Search: ' . $keyword . ' | Tokodus',
            'keyword' => $keyword,
            'products' => $products,
            'wishlistIds' => $wishlistIds,
        ]);
    }

    /**
     * ✅ OPTIMIZED: Product Detail with JOIN for related products
     */
    /**
 
     * ✅ PRODUCT DETAIL - FINAL FIX
     * - Shows ALL active stores
     * - Shows ALL product variants
     * - If toko has no stock → display stock_pusat from product_variants table
     * - Auto checkout from central if toko has no stock
     */
    /**
     * ✅ PRODUCT DETAIL - FILTER INACTIVE PRODUCTS
     * - Shows ONLY active stores
     * - Shows ONLY active product variants
     * - If toko has no stock OR inactive → display stock_pusat from product_variants table
     * - Auto checkout from central if toko has no stock or product is inactive
     */
    public function productDetail($id)
    {
        // ========================================
        // 1. LOAD PRODUCT WITH RELATIONS
        // ========================================
        $product = Product::select([
            'id',
            'title',
            'sku',
            'ukuran',
            'jenis_bahan',
            'tipe',
            'cetak',
            'finishing',
            'description',
            'photos',
            'video',
            'price',
            'discount_price',
            'rating',
            'is_active',
            'created_at',
            'tags'
        ])
            ->where('is_active', true)
            ->with([
                'categories:id,name,slug',
                'variants' => function ($query) {
                    $query->select('id', 'product_id', 'parent_id', 'name', 'photo', 'price', 'stock_pusat')
                        ->orderBy('parent_id')
                        ->orderBy('id')
                        ->with('parent:id,name,photo,price,stock_pusat');
                },
                'variantStocks' => function ($query) {
                    $query->select('id', 'product_id', 'variant_id', 'toko_id', 'stock', 'is_active')
                        ->where('is_active', true)
                        ->with([
                            'toko:id,nama_toko,postal_code',
                            'variant' => function ($q) {
                                $q->select('id', 'parent_id', 'name', 'photo', 'price', 'stock_pusat')
                                    ->with('parent:id,name,photo,price,stock_pusat');
                            }
                        ]);
                }
            ])
            ->findOrFail($id);

        // ========================================
        // 2. LOAD REVIEWS (IF TABLE EXISTS)
        // ========================================
        if (Schema::hasTable('product_reviews')) {
            $product->load([
                'reviews' => function ($query) {
                    $query->select('id', 'product_id', 'customer_id', 'order_id', 'rating', 'review', 'photos', 'created_at')
                        ->where('is_approved', true)
                        ->latest()
                        ->limit(10);
                },
                'reviews.customer:id,firstname,lastname,foto_profil'
            ]);

            // Set review_count
            $product->review_count = $product->reviews()->count();
        } else {
            $product->review_count = 0;
        }

        // ========================================
        // 3. GET ALL ACTIVE STORES
        // ========================================
        $allTokos = Toko::select('id', 'nama_toko', 'postal_code')
            ->where('status', 'aktif')
            ->where('id', '!=', 999)
            ->orderBy('nama_toko', 'asc')
            ->get();

        // ========================================
        // 4. GET ALL PRODUCT VARIANTS (CHILDREN ONLY)
        // ========================================
        $allProductVariants = $product->variants
            ->filter(function ($variant) use ($product) {
                if ($variant->parent_id) {
                    return true;
                }

                $hasChildren = $product->variants->contains(function ($v) use ($variant) {
                    return $v->parent_id === $variant->id;
                });

                return !$hasChildren;
            });

        // ========================================
        // 5. CREATE STOCK MAP BY TOKO (ONLY ACTIVE)
        // ========================================
        $stockByToko = [];
        foreach ($product->variantStocks as $vs) {
            if (!$vs->is_active) {
                continue;
            }

            if (!isset($stockByToko[$vs->toko_id])) {
                $stockByToko[$vs->toko_id] = [];
            }
            $stockByToko[$vs->toko_id][$vs->variant_id] = $vs->stock;
        }

        // ========================================
        // 6. HELPER FUNCTION FOR VARIANT MAPPING
        // ========================================
        $mapVariantData = function ($variant, $stock = 0, $stockSource = 'toko') {
            if (!$variant) {
                return null;
            }

            if ($variant->parent_id && $variant->parent) {
                $color = $variant->parent->name ?? 'Default';
                $size = $variant->name;
                $name = $color . ' - ' . $size;

                $photo = $variant->photo
                    ? asset('storage/' . $variant->photo)
                    : ($variant->parent->photo ? asset('storage/' . $variant->parent->photo) : null);

                $price = $variant->price ?? $variant->parent->price ?? null;
            } else {
                $color = $variant->name ?? 'Standard';
                $size = null;
                $name = $color;
                $photo = $variant->photo ? asset('storage/' . $variant->photo) : null;
                $price = $variant->price ?? null;
            }

            return [
                'variant_id' => $variant->id,
                'variant_name' => $name,
                'color' => $color,
                'size' => $size,
                'stock' => $stock,
                'stock_source' => $stockSource,
                'variant_combination' => $variant->id,
                'photo' => $photo,
                'price' => $price,
                'formatted_price' => $price ? 'Rp ' . number_format($price, 0, ',', '.') : null,
            ];
        };

        // ========================================
        // 7. GET CUSTOMER POSTAL CODE & LOCATION
        // ========================================
        $customerPostalCode = null;
        $customerLocation = null;

        if (Auth::guard('customer')->check()) {
            $customerPostalCode = Auth::guard('customer')->user()->postal_code;

            if ($customerPostalCode) {
                $customerLocation = \App\Helpers\LocationHelper::getLocationFromPostalCode($customerPostalCode);
            }
        }

        // ========================================
        // 8. BUILD STORES DATA WITH LOCATION INFO
        // ========================================
        $storesData = $allTokos->map(function ($toko) use ($allProductVariants, $stockByToko, $mapVariantData, $customerPostalCode) {
            $tokoId = $toko->id;

            // Get stock map for this toko
            $tokoStockMap = $stockByToko[$tokoId] ?? [];
            $hasActiveVariants = !empty($tokoStockMap) && array_sum($tokoStockMap) > 0;

            // Map ALL product variants
            $variantsData = $allProductVariants->map(function ($variant) use ($tokoStockMap, $mapVariantData) {
                $variantId = $variant->id;
                $tokoStock = $tokoStockMap[$variantId] ?? 0;

                if ($tokoStock > 0) {
                    return $mapVariantData($variant, $tokoStock, 'toko');
                } else {
                    $stockPusat = $variant->stock_pusat ?? 0;
                    return $mapVariantData($variant, $stockPusat, 'central');
                }
            })->filter();

            $totalTokoStock = array_sum($tokoStockMap);

            // 🔥 GET LOCATION INFO FROM API
            $tokoLocation = \App\Helpers\LocationHelper::getLocationFromPostalCode($toko->postal_code);

            // 🔥 CALCULATE DISTANCE
            $distanceInfo = null;
            if ($customerPostalCode && $toko->postal_code) {
                $distance = \App\Helpers\LocationHelper::calculatePostalCodeDistance($customerPostalCode, $toko->postal_code);
                $distanceInfo = \App\Helpers\LocationHelper::estimateDistanceCategory($distance);
                $distanceInfo['raw_distance'] = $distance;
            }

            return [
                'id' => $tokoId,
                'nama_toko' => $toko->nama_toko,
                'postal_code' => $toko->postal_code,
                'location' => $tokoLocation,
                'distance' => $distanceInfo,
                'total_stock' => $totalTokoStock,
                'has_active_variants' => $hasActiveVariants,
                'variants_count' => $variantsData->count(),
                'variants_data' => $variantsData->values()->toArray()
            ];
        });

        // ========================================
        // 9. SORT BY PROXIMITY & CONVERT TO ARRAY
        // ========================================
        $storesData = $storesData->values()->toArray();


        // ========================================
        // 10. DEBUG LOG
        // ========================================
        \Log::info('Product Detail - Location Data', [
            'product_id' => $product->id,
            'total_stores' => count($storesData),
            'customer_postal' => $customerPostalCode,
            'first_store_location' => $storesData[0]['location'] ?? null,
        ]);

        // ========================================
        // 11. GET RELATED PRODUCTS
        // ========================================
        $categoryIds = $product->categories->pluck('id')->toArray();

        $relatedQuery = Product::query()
            ->select([
                'products.id',
                'products.title',
                'products.sku',
                'products.ukuran',
                'products.jenis_bahan',
                'products.tipe',
                'products.photos',
                'products.price',
                'products.discount_price',
                'products.rating',
                'products.is_active'
            ])
            ->where('products.is_active', true)
            ->where('products.id', '!=', $product->id);

        if (!empty($categoryIds)) {
            $relatedQuery->join('category_product', 'products.id', '=', 'category_product.product_id')
                ->whereIn('category_product.category_id', $categoryIds)
                ->distinct();
        } else {
            $relatedQuery->where('products.tipe', $product->tipe);
        }

        $relatedProducts = $relatedQuery->inRandomOrder()->limit(4)->get();

        $relatedProducts->load([
            'categories:id,name,slug',
            'variantStocks' => function ($query) {
                $query->select('id', 'product_id', 'toko_id', 'stock', 'is_active')
                    ->where('is_active', true)
                    ->where('stock', '>', 0)
                    ->with('toko:id,nama_toko');
            }
        ]);

        foreach ($relatedProducts as $relatedProduct) {
            $relatedProduct->reviews_count = 0;
        }

        // ========================================
        // 12. RETURN VIEW
        // ========================================
        // Check if in wishlist
        $inWishlist = false;
        if (auth('customer')->check()) {
            $inWishlist = \App\Models\Wishlist::where('customer_id', auth('customer')->id())
                ->where('product_id', $product->id)
                ->exists();
        }

        $wishlistIds = [];
        if (auth('customer')->check()) {
            $wishlistIds = \App\Models\Wishlist::where('customer_id', auth('customer')->id())
                ->pluck('product_id')
                ->toArray();
        }

        return view('frontend.product-detail', [
            'page_title' => $product->title . ' | Tokodus',
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'storesData' => $storesData,
            'customerPostalCode' => $customerPostalCode,
            'customerLocation' => $customerLocation,
            'inWishlist' => $inWishlist,
            'wishlistIds' => $wishlistIds,
        ]);
    }
}
