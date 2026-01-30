<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Http\Requests\SuperAdmin\StoreProductRequest;
use App\Http\Requests\SuperAdmin\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;

class ProductController extends Controller
{
    /**
     * List semua produk
     */
    public function index(Request $request)
{
    $query = Product::with([
        'categories:id,name',
        'variants' => function($query) {
            $query->whereNull('parent_id')
                  ->select('id', 'product_id', 'name', 'photo', 'stock_pusat');
        }
    ])
    ->select('id', 'title', 'sku', 'tipe', 'price', 'discount_price', 'photos', 'is_active', 'created_at')
    ->withCount('reviews');

    // Filter
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('category_id')) {
        $query->whereHas('categories', function($q) use ($request) {
            $q->where('categories.id', $request->category_id);
        });
    }

    $products = $query->latest()->paginate(20);
    
    // ❌ JANGAN kirim semua data detail ke JS
    // ✅ HANYA kirim data minimal untuk list/card view
    $productsData = $products->map(function($product) {
        return [
            'id' => $product->id,
            'title' => $product->title,
            'sku' => $product->sku,
            'tipe' => $product->tipe,
            'price' => $product->price,
            'discount_price' => $product->discount_price,
            'photos' => $product->photos ? [$product->photos[0]] : [], // Hanya foto pertama
            'is_active' => $product->is_active,
            'created_at' => $product->created_at->timestamp,
        ];
    });
    
    $categories = Category::where('is_active', true)->get(['id', 'name']);

    return view('superadmin.products.index', compact('products', 'categories', 'productsData'));
}

    /**
     * Form tambah produk
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('superadmin.products.create', compact('categories'));
    }

    /**
     * Simpan produk baru
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        
        try {
            // 1. Upload photos
            $photos = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photos[] = $photo->store('products/photos', 'public');
                }
            }

            // 2. Upload video
            $videoPath = null;
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('products/videos', 'public');
            }

            // 3. Create product
            $product = Product::create([
                'title' => $request->title,
                'sku' => $request->sku,
                'ukuran' => $request->ukuran,
                'jenis_bahan' => $request->jenis_bahan,
                'tipe' => $request->tipe,
                'cetak' => $request->cetak,
                'finishing' => $request->finishing,
                'description' => $request->description,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'photos' => $photos,
                'video' => $videoPath,
                'tags' => $request->tags,
                'is_active' => $request->boolean('is_active', true),
            ]);

            // 4. Sync categories
            if ($request->has('category_ids')) {
                $product->categories()->sync($request->category_ids);
            }

            // 5. CREATE VARIANTS (LOOP COLORS + SIZES WITH PHOTOS)
            foreach ($request->colors as $colorData) {
                // Upload color photo
                $colorPhoto = null;
                if (isset($colorData['photo']) && is_file($colorData['photo'])) {
                    $colorPhoto = $colorData['photo']->store('products/variants', 'public');
                }

                // Cek apakah ada sizes
                $hasSizes = isset($colorData['sizes']) && count($colorData['sizes']) > 0;

                // Create parent color
                $color = ProductVariant::create([
                    'product_id' => $product->id,
                    'parent_id' => null,
                    'name' => $colorData['name'],
                    'photo' => $colorPhoto,
                    'stock_pusat' => $hasSizes ? 0 : ($colorData['stock_pusat'] ?? 0),
                    'price' => null, // parent tidak punya price
                ]);

                // Create children sizes WITH PHOTOS
                if ($hasSizes) {
                    foreach ($colorData['sizes'] as $sizeData) {
                        // 🆕 Upload size photo
                        $sizePhoto = null;
                        if (isset($sizeData['photo']) && is_file($sizeData['photo'])) {
                            $sizePhoto = $sizeData['photo']->store('products/variants', 'public');
                        }
                        
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'parent_id' => $color->id,
                            'name' => $sizeData['name'],
                            'photo' => $sizePhoto, // 🆕 Size sekarang bisa punya foto
                            'stock_pusat' => $sizeData['stock_pusat'],
                            'price' => $sizeData['price'],
                        ]);
                    }

                    // Auto-update parent color stock dari children
                    $color->updateParentStock();
                }
            }

            DB::commit();

            // KIRIM NOTIFIKASI
           NotificationHelper::productCreated($product, auth()->user());


            return redirect()
                ->route('superadmin.products.index')
                ->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus uploaded files jika ada error
            if (isset($photos)) {
                foreach ($photos as $photo) {
                    Storage::disk('public')->delete($photo);
                }
            }
            if (isset($videoPath)) {
                Storage::disk('public')->delete($videoPath);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Detail produk
     */
 /**
 * Detail produk - bisa untuk view biasa atau AJAX
 */
