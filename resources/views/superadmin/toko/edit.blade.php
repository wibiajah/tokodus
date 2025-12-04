<x-admin-layout title="Edit Toko">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-store text-primary"></i> Edit Toko
            </h1>
            <a href="{{ route('superadmin.toko.index') }}" class="btn btn-secondary btn-icon-split shadow-sm" style="border-radius: 8px;">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm mb-4" style="border-radius: 15px; border: none;">
            <div class="card-header py-3" style="background-color: #00ac17; border-radius: 15px 15px 0 0; border: none;">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-edit"></i> Form Edit Toko: {{ $toko->nama_toko }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('superadmin.toko.update', $toko) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nama Toko -->
                    <div class="form-group mb-3">
                        <label for="nama_toko" class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                            <i class="fas fa-store text-primary"></i> Nama Toko <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                            class="form-control @error('nama_toko') is-invalid @enderror" 
                            id="nama_toko" 
                            name="nama_toko" 
                            value="{{ old('nama_toko', $toko->nama_toko) }}"
                            placeholder="Contoh: Toko Pusat"
                            style="border-radius: 8px; border: 1px solid #e3e6f0; padding: 0.85rem; font-size: 1rem; min-height: 45px;"
                            required>
                        @error('nama_toko')
                            <div class="invalid-feedback d-block mt-2" style="font-size: 0.85rem;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="form-group mb-3">
                        <label for="alamat" class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                            <i class="fas fa-map-marker-alt text-primary"></i> Alamat Lengkap
                        </label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                            id="alamat" 
                            name="alamat" 
                            rows="3"
                            placeholder="Contoh: Jl. Merdeka No. 123, Jakarta"
                            style="border-radius: 8px; border: 1px solid #e3e6f0; padding: 0.85rem; font-size: 1rem; resize: vertical;">{{ old('alamat', $toko->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback d-block mt-2" style="font-size: 0.85rem;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Telepon & Email (2 Kolom) -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="telepon" class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                                    <i class="fas fa-phone text-primary"></i> Telepon
                                </label>
                                <input type="text" 
                                    class="form-control @error('telepon') is-invalid @enderror" 
                                    id="telepon" 
                                    name="telepon" 
                                    value="{{ old('telepon', $toko->telepon) }}"
                                    placeholder="Contoh: 021-1234567"
                                    style="border-radius: 8px; border: 1px solid #e3e6f0; padding: 0.85rem; font-size: 1rem; min-height: 45px;">
                                @error('telepon')
                                    <div class="invalid-feedback d-block mt-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email" class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                                    <i class="fas fa-envelope text-primary"></i> Email
                                </label>
                                <input type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', $toko->email) }}"
                                    placeholder="Contoh: toko@email.com"
                                    style="border-radius: 8px; border: 1px solid #e3e6f0; padding: 0.85rem; font-size: 1rem; min-height: 45px;">
                                @error('email')
                                    <div class="invalid-feedback d-block mt-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Google Maps Link -->
                    <div class="form-group mb-3">
                        <label for="googlemap" class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                            <i class="fas fa-map text-primary"></i> Link Google Maps
                        </label>
                        <input type="url" 
                            class="form-control @error('googlemap') is-invalid @enderror" 
                            id="googlemap" 
                            name="googlemap" 
                            value="{{ old('googlemap', $toko->googlemap) }}"
                            placeholder="https://maps.google.com/?q=..."
                            style="border-radius: 8px; border: 1px solid #e3e6f0; padding: 0.85rem; font-size: 1rem; min-height: 45px;">
                        <small class="form-text text-muted mt-2" style="font-size: 0.85rem;">
                            <i class="fas fa-info-circle"></i> Salin link dari Google Maps dengan tombol "Bagikan"
                        </small>
                        @error('googlemap')
                            <div class="invalid-feedback d-block mt-2" style="font-size: 0.85rem;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Google Maps Iframe (Embed) -->
                    <div class="form-group mb-3">
                        <label for="googlemap_iframe" class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                            <i class="fas fa-code text-primary"></i> Iframe Google Maps (Opsional)
                        </label>
                        
                        @if($toko->googlemap_iframe)
                            <div class="mb-3 p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                                <p class="small text-muted mb-2">
                                    <i class="fas fa-map-marked-alt"></i> Preview Iframe saat ini:
                                </p>
                                <div style="max-width: 100%; overflow: hidden; border-radius: 8px;">
                                    {!! $toko->googlemap_iframe !!}
                                </div>
                            </div>
                        @endif

                        <textarea class="form-control @error('googlemap_iframe') is-invalid @enderror" 
                            id="googlemap_iframe" 
                            name="googlemap_iframe" 
                            rows="4"
                            placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
                            style="border-radius: 8px; border: 1px solid #e3e6f0; padding: 0.85rem; font-size: 0.9rem; resize: vertical; font-family: monospace;">{{ old('googlemap_iframe', $toko->googlemap_iframe) }}</textarea>
                        <small class="form-text text-muted mt-2" style="font-size: 0.85rem;">
                            <i class="fas fa-info-circle"></i> Salin kode iframe dari Google Maps → Bagikan → Sematkan Peta. Kosongkan jika ingin menghapus iframe.
                        </small>
                        @error('googlemap_iframe')
                            <div class="invalid-feedback d-block mt-2" style="font-size: 0.85rem;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Foto Upload -->
                    <div class="form-group mb-3">
                        <label for="foto" class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                            <i class="fas fa-image text-primary"></i> Foto Toko
                        </label>
                        
                        <!-- Preview foto lama -->
                        @if($toko->foto)
                            <div class="mb-3 p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                                <p class="small text-muted mb-2">
                                    <i class="fas fa-image"></i> Foto saat ini:
                                </p>
                                <img src="{{ asset('storage/' . $toko->foto) }}" 
                                     alt="Foto {{ $toko->nama_toko }}" 
                                     style="max-width: 200px; height: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            </div>
                        @endif

                        <div class="custom-file" style="border-radius: 8px; overflow: hidden;">
                            <input type="file" 
                                class="custom-file-input @error('foto') is-invalid @enderror" 
                                id="foto" 
                                name="foto"
                                accept="image/jpeg,image/png,image/jpg,image/gif">
                            <label class="custom-file-label" for="foto" style="border-radius: 8px;">
                                <i class="fas fa-cloud-upload-alt"></i> Pilih file baru (opsional)...
                            </label>
                        </div>
                        <small class="form-text text-muted mt-2" style="font-size: 0.85rem;">
                            <i class="fas fa-info-circle"></i> Format: JPEG, PNG, JPG, GIF. Max: 2MB. Kosongkan jika tidak ingin mengubah foto
                        </small>
                        @error('foto')
                            <div class="invalid-feedback d-block mt-2" style="font-size: 0.85rem;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                        
                        <!-- Preview foto baru -->
                        <div id="fotoPreviewContainer" style="display: none; margin-top: 1rem;">
                            <p class="small text-muted mb-2">
                                <i class="fas fa-eye"></i> Preview foto baru:
                            </p>
                            <img id="fotoPreview" src="" alt="Preview" style="max-height: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        </div>
                    </div>

                    <!-- Status & Kepala Toko (2 Kolom) -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="status" class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                                    <i class="fas fa-toggle-on text-primary"></i> Status
                                </label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status"
                                    style="border-radius: 8px; border: 1px solid #e3e6f0; padding: 0.85rem; font-size: 0.95rem; min-height: 45px;"
                                    required>
                                    <option value="aktif" {{ old('status', $toko->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak_aktif" {{ old('status', $toko->status) === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @if($toko->kepalaToko)
                                    <small class="form-text text-success mt-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-info-circle"></i> Kepala Toko saat ini: <strong>{{ $toko->kepalaToko->name }}</strong>
                                    </small>
                                @endif
                                @error('status')
                                    <div class="invalid-feedback d-block mt-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Buttons -->
                    <div class="d-flex" style="gap: 8px;">
                        <button type="submit" class="btn btn-primary shadow-sm" style="border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.9rem;">
                            <i class="fas fa-save"></i> Perbarui
                        </button>
                        <a href="{{ route('superadmin.toko.index') }}" class="btn btn-secondary shadow-sm" style="border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.9rem;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus,
        .custom-file-input:focus + .custom-file-label {
            border-color: #00ac17;
            box-shadow: 0 0 0 0.2rem rgba(0, 172, 23, 0.15);
        }

        .custom-file-label::after {
            content: "Pilih";
            background-color: #00ac17;
            color: white;
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover:not(:disabled) {
            transform: translateY(-2px);
        }

        .form-group label {
            font-size: 0.95rem;
        }

        .invalid-feedback {
            font-size: 0.85rem;
            color: #dc3545;
        }

        iframe {
            max-width: 100%;
            height: 300px;
        }
    </style>

    <script>
        // Update label file input & preview
        document.getElementById('foto').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Pilih file baru (opsional)...';
            document.querySelector('.custom-file-label').textContent = fileName;

            if (e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.getElementById('fotoPreview');
                    img.src = event.target.result;
                    document.getElementById('fotoPreviewContainer').style.display = 'block';
                };
                reader.readAsDataURL(e.target.files[0]);
            } else {
                document.getElementById('fotoPreviewContainer').style.display = 'none';
            }
        });
    </script>
</x-admin-layout>