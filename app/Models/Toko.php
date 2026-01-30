<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_toko',
        'alamat',
        'postal_code',
        'telepon',
        'email',
        'googlemap',
        'foto',
        'googlemap_iframe',
        'status',
        'latitude',    // 🆕 TAMBAH INI
        'longitude',   // 🆕 TAMBAH INI
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================
    // EXISTING RELATIONSHIPS
    // ============================================

    /**
     * Relasi: Toko memiliki banyak User
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi: Toko memiliki satu Kepala Toko
     */
    public function kepalaToko()
    {
        return $this->hasOne(User::class)->where('role', 'kepala_toko');
    }

    /**
     * Relasi: Toko memiliki banyak Staff Admin
     */
    public function staffAdmin()
    {
        return $this->hasMany(User::class)->where('role', 'staff_admin');
    }

    /**
     * ⚠️ DEPRECATED: Use variantStocks() instead
     */
    public function productStocks()
    {
        return $this->variantStocks();
    }

    /**
     * Relasi ke Product (melalui stocks)
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variant_stocks', 'toko_id', 'product_id')
            ->withPivot('stock')
            ->where('stock', '>', 0);
    }

    // ============================================
    // 🆕 NEW VARIANT RELATIONSHIPS
    // ============================================

    /**
     * 🆕 Relasi ke variant stocks di toko ini
     */
    public function variantStocks()
    {
        return $this->hasMany(ProductVariantStock::class);
    }

    /**
     * 🆕 Relasi ke stock requests dari toko ini
     */
    public function stockRequests()
    {
        return $this->hasMany(StockRequest::class);
    }

    /**
     * 🆕 Relasi ke distribution logs untuk toko ini
     */
    public function distributionLogs()
    {
        return $this->hasMany(StockDistributionLog::class);
    }

    // ============================================
// 🆕 CHAT RELATIONSHIP - TAMBAHKAN DI TOKO.PHP (SETELAH RELATIONSHIP distributionLogs())
// ============================================

    /**
     * Relasi ke chat rooms
     */
    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class);
    }

    /**
     * Get active chat rooms
     */
    public function activeChatRooms()
    {
        return $this->hasMany(ChatRoom::class)->where('status', 'active');
    }

    /**
     * Get unread chat count untuk toko ini
     */
    public function getUnreadChatCountAttribute()
    {
        $count = 0;
        foreach ($this->activeChatRooms as $room) {
            // Get kepala toko
            $kepalaToko = $this->kepalaToko;
            if ($kepalaToko) {
                $count += $room->getUnreadCount(User::class, $kepalaToko->id);
            }
        }
        return $count;
    }

    // ============================================
    // EXISTING ACCESSORS & METHODS
    // ============================================

    /**
     * Accessor: Nama Pemilik (dari Kepala Toko)
     */
    public function getPemilikAttribute()
    {
        return $this->kepalaToko()?->name ?? 'Belum ditentukan';
    }

    /**
     * Helper: Cek status aktif
     */
    public function isAktif()
    {
        return $this->status === 'aktif';
    }

    /**
     * Helper: Update status otomatis berdasarkan Kepala Toko
     */
    public function syncStatus()
    {
        $hasKepalaToko = $this->kepalaToko()->exists();

        if ($hasKepalaToko && $this->status === 'tidak_aktif') {
            $this->update(['status' => 'aktif']);
        } elseif (!$hasKepalaToko && $this->status === 'aktif') {
            // Bisa diubah manual, jadi hanya log saja
        }
    }

    /**
     * Extract src dari iframe HTML
     */
    public function extractIframeSrc()
    {
        if (!$this->googlemap_iframe) {
            return '';
        }

        if (preg_match('/src=["\']([^"\']+)["\']/', $this->googlemap_iframe, $matches)) {
            return $matches[1];
        }

        if (filter_var($this->googlemap_iframe, FILTER_VALIDATE_URL)) {
            return $this->googlemap_iframe;
        }

        return '';
    }

    /**
     * Get clean iframe embed URL
     */
    public function getCleanMapEmbedAttribute()
    {
        return $this->extractIframeSrc();
    }

    // ============================================
    // 🆕 NEW HELPER METHODS
    // ============================================

    /**
     * 🆕 Get total stok semua varian di toko ini
     */
    public function getTotalStockAttribute()
    {
        return $this->variantStocks()->sum('stock');
    }

    /**
     * 🆕 Get pending stock requests count
     */
    public function getPendingRequestsCountAttribute()
    {
        return $this->stockRequests()->pending()->count();
    }

    /**
     * 🆕 Get stok untuk produk tertentu di toko ini
     */
    public function getProductStock($productId)
    {
        return $this->variantStocks()
            ->where('product_id', $productId)
            ->get();
    }

    /**
     * 🆕 Get stok untuk variant tertentu di toko ini
     */
    public function getVariantStock($variantId)
    {
        return $this->variantStocks()
            ->where('variant_id', $variantId)
            ->first();
    }
}
