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
        'telepon',
        'email',
        'googlemap',
        'foto',
        'googlemap_iframe', 
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi: Toko memiliki banyak User
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi: Toko memiliki satu Kepala Toko
    public function kepalaToko()
    {
        return $this->hasOne(User::class)->where('role', 'kepala_toko');
    }

    // Relasi: Toko memiliki banyak Staff Admin
    public function staffAdmin()
    {
        return $this->hasMany(User::class)->where('role', 'staff_admin');
    }

    public function productStocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    // Accessor: Nama Pemilik (dari Kepala Toko)
    public function getPemilikAttribute()
    {
        return $this->kepalaToko()?->name ?? 'Belum ditentukan';
    }

    // Helper: Cek status aktif
    public function isAktif()
    {
        return $this->status === 'aktif';
    }

    // Helper: Update status otomatis berdasarkan Kepala Toko
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
     * ✅ TAMBAHAN: Extract src dari iframe HTML
     * Mengubah: <iframe src="URL" ...></iframe>
     * Menjadi: URL saja
     */
    public function extractIframeSrc()
    {
        if (!$this->googlemap_iframe) {
            return '';
        }

        // Pattern untuk mencari src="..." atau src='...'
        if (preg_match('/src=["\']([^"\']+)["\']/', $this->googlemap_iframe, $matches)) {
            return $matches[1];
        }

        // Jika sudah berupa URL langsung (tidak ada tag iframe)
        if (filter_var($this->googlemap_iframe, FILTER_VALIDATE_URL)) {
            return $this->googlemap_iframe;
        }

        return '';
    }

    /**
     * ✅ TAMBAHAN: Get clean iframe embed URL
     * Untuk digunakan langsung di blade
     */
    public function getCleanMapEmbedAttribute()
    {
        return $this->extractIframeSrc();
    }

    // Relasi ke Product (melalui stocks)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_stocks', 'toko_id', 'product_id')
            ->withPivot('stock')
            ->where('stock', '>', 0);
    }
}