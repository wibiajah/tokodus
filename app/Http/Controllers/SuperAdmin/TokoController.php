<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\User;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TokoController extends Controller
{
    const HEAD_OFFICE_ID = 999; // ID Head Office

    public function __construct()
    {
        $this->middleware('role:super_admin');
    }

    public function index(Request $request)
    {
        $query = Toko::with('kepalaToko')
            ->where('id', '!=', self::HEAD_OFFICE_ID);

        if ($request->filled('search')) {
            $query->where('nama_toko', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kepala_toko')) {
            $query->whereHas('kepalaToko', function($q) use ($request) {
                $q->where('id', $request->kepala_toko);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        switch ($request->get('sort', 'terbaru')) {
            case 'terlama':
                $query->oldest();
                break;
            case 'nama_az':
                $query->orderBy('nama_toko', 'asc');
                break;
            case 'nama_za':
                $query->orderBy('nama_toko', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $tokos = $query->get();
        return view('superadmin.toko.index', compact('tokos'));
    }

    public function create()
    {
        // Ambil kepala toko yang belum punya toko (toko_id = HEAD_OFFICE atau null)
        $kepalaTokos = User::where('role', 'kepala_toko')
            ->whereIn('toko_id', [self::HEAD_OFFICE_ID, null])
            ->get();
        
        return view('superadmin.toko.create', compact('kepalaTokos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'googlemap' => ['nullable', 'url'],
            'googlemap_iframe' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'kepala_toko_id' => ['nullable', 'exists:users,id'],
        ]);

        DB::beginTransaction();
        try {
            // Upload foto
            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('tokos', 'public');
            }

            // Toko tetap aktif meskipun tanpa kepala toko
            $validated['status'] = 'aktif';

            $toko = Toko::create($validated);

            // Assign kepala toko jika dipilih
            if ($request->filled('kepala_toko_id')) {
                User::where('id', $request->kepala_toko_id)->update([
                    'toko_id' => $toko->id
                ]);
            }

            NotificationHelper::notifyRoles(
                ['super_admin'],
                NotificationHelper::tokoCreated($toko, auth()->user())
            );

            DB::commit();
            return redirect()->route('superadmin.toko.index')->with('success', 'Toko berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Toko $toko)
    {
        if ($toko->id === self::HEAD_OFFICE_ID) {
            abort(403, 'Head Office tidak dapat diakses.');
        }

        $toko->load('kepalaToko', 'staffAdmin');
        return view('superadmin.toko.show', compact('toko'));
    }

    public function edit(Toko $toko)
    {
        if ($toko->id === self::HEAD_OFFICE_ID) {
            abort(403, 'Head Office tidak dapat diedit.');
        }

        // Ambil kepala toko yang available (di HEAD_OFFICE) atau kepala toko saat ini
        $kepalaTokos = User::where('role', 'kepala_toko')
            ->where(function($q) use ($toko) {
                $q->whereIn('toko_id', [self::HEAD_OFFICE_ID, null])
                  ->orWhere('toko_id', $toko->id);
            })
            ->get();
        
        return view('superadmin.toko.edit', compact('toko', 'kepalaTokos'));
    }

    public function update(Request $request, Toko $toko)
    {
        if ($toko->id === self::HEAD_OFFICE_ID) {
            return redirect()->route('superadmin.toko.index')
                ->with('error', 'Head Office tidak dapat diupdate.');
        }

        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'googlemap' => ['nullable', 'url'],
            'googlemap_iframe' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:aktif,tidak_aktif'],
            'kepala_toko_id' => ['nullable', 'exists:users,id'],
        ]);

        DB::beginTransaction();
        try {
            // Upload foto baru
            if ($request->hasFile('foto')) {
                if ($toko->foto) {
                    Storage::disk('public')->delete($toko->foto);
                }
                $validated['foto'] = $request->file('foto')->store('tokos', 'public');
            }

            // Ambil kepala toko lama
            $oldKepalaTokoId = $toko->kepalaToko?->id;
            $newKepalaTokoId = $request->kepala_toko_id;

            // Update data toko
            $toko->update($validated);

            // Handle perubahan kepala toko
            if ($newKepalaTokoId) {
                // Jika kepala toko diganti, pindahkan yang lama ke HEAD_OFFICE
                if ($oldKepalaTokoId && $oldKepalaTokoId != $newKepalaTokoId) {
                    User::where('id', $oldKepalaTokoId)->update([
                        'toko_id' => self::HEAD_OFFICE_ID
                    ]);
                }

                // Assign kepala toko baru
                User::where('id', $newKepalaTokoId)->update([
                    'toko_id' => $toko->id
                ]);
            } else {
                // Jika kepala toko dihapus, pindahkan ke HEAD_OFFICE
                if ($oldKepalaTokoId) {
                    User::where('id', $oldKepalaTokoId)->update([
                        'toko_id' => self::HEAD_OFFICE_ID
                    ]);
                }
            }

            NotificationHelper::notifyRoles(
                ['super_admin'],
                NotificationHelper::tokoUpdated($toko, auth()->user())
            );

            DB::commit();
            return redirect()->route('superadmin.toko.index')->with('success', 'Toko berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Toko $toko)
    {
        if ($toko->id === self::HEAD_OFFICE_ID) {
            return redirect()->route('superadmin.toko.index')
                ->with('error', 'Head Office tidak dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            $namaToko = $toko->nama_toko;

            // Pindahkan semua user ke HEAD_OFFICE
            $toko->users()->update(['toko_id' => self::HEAD_OFFICE_ID]);

            if ($toko->foto) {
                Storage::disk('public')->delete($toko->foto);
            }

            $toko->delete();

            NotificationHelper::notifyRoles(
                ['super_admin'],
                NotificationHelper::tokoDeleted($namaToko, auth()->user())
            );

            DB::commit();
            return redirect()->route('superadmin.toko.index')->with('success', 'Toko berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Toko $toko)
    {
        if ($toko->id === self::HEAD_OFFICE_ID) {
            return redirect()->back()
                ->with('error', 'Status Head Office tidak dapat diubah.');
        }

        $newStatus = $toko->status === 'aktif' ? 'tidak_aktif' : 'aktif';
        $toko->update(['status' => $newStatus]);

        $message = $newStatus === 'aktif' 
            ? "Toko {$toko->nama_toko} telah diaktifkan"
            : "Toko {$toko->nama_toko} telah dinonaktifkan";
        
        NotificationHelper::notifyRoles(
            ['super_admin'],
            [
                'title' => 'Status Toko Diubah',
                'message' => $message,
                'type' => 'info',
                'action_url' => route('toko.show', $toko->id),
                'icon' => 'fas fa-toggle-on'
            ]
        );

        $statusText = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', "Toko berhasil {$statusText}!");
    }

    public function updateKepalaToko(Request $request, Toko $toko)
    {
        if ($toko->id === self::HEAD_OFFICE_ID) {
            return redirect()->back()
                ->with('error', 'Kepala Toko Head Office tidak dapat diubah.');
        }

        $validated = $request->validate([
            'kepala_toko_id' => ['nullable', 'exists:users,id'],
        ]);

        DB::beginTransaction();
        try {
            $oldKepalaTokoId = $toko->kepalaToko?->id;
            $newKepalaTokoId = $request->kepala_toko_id;

            if ($newKepalaTokoId) {
                // Pindahkan kepala toko lama ke HEAD_OFFICE jika ada
                if ($oldKepalaTokoId && $oldKepalaTokoId != $newKepalaTokoId) {
                    User::where('id', $oldKepalaTokoId)->update([
                        'toko_id' => self::HEAD_OFFICE_ID
                    ]);
                }

                // Assign kepala toko baru
                User::where('id', $newKepalaTokoId)->update([
                    'toko_id' => $toko->id
                ]);

                $newKepala = User::find($newKepalaTokoId);
                
                NotificationHelper::notifyRoles(
                    ['super_admin'],
                    [
                        'title' => 'Kepala Toko Diperbarui',
                        'message' => "Kepala toko {$toko->nama_toko} telah diubah menjadi {$newKepala->name}",
                        'type' => 'success',
                        'action_url' => route('toko.show', $toko->id),
                        'icon' => 'fas fa-user-tie'
                    ]
                );

                DB::commit();
                return redirect()->back()
                    ->with('success', 'Kepala toko berhasil diperbarui!');
            } else {
                // Pindahkan kepala toko ke HEAD_OFFICE
                if ($oldKepalaTokoId) {
                    User::where('id', $oldKepalaTokoId)->update([
                        'toko_id' => self::HEAD_OFFICE_ID
                    ]);
                }

                NotificationHelper::notifyRoles(
                    ['super_admin'],
                    [
                        'title' => 'Kepala Toko Dihapus',
                        'message' => "Kepala toko {$toko->nama_toko} telah dihapus",
                        'type' => 'warning',
                        'action_url' => route('toko.show', $toko->id),
                        'icon' => 'fas fa-user-times'
                    ]
                );

                DB::commit();
                return redirect()->back()
                    ->with('success', 'Kepala toko berhasil dihapus!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}