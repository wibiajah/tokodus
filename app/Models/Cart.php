<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'variant_id',
        'toko_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // ============================================
    // EXISTING ACCESSORS
    // ============================================

    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getFinalPriceAttribute()
    {
        if ($this->variant && $this->variant->price) {
            return $this->variant->price;
        }
        
        return $this->product->final_price ?? $this->price;
    }

    public function getFinalSubtotalAttribute()
    {
        return $this->final_price * $this->quantity;
    }

    // ============================================
    // 🔥 FIXED ACCESSORS FOR PRODUCTION
    // ============================================

    /**
     * Get variant display name
     */
    public function getVariantNameAttribute()
    {
        return $this->variant?->display_name ?? '-';
    }

    /**
     * Check if variant exists
     */
    public function hasVariant()
    {
        return $this->variant_id !== null;
    }

    /**
     * Get variant color name
     */
    public function getVariantColorAttribute()
    {
        if (!$this->variant) {
            return '-';
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
     * Get variant size name
     */
    public function getVariantSizeAttribute()
    {
        if (!$this->variant) {
            return '-';
        }
        
        if ($this->variant->type === 'size') {
            return $this->variant->name;
        }
        
        return '-';
    }

    /**
     * 🔥 FIX: Get variant photo URL with proper fallback
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
}

