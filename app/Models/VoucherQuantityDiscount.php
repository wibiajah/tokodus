<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherQuantityDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_id',
        'min_quantity',
        'discount_amount',
    ];

    protected $casts = [
        'min_quantity' => 'integer',
        'discount_amount' => 'decimal:2',
    ];

    // Relasi ke voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}