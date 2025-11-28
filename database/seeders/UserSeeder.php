<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Toko;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Buat Toko 1
        $toko1 = Toko::create([
            'nama_toko' => 'Toko Cabang 1',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta',
            'telepon' => '021-1234567',
        ]);

        // Buat Toko 2
        $toko2 = Toko::create([
            'nama_toko' => 'Toko Cabang 2',
            'alamat' => 'Jl. Sudirman No. 2, Bandung',
            'telepon' => '022-7654321',
        ]);

        // 1. Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@toko.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'toko_id' => null,
        ]);

        // 2. Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'toko_id' => null,
        ]);

        // 3. Kepala Toko 1
        User::create([
            'name' => 'Kepala Toko 1',
            'email' => 'kepala1@toko.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_toko',
            'toko_id' => $toko1->id,
        ]);

        // 4. Staff Toko 1
        User::create([
            'name' => 'Staff Toko 1',
            'email' => 'staff1@toko.com',
            'password' => Hash::make('password'),
            'role' => 'staff_admin',
            'toko_id' => $toko1->id,
        ]);

        // 5. Kepala Toko 2
        User::create([
            'name' => 'Kepala Toko 2',
            'email' => 'kepala2@toko.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_toko',
            'toko_id' => $toko2->id,
        ]);

        // 6. Staff Toko 2
        User::create([
            'name' => 'Staff Toko 2',
            'email' => 'staff2@toko.com',
            'password' => Hash::make('password'),
            'role' => 'staff_admin',
            'toko_id' => $toko2->id,
        ]);
    }
}