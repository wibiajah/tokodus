<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // ============================================
    // 🆕 STATUS CONSTANTS
    // ============================================
    const STATUS_PENDING = 'pending';      // Menunggu Pembayaran
    const STATUS_PAID = 'paid';            // Dibayar (BARU - pengganti processing)
    const STATUS_SHIPPED = 'shipped';      // Dikirim
    const STATUS_COMPLETED = 'completed';  // Selesai
    const STATUS_CANCELLED = 'cancelled';  // Dibatalkan

    // Payment Status Constants
    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_REFUNDED = 'refunded';

    protected $fillable = [
        'order_number',
        'payment_reference',
        'customer_id',
        'toko_id',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'shipping_cost',
        'shipping_type',
        'shipping_distance',
        'customer_latitude',
        'customer_longitude',
        'toko_latitude',
        'toko_longitude',
        'total',
        'status',
        'payment_status',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'delivery_method',
        'pickup_toko_id',
        'notes',
        'resi_number',
        'courier_name',
        'courier_phone',
        'courier_photo',
        'shipping_notes',
        'shipped_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'shipping_distance' => 'decimal:2',
        'customer_latitude' => 'decimal:8',
        'customer_longitude' => 'decimal:8',
        'toko_latitude' => 'decimal:8',
        'toko_longitude' => 'decimal:8',
        'total' => 'decimal:2',
        'shipped_at' => 'datetime',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function voucherUsages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function pickupToko()
    {
        return $this->belongsTo(Toko::class, 'pickup_toko_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class)->orderBy('created_at', 'desc');
    }

    // 🆕 Relationship to Product Reviews
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    // ============================================
// 🆕 CHAT RELATIONSHIP - TAMBAHKAN DI ORDER.PHP (SETELAH RELATIONSHIP reviews())
// ============================================

/**
 * Relasi ke chat rooms
 */
public function chatRooms()
{
    return $this->hasMany(ChatRoom::class);
}

/**
 * Get active chat room untuk order ini
 */
public function activeChatRoom()
{
    return $this->hasOne(ChatRoom::class)->where('status', 'active');
}

/**
 * Check apakah order ini punya chat room aktif
 */
public function hasChatRoom()
{
    return $this->chatRooms()->where('status', 'active')->exists();
}

    // ============================================
    // ACCESSORS
    // ============================================

    public function getVoucherAttribute()
    {
        $usage = $this->voucherUsages()->first();
        return $usage ? $usage->voucher : null;
    }

    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    public function getSavingsAttribute()
    {
        return $this->discount_amount;
    }

    public function isPickup()
    {
        return $this->delivery_method === 'pickup';
    }

    public function isDelivery()
    {
        return $this->delivery_method === 'delivery';
    }

    public function getDeliveryMethodTextAttribute()
    {
        return $this->isPickup() ? 'Ambil di Toko' : 'Dikirim ke Alamat';
    }

    public function getShippingTypeTextAttribute()
    {
        $types = [
            'reguler' => 'Reguler',
            'instant' => 'Instant',
        ];
        return $types[$this->shipping_type] ?? '-';
    }

    public function isShipped()
    {
        return $this->status === self::STATUS_SHIPPED && !empty($this->resi_number);
    }

    // ============================================
    // 🆕 STATUS LABEL & COLOR ATTRIBUTES
    // ============================================

    public function getStatusLabelAttribute(): string
    {
        return [
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_PAID => 'Dibayar',
            self::STATUS_SHIPPED => 'Dikirim',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ][$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute(): string
    {
        return [
            self::STATUS_PENDING => 'warning',    // kuning
            self::STATUS_PAID => 'info',          // biru
            self::STATUS_SHIPPED => 'primary',    // biru tua
            self::STATUS_COMPLETED => 'success',  // hijau
            self::STATUS_CANCELLED => 'danger',   // merah
        ][$this->status] ?? 'secondary';
    }

    // ============================================
    // BADGES (Updated)
    // ============================================

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => '<span class="badge bg-warning">Menunggu Pembayaran</span>',
            self::STATUS_PAID => '<span class="badge bg-info">Dibayar</span>',
            self::STATUS_SHIPPED => '<span class="badge bg-primary">Dikirim</span>',
            self::STATUS_COMPLETED => '<span class="badge bg-success">Selesai</span>',
            self::STATUS_CANCELLED => '<span class="badge bg-danger">Dibatalkan</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            self::PAYMENT_UNPAID => '<span class="badge bg-warning">Belum Dibayar</span>',
            self::PAYMENT_PAID => '<span class="badge bg-success">Sudah Dibayar</span>',
            self::PAYMENT_REFUNDED => '<span class="badge bg-danger">Refund</span>',
        ];

        return $badges[$this->payment_status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    // ============================================
    // STATUS HELPERS (Updated)
    // ============================================

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid()
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    // ============================================
    // PAYMENT STATUS HELPERS
    // ============================================

    public function isPaymentPaid()
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function isUnpaid()
    {
        return $this->payment_status === self::PAYMENT_UNPAID;
    }

    public function isRefunded()
    {
        return $this->payment_status === self::PAYMENT_REFUNDED;
    }

    // ============================================
    // 🆕 VALIDATION HELPERS - CANCEL
    // ============================================

    /**
     * Cek apakah order bisa dicancel oleh CUSTOMER
     * Rule: Hanya bisa cancel jika status PENDING
     */
    public function canBeCancelledByCustomer(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Cek apakah order bisa dicancel oleh ADMIN/SUPERADMIN
     * Rule: Bisa cancel selama belum COMPLETED
     */
    public function canBeCancelledByAdmin(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_PAID,
            self::STATUS_SHIPPED
        ]);
    }

    // Backward compatibility - deprecated
    public function canBeCancelled()
    {
        return $this->canBeCancelledByCustomer();
    }

    // ============================================
    // 🆕 VALIDATION HELPERS - COMPLETE
    // ============================================

    /**
     * Cek apakah order bisa diselesaikan oleh CUSTOMER
     * Rule: Hanya bisa complete jika status SHIPPED
     */
    public function canBeCompletedByCustomer(): bool
    {
        return $this->status === self::STATUS_SHIPPED;
    }


    public function canReportProblem(): bool
    {
        return in_array($this->status, [
            self::STATUS_PAID,
            self::STATUS_SHIPPED,
            self::STATUS_COMPLETED
        ]);
    }
    
    public function canBeConfirmedPayment(): bool
    {
        return $this->status === self::STATUS_PENDING
            && $this->payment_status === self::PAYMENT_UNPAID;
    }

    // ============================================
    // 🆕 VALIDATION HELPERS - REVIEW
    // ============================================

    /**
     * Cek apakah order bisa direview
     * Rule: Hanya bisa review jika status COMPLETED
     */
    public function canBeReviewed(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Cek apakah produk tertentu sudah direview
     */
    public function hasReviewedProduct(int $productId): bool
    {
        return $this->reviews()
            ->where('product_id', $productId)
            ->exists();
    }

    /**
     * Get review untuk produk tertentu
     */
    public function getProductReview(int $productId)
    {
        return $this->reviews()
            ->where('product_id', $productId)
            ->first();
    }

    /**
     * Get semua produk yang belum direview dari order ini
     */
    public function getUnreviewedProducts()
    {
        $reviewedProductIds = $this->reviews()->pluck('product_id')->toArray();

        return $this->items()
            ->whereNotIn('product_id', $reviewedProductIds)
            ->with('product')
            ->get()
            ->pluck('product')
            ->unique('id');
    }

    // ============================================
    // SCOPES (Updated)
    // ============================================

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeShipped($query)
    {
        return $query->where('status', self::STATUS_SHIPPED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_UNPAID);
    }

    public function scopePaymentPaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_PAID);
    }

    public function scopeForToko($query, $tokoId)
    {
        return $query->where('toko_id', $tokoId);
    }

    public function scopeByPaymentReference($query, $reference)
    {
        return $query->where('payment_reference', $reference);
    }

    // ============================================
    // BUSINESS LOGIC
    // ============================================

    /**
     * Check if order can be updated by user
     */
    public function canBeUpdatedBy($user)
    {
        // Super Admin bisa update semua
        if ($user->role === 'super_admin') {
            return true;
        }

        // Kepala Toko hanya bisa update order tokonya sendiri
        if ($user->role === 'kepala_toko') {
            return $this->toko_id === $user->toko_id;
        }

        return false;
    }

    /**
     * Log status change
     */
    public function logStatusChange($statusFrom, $statusTo, $userId = null, $notes = null)
    {
        return $this->statusLogs()->create([
            'status_from' => $statusFrom,
            'status_to' => $statusTo,
            'changed_by' => $userId,
            'notes' => $notes,
            'created_at' => now(),
        ]);
    }

    /**
     * Update status with logging
     */
    public function updateStatus($newStatus, $userId = null, $notes = null, $additionalData = [])
    {
        $oldStatus = $this->status;

        // Update status
        $this->status = $newStatus;

        // Update additional data (resi, courier, etc)
        if (!empty($additionalData)) {
            foreach ($additionalData as $key => $value) {
                $this->$key = $value;
            }
        }

        // Jika status shipped, set shipped_at
        if ($newStatus === self::STATUS_SHIPPED && empty($this->shipped_at)) {
            $this->shipped_at = now();
        }

        $this->save();

        // Log the change
        $this->logStatusChange($oldStatus, $newStatus, $userId, $notes);

        return true;
    }

    // ============================================
    // STATIC METHODS
    // ============================================

    public static function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        return "ORD-{$date}-{$random}";
    }

    /**
     * 🆕 Get valid status transitions
     * Untuk validasi perubahan status
     */
    public static function getValidStatusTransitions(): array
    {
        return [
            self::STATUS_PENDING => [self::STATUS_PAID, self::STATUS_CANCELLED],
            self::STATUS_PAID => [self::STATUS_SHIPPED, self::STATUS_CANCELLED],
            self::STATUS_SHIPPED => [self::STATUS_COMPLETED, self::STATUS_CANCELLED],
            self::STATUS_COMPLETED => [], // completed tidak bisa diubah
            self::STATUS_CANCELLED => [], // cancelled tidak bisa diubah
        ];
    }

    /**
     * 🆕 Validate status transition
     */
    public function isValidStatusTransition(string $newStatus): bool
    {
        $validTransitions = self::getValidStatusTransitions();
        return in_array($newStatus, $validTransitions[$this->status] ?? []);
    }
}
