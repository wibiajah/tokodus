<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_id',
        'order_id',
        'rating',
        'review',
        'photos',
        'is_approved',
        'viewed_at', // ✅ NEW
    ];

    protected $casts = [
        'photos' => 'array',
        'is_approved' => 'boolean',
        'rating' => 'integer',
        'viewed_at' => 'datetime', // ✅ NEW
    ];

    protected $attributes = [
        'is_approved' => true,
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeHighestRating($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    public function scopeLowestRating($query)
    {
        return $query->orderBy('rating', 'asc');
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // ✅ NEW - Scope untuk review yang belum dilihat (PRODUCTION-READY)
    public function scopeUnviewed($query)
    {
        return $query->whereNull('viewed_at');
    }

    // ✅ NEW - Get count unviewed reviews (FAST & INDEXED)
    public static function getUnviewedCount()
    {
        return static::whereNull('viewed_at')->count();
    }

    // ✅ NEW - Mark as viewed
    public function markAsViewed()
    {
        if (is_null($this->viewed_at)) {
            $this->update(['viewed_at' => now()]);
        }
    }

    // ✅ NEW - Mark all unviewed as viewed (bulk operation)
    public static function markAllAsViewed()
    {
        return static::whereNull('viewed_at')
            ->update(['viewed_at' => now()]);
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getRatingStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $this->rating ? '⭐' : '☆';
        }
        return $stars;
    }

    public function getCustomerNameAttribute()
    {
        return $this->customer 
            ? $this->customer->firstname . ' ' . $this->customer->lastname
            : 'Unknown';
    }

    public function getPhotoUrlsAttribute()
    {
        if (!$this->photos || !is_array($this->photos)) {
            return [];
        }

        return array_map(function($photo) {
            return asset('storage/' . $photo);
        }, $this->photos);
    }

    // ✅ NEW - Check if viewed
    public function getIsViewedAttribute()
    {
        return !is_null($this->viewed_at);
    }

    // ============================================
    // BOOT - Auto update product rating
    // ============================================

    protected static function booted()
    {
        static::created(function ($review) {
            $review->product->updateRatingCache();
        });

        static::updated(function ($review) {
        // ✅ HANYA update rating jika rating atau is_approved berubah
        if ($review->isDirty('rating') || $review->isDirty('is_approved')) {
            $review->product->updateRatingCache();
        }
    });
        static::deleted(function ($review) {
            $review->product->updateRatingCache();
        });
    }
}