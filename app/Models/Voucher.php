<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_purchase',
        'start_date',
        'end_date',
        'is_active',
        'usage_limit_total',
        'usage_limit_per_customer',
        'usage_count',
        'can_combine',
        'distribution_type',
        'scope',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'can_combine' => 'boolean',
        'usage_count' => 'integer',
    ];

    // ===== RELASI =====
    
    /**
     * ✅ FIXED: Pastikan nama tabel pivot konsisten
     * Nama tabel pivot harus: voucher_products (singular_plural)
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'voucher_products', 'voucher_id', 'product_id');
    }

    /**
     * ✅ FIXED: Pastikan nama tabel pivot konsisten
     * Nama tabel pivot harus: voucher_categories
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'voucher_categories', 'voucher_id', 'category_id');
    }

    /**
     * ✅ FIXED: Pastikan nama tabel pivot konsisten
     * Nama tabel pivot harus: voucher_customers
     */
    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'voucher_customers', 'voucher_id', 'customer_id');
    }

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    // ===== VALIDASI VOUCHER =====
    
    /**
     * Cek apakah voucher valid secara umum
     */
    public function isValid()
    {
        // Cek status aktif
        if (!$this->is_active) {
            return false;
        }

        // Cek tanggal
        $now = Carbon::now();
        if ($now->lt($this->start_date) || $now->gt($this->end_date)) {
            return false;
        }

        // Cek usage limit total
        if ($this->usage_limit_total && $this->usage_count >= $this->usage_limit_total) {
            return false;
        }

        return true;
    }

    /**
     * Validasi voucher untuk customer tertentu
     */
    public function isValidForCustomer($customerId, $cartTotal)
    {
        // Cek validasi umum
        if (!$this->isValid()) {
            return [
                'valid' => false,
                'message' => 'Voucher tidak valid atau sudah kadaluarsa'
            ];
        }

        // Cek minimal pembelian
        if ($cartTotal < $this->min_purchase) {
            return [
                'valid' => false,
                'message' => 'Minimal pembelian Rp ' . number_format($this->min_purchase, 0, ',', '.') . ' untuk menggunakan voucher ini'
            ];
        }

        // Cek apakah customer berhak (untuk private voucher atau public dengan pembatasan)
        if ($this->distribution_type === 'private' || $this->customers()->count() > 0) {
            if (!$this->customers()->where('customers.id', $customerId)->exists()) {
                return [
                    'valid' => false,
                    'message' => 'Voucher ini tidak tersedia untuk Anda'
                ];
            }
        }

        // Cek usage limit per customer
        if ($this->usage_limit_per_customer) {
            $customerUsageCount = $this->usages()
                ->where('customer_id', $customerId)
                ->count();
            
            if ($customerUsageCount >= $this->usage_limit_per_customer) {
                return [
                    'valid' => false,
                    'message' => 'Anda sudah mencapai batas penggunaan voucher ini'
                ];
            }
        }

        return ['valid' => true];
    }

    /**
     * Cek apakah produk termasuk dalam voucher
     */
    public function isApplicableToProduct($productId)
    {
        if ($this->scope === 'all_products') {
            return true;
        }

        if ($this->scope === 'specific_products') {
            return $this->products()->where('products.id', $productId)->exists();
        }

        if ($this->scope === 'specific_categories') {
            $product = Product::find($productId);
            if ($product) {
                $productCategoryIds = $product->categories()->pluck('categories.id')->toArray();
                $voucherCategoryIds = $this->categories()->pluck('categories.id')->toArray();
                
                return count(array_intersect($productCategoryIds, $voucherCategoryIds)) > 0;
            }
        }

        return false;
    }

    /**
     * Hitung diskon untuk cart
     */
    public function calculateDiscount($cartTotal, $cartItems = [])
    {
        $discount = 0;

        // Hitung subtotal produk yang eligible
        $eligibleTotal = 0;
        
        if ($this->scope === 'all_products') {
            $eligibleTotal = $cartTotal;
        } else {
            foreach ($cartItems as $item) {
                if ($this->isApplicableToProduct($item['product_id'])) {
                    $eligibleTotal += $item['subtotal'];
                }
            }
        }

        // Hitung diskon
        if ($this->discount_type === 'fixed') {
            $discount = $this->discount_value;
        } else { // percentage
            $discount = ($eligibleTotal * $this->discount_value) / 100;
            
            // Apply max discount jika ada
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        }

        // Pastikan diskon tidak lebih dari eligible total
        $discount = min($discount, $eligibleTotal);

        return $discount;
    }

    /**
     * Record penggunaan voucher
     */
    public function recordUsage($customerId, $orderId, $discountAmount, $orderTotal)
    {
        VoucherUsage::create([
            'voucher_id' => $this->id,
            'customer_id' => $customerId,
            'order_id' => $orderId,
            'discount_amount' => $discountAmount,
            'order_total' => $orderTotal,
            'used_at' => now(),
        ]);

        $this->increment('usage_count');
    }

    // ===== ACCESSOR & HELPER =====
    
    public function getDiscountDisplayAttribute()
    {
        if ($this->discount_type === 'fixed') {
            return 'Rp ' . number_format($this->discount_value, 0, ',', '.');
        }
        return $this->discount_value . '%';
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) return 'Nonaktif';
        
        $now = Carbon::now();
        if ($now->lt($this->start_date)) return 'Belum Mulai';
        if ($now->gt($this->end_date)) return 'Kadaluarsa';
        
        if ($this->usage_limit_total && $this->usage_count >= $this->usage_limit_total) {
            return 'Limit Tercapai';
        }
        
        return 'Aktif';
    }

    public function getRemainingUsageAttribute()
    {
        if (!$this->usage_limit_total) return 'Unlimited';
        
        $remaining = $this->usage_limit_total - $this->usage_count;
        return max(0, $remaining);
    }

    //mutator

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = $value ? strtoupper($value) : null;
    }

    public function getCodeAttribute($value)
    {
        return $value ? strtoupper($value) : null;
    }
}