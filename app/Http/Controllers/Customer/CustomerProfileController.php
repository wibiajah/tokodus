<?php
// app/Http/Controllers/Customer/CustomerProfileController.php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerProfileController extends Controller
{
    /**
     * Tampilkan halaman profil customer
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.profile', compact('customer'));
    }

    /**
     * Update profil customer (alamat, foto, dll)
     */
    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:customers,username,' . $customer->id],
            'email' => ['required', 'email', 'unique:customers,email,' . $customer->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        try {
            if ($request->hasFile('foto_profil')) {
                if ($customer->foto_profil) {
                    Storage::delete($customer->foto_profil);
                }
                $path = $request->file('foto_profil')->store('customer-profiles', 'public');
                $validated['foto_profil'] = $path;
            }

            $customer->update($validated);

            return back()->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Customer profile update failed', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id
            ]);

            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat memperbarui profil.',
            ]);
        }
    }

    /**
     * Set password pertama kali (untuk customer Google)
     */
    public function setPassword(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        // Cek apakah sudah punya password
        if ($customer->password) {
            return back()->withErrors(['error' => 'Anda sudah memiliki password. Gunakan fitur Ganti Password.']);
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $customer->update([
            'password' => bcrypt($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil dibuat! Sekarang Anda bisa login dengan email & password.');
    }

    /**
     * Ganti password (untuk customer yang sudah punya password)
     */
    public function changePassword(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        // Cek apakah punya password
        if (!$customer->password) {
            return back()->withErrors(['error' => 'Anda belum memiliki password. Gunakan fitur Set Password terlebih dahulu.']);
        }

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Cek password lama
        if (!\Hash::check($validated['current_password'], $customer->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $customer->update([
            'password' => bcrypt($validated['new_password']),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
