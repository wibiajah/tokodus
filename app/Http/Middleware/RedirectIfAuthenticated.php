<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // Redirect ke dashboard sesuai role
                return match($user->role) {
                    'super_admin' => redirect()->route('superadmin.dashboard'),
                    'admin' => redirect()->route('admin.dashboard'),
                    'kepala_toko' => redirect()->route('kepala-toko.dashboard'),
                    'staff_admin' => redirect()->route('staff.dashboard'),
                    default => redirect()->route('home'),
                };
            }
        }

        return $next($request);
    }
}