<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    public function index()
    {
        $toko = auth()->user()->toko;
        return view('staff.dashboard', compact('toko'));
    }
}