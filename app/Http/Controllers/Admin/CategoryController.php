<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $category = Category::create($validated);

        // ✅ KIRIM NOTIFIKASI KE SEMUA ROLE
        NotificationHelper::notifyRoles(
            ['super_admin', 'admin', 'kepala_toko', 'staff_admin'],
            NotificationHelper::categoryCreated($category->fresh(), auth()->user())
        );

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        // ✅ KIRIM NOTIFIKASI KE SEMUA ROLE
        NotificationHelper::notifyRoles(
            ['super_admin', 'admin', 'kepala_toko', 'staff_admin'],
            NotificationHelper::categoryUpdated($category->fresh(), auth()->user())
        );

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh produk!');
        }

        $categoryName = $category->name;

        $category->delete();

        // ✅ KIRIM NOTIFIKASI KE SEMUA ROLE
        NotificationHelper::notifyRoles(
            ['super_admin', 'admin', 'kepala_toko', 'staff_admin'],
            NotificationHelper::categoryDeleted($categoryName, auth()->user())
        );

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}