<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'name',
        'photo',
        'price',
        'parent_id',
        'stock_pusat',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_pusat' => 'integer',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Relasi ke Product
     * Setiap variant belongs to satu product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke parent variant (untuk size -> color)
     * Size variant akan punya parent_id = color variant
     */
    public function parent()
    {
        return $this->belongsTo(ProductVariant::class, 'parent_id');
    }

    /**
     * Relasi ke children variants (untuk color -> sizes)
     * Color variant bisa punya banyak size children
     */
    public function children()
    {
        return $this->hasMany(ProductVariant::class, 'parent_id');
    }

    /**
     * Relasi ke stocks di berbagai toko
     */
    public function stocks()
    {
        return $this->hasMany(ProductVariantStock::class, 'variant_id');
    }

    /**
     * Relasi ke distribution logs
     */
    public function distributionLogs()
    {
        return $this->hasMany(StockDistributionLog::class, 'variant_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope: hanya color variants
     */
    public function scopeColors($query)
    {
        return $query->where('type', 'color');
    }

    /**
     * Scope: hanya size variants
     */
    public function scopeSizes($query)
    {
        return $query->where('type', 'size');
    }

    /**
     * Scope: hanya parent variants (color tanpa parent)
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope: children dari parent tertentu
     */
    public function scopeChildrenOf($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    /**
     * Cek apakah ini color variant
     */
    public function isColor()
    {
        return $this->type === 'color';
    }

    /**
     * Cek apakah ini size variant
     */
    public function isSize()
    {
        return $this->type === 'size';
    }

    /**
     * Cek apakah punya children (untuk color)
     */
    public function hasChildren()
    {
        return $this->children()->exists();
    }

    /**
     * Get stok di toko tertentu
     */
    public function stockInToko($tokoId)
    {
        return $this->stocks()->where('toko_id', $tokoId)->first();
    }

    /**
     * Get total stok di semua toko
     */
    public function getTotalTokoStockAttribute()
    {
        return $this->stocks()->sum('stock');
    }

    /**
     * Get foto URL (untuk color)
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('frontend/assets/img/placeholder-variant.png');
    }

    /**
     * Get display name (untuk UI)
     */
    public function getDisplayNameAttribute()
    {
        if ($this->isSize() && $this->parent) {
            return $this->parent->name . ' - ' . $this->name;
        }
        return $this->name;
    }

    /**
     * Format harga (untuk size)
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->price) {
            return 'Rp ' . number_format($this->price, 0, ',', '.');
        }
        return '-';
    }

    /**
     * Cek apakah stok tersedia
     */
    public function isAvailable()
    {
        return $this->stock_pusat > 0;
    }

    /**
     * Auto-update parent stock ketika child stock berubah
     * Dipanggil dari Observer
     */
    public function updateParentStock()
    {
        if ($this->isSize() && $this->parent) {
            $totalChildStock = $this->parent->children()->sum('stock_pusat');
            $this->parent->update(['stock_pusat' => $totalChildStock]);
        }
    }
}