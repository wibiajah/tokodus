<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'price_per_km',
        'min_charge',
        'max_distance',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_per_km' => 'decimal:2',
        'min_charge' => 'decimal:2',
        'max_distance' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk filter shipping yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get shipping setting by slug
     */
    public static function getBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }
}