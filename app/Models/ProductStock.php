<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'toko_id',
        'stock',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke toko
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }
}