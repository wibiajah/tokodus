<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(20);

        return view('superadmin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('superadmin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Upload foto
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('categories', 'public');
        }

        $category = Category::create($validated);

        // ✅ KIRIM NOTIFIKASI KE SEMUA ROLE
        NotificationHelper::notifyRoles(
            ['super_admin', 'admin', 'kepala_toko', 'staff_admin'],
            NotificationHelper::categoryCreated($category->fresh(), auth()->user())
        );

        return redirect()
            ->route('superadmin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        return view('superadmin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        'description' => 'nullable|string',
        'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        'is_active' => 'boolean',
    ]);

    $validated['is_active'] = $request->has('is_active');

    // Handle foto
    if ($request->hasFile('photo')) {
        // Hapus foto lama jika ada
        if ($category->photo && Storage::disk('public')->exists($category->photo)) {
            Storage::disk('public')->delete($category->photo);
        }
        $validated['photo'] = $request->file('photo')->store('categories', 'public');
    } elseif (!$request->has('keep_photo') && $category->photo) {
        // User menghapus foto lama tanpa upload baru
        if (Storage::disk('public')->exists($category->photo)) {
            Storage::disk('public')->delete($category->photo);
        }
        $validated['photo'] = null;
    }

    $category->update($validated);

    NotificationHelper::notifyRoles(
        ['super_admin', 'admin', 'kepala_toko', 'staff_admin'],
        NotificationHelper::categoryUpdated($category->fresh(), auth()->user())
    );

    return redirect()
        ->route('superadmin.categories.index')
        ->with('success', 'Kategori berhasil diupdate!');
}

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()
                ->route('superadmin.categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh produk!');
        }

        $categoryName = $category->name;

        // Hapus foto
        if ($category->photo && Storage::disk('public')->exists($category->photo)) {
            Storage::disk('public')->delete($category->photo);
        }

        $category->delete();

        // ✅ KIRIM NOTIFIKASI KE SEMUA ROLE
        NotificationHelper::notifyRoles(
            ['super_admin', 'admin', 'kepala_toko', 'staff_admin'],
            NotificationHelper::categoryDeleted($categoryName, auth()->user())
        );

        return redirect()
            ->route('superadmin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}