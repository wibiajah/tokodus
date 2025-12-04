<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // List semua produk
    public function index()
    {
        $products = Product::with(['categories', 'stocks'])
            ->withCount('reviews')
            ->latest()
            ->paginate(20);

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
            'photos.*' => 'nullable|image|max:2048', // Max 2MB per foto
            'video' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/quicktime,video/x-msvideo|max:10240', // Max 10MB
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

        return redirect()
            ->route('superadmin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    // Detail produk
    public function show(Product $product)
    {
        $product->load(['categories', 'stocks.toko', 'reviews.user']);
        
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
            'remove_video' => 'nullable|boolean', // Untuk hapus video existing
        ]);

        // Upload foto baru (tetap simpan foto lama)
        $photos = $product->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('products/photos', 'public');
            }
        }

        // Handle video
        $videoPath = $product->video;
        
        // Jika user minta hapus video
        if ($request->has('remove_video') && $request->remove_video) {
            if ($product->video) {
                Storage::disk('public')->delete($product->video);
            }
            $videoPath = null;
        }
        
        // Jika ada video baru di-upload
        if ($request->hasFile('video')) {
            // Hapus video lama
            if ($product->video) {
                Storage::disk('public')->delete($product->video);
            }
            // Upload video baru
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
            'photos' => $photos,
            'video' => $videoPath,
            'variants' => $variants,
            'tags' => $tags,
        ]);

        // Sync kategori
        if ($request->has('categories')) {
            $product->categories()->sync($validated['categories']);
        }

        return redirect()
            ->route('superadmin.products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    // Hapus produk
    public function destroy(Product $product)
    {
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

        return redirect()
            ->route('superadmin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    // Method tambahan untuk hapus foto individual (opsional)
    public function deletePhoto(Product $product, $index)
    {
        $photos = $product->photos ?? [];
        
        if (isset($photos[$index])) {
            // Hapus file dari storage
            Storage::disk('public')->delete($photos[$index]);
            
            // Hapus dari array
            unset($photos[$index]);
            
            // Re-index array
            $photos = array_values($photos);
            
            // Update product
            $product->update(['photos' => $photos]);
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
}