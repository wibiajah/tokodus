<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'sku',
        'description',
        'photos',
        'video',
        'price',
        'discount_price',
        'initial_stock',
        'variants',
        'rating',
        'review_count',
        'tags',
        'is_active',
    ];

    protected $casts = [
        'photos' => 'array',
        'variants' => 'array',
        'tags' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relasi ke kategori (many to many)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    // Relasi ke stok per toko
    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    // Stok di toko tertentu
    public function stockInToko($tokoId)
    {
        return $this->stocks()->where('toko_id', $tokoId)->first();
    }

    // Sisa stok awal (yang belum diambil toko)
    public function getRemainingInitialStockAttribute()
    {
        $allocatedStock = $this->stocks()->sum('stock');
        return $this->initial_stock - $allocatedStock;
    }

    // Relasi ke review
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    // Relasi ke voucher
    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'voucher_products');
    }

    // Hitung harga akhir setelah diskon
    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }
}