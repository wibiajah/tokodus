<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function authenticate(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Rate limiting: Max 5 attempts per minute
    $throttleKey = 'login:' . strtolower($request->input('email'));
    
    if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
        $seconds = RateLimiter::availableIn($throttleKey);
        
        // ✅ Kirim seconds ke session untuk countdown
        return back()->withErrors([
            'email' => 'Terlalu banyak percobaan login.',
        ])->onlyInput('email')->with('throttle_seconds', $seconds);
    }

    // Regenerate session sebelum login (security)
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        
        $user = Auth::user();

        // Clear rate limiter setelah login sukses
        RateLimiter::clear($throttleKey);

        // Redirect berdasarkan role
        return match($user->role) {
            'super_admin' => redirect()->intended(route('superadmin.dashboard')),
            'admin' => redirect()->intended(route('admin.dashboard')),
            'kepala_toko' => redirect()->intended(route('kepala-toko.dashboard')),
            'staff_admin' => redirect()->intended(route('staff.dashboard')),
            default => redirect()->intended(route('home')),
        };
    }

    // Increment rate limiter untuk failed attempts
    RateLimiter::hit($throttleKey, 60);

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
}

    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstname' => ['nullable', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, dash, dan underscore.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $name = trim(($validated['firstname'] ?? '') . ' ' . ($validated['lastname'] ?? ''));
        if (empty($name)) {
            $name = $validated['username'];
        }

        \DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $name,
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'staff_admin',
                'toko_id' => 999, // Head Office default
            ]);

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Auth::login($user);
            $request->session()->regenerate();

            \DB::commit();

            return redirect()->route('staff.dashboard')->with('success', 'Registrasi berhasil!');
        } catch (\Exception $e) {
            \DB::rollBack();
            
            \Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);
            
            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.',
            ])->withInput();
        }
    }

   public function logout(Request $request)
{
    Auth::logout();
    
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home')->with('success', 'Berhasil logout.');
}
}