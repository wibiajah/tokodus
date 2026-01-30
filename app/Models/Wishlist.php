<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
    ];

    /**
     * Get the customer that owns the wishlist
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product in the wishlist
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope untuk filter by customer
     */
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Check apakah produk sudah ada di wishlist customer
     */
    public static function exists($customerId, $productId): bool
    {
        return self::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->exists();
    }

    /**
     * Toggle wishlist (add jika belum ada, remove jika sudah ada)
     */
    public static function toggle($customerId, $productId): array
    {
        $wishlist = self::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            // Sudah ada, hapus dari wishlist
            $wishlist->delete();
            return [
                'action' => 'removed',
                'message' => 'Produk dihapus dari wishlist'
            ];
        } else {
            // Belum ada, tambahkan ke wishlist
            self::create([
                'customer_id' => $customerId,
                'product_id' => $productId,
            ]);
            return [
                'action' => 'added',
                'message' => 'Produk ditambahkan ke wishlist'
            ];
        }
    }
}