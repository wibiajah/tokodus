<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan daftar user
    public function index()
    {
        $users = User::with('toko')->latest()->get();
        return view('admin.user.index', compact('users'));
    }

    // Form tambah user
    public function create()
    {
        $tokos = Toko::all();
        
        // Tentukan role yang boleh dibuat berdasarkan user login
        if (auth()->user()->role === 'super_admin') {
            // Super Admin bisa buat semua role
            $availableRoles = [
                'super_admin' => 'Super Admin',
                'admin' => 'Admin',
                'kepala_toko' => 'Kepala Toko',
                'staff_admin' => 'Staff Admin',
            ];
        } else {
            // Admin tidak bisa buat Super Admin
            $availableRoles = [
                'admin' => 'Admin',
                'kepala_toko' => 'Kepala Toko',
                'staff_admin' => 'Staff Admin',
            ];
        }
        
        return view('admin.user.create', compact('tokos', 'availableRoles'));
    }

    // Simpan user baru
    public function store(Request $request)
    {
        // Validasi role berdasarkan user login
        $allowedRoles = auth()->user()->role === 'super_admin' 
            ? ['super_admin', 'admin', 'kepala_toko', 'staff_admin']
            : ['admin', 'kepala_toko', 'staff_admin'];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'toko_id' => ['nullable', 'exists:tokos,id'],
        ]);

        // Cek: jika admin coba buat super_admin
        if (auth()->user()->role !== 'super_admin' && $validated['role'] === 'super_admin') {
            return back()->withErrors(['role' => 'Anda tidak memiliki akses untuk membuat Super Admin.']);
        }

        // Validasi: kepala_toko dan staff_admin harus punya toko
        if (in_array($validated['role'], ['kepala_toko', 'staff_admin']) && !$validated['toko_id']) {
            return back()->withErrors(['toko_id' => 'Kepala Toko dan Staff Admin harus memiliki toko.']);
        }

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    // Tampilkan detail user
    public function show(User $user)
    {
        $user->load('toko');
        return view('admin.user.show', compact('user'));
    }

    // Form edit user
    public function edit(User $user)
    {
        // Cek: Admin tidak bisa edit Super Admin
        if (auth()->user()->role !== 'super_admin' && $user->role === 'super_admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit Super Admin.');
        }

        $tokos = Toko::all();
        
        // Tentukan role yang boleh dipilih
        if (auth()->user()->role === 'super_admin') {
            $availableRoles = [
                'super_admin' => 'Super Admin',
                'admin' => 'Admin',
                'kepala_toko' => 'Kepala Toko',
                'staff_admin' => 'Staff Admin',
            ];
        } else {
            $availableRoles = [
                'admin' => 'Admin',
                'kepala_toko' => 'Kepala Toko',
                'staff_admin' => 'Staff Admin',
            ];
        }
        
        return view('admin.user.edit', compact('user', 'tokos', 'availableRoles'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        // Cek: Admin tidak bisa edit Super Admin
        if (auth()->user()->role !== 'super_admin' && $user->role === 'super_admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit Super Admin.');
        }

        // Validasi role berdasarkan user login
        $allowedRoles = auth()->user()->role === 'super_admin' 
            ? ['super_admin', 'admin', 'kepala_toko', 'staff_admin']
            : ['admin', 'kepala_toko', 'staff_admin'];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'toko_id' => ['nullable', 'exists:tokos,id'],
        ]);

        // Validasi: kepala_toko dan staff_admin harus punya toko
        if (in_array($validated['role'], ['kepala_toko', 'staff_admin']) && !$validated['toko_id']) {
            return back()->withErrors(['toko_id' => 'Kepala Toko dan Staff Admin harus memiliki toko.']);
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:8', 'confirmed'],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    // Hapus user
    public function destroy(User $user)
    {
        // Cek: Admin tidak bisa hapus Super Admin
        if (auth()->user()->role !== 'super_admin' && $user->role === 'super_admin') {
            return redirect()->route('user.index')->with('error', 'Anda tidak memiliki akses untuk menghapus Super Admin.');
        }

        // Tidak bisa hapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }
}