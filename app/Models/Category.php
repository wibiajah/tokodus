<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Auto generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });

        // Hapus foto saat kategori dihapus
        static::deleting(function ($category) {
            if ($category->photo && Storage::disk('public')->exists($category->photo)) {
                Storage::disk('public')->delete($category->photo);
            }
        });
    }

    // Relasi ke produk
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }
}