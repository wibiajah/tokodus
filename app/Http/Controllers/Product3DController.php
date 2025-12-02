<?php

namespace App\Http\Controllers;

use App\Models\Product3D;
use Illuminate\Http\Request;

class Product3DController extends Controller
{
    // Menampilkan list semua produk 3D
    public function index()
    {
        $products = Product3D::where('is_active', true)->get();
        return view('frontend.products-3d.index', compact('products'));
    }

    // Menampilkan detail produk 3D dengan viewer
    public function show(Product3D $product3d)
    {
        return view('frontend.products-3d.viewer', ['product' => $product3d]);
    }

    // API: Mendapatkan data produk dalam format JSON
    public function getProductData(Product3D $product3d)
    {
        return response()->json([
            'id' => $product3d->id,
            'name' => $product3d->name,
            'width' => $product3d->width,
            'height' => $product3d->height,
            'depth' => $product3d->depth,
            'material' => $product3d->material,
            'default_color' => $product3d->default_color,
            'price' => $product3d->price,
            'description_3d' => $product3d->description_3d,
            'min_order' => $product3d->min_order,
        ]);
    }
}