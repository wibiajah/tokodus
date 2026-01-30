<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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
        ->whereHas('categories', function($query) {
            $query->whereIn('slug', ['new-release', 'lebaran', 'christmas', 'imlek']);
        })
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

    // ✅ GET WISHLIST IDs
    $wishlistIds = [];
    if (auth('customer')->check()) {
        $wishlistIds = \App\Models\Wishlist::where('customer_id', auth('customer')->id())
            ->pluck('product_id')
            ->toArray();
    }

    return view('frontend.index', [
        'page_title' => 'Tokodus | Solusi Packaging Anda',
        'categories' => $categories,
        'recommendedProducts' => $recommendedProducts,
        'specialProducts' => $specialProducts,
        'tokos' => $tokos,
        'wishlistIds' => $wishlistIds, // ✅ TAMBAHKAN INI
    ]);
}
}