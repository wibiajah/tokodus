<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Toko;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    const HEAD_OFFICE_ID = 999; // ID Head Office

    // Tampilkan daftar user
    public function index()
    {
        $users = User::with('toko')->latest()->get();
        return view('admin.user.index', compact('users'));
    }

    // Form tambah user
    public function create()
    {
        $tokos = Toko::where('id', '!=', self::HEAD_OFFICE_ID)->get();
        $headOffice = Toko::find(self::HEAD_OFFICE_ID);
        
        $availableRoles = auth()->user()->role === 'super_admin' 
            ? User::ROLES
            : collect(User::ROLES)->except('super_admin')->toArray();
        
        return view('admin.user.create', compact('tokos', 'headOffice', 'availableRoles'));
    }

    // Simpan user baru
    public function store(Request $request)
    {
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

        // 🔥 LOGIKA BARU: Jika tidak memilih toko, otomatis Head Office
        if (empty($validated['toko_id'])) {
            $validated['toko_id'] = self::HEAD_OFFICE_ID;
        }

        // 🔥 CEK: Jika role kepala_toko dan memilih toko cabang (bukan Head Office)
        if ($validated['role'] === 'kepala_toko' && $validated['toko_id'] != self::HEAD_OFFICE_ID) {
            $existingKepala = User::where('role', 'kepala_toko')
                ->where('toko_id', $validated['toko_id'])
                ->first();

            if ($existingKepala) {
                return back()->withErrors([
                    'toko_id' => "Toko ini sudah memiliki Kepala Toko ({$existingKepala->name}). Silakan pilih toko lain atau kosongkan untuk Head Office."
                ])->withInput();
            }
        }

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        // Sync status toko jika user adalah Kepala Toko DAN bukan Head Office
        if ($validated['role'] === 'kepala_toko' && $validated['toko_id'] != self::HEAD_OFFICE_ID) {
            $toko = Toko::find($validated['toko_id']);
            if ($toko) {
                $toko->update(['status' => 'aktif']);
            }
        }

        NotificationHelper::notifyRoles(
            ['super_admin', 'admin'],
            NotificationHelper::userCreated($user, auth()->user())
        );

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
        if (auth()->user()->role !== 'super_admin' && $user->role === 'super_admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit Super Admin.');
        }

        $tokos = Toko::where('id', '!=', self::HEAD_OFFICE_ID)->get();
        $headOffice = Toko::find(self::HEAD_OFFICE_ID);
        
        $availableRoles = auth()->user()->role === 'super_admin' 
            ? User::ROLES
            : collect(User::ROLES)->except('super_admin')->toArray();
        
        return view('admin.user.edit', compact('user', 'tokos', 'headOffice', 'availableRoles'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'super_admin' && $user->role === 'super_admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit Super Admin.');
        }

        $allowedRoles = auth()->user()->role === 'super_admin' 
            ? ['super_admin', 'admin', 'kepala_toko', 'staff_admin']
            : ['admin', 'kepala_toko', 'staff_admin'];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'toko_id' => ['nullable', 'exists:tokos,id'],
            'confirm_replace' => ['nullable', 'boolean'],
        ]);

        // 🔥 LOGIKA BARU: Jika tidak memilih toko, otomatis Head Office
        if (empty($validated['toko_id'])) {
            $validated['toko_id'] = self::HEAD_OFFICE_ID;
        }

        // 🔥 LOGIKA KEPALA TOKO (hanya untuk toko cabang, bukan Head Office)
        if ($validated['role'] === 'kepala_toko' && $validated['toko_id'] != self::HEAD_OFFICE_ID) {
            $existingKepala = User::where('role', 'kepala_toko')
                ->where('toko_id', $validated['toko_id'])
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingKepala) {
                // Jika belum konfirmasi, tampilkan pesan konfirmasi
                if (!$request->has('confirm_replace')) {
                    return back()->withInput()->with('confirm_replace', [
                        'message' => "Toko ini sudah memiliki Kepala Toko: <strong>{$existingKepala->name}</strong>. Jika Anda melanjutkan, {$existingKepala->name} akan dipindahkan ke Head Office.",
                        'existing_kepala_name' => $existingKepala->name,
                    ]);
                }

                // Jika sudah konfirmasi, pindahkan kepala toko lama ke Head Office
                $existingKepala->update(['toko_id' => self::HEAD_OFFICE_ID]);
                
                // 🔥 Kirim notifikasi dengan format yang benar
                $toko = Toko::find($validated['toko_id']);
                NotificationHelper::notifyRoles(
                    ['super_admin', 'admin'],
                    NotificationHelper::kepalaTokoReplaced($existingKepala, $user, $toko, auth()->user())
                );
            }
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:8', 'confirmed'],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $oldRole = $user->role;
        $oldTokoId = $user->toko_id;

        $user->update($validated);

        // Handle Kepala Toko status (hanya untuk toko cabang)
        if ($validated['role'] === 'kepala_toko' && $validated['toko_id'] != self::HEAD_OFFICE_ID) {
            $toko = Toko::find($validated['toko_id']);
            if ($toko) {
                $toko->update(['status' => 'aktif']);
            }
        }

        // Handle role change dari Kepala Toko (hanya untuk toko cabang)
        if ($oldRole === 'kepala_toko' && $validated['role'] !== 'kepala_toko' && $oldTokoId && $oldTokoId != self::HEAD_OFFICE_ID) {
            $toko = Toko::find($oldTokoId);
            if ($toko && !$toko->kepalaToko()->exists()) {
                $toko->update(['status' => 'tidak_aktif']);
            }
        }

        NotificationHelper::notifyRoles(
            ['super_admin', 'admin'],
            NotificationHelper::userUpdated($user, auth()->user())
        );

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    // Hapus user
    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'super_admin' && $user->role === 'super_admin') {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus Super Admin.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $tokoId = $user->toko_id;
        $role = $user->role;
        $userName = $user->name;

        $user->delete();

        // Handle Kepala Toko status saat dihapus (hanya untuk toko cabang)
        if ($role === 'kepala_toko' && $tokoId && $tokoId != self::HEAD_OFFICE_ID) {
            $toko = Toko::find($tokoId);
            if ($toko && !$toko->kepalaToko()->exists()) {
                $toko->update(['status' => 'tidak_aktif']);
            }
        }

        NotificationHelper::notifyRoles(
            ['super_admin', 'admin'],
            NotificationHelper::userDeleted($userName, auth()->user())
        );

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }
}