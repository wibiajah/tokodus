<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Toko;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    const HEAD_OFFICE_ID = 999;

    public function __construct()
    {
        $this->middleware('role:super_admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('toko')->latest()->get();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tokos = Toko::where('id', '!=', self::HEAD_OFFICE_ID)->get();
        $headOffice = Toko::find(self::HEAD_OFFICE_ID);
        $availableRoles = User::ROLES;
        
        return view('admin.user.create', compact('tokos', 'headOffice', 'availableRoles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'no_telepon' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]*$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:super_admin,admin,kepala_toko,staff_admin'],
            'toko_id' => ['nullable', 'exists:tokos,id'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Handle foto profil upload
        if ($request->hasFile('foto_profil')) {
            $validated['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
        }

        // Set default toko_id ke Head Office jika kosong
        if (empty($validated['toko_id'])) {
            $validated['toko_id'] = self::HEAD_OFFICE_ID;
        }

        // Validasi Kepala Toko: Cek apakah toko sudah punya Kepala Toko
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

        // Hash password
        $validated['password'] = Hash::make($validated['password']);
        
        // Create user
        $user = User::create($validated);

        // Aktifkan toko jika user adalah Kepala Toko
        if ($validated['role'] === 'kepala_toko' && $validated['toko_id'] != self::HEAD_OFFICE_ID) {
            $toko = Toko::find($validated['toko_id']);
            if ($toko) {
                $toko->update(['status' => 'aktif']);
            }
        }

        // Kirim notifikasi ke Super Admin
        NotificationHelper::notifyRoles(
            ['super_admin'],
            NotificationHelper::userCreated($user, auth()->user())
        );

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('toko');
        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $tokos = Toko::where('id', '!=', self::HEAD_OFFICE_ID)->get();
        $headOffice = Toko::find(self::HEAD_OFFICE_ID);
        $availableRoles = User::ROLES;
        
        return view('admin.user.edit', compact('user', 'tokos', 'headOffice', 'availableRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'no_telepon' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]*$/'],
            'role' => ['required', 'in:super_admin,admin,kepala_toko,staff_admin'],
            'toko_id' => ['nullable', 'exists:tokos,id'],
            'confirm_replace' => ['nullable', 'boolean'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'remove_foto' => ['nullable', 'boolean'],
        ]);

        // Handle foto profil
        if ($request->boolean('remove_foto')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $validated['foto_profil'] = null;
        } elseif ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            // Upload foto baru
            $validated['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
        }

        // Set default toko_id ke Head Office jika kosong
        if (empty($validated['toko_id'])) {
            $validated['toko_id'] = self::HEAD_OFFICE_ID;
        }

        // Validasi Kepala Toko: Cek apakah toko sudah punya Kepala Toko lain
        if ($validated['role'] === 'kepala_toko' && $validated['toko_id'] != self::HEAD_OFFICE_ID) {
            $existingKepala = User::where('role', 'kepala_toko')
                ->where('toko_id', $validated['toko_id'])
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingKepala) {
                // Jika belum konfirmasi, tampilkan peringatan
                if (!$request->has('confirm_replace')) {
                    return back()->withInput()->with('confirm_replace', [
                        'message' => "Toko ini sudah memiliki Kepala Toko: <strong>{$existingKepala->name}</strong>. Jika Anda melanjutkan, {$existingKepala->name} akan dipindahkan ke Head Office.",
                        'existing_kepala_name' => $existingKepala->name,
                    ]);
                }

                // Jika sudah konfirmasi, pindahkan Kepala Toko lama ke Head Office
                $existingKepala->update(['toko_id' => self::HEAD_OFFICE_ID]);
                
                $toko = Toko::find($validated['toko_id']);
                NotificationHelper::notifyRoles(
                    ['super_admin'],
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

        // Simpan data lama untuk pengecekan
        $oldRole = $user->role;
        $oldTokoId = $user->toko_id;

        // Update user
        $user->update($validated);

        // Aktifkan toko jika user adalah Kepala Toko
        if ($validated['role'] === 'kepala_toko' && $validated['toko_id'] != self::HEAD_OFFICE_ID) {
            $toko = Toko::find($validated['toko_id']);
            if ($toko) {
                $toko->update(['status' => 'aktif']);
            }
        }

        // Nonaktifkan toko jika Kepala Toko dirubah rolenya atau dipindahkan
        if ($oldRole === 'kepala_toko' && $validated['role'] !== 'kepala_toko' && $oldTokoId && $oldTokoId != self::HEAD_OFFICE_ID) {
            $toko = Toko::find($oldTokoId);
            if ($toko && !$toko->kepalaToko()->exists()) {
                $toko->update(['status' => 'tidak_aktif']);
            }
        }

        // Kirim notifikasi ke Super Admin
        NotificationHelper::notifyRoles(
            ['super_admin'],
            NotificationHelper::userUpdated($user, auth()->user())
        );

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        // Simpan data untuk pengecekan
        $tokoId = $user->toko_id;
        $role = $user->role;
        $userName = $user->name;

        // Hapus foto profil jika ada
        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        // Delete user
        $user->delete();

        // Nonaktifkan toko jika Kepala Toko dihapus
        if ($role === 'kepala_toko' && $tokoId && $tokoId != self::HEAD_OFFICE_ID) {
            $toko = Toko::find($tokoId);
            if ($toko && !$toko->kepalaToko()->exists()) {
                $toko->update(['status' => 'tidak_aktif']);
            }
        }

        // Kirim notifikasi ke Super Admin
        NotificationHelper::notifyRoles(
            ['super_admin'],
            NotificationHelper::userDeleted($userName, auth()->user())
        );

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }
}