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
        'username',
        'email',
        'no_telepon',
        'password',
        'role',
        'toko_id',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function getFotoProfilUrlAttribute()
    {
        if ($this->foto_profil) {
            return Storage::url($this->foto_profil);
        }
        
        return asset('img/default-avatar.png');
    }

    public function getFormattedNoTeleponAttribute()
    {
        if (!$this->no_telepon) {
            return '-';
        }
        
        return preg_replace('/(\d{4})(\d{4})(\d+)/', '$1-$2-$3', $this->no_telepon);
    }

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

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}