<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLES = [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'kepala_toko' => 'Kepala Toko',
        'staff_admin' => 'Staff Admin',
    ];

    protected $fillable = [
        'name',
        'email',
        'no_telepon', // TAMBAHKAN INI
        'password',
        'role',
        'toko_id',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi: User milik satu Toko
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    // Helper: Get foto profil URL
    public function getFotoProfilUrlAttribute()
    {
        if ($this->foto_profil) {
            return Storage::url($this->foto_profil);
        }
        
        // Default avatar jika tidak ada foto
        return asset('img/default-avatar.png');
    }

    // Helper: Format nomor telepon
    public function getFormattedNoTeleponAttribute()
    {
        if (!$this->no_telepon) {
            return '-';
        }
        
        // Format: 0812-3456-7890
        return preg_replace('/(\d{4})(\d{4})(\d+)/', '$1-$2-$3', $this->no_telepon);
    }

    // Helper: Cek role
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKepalaToko()
    {
        return $this->role === 'kepala_toko';
    }

    public function isStaffAdmin()
    {
        return $this->role === 'staff_admin';
    }

    // Scope: Filter by role
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}