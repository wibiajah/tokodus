<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    // Tampilkan daftar toko
    public function index()
    {
        $tokos = Toko::withCount(['users', 'kepalaToko', 'staffAdmin'])->latest()->get();
        return view('admin.toko.index', compact('tokos'));
    }

    // Form tambah toko
    public function create()
    {
        return view('admin.toko.create');
    }

    // Simpan toko baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
        ]);

        Toko::create($validated);

        return redirect()->route('toko.index')->with('success', 'Toko berhasil ditambahkan!');
    }

    // Tampilkan detail toko
    public function show(Toko $toko)
    {
        $toko->load(['kepalaToko', 'staffAdmin']);
        return view('admin.toko.show', compact('toko'));
    }

    // Form edit toko
    public function edit(Toko $toko)
    {
        return view('admin.toko.edit', compact('toko'));
    }

    // Update toko
    public function update(Request $request, Toko $toko)
    {
        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
        ]);

        $toko->update($validated);

        return redirect()->route('toko.index')->with('success', 'Toko berhasil diperbarui!');
    }

    // Hapus toko
    public function destroy(Toko $toko)
    {
        // Cek apakah ada user yang terkait
        if ($toko->users()->count() > 0) {
            return redirect()->route('toko.index')->with('error', 'Tidak dapat menghapus toko yang masih memiliki user!');
        }

        $toko->delete();

        return redirect()->route('toko.index')->with('success', 'Toko berhasil dihapus!');
    }
}