<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Toko;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function index()
    {
        // 📊 SHIPPING STATISTICS
        $shippingStats = $this->getShippingStatistics();
        
        return view('superadmin.dashboard', compact('shippingStats'));
    }
    
    private function getShippingStatistics()
    {
        // Total Revenue dari Ongkir
        $totalShippingRevenue = Order::where('payment_status', 'paid')
            ->where('delivery_method', 'delivery')
            ->sum('shipping_cost');
        
        // Total Orders Reguler vs Instant
        $ordersByShippingType = Order::where('delivery_method', 'delivery')
            ->select('shipping_type', DB::raw('COUNT(*) as count'))
            ->groupBy('shipping_type')
            ->get()
            ->pluck('count', 'shipping_type')
            ->toArray();
        
        $totalReguler = $ordersByShippingType['reguler'] ?? 0;
        $totalInstant = $ordersByShippingType['instant'] ?? 0;
        
        // Rata-rata Jarak Pengiriman
        $avgDistance = Order::where('delivery_method', 'delivery')
            ->whereNotNull('shipping_distance')
            ->avg('shipping_distance');
        
        // Top 5 Toko dengan Revenue Ongkir Tertinggi
        $topTokosByShipping = Order::where('payment_status', 'paid')
            ->where('delivery_method', 'delivery')
            ->select('toko_id', DB::raw('SUM(shipping_cost) as total_shipping'))
            ->groupBy('toko_id')
            ->orderByDesc('total_shipping')
            ->limit(5)
            ->with('toko:id,nama_toko')
            ->get();
        
        // Trend Ongkir per Bulan (6 bulan terakhir)
        $shippingTrend = Order::where('payment_status', 'paid')
            ->where('delivery_method', 'delivery')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(shipping_cost) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return [
            'total_revenue' => $totalShippingRevenue,
            'total_reguler' => $totalReguler,
            'total_instant' => $totalInstant,
            'avg_distance' => round($avgDistance, 2),
            'top_tokos' => $topTokosByShipping,
            'trend' => $shippingTrend
        ];
    }
} 