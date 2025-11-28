<?php

namespace App\Http\Controllers\KepalaToko;

use App\Http\Controllers\Controller;

class KepalaTokController extends Controller
{
    public function index()
    {
        $toko = auth()->user()->toko;
        return view('kepala-toko.dashboard', compact('toko'));
    }
}