public function show(Product $product, Request $request)
{
    $product->load([
        'categories',
        'variants' => function($query) {
            $query->with(['children', 'stocks.toko'])->whereNull('parent_id');
        },
        'variantStocks.variant',
        'variantStocks.toko'
    ]);
    
    // Calculate stock
    $stockPusat = 0;
    foreach($product->variants as $cv) {
        if($cv->children && $cv->children->count() > 0) {
            $stockPusat += $cv->children->sum('stock_pusat');
        } else {
            $stockPusat += $cv->stock_pusat;
        }
    }
    $stockToko = $product->variantStocks->sum('stock') ?? 0;
    
    // ✅ Jika request AJAX, return JSON
    if ($request->wantsJson() || $request->ajax()) {
        return response()->json([
            'id' => $product->id,
            'title' => $product->title,
            'sku' => $product->sku,
            'tipe' => $product->tipe,
            'ukuran' => $product->ukuran,
            'jenis_bahan' => $product->jenis_bahan,
            'cetak' => $product->cetak,
            'finishing' => $product->finishing,
            'price' => $product->price,
            'discount_price' => $product->discount_price,
            'photos' => $product->photos,
            'video' => $product->video,
            'description' => $product->description,
            'tags' => $product->tags,
            'is_active' => $product->is_active,
            'categories' => $product->categories,
            'rating' => $product->rating ?? 0,
            'reviews_count' => $product->reviews_count ?? 0,
            'stock_pusat' => $stockPusat,
            'stock_toko' => $stockToko,
            'total_stock' => $stockPusat + $stockToko,
            'variants' => $product->variants,
            'variantStocks' => $product->variantStocks,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ]);
    }
    
    // ✅ Jika bukan AJAX, return view biasa (untuk halaman detail standalone)
    return view('superadmin.products.show', compact('product'));
}

    /**
     * Form edit produk
     */
    public function edit(Product $product)
    {
        $product->load([
            'variants' => function($query) {
                $query->with('children')->whereNull('parent_id');
            }
        ]);
        
        $categories = Category::where('is_active', true)->get();
        
        return view('superadmin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update produk
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        
        try {
            // 1. Handle photos (keep old + upload new)
            $oldPhotos = $product->photos ?? [];
            $keepPhotosIndices = $request->input('existing_photos', []);
            $photosToKeep = [];
            
            // Keep selected old photos
            foreach ($oldPhotos as $index => $photoPath) {
                if (in_array($index, $keepPhotosIndices)) {
                    $photosToKeep[] = $photoPath;
                } else {
                    // Delete removed photos
                    Storage::disk('public')->delete($photoPath);
                }
            }
            
            // Upload new photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photosToKeep[] = $photo->store('products/photos', 'public');
                }
            }

            // 2. Handle video
            $videoPath = $product->video;
            
            if ($request->boolean('remove_video')) {
                if ($product->video) {
                    Storage::disk('public')->delete($product->video);
                }
                $videoPath = null;
            }
            
            if ($request->hasFile('video')) {
                if ($product->video) {
                    Storage::disk('public')->delete($product->video);
                }
                $videoPath = $request->file('video')->store('products/videos', 'public');
            }

            // 3. Update product
            $product->update([
                'title' => $request->title,
                'sku' => $request->sku,
                'ukuran' => $request->ukuran,
                'jenis_bahan' => $request->jenis_bahan,
                'tipe' => $request->tipe,
                'cetak' => $request->cetak,
                'finishing' => $request->finishing,
                'description' => $request->description,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'photos' => $photosToKeep,
                'video' => $videoPath,
                'tags' => $request->tags,
                'is_active' => $request->boolean('is_active', true),
            ]);

            // 4. Sync categories
            if ($request->has('category_ids')) {
                $product->categories()->sync($request->category_ids);
            }

            // 5. UPDATE/CREATE/DELETE VARIANTS WITH PHOTOS
            $submittedVariantIds = [];

            foreach ($request->colors as $colorData) {
                // Handle color photo
                $colorPhoto = $colorData['existing_photo'] ?? null;
                
                if (isset($colorData['photo']) && is_file($colorData['photo'])) {
                    // Delete old photo if exists
                    if ($colorPhoto) {
                        Storage::disk('public')->delete($colorPhoto);
                    }
                    $colorPhoto = $colorData['photo']->store('products/variants', 'public');
                }

                $hasSizes = isset($colorData['sizes']) && count($colorData['sizes']) > 0;

                // UPDATE atau CREATE color
                if (isset($colorData['id'])) {
                    // UPDATE existing color
                    $color = ProductVariant::find($colorData['id']);
                    
                    if ($color) {
                        $color->update([
                            'name' => $colorData['name'],
                            'photo' => $colorPhoto,
                            'stock_pusat' => $hasSizes ? 0 : ($colorData['stock_pusat'] ?? 0),
                        ]);
                        $submittedVariantIds[] = $color->id;
                    }
                } else {
                    // CREATE new color
                    $color = ProductVariant::create([
                        'product_id' => $product->id,
                        'parent_id' => null,
                        'name' => $colorData['name'],
                        'photo' => $colorPhoto,
                        'stock_pusat' => $hasSizes ? 0 : ($colorData['stock_pusat'] ?? 0),
                        'price' => null,
                    ]);
                    $submittedVariantIds[] = $color->id;
                }

                // UPDATE/CREATE sizes WITH PHOTOS
                if ($hasSizes) {
                    foreach ($colorData['sizes'] as $sizeData) {
                        if (isset($sizeData['id'])) {
                            // UPDATE existing size
                            $size = ProductVariant::find($sizeData['id']);
                            
                            if ($size) {
                                // 🆕 Handle size photo update
                                $sizePhoto = $sizeData['existing_photo'] ?? $size->photo;
                                
                                if (isset($sizeData['photo']) && is_file($sizeData['photo'])) {
                                    // Delete old photo if exists
                                    if ($sizePhoto) {
                                        Storage::disk('public')->delete($sizePhoto);
                                    }
                                    $sizePhoto = $sizeData['photo']->store('products/variants', 'public');
                                }
                                
                                $size->update([
                                    'name' => $sizeData['name'],
                                    'photo' => $sizePhoto, // 🆕 Update photo
                                    'stock_pusat' => $sizeData['stock_pusat'],
                                    'price' => $sizeData['price'],
                                ]);
                                $submittedVariantIds[] = $size->id;
                            }
                        } else {
                            // CREATE new size
                            $sizePhoto = null;
                            if (isset($sizeData['photo']) && is_file($sizeData['photo'])) {
                                $sizePhoto = $sizeData['photo']->store('products/variants', 'public');
                            }
                            
                            $size = ProductVariant::create([
                                'product_id' => $product->id,
                                'parent_id' => $color->id,
                                'name' => $sizeData['name'],
                                'photo' => $sizePhoto, // 🆕 Ada foto
                                'stock_pusat' => $sizeData['stock_pusat'],
                                'price' => $sizeData['price'],
                            ]);
                            $submittedVariantIds[] = $size->id;
                        }
                    }

                    // Auto-update parent color stock
                    $color->updateParentStock();
                }
            }

            // 6. DELETE variants yang tidak ada di form
            $deletedVariants = ProductVariant::where('product_id', $product->id)
                ->whereNotIn('id', $submittedVariantIds)
                ->get();

            foreach ($deletedVariants as $variant) {
                // Delete photo if exists (both color and size photos)
                if ($variant->photo) {
                    Storage::disk('public')->delete($variant->photo);
                }
                $variant->delete();
            }

            DB::commit();

            // KIRIM NOTIFIKASI
           NotificationHelper::productUpdated($product, auth()->user());

            return redirect()
                ->route('superadmin.products.index')
                ->with('success', 'Produk berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Hapus produk
     */
    /**
 * Hapus produk
 */
public function destroy(Product $product)
{
    DB::beginTransaction();
    
    try {
        // ✅ VALIDASI: Cek apakah produk punya stock request yang pending
        $pendingRequests = DB::table('stock_requests')
            ->where('product_id', $product->id)
            ->where('status', 'pending')
            ->count();
        
        if ($pendingRequests > 0) {
            return back()->withErrors([
                'error' => "Tidak dapat menghapus produk karena masih ada {$pendingRequests} stock request yang pending! Selesaikan request tersebut terlebih dahulu."
            ]);
        }

        $productTitle = $product->title;

        // Hapus semua foto produk
        if ($product->photos) {
            foreach ($product->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        // Hapus video
        if ($product->video) {
            Storage::disk('public')->delete($product->video);
        }

        // Hapus foto variants (both colors and sizes)
        foreach ($product->variants as $variant) {
            if ($variant->photo) {
                Storage::disk('public')->delete($variant->photo);
            }
        }

        // Delete product (variants akan auto-delete karena cascade)
        $product->delete();

        DB::commit();

        // KIRIM NOTIFIKASI
NotificationHelper::productDeleted($product->title, auth()->user());

        return redirect()
            ->route('superadmin.products.index')
            ->with('success', 'Produk berhasil dihapus!');

    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()
            ->route('superadmin.products.index')
            ->withErrors(['error' => 'Gagal menghapus produk: ' . $e->getMessage()]);
    }
}

    /**
     * AJAX: Delete single variant
     */
    public function deleteVariant(ProductVariant $variant)
    {
        DB::beginTransaction();
        
        try {
            // Validasi: tidak bisa delete color yang punya sizes
            if ($variant->isColor() && $variant->hasChildren()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa menghapus warna yang memiliki ukuran. Hapus ukuran terlebih dahulu.'
                ], 400);
            }

            // Get parent (untuk update stock nanti)
            $parent = $variant->parent;

            // Delete photo if exists (both color and size photos)
            if ($variant->photo) {
                Storage::disk('public')->delete($variant->photo);
            }

            // Delete variant
            $variant->delete();

            // Update parent stock jika ini adalah size
            if ($parent) {
                $parent->updateParentStock();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Varian berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus varian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDetail(Product $product)
{
    $product->load([
        'categories',
        'variants' => function($query) {
            $query->with(['children', 'stocks.toko'])->whereNull('parent_id');
        },
        'variantStocks.variant',
        'variantStocks.toko'
    ]);
    
    // Calculate stock
    $stockPusat = 0;
    foreach($product->variants as $cv) {
        if($cv->children && $cv->children->count() > 0) {
            $stockPusat += $cv->children->sum('stock_pusat');
        } else {
            $stockPusat += $cv->stock_pusat;
        }
    }
    $stockToko = $product->variantStocks->sum('stock') ?? 0;
    
    return response()->json([
        'id' => $product->id,
        'title' => $product->title,
        'sku' => $product->sku,
        'tipe' => $product->tipe,
        'ukuran' => $product->ukuran,
        'jenis_bahan' => $product->jenis_bahan,
        'cetak' => $product->cetak,
        'finishing' => $product->finishing,
        'price' => $product->price,
        'discount_price' => $product->discount_price,
        'photos' => $product->photos,
        'video' => $product->video,
        'description' => $product->description,
        'tags' => $product->tags,
        'is_active' => $product->is_active,
        'categories' => $product->categories,
        'rating' => $product->rating ?? 0,
        'reviews_count' => $product->reviews_count ?? 0,
        'stock_pusat' => $stockPusat,
        'stock_toko' => $stockToko,
        'total_stock' => $stockPusat + $stockToko,
        'variants' => $product->variants,
        'variantStocks' => $product->variantStocks,
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
    ]);
}

/**
 * Toggle product active status (AJAX)
 */
public function toggleStatus(Product $product)
{
    try {
        $product->is_active = !$product->is_active;
        $product->save();

        return response()->json([
            'success' => true,
            'is_active' => $product->is_active,
            'message' => 'Status produk berhasil diubah'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Toggle product status error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengubah status: ' . $e->getMessage()
        ], 500);
    }
}
}