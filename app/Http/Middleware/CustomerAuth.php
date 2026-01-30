<?php
// app/Http/Middleware/CustomerAuth.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('home')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}