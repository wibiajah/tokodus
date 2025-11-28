<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLES = [
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

    // Relasi ke toko
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    // Helper methods
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
}