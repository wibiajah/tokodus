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
        'initial_stock' => 'integer',
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

    // Sisa stok awal (yang belum didistribusikan ke toko)
    public function getRemainingInitialStockAttribute()
    {
        $allocatedStock = $this->stocks()->sum('stock');
        $remaining = ($this->initial_stock ?? 0) - $allocatedStock;
        return max(0, $remaining);
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

    // Total stok yang sudah didistribusikan
    public function getTotalDistributedStockAttribute()
    {
        return $this->stocks()->sum('stock');
    }

    // ✅ ACCESSOR UNTUK REALTIME (Pastikan ini ada)
    
    /**
     * Get thumbnail (foto pertama)
     */
    public function getThumbnailAttribute()
    {
        if (is_array($this->photos) && count($this->photos) > 0) {
            return asset('storage/' . $this->photos[0]);
        }
        return asset('frontend/assets/img/placeholder-product.png');
    }

    /**
     * Get semua foto sebagai URL
     */
    public function getPhotoUrlsAttribute()
    {
        if (is_array($this->photos)) {
            return array_map(function($photo) {
                return asset('storage/' . $photo);
            }, $this->photos);
        }
        return [asset('frontend/assets/img/placeholder-product.png')];
    }

    /**
     * Cek apakah produk available (ada stok)
     */
    public function getIsAvailableAttribute()
    {
        return $this->stocks->sum('stock') > 0;
    }

    /**
     * Get total stok dari semua toko
     */
    public function getTotalStockAttribute()
    {
        return $this->stocks->sum('stock');
    }

    /**
     * Format harga final dengan Rupiah
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }

    /**
     * Format harga asli (sebelum diskon)
     */
    public function getFormattedOriginalPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Cek apakah produk sedang diskon
     */
    public function getHasDiscountAttribute()
    {
        return $this->discount_price !== null && $this->discount_price < $this->price;
    }

    /**
     * Hitung persentase diskon
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->has_discount) {
            return round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }

    /**
     * Get kategori pertama (untuk display)
     */
    public function getPrimaryCategoryAttribute()
    {
        return $this->categories->first();
    }
}