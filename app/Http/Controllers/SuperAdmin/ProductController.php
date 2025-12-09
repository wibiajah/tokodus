<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\NotificationHelper;

class ProductController extends Controller
{
    // List semua produk
    public function index()
{
    $products = Product::with([
        'categories:id,name',
        'stocks.toko' => function($query) {
            $query->where('id', '!=', 999)->where('status', 'aktif');
        }
    ])
    ->withCount('reviews')
    ->latest()
    ->paginate(20);

    // ✅ Hitung remaining stock SETELAH eager loading selesai
    $products->getCollection()->transform(function($product) {
        // Hitung total yang sudah didistribusikan ke toko
        $totalDistributed = $product->stocks->sum('stock');
        
        // Simpan ke attribute baru (bukan query lagi)
        $product->remaining_stock_cached = max(0, $product->initial_stock - $totalDistributed);
        $product->total_distributed_cached = $totalDistributed;
        
        return $product;
    });

    return view('superadmin.products.index', compact('products'));
}

    // Form tambah produk
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('superadmin.products.create', compact('categories'));
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'initial_stock' => 'required|integer|min:0',
            'photos.*' => 'nullable|image|max:2048',
            'video' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/quicktime,video/x-msvideo|max:10240',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'variants' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        // Upload foto
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('products/photos', 'public');
            }
        }

        // Upload video
        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('products/videos', 'public');
        }

        // Parse variants dan tags dari JSON string
        $variants = $request->variants ? json_decode($request->variants, true) : null;
        $tags = $request->tags ? json_decode($request->tags, true) : null;

        $product = Product::create([
            'title' => $validated['title'],
            'sku' => $validated['sku'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'initial_stock' => $validated['initial_stock'],
            'photos' => $photos,
            'video' => $videoPath,
            'variants' => $variants,
            'tags' => $tags,
        ]);

        // Attach kategori
        if ($request->has('categories')) {
            $product->categories()->attach($validated['categories']);
        }

        // 🔥 KIRIM NOTIFIKASI
        NotificationHelper::notifyRoles(
            ['super_admin', 'admin'],
            NotificationHelper::productCreated($product, auth()->user())
        );

        return redirect()
            ->route('superadmin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    // Detail produk
    public function show(Product $product)
    {
        $product->load([
            'categories', 
            'stocks.toko' => function($query) {
                $query->where('id', '!=', 999);
            }, 
            'reviews.user'
        ]);
        
        return view('superadmin.products.show', compact('product'));
    }

    // Form edit produk
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('superadmin.products.edit', compact('product', 'categories'));
    }

    // Update produk
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'initial_stock' => 'required|integer|min:0',
            'photos.*' => 'nullable|image|max:2048',
            'video' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/quicktime,video/x-msvideo|max:10240',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'variants' => 'nullable|string',
            'tags' => 'nullable|string',
            'keep_photos' => 'nullable|array',
            'keep_photos.*' => 'integer',
            'keep_video' => 'nullable|boolean',
        ]);

        // Handle foto yang dipertahankan
        $oldPhotos = $product->photos ?? [];
        $keepPhotos = $request->input('keep_photos', []);
        $photosToKeep = [];
        
        foreach ($oldPhotos as $index => $photoPath) {
            if (in_array($index, $keepPhotos)) {
                $photosToKeep[] = $photoPath;
            } else {
                Storage::disk('public')->delete($photoPath);
            }
        }
        
        // Tambahkan foto baru
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photosToKeep[] = $photo->store('products/photos', 'public');
            }
        }

        // Handle video
        $videoPath = null;
        
        if ($request->has('keep_video') && $request->keep_video) {
            $videoPath = $product->video;
        } else {
            if ($product->video) {
                Storage::disk('public')->delete($product->video);
            }
        }
        
        if ($request->hasFile('video')) {
            if ($product->video) {
                Storage::disk('public')->delete($product->video);
            }
            $videoPath = $request->file('video')->store('products/videos', 'public');
        }

        // Parse variants dan tags
        $variants = $request->variants ? json_decode($request->variants, true) : $product->variants;
        $tags = $request->tags ? json_decode($request->tags, true) : $product->tags;

        $product->update([
            'title' => $validated['title'],
            'sku' => $validated['sku'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'initial_stock' => $validated['initial_stock'],
            'photos' => $photosToKeep,
            'video' => $videoPath,
            'variants' => $variants,
            'tags' => $tags,
        ]);

        // Sync kategori
        if ($request->has('categories')) {
            $product->categories()->sync($validated['categories']);
        }

        // 🔥 KIRIM NOTIFIKASI
        NotificationHelper::notifyRoles(
            ['super_admin', 'admin'],
            NotificationHelper::productUpdated($product, auth()->user())
        );

        return redirect()
            ->route('superadmin.products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    // Hapus produk
    public function destroy(Product $product)
    {
        // Simpan nama produk sebelum dihapus
        $productTitle = $product->title;

        // Hapus semua foto
        if ($product->photos) {
            foreach ($product->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        // Hapus video
        if ($product->video) {
            Storage::disk('public')->delete($product->video);
        }

        $product->delete();

        // 🔥 KIRIM NOTIFIKASI
        NotificationHelper::notifyRoles(
            ['super_admin', 'admin'],
            NotificationHelper::productDeleted($productTitle, auth()->user())
        );

        return redirect()
            ->route('superadmin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}