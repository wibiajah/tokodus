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
}