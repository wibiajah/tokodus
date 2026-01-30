<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingSetting;

class ShippingSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shippingSettings = [
            [
                'name' => 'Reguler',
                'slug' => 'reguler',
                'price_per_km' => 3000, // Rp 3.000/km
                'min_charge' => 10000, // Minimal Rp 10.000
                'max_distance' => 50, // Maksimal 50 km
                'is_active' => true,
            ],
            [
                'name' => 'Instant',
                'slug' => 'instant',
                'price_per_km' => 5000, // Rp 5.000/km
                'min_charge' => 15000, // Minimal Rp 15.000
                'max_distance' => 15, // Maksimal 15 km
                'is_active' => true,
            ],
        ];

        foreach ($shippingSettings as $setting) {
            ShippingSetting::updateOrCreate(
                ['slug' => $setting['slug']], // Cek berdasarkan slug
                $setting // Data yang akan di-insert/update
            );
        }

        $this->command->info('✅ Shipping settings berhasil di-seed!');
    }
}