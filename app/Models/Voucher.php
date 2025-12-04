<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'usage_limit',
        'usage_count',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relasi ke produk yang kena diskon
    public function products()
    {
        return $this->belongsToMany(Product::class, 'voucher_products');
    }

    // Relasi ke diskon kuantitas
    public function quantityDiscounts()
    {
        return $this->hasMany(VoucherQuantityDiscount::class);
    }

    // Cek apakah voucher masih valid
    public function isValid()
    {
        return $this->is_active 
            && now()->between($this->start_date, $this->end_date)
            && ($this->usage_limit === null || $this->usage_count < $this->usage_limit);
    }

    // Hitung diskon berdasarkan quantity
    public function calculateDiscount($productPrice, $quantity = 1)
    {
        $discount = 0;

        // Cek diskon kuantitas
        $quantityDiscount = $this->quantityDiscounts()
            ->where('min_quantity', '<=', $quantity)
            ->orderBy('min_quantity', 'desc')
            ->first();

        if ($quantityDiscount) {
            $discount = $quantityDiscount->discount_amount * $quantity;
        } else {
            // Diskon dasar
            if ($this->discount_type === 'fixed') {
                $discount = $this->discount_value;
            } else {
                $discount = ($productPrice * $this->discount_value) / 100;
            }
        }

        return $discount;
    }
}