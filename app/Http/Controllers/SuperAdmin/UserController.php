<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Toko;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    const HEAD_OFFICE_ID = 999;

    public function __construct()
    {
        $this->middleware('role:super_admin');
    }

    public function index()
    {
        $users = User::with('toko')->latest()->get();
        return view('superadmin.user.index', compact('users'));
    }

    public function create()
    {
        $tokos = Toko::where('id', '!=', self::HEAD_OFFICE_ID)->get();
        $headOffice = Toko::find(self::HEAD_OFFICE_ID);
        $availableRoles = User::ROLES;
        
        return view('superadmin.user.create', compact('tokos', 'headOffice', 'availableRoles'));
    }

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

    DB::beginTransaction();
    try {
        if ($request->hasFile('foto_profil')) {
            $validated['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
        }

        if (empty($validated['toko_id'])) {
            $validated['toko_id'] = self::HEAD_OFFICE_ID;
        }

        // FIX: Gunakan lockForUpdate untuk prevent race condition
        if ($validated['role'] === 'kepala_toko' && $validated['toko_id'] != self::HEAD_OFFICE_ID) {
            $existingKepala = User::where('role', 'kepala_toko')
                ->where('toko_id', $validated['toko_id'])
                ->lockForUpdate()
                ->first();

            if ($existingKepala) {
                DB::rollBack();
                return back()->withErrors([
                    'toko_id' => "Toko ini sudah memiliki Kepala Toko ({$existingKepala->name}). Silakan pilih toko lain atau kosongkan untuk Head Office."
                ])->withInput();
            }
        }

        $validated['password'] = Hash::make($validated['password']);
        
        $user = User::create($validated);

        NotificationHelper::notifyRoles(
            ['super_admin'],
            NotificationHelper::userCreated($user, auth()->user())
        );

        DB::commit();
        return redirect()->route('superadmin.user.index')->with('success', 'User berhasil ditambahkan!');
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('User creation failed', [
            'error' => $e->getMessage(),
            'user' => auth()->id(),
            'data' => $request->except('password')
        ]);
        
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    }
}

    public function show(User $user)
    {
        $user->load('toko');
        return view('superadmin.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        $tokos = Toko::where('id', '!=', self::HEAD_OFFICE_ID)->get();
        $headOffice = Toko::find(self::HEAD_OFFICE_ID);
        $availableRoles = User::ROLES;
        
        return view('superadmin.user.edit', compact('user', 'tokos', 'headOffice', 'availableRoles'));
    }

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

    DB::beginTransaction();
    try {
        if ($request->boolean('remove_foto')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $validated['foto_profil'] = null;
        } elseif ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
        }

        if (empty($validated['toko_id'])) {
            $validated['toko_id'] = self::HEAD_OFFICE_ID;
        }

        $oldRole = $user->role;
        $oldTokoId = $user->toko_id;

        // FIX: Gunakan lockForUpdate untuk prevent race condition
        if ($validated['role'] === 'kepala_toko' && $validated['toko_id'] != self::HEAD_OFFICE_ID) {
            $existingKepala = User::where('role', 'kepala_toko')
                ->where('toko_id', $validated['toko_id'])
                ->where('id', '!=', $user->id)
                ->lockForUpdate()
                ->first();

            if ($existingKepala) {
                if (!$request->has('confirm_replace')) {
                    DB::rollBack();
                    return back()->withInput()->with('confirm_replace', [
                        'message' => "Toko ini sudah memiliki Kepala Toko: <strong>{$existingKepala->name}</strong>. Jika Anda melanjutkan, {$existingKepala->name} akan dipindahkan ke Head Office.",
                        'existing_kepala_name' => $existingKepala->name,
                    ]);
                }

                $existingKepala->update(['toko_id' => self::HEAD_OFFICE_ID]);
                
                $toko = Toko::find($validated['toko_id']);
                NotificationHelper::notifyRoles(
                    ['super_admin'],
                    NotificationHelper::kepalaTokoReplaced($existingKepala, $user, $toko, auth()->user())
                );
            }
        }

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:8', 'confirmed'],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        NotificationHelper::notifyRoles(
            ['super_admin'],
            NotificationHelper::userUpdated($user, auth()->user())
        );

        DB::commit();
        return redirect()->route('superadmin.user.index')->with('success', 'User berhasil diperbarui!');
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('User update failed', [
            'error' => $e->getMessage(),
            'user_id' => $user->id,
            'actor' => auth()->id()
        ]);
        
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    }
}

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('superadmin.user.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        DB::beginTransaction();
        try {
            $userName = $user->name;

            // Hapus foto profil jika ada
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            // Delete user
            $user->delete();

            // Kirim notifikasi ke Super Admin
            NotificationHelper::notifyRoles(
                ['super_admin'],
                NotificationHelper::userDeleted($userName, auth()->user())
            );

            DB::commit();
            return redirect()->route('superadmin.user.index')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}