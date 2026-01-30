<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',      // 🆕 ADDED
        'product_title',
        'product_sku',
        'variant_name',    // 🆕 ADDED
        'quantity',
        'price',
        'discount_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 🆕 Relasi ke ProductVariant
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // ============================================
    // ACCESSORS
    // ============================================

    /**
     * Get harga final (dengan diskon jika ada)
     */
    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Check if has discount
     */
    public function getHasDiscountAttribute()
    {
        return $this->discount_price && $this->discount_price < $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount) return 0;

        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    /**
     * Get savings per item
     */
    public function getSavingsAttribute()
    {
        if (!$this->has_discount) return 0;

        return ($this->price - $this->discount_price) * $this->quantity;
    }

    // ============================================
    // 🆕 VARIANT ACCESSORS
    // ============================================

    /**
     * Get variant display name
     * Fallback ke variant_name (string) jika variant relationship tidak ada
     */
    public function getVariantDisplayAttribute()
    {
        if ($this->variant) {
            return $this->variant->display_name;
        }

        return $this->variant_name ?? '-';
    }

    /**
     * Get variant photo URL
     */
    /**
     * 🔥 FIXED: Get variant photo URL
     * Ganti accessor ini di OrderItem Model Anda
     */
    public function getVariantPhotoAttribute()
    {
        // No variant -> use product thumbnail
        if (!$this->variant) {
            return $this->product
                ? $this->product->thumbnail
                : asset('frontend/assets/img/placeholder-product.png');
        }

        // Size variant -> use parent (color) photo
        if ($this->variant->type === 'size' && $this->variant->parent) {
            if ($this->variant->parent->photo) {
                return asset('storage/' . $this->variant->parent->photo);
            }
        }

        // Color variant -> use own photo
        if ($this->variant->type === 'color' && $this->variant->photo) {
            return asset('storage/' . $this->variant->photo);
        }

        // Final fallback to product thumbnail
        return $this->product
            ? $this->product->thumbnail
            : asset('frontend/assets/img/placeholder-product.png');
    }

    /**
     * Get variant color name
     */
    /**
     * 🔥 FIXED: Get variant color name
     * Ganti accessor ini di OrderItem Model Anda
     */
    public function getVariantColorAttribute()
    {
        if (!$this->variant) {
            return $this->parseVariantName('color');
        }

        // Size variant -> get color from parent
        if ($this->variant->type === 'size' && $this->variant->parent) {
            return $this->variant->parent->name;
        }

        // Color variant -> get own name
        if ($this->variant->type === 'color') {
            return $this->variant->name;
        }

        return '-';
    }

    /**
     * 🔥 FIXED: Get variant size name
     * Ganti accessor ini di OrderItem Model Anda
     */
    public function getVariantSizeAttribute()
    {
        if (!$this->variant) {
            return $this->parseVariantName('size');
        }

        if ($this->variant->type === 'size') {
            return $this->variant->name;
        }

        return '-';
    }

    /**
     * Check if has variant
     */
    public function hasVariant()
    {
        return $this->variant_id !== null;
    }

    /**
     * Parse variant_name string untuk extract color/size
     * Format expected: "Color: Red, Size: L" atau "Color: Blue"
     */
    private function parseVariantName($type)
    {
        if (!$this->variant_name) {
            return '-';
        }

        $parts = explode(',', $this->variant_name);

        foreach ($parts as $part) {
            $part = trim($part);

            if ($type === 'color' && stripos($part, 'Color:') !== false) {
                return trim(str_replace(['Color:', 'Warna:'], '', $part));
            }

            if ($type === 'size' && stripos($part, 'Size:') !== false) {
                return trim(str_replace(['Size:', 'Ukuran:'], '', $part));
            }
        }

        return '-';
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get formatted final price with currency
     */
    public function getFormattedFinalPriceAttribute()
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }

    /**
     * Get formatted subtotal with currency
     */
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    // ============================================
// 🆕 TAMBAHKAN DI OrderItem.php (di bagian bawah sebelum closing class)
// ============================================

    /**
     * Check apakah item ini sudah direview
     */
    public function hasReview(): bool
    {
        return ProductReview::where('order_id', $this->order_id)
            ->where('product_id', $this->product_id)
            ->exists();
    }

    /**
     * Get review untuk item ini
     */
    public function getReview()
    {
        return ProductReview::where('order_id', $this->order_id)
            ->where('product_id', $this->product_id)
            ->first();
    }

    /**
     * Check apakah item bisa direview
     */
    public function canBeReviewed(): bool
    {
        // Order harus completed
        if (!$this->order || !$this->order->canBeReviewed()) {
            return false;
        }

        // Belum pernah direview
        if ($this->hasReview()) {
            return false;
        }

        return true;
    }
}
