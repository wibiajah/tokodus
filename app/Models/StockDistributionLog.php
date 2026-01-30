<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDistributionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_id',
        'toko_id',
        'quantity',
        'type',
        'source_type',
        'source_id',
        'performed_by',
        'stock_before',
        'stock_after',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function stockRequest()
    {
        return $this->belongsTo(StockRequest::class, 'source_id');
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeForToko($query, $tokoId)
    {
        return $query->where('toko_id', $tokoId);
    }

    public function scopeForVariant($query, $variantId)
    {
        return $query->where('variant_id', $variantId);
    }

    /**
     * 🔥 Scope: Distribusi dari warehouse (IN)
     */
    public function scopeIncoming($query)
    {
        return $query->where('type', 'in');
    }

    /**
     * 🔥 Scope: Keluar dari warehouse (OUT)
     */
    public function scopeOutgoing($query)
    {
        return $query->where('type', 'out');
    }

    /**
     * 🔥 NEW: Scope untuk distribusi warehouse (direct atau request)
     */
    public function scopeWarehouseDistribution($query)
    {
        return $query->where('type', 'in')
            ->whereIn('source_type', ['direct', 'request']);
    }

    /**
     * 🔥 NEW: Scope untuk edit manual kepala toko
     */
    public function scopeManualEdit($query)
    {
        return $query->where('type', 'manual_adjustment')
            ->where('source_type', 'manual_edit');
    }

    public function scopeFromDirect($query)
    {
        return $query->where('source_type', 'direct');
    }

    public function scopeFromRequest($query)
    {
        return $query->where('source_type', 'request');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function isIncoming()
    {
        return $this->type === 'in';
    }

    public function isOutgoing()
    {
        return $this->type === 'out';
    }

    /**
     * 🔥 NEW: Cek apakah manual adjustment
     */
    public function isManualAdjustment()
    {
        return $this->type === 'manual_adjustment';
    }

    /**
     * 🔥 NEW: Cek apakah distribusi warehouse
     */
    public function isWarehouseDistribution()
    {
        return $this->type === 'in' && in_array($this->source_type, ['direct', 'request']);
    }

    public function getVariantNameAttribute()
    {
        return $this->variant?->display_name ?? '-';
    }

    public function getTokoNameAttribute()
    {
        return $this->toko?->nama_toko ?? '-';
    }

    public function getPerformerNameAttribute()
    {
        return $this->performedBy?->name ?? '-';
    }

    /**
     * 🔥 UPDATED: Get type label (untuk UI)
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'in' => 'Masuk ke Toko',
            'out' => 'Keluar dari Warehouse',
            'manual_adjustment' => 'Edit Manual',
            default => 'Unknown',
        };
    }

    /**
     * 🔥 UPDATED: Get type badge color
     */
    public function getTypeBadgeAttribute()
    {
        return match($this->type) {
            'in' => 'success',
            'out' => 'danger',
            'manual_adjustment' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * 🔥 UPDATED: Get source label
     */
    public function getSourceLabelAttribute()
    {
        return match($this->source_type) {
            'direct' => 'Distribusi Langsung',
            'request' => 'Request Toko',
            'manual_edit' => 'Edit Manual Kepala Toko',
            default => 'Unknown',
        };
    }

    public function getStockDifferenceAttribute()
    {
        return $this->stock_after - $this->stock_before;
    }

    /**
     * 🔥 UPDATED: Format quantity untuk display (support negatif)
     */
    public function getFormattedQuantityAttribute()
    {
        if ($this->isManualAdjustment()) {
            // Untuk manual adjustment, quantity bisa negatif
            $prefix = $this->quantity > 0 ? '+' : '';
            return $prefix . number_format($this->quantity, 0, ',', '.') . ' pcs';
        }
        
        // Untuk distribusi warehouse, selalu positif
        $prefix = $this->isIncoming() ? '+' : '-';
        return $prefix . number_format(abs($this->quantity), 0, ',', '.') . ' pcs';
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d M Y H:i');
    }

    /**
     * 🔥 UPDATED: Get full description untuk log
     */
    public function getDescriptionAttribute()
    {
        if ($this->isManualAdjustment()) {
            $action = $this->quantity > 0 ? 'ditambah' : 'dikurangi';
            return "{$this->variant_name} {$action} {$this->formatted_quantity} di {$this->toko_name}";
        }
        
        $action = $this->isIncoming() ? 'masuk ke' : 'keluar dari';
        return "{$this->formatted_quantity} {$this->variant_name} {$action} {$this->toko_name}";
    }
}