<?php

namespace App\Http\Controllers\admin;

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

        return view('admin.products.index', compact('products'));
    }

    // Form tambah produk
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
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
            'video' => 'nullable|url',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'variants' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        // Upload foto
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('products', 'public');
            }
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
            'video' => $validated['video'] ?? null,
            'variants' => $variants,
            'tags' => $tags,
        ]);

        // Attach kategori
        if ($request->has('categories')) {
            $product->categories()->attach($validated['categories']);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    // Detail produk
    public function show(Product $product)
    {
        $product->load(['categories', 'stocks.toko', 'reviews.user']);
        
        return view('admin.products.show', compact('product'));
    }

    // Form edit produk
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
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
            'video' => 'nullable|url',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'variants' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        // Upload foto baru
        $photos = $product->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('products', 'public');
            }
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
            'video' => $validated['video'] ?? null,
            'variants' => $variants,
            'tags' => $tags,
        ]);

        // Sync kategori
        if ($request->has('categories')) {
            $product->categories()->sync($validated['categories']);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    // Hapus produk
    public function destroy(Product $product)
    {
        // Hapus foto
        if ($product->photos) {
            foreach ($product->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}