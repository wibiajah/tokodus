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
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function kepalaToko()
    {
        return $this->hasOne(User::class)->where('role', 'kepala_toko');
    }

    public function staffAdmin()
    {
        return $this->hasMany(User::class)->where('role', 'staff_admin');
    }
}