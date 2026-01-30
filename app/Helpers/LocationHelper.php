<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class LocationHelper
{
    /**
     * Get location details from postal code using API
     * 
     * @param string $postalCode
     * @return array|null
     */
    public static function getLocationFromPostalCode($postalCode)
    {
        if (empty($postalCode)) {
            return null;
        }
        
        $cleanCode = preg_replace('/\D/', '', $postalCode);
        $cacheKey = "postal_code_{$cleanCode}";
        
        return Cache::remember($cacheKey, 86400, function() use ($cleanCode) {
            try {
                $response = Http::timeout(5)
                    ->get("https://kodepos.vercel.app/search/", [
                        'q' => $cleanCode
                    ]);
                
                if ($response->successful() && $response->json('data')) {
                    $data = $response->json('data');
                    
                    if (!empty($data) && isset($data[0])) {
                        $location = $data[0];
                        
                        // 🔥 FIX: Use correct API keys
                        return [
                            'postal_code' => $location['postalcode'] ?? $location['code'] ?? $cleanCode,
                            'kelurahan' => $location['village'] ?? $location['urban'] ?? null,
                            'kecamatan' => $location['district'] ?? $location['subdistrict'] ?? null,
                            'kota' => $location['regency'] ?? $location['city'] ?? null,
                            'provinsi' => $location['province'] ?? null,
                            'full_address' => self::formatFullAddress($location),
                            'short_address' => self::formatShortAddress($location),
                        ];
                    }
                }
                
                return null;
                
            } catch (\Exception $e) {
                Log::error('Postal Code API Error', [
                    'postal_code' => $cleanCode,
                    'error' => $e->getMessage()
                ]);
                
                return null;
            }
        });
    }
    
    /**
     * Format full address - Support both API formats
     */
    private static function formatFullAddress($location)
    {
        $parts = array_filter([
            $location['village'] ?? $location['urban'] ?? null,
            $location['district'] ?? $location['subdistrict'] ?? null,
            $location['regency'] ?? $location['city'] ?? null,
            $location['province'] ?? null,
        ]);
        
        return implode(', ', $parts);
    }
    
    /**
     * Format short address (Kecamatan, Kota)
     */
    private static function formatShortAddress($location)
    {
        $parts = array_filter([
            $location['district'] ?? $location['subdistrict'] ?? null,
            $location['regency'] ?? $location['city'] ?? null,
        ]);
        
        return implode(', ', $parts);
    }
    
    /**
     * Calculate accurate distance based on postal code prefix
     */
    public static function calculatePostalCodeDistance($postalCode1, $postalCode2)
    {
        $code1 = preg_replace('/\D/', '', $postalCode1);
        $code2 = preg_replace('/\D/', '', $postalCode2);
        
        if (strlen($code1) < 5 || strlen($code2) < 5) {
            return 999999;
        }
        
        $area1 = substr($code1, 0, 2);
        $district1 = substr($code1, 2, 1);
        $subdistrict1 = substr($code1, 3, 2);
        
        $area2 = substr($code2, 0, 2);
        $district2 = substr($code2, 2, 1);
        $subdistrict2 = substr($code2, 3, 2);
        
        $areaWeight = 1000;
        $districtWeight = 100;
        $subdistrictWeight = 1;
        
        $distance = 0;
        
        if ($area1 !== $area2) {
            $distance += abs(intval($area1) - intval($area2)) * $areaWeight;
        } else {
            if ($district1 !== $district2) {
                $distance += abs(intval($district1) - intval($district2)) * $districtWeight;
            }
            $distance += abs(intval($subdistrict1) - intval($subdistrict2)) * $subdistrictWeight;
        }
        
        return $distance;
    }
    
    /**
     * Estimate distance category
     */
    public static function estimateDistanceCategory($distance)
    {
        if ($distance === 0) {
            return [
                'category' => 'same_area',
                'label' => 'Lokasi Sama',
                'estimate' => '< 1 km',
                'icon' => '📍'
            ];
        } elseif ($distance < 10) {
            return [
                'category' => 'very_near',
                'label' => 'Sangat Dekat',
                'estimate' => '< 1 km',
                'icon' => '🟢'
            ];
        } elseif ($distance < 50) {
            return [
                'category' => 'very_near',
                'label' => 'Sangat Dekat',
                'estimate' => '1-3 km',
                'icon' => '🟢'
            ];
        } elseif ($distance < 100) {
            return [
                'category' => 'near',
                'label' => 'Dekat',
                'estimate' => '3-5 km',
                'icon' => '🔵'
            ];
        } elseif ($distance < 500) {
            return [
                'category' => 'near',
                'label' => 'Dekat',
                'estimate' => '5-10 km',
                'icon' => '🔵'
            ];
        } elseif ($distance < 1000) {
            return [
                'category' => 'medium',
                'label' => 'Sedang',
                'estimate' => '10-20 km',
                'icon' => '🟡'
            ];
        } elseif ($distance < 5000) {
            return [
                'category' => 'far',
                'label' => 'Jauh',
                'estimate' => '20-50 km',
                'icon' => '🟠'
            ];
        } else {
            return [
                'category' => 'very_far',
                'label' => 'Sangat Jauh',
                'estimate' => '> 50 km',
                'icon' => '🔴'
            ];
        }
    }
    
    /**
     * Sort stores by proximity - ALWAYS RETURN ARRAY
     */
    public static function sortStoresByProximity($stores, $customerPostalCode)
    {
        if (empty($customerPostalCode)) {
            return $stores instanceof Collection ? $stores->values()->toArray() : $stores;
        }
        
        $storesArray = $stores instanceof Collection ? $stores->toArray() : $stores;
        
        usort($storesArray, function($a, $b) use ($customerPostalCode) {
            $postalA = is_array($a) ? ($a['postal_code'] ?? '') : ($a->postal_code ?? '');
            $postalB = is_array($b) ? ($b['postal_code'] ?? '') : ($b->postal_code ?? '');
            
            if (empty($postalA)) return 1;
            if (empty($postalB)) return -1;
            
            $distanceA = self::calculatePostalCodeDistance($customerPostalCode, $postalA);
            $distanceB = self::calculatePostalCodeDistance($customerPostalCode, $postalB);
            
            return $distanceA <=> $distanceB;
        });
        
        return $storesArray;
    }
}