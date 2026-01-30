<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'toko_id',
        'type',
        'requested_by',
        'approved_by',
        'processed_by', // ✅ TAMBAHKAN INI
        'status',
        'items',
        'notes',
        'requested_at',
        'approved_at',
        'processed_at', // ✅ TAMBAHKAN INI
        'approval_notes', // ✅ TAMBAHKAN INI
        'rejection_reason', // ✅ TAMBAHKAN INI
    ];

    protected $casts = [
        'items' => 'array',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'processed_at' => 'datetime', // ✅ TAMBAHKAN INI
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
     * Relasi ke Toko yang request
     */
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    /**
     * Relasi ke User yang request (Kepala Toko / Staff)
     */
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * ✅ NEW: Relasi ke User yang approve/reject (Super Admin / Admin)
     * Alias untuk backward compatibility
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * ✅ NEW: Relasi ke User yang proses (approve/reject/cancel)
     * Ini yang dipanggil di controller
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Relasi ke distribution logs
     */
    public function distributionLogs()
    {
        return $this->hasMany(StockDistributionLog::class, 'source_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope: hanya request type (bukan direct)
     */
    public function scopeRequests($query)
    {
        return $query->where('type', 'request');
    }

    /**
     * Scope: hanya direct type
     */
    public function scopeDirect($query)
    {
        return $query->where('type', 'direct');
    }

    /**
     * Scope: hanya pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: hanya approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: hanya rejected
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: filter by toko
     */
    public function scopeForToko($query, $tokoId)
    {
        return $query->where('toko_id', $tokoId);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    /**
     * Cek apakah pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Cek apakah approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Cek apakah rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Cek apakah direct distribution
     */
    public function isDirect()
    {
        return $this->type === 'direct';
    }

    /**
     * Cek apakah request dari toko
     */
    public function isRequest()
    {
        return $this->type === 'request';
    }

    /**
     * Get total quantity dari semua items
     */
    public function getTotalQuantityAttribute()
    {
        if (!is_array($this->items)) {
            return 0;
        }
        
        return collect($this->items)->sum('quantity');
    }

    /**
     * Get variants yang direquest (dengan data lengkap)
     */
    public function getVariantsAttribute()
    {
        if (!is_array($this->items)) {
            return collect();
        }

        $variantIds = collect($this->items)->pluck('variant_id');
        return ProductVariant::whereIn('id', $variantIds)->get();
    }

    /**
     * Get status badge color (untuk UI)
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get status label (untuk UI)
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    /**
     * Get type label (untuk UI)
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'direct' => 'Distribusi Langsung',
            'request' => 'Request dari Toko',
            default => 'Unknown',
        };
    }

    /**
     * Format requested_at untuk display
     */
    public function getFormattedRequestedAtAttribute()
    {
        return $this->requested_at?->format('d M Y H:i') ?? '-';
    }

    /**
     * Format approved_at untuk display
     */
    public function getFormattedApprovedAtAttribute()
    {
        return $this->approved_at?->format('d M Y H:i') ?? '-';
    }

    /**
     * ✅ NEW: Format processed_at untuk display
     */
    public function getFormattedProcessedAtAttribute()
    {
        return $this->processed_at?->format('d M Y H:i') ?? '-';
    }
}