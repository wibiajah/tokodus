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
        'ukuran',
        'jenis_bahan',
        'tipe',
        'cetak',
        'finishing',
        'description',
        'photos',
        'video',
        'price',
        'discount_price',
        // ❌ REMOVED: 'initial_stock',
        // ❌ REMOVED: 'variants',
        'rating',
        'review_count',
        'tags',
        'is_active',
    ];

    protected $casts = [
        'photos' => 'array',
        // ❌ REMOVED: 'variants' => 'array',
        'tags' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
        // ❌ REMOVED: 'initial_stock' => 'integer',
    ];

    // ============================================
    // 🆕 NEW VARIANT RELATIONSHIPS
    // ============================================

    /**
     * 🆕 Relasi ke semua variants (color & size)
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * 🆕 Relasi ke color variants saja (parent variants)
     */
    public function colorVariants()
    {
        return $this->hasMany(ProductVariant::class)
            ->where('type', 'color')
            ->whereNull('parent_id');
    }

    /**
     * 🆕 Relasi ke size variants saja
     */
    public function sizeVariants()
    {
        return $this->hasMany(ProductVariant::class)
            ->where('type', 'size');
    }

    /**
     * 🆕 Relasi ke variant stocks (stok per varian per toko)
     */
    public function variantStocks()
    {
        return $this->hasMany(ProductVariantStock::class);
    }

    /**
     * 🆕 Relasi ke stock requests
     */
    public function stockRequests()
    {
        return $this->hasMany(StockRequest::class);
    }

    /**
     * 🆕 Relasi ke distribution logs
     */
    public function distributionLogs()
    {
        return $this->hasMany(StockDistributionLog::class);
    }

    // ============================================
    // ⚠️ DEPRECATED - OLD STOCK SYSTEM (KEEP FOR COMPATIBILITY)
    // ============================================

    /**
     * ⚠️ DEPRECATED: Use variantStocks() instead
     * Kept for backward compatibility
     */
    public function stocks()
    {
        return $this->variantStocks();
    }

    /**
     * ⚠️ DEPRECATED: Use variant-based stock check
     */
    public function stockInToko($tokoId)
    {
        return $this->variantStocks()->where('toko_id', $tokoId)->get();
    }

    /**
     * ⚠️ DEPRECATED: No more initial_stock column
     */
    public function getRemainingInitialStockAttribute()
    {
        // Return 0 or calculate from variants
        return $this->variants()->sum('stock_pusat');
    }

    // ============================================
    // EXISTING RELATIONSHIPS (UNCHANGED)
    // ============================================

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'voucher_products');
    }

    public function voucherProducts()
    {
        return $this->hasMany(VoucherProduct::class);
    }

    public function activeVouchers()
    {
        return $this->belongsToMany(Voucher::class, 'voucher_products')
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function tokos()
    {
        return $this->belongsToMany(Toko::class, 'product_variant_stocks', 'product_id', 'toko_id')
            ->withPivot('stock')
            ->where('stock', '>', 0);
    }

    // ============================================
    // 🆕 NEW COMPUTED ATTRIBUTES (VARIANT SYSTEM)
    // ============================================

    /**
     * 🆕 COMPUTED: Total stok di warehouse (semua varian)
     */
    public function getTotalStockAttribute()
    {
        return $this->variants()
            ->whereNull('parent_id') // hanya parent (color)
            ->sum('stock_pusat');
    }

    /**
     * 🆕 COMPUTED: Total stok di semua toko
     */
    public function getTotalDistributedStockAttribute()
    {
        return $this->variantStocks()->sum('stock');
    }

    /**
     * 🆕 COMPUTED: Cek apakah ada stok tersedia
     */
    public function getIsAvailableAttribute()
    {
        return $this->total_stock > 0;
    }

    // ============================================
    // EXISTING ACCESSORS (UNCHANGED)
    // ============================================

    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    public function getThumbnailAttribute()
    {
        if (is_array($this->photos) && count($this->photos) > 0) {
            return asset('storage/' . $this->photos[0]);
        }
        return asset('frontend/assets/img/placeholder-product.png');
    }

    public function getPhotoUrlsAttribute()
    {
        if (is_array($this->photos)) {
            return array_map(function($photo) {
                return asset('storage/' . $photo);
            }, $this->photos);
        }
        return [asset('frontend/assets/img/placeholder-product.png')];
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getHasDiscountAttribute()
    {
        return $this->discount_price !== null && $this->discount_price < $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->has_discount) {
            return round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getPrimaryCategoryAttribute()
    {
        return $this->categories->first();
    }

    public function getTipeDisplayAttribute()
    {
        return ucfirst($this->tipe);
    }

    public function getFullSpecificationAttribute()
    {
        $specs = [
            'Ukuran' => $this->ukuran,
            'Tipe' => $this->tipe_display,
        ];

        if ($this->jenis_bahan) {
            $specs['Jenis Bahan'] = $this->jenis_bahan;
        }
        if ($this->cetak) {
            $specs['Cetak'] = $this->cetak;
        }
        if ($this->finishing) {
            $specs['Finishing'] = $this->finishing;
        }

        return $specs;
    }

       public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get customers who wishlisted this product
     */
    public function wishlistedByCustomers()
    {
        return $this->belongsToMany(Customer::class, 'wishlists')
            ->withTimestamps();
    }

    /**
     * Get wishlist count for this product
     */
    public function getWishlistCountAttribute(): int
    {
        return $this->wishlists()->count();
    }

    // ============================================
// 🆕 TAMBAHKAN DI Product.php (setelah relationship reviews() yang sudah ada)
// ============================================

/**
 * Update rating cache di kolom products.rating & products.review_count
 * Dipanggil otomatis dari ProductReview::booted()
 */
public function updateRatingCache()
{
    $avgRating = $this->reviews()->avg('rating') ?? 0;
    $totalReviews = $this->reviews()->count();
    
    $this->update([
        'rating' => round($avgRating, 2),
        'review_count' => $totalReviews
    ]);
}

/**
 * Get average rating attribute
 */
public function getAverageRatingAttribute()
{
    return $this->rating;
}

/**
 * Get total reviews attribute
 */
public function getTotalReviewsAttribute()
{
    return $this->review_count;
}

/**
 * Get star rating HTML
 */
public function getRatingStarsHtmlAttribute()
{
    $rating = $this->rating;
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
    
    $stars = str_repeat('⭐', $fullStars);
    if ($halfStar) $stars .= '⭐'; // bisa ganti dengan half star icon
    $stars .= str_repeat('☆', $emptyStars);
    
    return $stars;
}

/**
 * Get reviews grouped by rating (for statistics)
 */
public function getReviewsByRatingAttribute()
{
    return [
        5 => $this->reviews()->byRating(5)->count(),
        4 => $this->reviews()->byRating(4)->count(),
        3 => $this->reviews()->byRating(3)->count(),
        2 => $this->reviews()->byRating(2)->count(),
        1 => $this->reviews()->byRating(1)->count(),
    ];
}
}