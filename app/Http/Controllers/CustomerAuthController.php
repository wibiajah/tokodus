<?php
// app/Http/Controllers/CustomerAuthController.php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class CustomerAuthController extends Controller
{
    /**
     * Register Customer Baru (Simplified - hanya data wajib)
     */
    public function register(Request $request)
{
    $validated = $request->validate([
        'firstname' => ['required', 'string', 'max:255'],
        'lastname' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255', 'unique:customers', 'alpha_dash'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ], [
        'firstname.required' => 'Nama depan wajib diisi.',
        'lastname.required' => 'Nama belakang wajib diisi.',
        'username.required' => 'Username wajib diisi.',
        'username.unique' => 'Username sudah digunakan.',
        'username.alpha_dash' => 'Username hanya boleh huruf, angka, dash, dan underscore.',
        'email.required' => 'Email wajib diisi.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    try {
        \DB::beginTransaction();

        $customer = Customer::create([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => null,  // ✅ BELUM VERIFIED
        ]);

        // Kirim email verification
        $customer->sendEmailVerificationNotification();

        \DB::commit();

        // ❌ JANGAN AUTO-LOGIN!
        return redirect()->route('home')
            ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi.');

    } catch (\Exception $e) {
        \DB::rollBack();
        
        \Log::error('Customer registration failed', [
            'error' => $e->getMessage(),
            'email' => $request->email
        ]);
        
        return back()->withErrors([
            'email' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.',
        ])->withInput();
    }
}

    /**
     * Login Customer (menggunakan email & password)
     */
   public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Rate limiting
    $throttleKey = 'customer_login:' . strtolower($request->input('email'));
    
    if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
        $seconds = RateLimiter::availableIn($throttleKey);
        
        return back()->withErrors([
            'email' => 'Terlalu banyak percobaan login.',
        ])->onlyInput('email')->with('throttle_seconds', $seconds);
    }

    // Attempt login
    if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
        $customer = Auth::guard('customer')->user();

        // ✅ CEK EMAIL VERIFICATION
        if (!$customer->hasVerifiedEmail()) {
            Auth::guard('customer')->logout();
            $request->session()->invalidate();

            return back()->withErrors([
                'email' => 'Email Anda belum diverifikasi. Silakan cek inbox email Anda.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        RateLimiter::clear($throttleKey);

        return redirect()->route('home')
            ->with('success', 'Selamat datang kembali, ' . $customer->firstname . '!');
    }

    RateLimiter::hit($throttleKey, 60);

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
}

    /**
     * Logout Customer
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Berhasil logout.');
    }
}