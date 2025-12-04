<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'review',
        'photos',
        'is_approved',
    ];

    protected $casts = [
        'photos' => 'array',
        'is_approved' => 'boolean',
        'rating' => 'integer',
    ];

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Auto update rating produk saat review dibuat
    protected static function booted()
    {
        static::created(function ($review) {
            $product = $review->product;
            $product->update([
                'rating' => $product->reviews()->avg('rating'),
                'review_count' => $product->reviews()->count(),
            ]);
        });

        static::updated(function ($review) {
            $product = $review->product;
            $product->update([
                'rating' => $product->reviews()->avg('rating'),
                'review_count' => $product->reviews()->count(),
            ]);
        });

        static::deleted(function ($review) {
            $product = $review->product;
            $product->update([
                'rating' => $product->reviews()->avg('rating') ?? 0,
                'review_count' => $product->reviews()->count(),
            ]);
        });
    }
}