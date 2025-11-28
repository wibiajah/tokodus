<x-admin-layout title="Tambah Toko">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Toko Baru</h1>
            <a href="{{ route('toko.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Tambah Toko</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('toko.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="nama_toko">Nama Toko <span class="text-danger">*</span></label>
                        <input type="text" 
                            class="form-control @error('nama_toko') is-invalid @enderror" 
                            id="nama_toko" 
                            name="nama_toko" 
                            value="{{ old('nama_toko') }}"
                            placeholder="Contoh: Toko Cabang 1"
                            required>
                        @error('nama_toko')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                            id="alamat" 
                            name="alamat" 
                            rows="3"
                            placeholder="Contoh: Jl. Merdeka No. 123, Jakarta">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="telepon">Telepon</label>
                        <input type="text" 
                            class="form-control @error('telepon') is-invalid @enderror" 
                            id="telepon" 
                            name="telepon" 
                            value="{{ old('telepon') }}"
                            placeholder="Contoh: 021-1234567">
                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('toko.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>