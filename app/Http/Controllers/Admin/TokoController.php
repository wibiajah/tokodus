<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\User;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TokoController extends Controller
{
    const HEAD_OFFICE_ID = 999; // ID Head Office

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Toko::with('kepalaToko')
            ->where('id', '!=', self::HEAD_OFFICE_ID); // 🔥 EXCLUDE HEAD OFFICE

        // Filter: Search
        if ($request->filled('search')) {
            $query->where('nama_toko', 'like', '%' . $request->search . '%');
        }

        // Filter: Kepala Toko
        if ($request->filled('kepala_toko')) {
            $query->whereHas('kepalaToko', function($q) use ($request) {
                $q->where('id', $request->kepala_toko);
            });
        }

        // Filter: Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
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
            default: // terbaru
                $query->latest();
                break;
        }

        $tokos = $query->get();

        return view('admin.toko.index', compact('tokos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kepalaTokos = User::where('role', 'kepala_toko')->get();
        return view('admin.toko.create', compact('kepalaTokos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'googlemap' => ['nullable', 'url'],
            'googlemap_iframe' => ['nullable', 'string'], // 🔥 NEW FIELD
            'foto' => ['nullable', 'image', 'max:2048'],
            'kepala_toko_id' => ['nullable', 'exists:users,id'],
        ]);

        // Upload foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('tokos', 'public');
        }

        // Tentukan status berdasarkan kepala toko
        $validated['status'] = $request->filled('kepala_toko_id') ? 'aktif' : 'tidak_aktif';

        $toko = Toko::create($validated);

        // Update toko_id kepala toko jika dipilih
        if ($request->filled('kepala_toko_id')) {
            User::find($request->kepala_toko_id)->update(['toko_id' => $toko->id]);
        }

        // Kirim notifikasi
        NotificationHelper::notifyRoles(
            ['super_admin', 'admin'],
            NotificationHelper::tokoCreated($toko, auth()->user())
        );

        return redirect()->route('toko.index')->with('success', 'Toko berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Toko $toko)
    {
        // 🔥 PREVENT viewing Head Office
        if ($toko->id === self::HEAD_OFFICE_ID) {
            abort(403, 'Head Office tidak dapat diakses.');
        }

        $toko->load('kepalaToko', 'staffAdmin');
        return view('admin.toko.show', compact('toko'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Toko $toko)
    {
        // 🔥 PREVENT editing Head Office
        if ($toko->id === self::HEAD_OFFICE_ID) {
            abort(403, 'Head Office tidak dapat diedit.');
        }

        $kepalaTokos = User::where('role', 'kepala_toko')->get();
        return view('admin.toko.edit', compact('toko', 'kepalaTokos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Toko $toko)
    {
        // 🔥 PREVENT updating Head Office
        if ($toko->id === self::HEAD_OFFICE_ID) {
            return redirect()->route('toko.index')
                ->with('error', 'Head Office tidak dapat diupdate.');
        }

        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'googlemap' => ['nullable', 'url'],
            'googlemap_iframe' => ['nullable', 'string'], // 🔥 NEW FIELD
            'foto' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:aktif,tidak_aktif'],
            'kepala_toko_id' => ['nullable', 'exists:users,id'],
        ]);

        // Upload foto baru
        if ($request->hasFile('foto')) {
            if ($toko->foto) {
                Storage::disk('public')->delete($toko->foto);
            }
            $validated['foto'] = $request->file('foto')->store('tokos', 'public');
        }

        // Simpan kepala toko lama
        $oldKepalaTokoId = $toko->kepalaToko?->id;

        $toko->update($validated);

        // Handle perubahan kepala toko
        if ($request->filled('kepala_toko_id')) {
            $newKepalaTokoId = $request->kepala_toko_id;

            // Jika kepala toko diganti
            if ($oldKepalaTokoId && $oldKepalaTokoId != $newKepalaTokoId) {
                User::find($oldKepalaTokoId)->update(['toko_id' => null]);
            }

            User::find($newKepalaTokoId)->update(['toko_id' => $toko->id]);
            $toko->update(['status' => 'aktif']);
        } else {
            // Jika kepala toko dihapus
            if ($oldKepalaTokoId) {
                User::find($oldKepalaTokoId)->update(['toko_id' => null]);
                $toko->update(['status' => 'tidak_aktif']);
            }
        }

        // Kirim notifikasi
        NotificationHelper::notifyRoles(
            ['super_admin', 'admin'],
            NotificationHelper::tokoUpdated($toko, auth()->user())
        );

        return redirect()->route('toko.index')->with('success', 'Toko berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Toko $toko)
    {
        // 🔥 PREVENT deleting Head Office
        if ($toko->id === self::HEAD_OFFICE_ID) {
            return redirect()->route('toko.index')
                ->with('error', 'Head Office tidak dapat dihapus.');
        }

        $namaToko = $toko->nama_toko;

        // Lepas semua user dari toko
        $toko->users()->update(['toko_id' => null]);

        // Hapus foto
        if ($toko->foto) {
            Storage::disk('public')->delete($toko->foto);
        }

        $toko->delete();

        // Kirim notifikasi
        NotificationHelper::notifyRoles(
            ['super_admin', 'admin'],
            NotificationHelper::tokoDeleted($namaToko, auth()->user())
        );

        return redirect()->route('toko.index')->with('success', 'Toko berhasil dihapus!');
    }
}  