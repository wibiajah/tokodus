<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_id',
        'toko_id',
        'stock',
        'is_active', // 🔥 TAMBAHKAN INI!
    ];

    protected $casts = [
        'stock' => 'integer',
        'is_active' => 'boolean', // 🔥 TAMBAHKAN INI!
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Relasi ke Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke ProductVariant
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Relasi ke Toko
     */
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope: filter by toko
     */
    public function scopeInToko($query, $tokoId)
    {
        return $query->where('toko_id', $tokoId);
    }

    /**
     * Scope: filter by product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope: filter by variant
     */
    public function scopeForVariant($query, $variantId)
    {
        return $query->where('variant_id', $variantId);
    }

    /**
     * Scope: hanya yang ada stok
     */
    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * 🔥 NEW: Scope untuk yang aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 🔥 NEW: Scope untuk yang nonaktif
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    /**
     * Cek apakah stok tersedia
     */
    public function isAvailable()
    {
        return $this->stock > 0;
    }

    /**
     * 🔥 NEW: Cek apakah aktif
     */
    public function isActive()
    {
        return $this->is_active === true || $this->is_active === 1;
    }

    /**
     * Tambah stok
     */
    public function addStock($quantity)
    {
        $this->increment('stock', $quantity);
        return $this;
    }

    /**
     * Kurangi stok
     */
    public function reduceStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    /**
     * Get variant display name
     */
    public function getVariantNameAttribute()
    {
        return $this->variant?->display_name ?? '-';
    }

    /**
     * Get toko name
     */
    public function getTokoNameAttribute()
    {
        return $this->toko?->nama_toko ?? '-';
    }

    /**
     * Format stock for display
     */
    public function getFormattedStockAttribute()
    {
        return number_format($this->stock, 0, ',', '.') . ' pcs';
    }

    /**
     * 🔥 NEW: Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        return $this->is_active 
            ? '<span class="badge badge-success">Aktif</span>' 
            : '<span class="badge badge-warning">Nonaktif</span>';
    }

    /**
     * 🔥 NEW: Get status text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }
}