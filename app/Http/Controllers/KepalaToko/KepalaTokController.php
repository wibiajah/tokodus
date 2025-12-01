<?php

namespace App\Http\Controllers\KepalaToko;

use App\Http\Controllers\Controller;
use App\Models\Toko;

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
        
        return view('kepala-toko.dashboard', compact('toko', 'allTokos'));
    }
}