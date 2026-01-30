<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 🔒 SECURITY 1: Rate limiting berdasarkan IP + Email
        $throttleKey = 'login:' . strtolower($request->input('email')) . ':' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            // Log suspicious activity
            Log::warning('Login rate limit exceeded', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan login. Silakan tunggu beberapa saat.',
            ])->onlyInput('email')->with('throttle_seconds', $seconds);
        }

        // 🔒 SECURITY 2: Cek apakah ada user/customer dengan email ini
        $customer = Customer::where('email', $credentials['email'])->first();
        $user = User::where('email', $credentials['email'])->first();
        
        // 🔒 SECURITY 3: Jangan beri tahu attacker bahwa email tidak ditemukan
        if (!$customer && !$user) {
            RateLimiter::hit($throttleKey, 60);
            
            // Sleep random time untuk prevent timing attack
            usleep(random_int(100000, 300000)); // 0.1-0.3 detik
            
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        $loginSuccess = false;
        $authenticatedUser = null;
        $guardType = null;

        // 🔒 SECURITY 4: Coba login customer dulu
        if ($customer && Hash::check($credentials['password'], $customer->password)) {
            Auth::guard('customer')->login($customer, $request->boolean('remember'));
            $loginSuccess = true;
            $authenticatedUser = $customer;
            $guardType = 'customer';
        }
        
        // 🔒 SECURITY 5: Kalau bukan customer, coba admin
        if (!$loginSuccess && $user && Hash::check($credentials['password'], $user->password)) {
            Auth::guard('web')->login($user, $request->boolean('remember'));
            $loginSuccess = true;
            $authenticatedUser = $user;
            $guardType = 'admin';
        }

        // 🔒 SECURITY 6: Handle success/failed login
        if ($loginSuccess) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey);
            
            // Log successful login
            Log::info('Login successful', [
                'guard' => $guardType,
                'user_id' => $authenticatedUser->id,
                'ip' => $request->ip(),
            ]);
            
            $welcomeMessage = $guardType === 'customer' 
                ? 'Selamat datang kembali, ' . $authenticatedUser->firstname . '!'
                : 'Berhasil login.';
            
            return redirect()->route('home')->with('success', $welcomeMessage);
        }

        // 🔒 SECURITY 7: Failed login
        RateLimiter::hit($throttleKey, 60);
        
        // Log failed login attempt
        Log::warning('Login failed', [
            'email' => $credentials['email'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        // Sleep random time untuk prevent timing attack
        usleep(random_int(100000, 300000)); // 0.1-0.3 detik

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        // 🔒 SECURITY: Rate limiting untuk registrasi
        $throttleKey = 'register:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan registrasi. Silakan tunggu beberapa saat.',
            ])->withInput();
        }

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
                'toko_id' => 999,
            ]);

            Auth::login($user);
            $request->session()->regenerate();
            
            RateLimiter::clear($throttleKey);
            
            \DB::commit();

            Log::info('User registered', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);

            return redirect()->route('home')->with('success', 'Registrasi berhasil!');
        } catch (\Exception $e) {
            \DB::rollBack();
            
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);
            
            RateLimiter::hit($throttleKey, 300);
            
            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.',
            ])->withInput();
        }
    }

    public function logout(Request $request)
    {
        // Log logout
        if (Auth::check()) {
            Log::info('User logged out', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
            ]);
        }

        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Berhasil logout.');
    }
}