<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function show()
    {
        $user = auth()->user();
        $user->load('toko');
        
        return view('profile.show', compact('user'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = auth()->user();
        $user->load('toko');
        
        return view('profile.edit', compact('user'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'no_telepon' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]*$/'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'remove_foto' => ['nullable', 'boolean'],
        ]);

        // Handle foto profil
        if ($request->boolean('remove_foto')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $validated['foto_profil'] = null;
        } elseif ($request->hasFile('foto_profil')) {
            // Hapus foto lama
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            // Upload foto baru
            $validated['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Show edit password form
     */
    public function editPassword()
    {
        return view('profile.edit-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai!'
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password berhasil diubah!');
    }
}