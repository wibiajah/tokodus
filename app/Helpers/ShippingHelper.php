<?php

namespace App\Helpers;

use App\Models\ShippingSetting;
use App\Models\Customer;
use App\Models\Toko;

class ShippingHelper
{
    /**
     * Hitung jarak antara 2 koordinat menggunakan Haversine Formula
     * 
     * @param float $lat1 Latitude titik 1
     * @param float $lon1 Longitude titik 1
     * @param float $lat2 Latitude titik 2
     * @param float $lon2 Longitude titik 2
     * @return float Jarak dalam kilometer
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Radius bumi dalam kilometer
        $earthRadius = 6371;

        // Convert degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Haversine formula
        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        // Bulatkan 2 desimal
        return round($distance, 2);
    }

    /**
     * Hitung ongkir berdasarkan jarak dan tipe shipping
     * 
     * @param float $distance Jarak dalam KM
     * @param string $shippingType Slug shipping type (reguler/instant)
     * @return array ['cost' => float, 'setting' => ShippingSetting, 'error' => string|null]
     */
    public static function calculateShippingCost($distance, $shippingType)
    {
        $setting = ShippingSetting::where('slug', $shippingType)
            ->where('is_active', true)
            ->first();

        if (!$setting) {
            return [
                'cost' => 0,
                'setting' => null,
                'error' => 'Metode pengiriman tidak tersedia'
            ];
        }

        // Cek apakah jarak melebihi maksimal
        if ($distance > $setting->max_distance) {
            return [
                'cost' => 0,
                'setting' => $setting,
                'error' => "Jarak {$distance} km melebihi maksimal {$setting->max_distance} km untuk {$setting->name}"
            ];
        }

        // Hitung ongkir
        $calculatedCost = $distance * $setting->price_per_km;

        // Pastikan tidak kurang dari minimal charge
        $finalCost = max($calculatedCost, $setting->min_charge);

        return [
            'cost' => round($finalCost, 0),
            'setting' => $setting,
            'error' => null
        ];
    }

    /**
     * Get semua opsi shipping yang tersedia untuk customer tertentu
     * 
     * @param int $customerId
     * @param int $tokoId
     * @return array
     */
    public static function getShippingOptions($customerId, $tokoId)
    {
        $customer = Customer::find($customerId);
        $toko = Toko::find($tokoId);

        // Validasi koordinat
        if (!self::hasCoordinates($customer, $toko)) {
            return [
                'success' => false,
                'message' => 'Koordinat customer atau toko belum diset',
                'options' => []
            ];
        }

        // Hitung jarak
        $distance = self::calculateDistance(
            $customer->latitude,
            $customer->longitude,
            $toko->latitude,
            $toko->longitude
        );

        // Get semua shipping settings yang aktif
        $shippingSettings = ShippingSetting::where('is_active', true)->get();

        $options = [];

        foreach ($shippingSettings as $setting) {
            $result = self::calculateShippingCost($distance, $setting->slug);

            $options[] = [
                'id' => $setting->id,
                'name' => $setting->name,
                'slug' => $setting->slug,
                'distance' => $distance,
                'price_per_km' => $setting->price_per_km,
                'min_charge' => $setting->min_charge,
                'max_distance' => $setting->max_distance,
                'cost' => $result['cost'],
                'available' => $result['error'] === null,
                'error' => $result['error']
            ];
        }

        return [
            'success' => true,
            'distance' => $distance,
            'options' => $options,
            'customer_coords' => [
                'lat' => $customer->latitude,
                'lng' => $customer->longitude
            ],
            'toko_coords' => [
                'lat' => $toko->latitude,
                'lng' => $toko->longitude
            ]
        ];
    }

    /**
     * Validasi apakah customer & toko sudah memiliki koordinat
     * 
     * @param Customer|null $customer
     * @param Toko|null $toko
     * @return bool
     */
    public static function hasCoordinates($customer, $toko)
    {
        if (!$customer || !$toko) {
            return false;
        }

        return $customer->latitude !== null 
            && $customer->longitude !== null
            && $toko->latitude !== null
            && $toko->longitude !== null;
    }

    /**
     * Format ongkir untuk display
     * 
     * @param float $cost
     * @return string
     */
    public static function formatCost($cost)
    {
        return 'Rp ' . number_format($cost, 0, ',', '.');
    }

    /**
     * Get estimasi waktu pengiriman berdasarkan shipping type
     * 
     * @param string $shippingType
     * @return string
     */
    public static function getEstimatedTime($shippingType)
    {
        $estimates = [
            'reguler' => '2-3 hari',
            'instant' => '1-2 jam',
        ];

        return $estimates[$shippingType] ?? 'Tidak diketahui';
    }

    /**
     * Validasi apakah shipping bisa digunakan
     * 
     * @param float $distance
     * @param string $shippingType
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validateShipping($distance, $shippingType)
    {
        $result = self::calculateShippingCost($distance, $shippingType);

        return [
            'valid' => $result['error'] === null,
            'message' => $result['error']
        ];
    }
}