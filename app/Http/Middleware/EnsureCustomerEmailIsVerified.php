<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCustomerEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $customer = Auth::guard('customer')->user();

        if ($customer && !$customer->hasVerifiedEmail()) {
            return redirect()->route('home')
                ->with('warning', 'Silakan verifikasi email Anda terlebih dahulu. Cek inbox email Anda!');
        }

        return $next($request);
    }
}