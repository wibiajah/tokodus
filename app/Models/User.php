<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'password',
        'role',
        'toko_id',
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