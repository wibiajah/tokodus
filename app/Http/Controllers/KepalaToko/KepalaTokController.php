<?php
// Update Controller: KepalaTokController.php

namespace App\Http\Controllers\KepalaToko;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\Order;

class KepalaTokController extends Controller
{
    public function index()
    {
        $toko = auth()->user()->toko;
        
        // Ambil semua toko dengan statistik pegawai
        $allTokos = Toko::withCount([
            'users',
            'staffAdmin',
        ])->with(['kepalaToko', 'staffAdmin'])
        ->orderBy('nama_toko', 'asc')
        ->get();
        
        // 🆕 Stats orderan toko sendiri
        $orderStats = null;
        if ($toko) {
            $orderStats = [
                'total' => Order::forToko($toko->id)->count(),
                'pending' => Order::forToko($toko->id)->pending()->count(),
                'processing' => Order::forToko($toko->id)->processing()->count(),
                'shipped' => Order::forToko($toko->id)->shipped()->count(),
                'completed' => Order::forToko($toko->id)->completed()->count(),
            ];
        }
        
        return view('kepala-toko.dashboard', compact('toko', 'allTokos', 'orderStats'));
    }
}