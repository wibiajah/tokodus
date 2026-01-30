<?php
// app/Models/Customer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomerVerifyEmail;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $guard = 'customer';

     public function sendEmailVerificationNotification()
{
    $this->notify(new CustomerVerifyEmail);
}

    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'postal_code',
        'foto_profil',
        'latitude',    // 🆕 TAMBAH INI
        'longitude',
        'google_id',  // tambah ini
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ========== CART RELATIONS ==========
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function getCartCountAttribute()
    {
        return $this->carts()->sum('quantity');
    }

    public function getCartTotalAttribute()
    {
        return $this->carts()->get()->sum('final_subtotal');
    }

    public function hasCartItems()
    {
        return $this->carts()->count() > 0;
    }

    public function getGroupedCartItems()
    {
        return $this->carts()
            ->with(['product', 'toko'])
            ->get()
            ->groupBy('toko_id');
    }

    // ========== ORDER RELATIONS (BARU) ==========
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getTotalSpentAttribute()
    {
        return $this->orders()->paid()->sum('total');
    }

    public function getTotalSavingsAttribute()
    {
        return $this->orders()->paid()->sum('discount_amount');
    }

    public function getPendingOrdersCountAttribute()
    {
        return $this->orders()->pending()->count();
    }

    public function getCompletedOrdersCountAttribute()
    {
        return $this->orders()->completed()->count();
    }

    public function getTotalOrdersAttribute()
    {
        return $this->orders()->count();
    }

    // ========== VOUCHER RELATIONS ==========
    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'voucher_customers');
    }

    public function voucherUsages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function availableVouchers()
    {
        return $this->vouchers()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereRaw('(usage_limit_total IS NULL OR usage_count < usage_limit_total)');
    }

    public function canUseVoucher($voucherId)
    {
        $voucher = Voucher::find($voucherId);

        if (!$voucher) return false;

        // Check usage limit per customer
        if ($voucher->usage_limit_per_customer) {
            $usageCount = $this->voucherUsages()
                ->where('voucher_id', $voucherId)
                ->count();

            if ($usageCount >= $voucher->usage_limit_per_customer) {
                return false;
            }
        }

        // Check customer access (for private vouchers)
        if ($voucher->distribution_type === 'private' || $voucher->customers()->count() > 0) {
            return $voucher->customers()->where('customer_id', $this->id)->exists();
        }

        return true;
    }

    // ========== PROFILE ATTRIBUTES ==========
    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getFotoProfilUrlAttribute()
    {
        if ($this->foto_profil) {
            return Storage::url($this->foto_profil);
        }
        return asset('frontend/assets/img/default-avatar.png');
    }

    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return '-';
        return preg_replace('/(\d{4})(\d{4})(\d+)/', '$1-$2-$3', $this->phone);
    }

    // ========== LOYALTY & REWARDS ==========
    public function getLoyaltyPointsAttribute()
    {
        // 1 point per Rp 10.000
        return floor($this->total_spent / 10000);
    }

    public function getMembershipTierAttribute()
    {
        $spent = $this->total_spent;

        if ($spent >= 10000000) return 'Platinum';
        if ($spent >= 5000000) return 'Gold';
        if ($spent >= 1000000) return 'Silver';
        return 'Bronze';
    }

       public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get all wishlist products (many-to-many through wishlists table)
     */
    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
            ->withTimestamps();
    }

    /**
     * Check if customer has product in wishlist
     */
    public function hasInWishlist($productId): bool
    {
        return $this->wishlists()->where('product_id', $productId)->exists();
    }

    /**
     * Get wishlist product IDs (untuk pass ke view)
     */
    public function getWishlistProductIds(): array
    {
        return $this->wishlists()->pluck('product_id')->toArray();
    }

    // ============================================
// 🆕 TAMBAHKAN DI Customer.php (setelah relationship orders())
// ============================================

/**
 * Relasi ke product reviews
 */
public function reviews()
{
    return $this->hasMany(ProductReview::class);
}

/**
 * Check apakah customer sudah review produk di order tertentu
 */
public function hasReviewedProduct($productId, $orderId): bool
{
    return $this->reviews()
        ->where('product_id', $productId)
        ->where('order_id', $orderId)
        ->exists();
}

/**
 * Validasi apakah customer bisa review produk
 */
public function canReviewProduct($productId, $orderId): bool
{
    $order = $this->orders()->find($orderId);
    
    if (!$order) {
        return false;
    }
    
    // Order harus completed
    if (!$order->canBeReviewed()) {
        return false;
    }
    
    // Produk harus ada di order items
    $hasProduct = $order->items()->where('product_id', $productId)->exists();
    if (!$hasProduct) {
        return false;
    }
    
    // Belum pernah review
    if ($this->hasReviewedProduct($productId, $orderId)) {
        return false;
    }
    
    return true;
}

/**
 * Get total reviews by customer
 */
public function getTotalReviewsAttribute()
{
    return $this->reviews()->count();
}

// ============================================
// 🆕 CHAT RELATIONSHIP - TAMBAHKAN DI CUSTOMER.PHP (SETELAH RELATIONSHIP reviews())
// ============================================

/**
 * Relasi ke chat participants (as customer)
 */
public function chatParticipants()
{
    return $this->morphMany(ChatParticipant::class, 'participantable');
}

/**
 * Relasi ke chat messages (as sender)
 */
public function chatMessages()
{
    return $this->morphMany(ChatMessage::class, 'sender');
}

/**
 * Get all chat rooms customer ini
 */
public function chatRooms()
{
    return $this->morphToMany(ChatRoom::class, 'participantable', 'chat_participants')
        ->withPivot('role', 'last_read_at', 'joined_at')
        ->withTimestamps();
}

/**
 * Get active chat rooms
 */
public function activeChatRooms()
{
    return $this->chatRooms()->where('status', 'active');
}

/**
 * Get total unread messages count
 */
public function getUnreadChatCountAttribute()
{
    $count = 0;
    foreach ($this->activeChatRooms as $room) {
        $count += $room->getUnreadCount(Customer::class, $this->id);
    }
    return $count;
}
}
