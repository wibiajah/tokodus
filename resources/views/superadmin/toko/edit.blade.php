<x-admin-layout title="Edit Toko">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-store text-primary"></i> Edit Toko
            </h1>
            <a href="{{ route('superadmin.toko.index') }}" class="btn btn-secondary btn-icon-split shadow-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-edit"></i> Form Edit Toko: {{ $toko->nama_toko }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('superadmin.toko.update', $toko) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Dasar -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-info-circle text-primary"></i> Informasi Dasar
                        </h5>

                        <!-- Nama Toko -->
                        <div class="form-group">
                            <label for="nama_toko" class="font-weight-bold">
                                Nama Toko <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                class="form-control @error('nama_toko') is-invalid @enderror" 
                                id="nama_toko" 
                                name="nama_toko" 
                                value="{{ old('nama_toko', $toko->nama_toko) }}"
                                placeholder="Contoh: Toko Pusat"
                                required>
                            @error('nama_toko')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="form-group">
                            <label for="alamat" class="font-weight-bold">Alamat Lengkap</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                id="alamat" 
                                name="alamat" 
                                rows="3"
                                placeholder="Contoh: Jl. Merdeka No. 123, Jakarta">{{ old('alamat', $toko->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telepon & Email (2 Kolom) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telepon" class="font-weight-bold">Telepon</label>
                                    <input type="text" 
                                        class="form-control @error('telepon') is-invalid @enderror" 
                                        id="telepon" 
                                        name="telepon" 
                                        value="{{ old('telepon', $toko->telepon) }}"
                                        placeholder="Contoh: 021-1234567">
                                    @error('telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="font-weight-bold">Email</label>
                                    <input type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email', $toko->email) }}"
                                        placeholder="Contoh: toko@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Lokasi & Peta -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-map-marker-alt text-primary"></i> Lokasi & Peta
                        </h5>

                        <!-- Google Maps Link -->
                        <div class="form-group">
                            <label for="googlemap" class="font-weight-bold">Link Google Maps</label>
                            <input type="url" 
                                class="form-control @error('googlemap') is-invalid @enderror" 
                                id="googlemap" 
                                name="googlemap" 
                                value="{{ old('googlemap', $toko->googlemap) }}"
                                placeholder="https://maps.google.com/?q=...">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Salin link dari Google Maps dengan tombol "Bagikan"
                            </small>
                            @error('googlemap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Google Maps Iframe (Embed) -->
                        <div class="form-group">
                            <label for="googlemap_iframe" class="font-weight-bold">Iframe Google Maps (Opsional)</label>
                            
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
                                placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'>{{ old('googlemap_iframe', $toko->googlemap_iframe) }}</textarea>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Salin kode iframe dari Google Maps → Bagikan → Sematkan Peta. Kosongkan jika ingin menghapus iframe.
                            </small>
                            @error('googlemap_iframe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Media Toko -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-image text-primary"></i> Media Toko
                        </h5>

                        <!-- Foto Upload -->
                        <div class="form-group">
                            <label for="foto" class="font-weight-bold">Foto Toko</label>
                            
                            <!-- Preview foto lama dengan tombol hapus realtime -->
                            @if($toko->foto)
                                <div id="existingPhotoContainer" class="photo-container mb-3">
                                    <div class="photo-item">
                                        <img src="{{ asset('storage/' . $toko->foto) }}" 
                                             alt="Foto {{ $toko->nama_toko }}">
                                        <button type="button" class="delete-photo-btn" onclick="deleteExistingPhoto()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <input type="hidden" name="keep_foto" value="1" id="keep_foto">
                                    </div>
                                </div>
                            @endif

                            <div class="custom-file">
                                <input type="file" 
                                    class="custom-file-input @error('foto') is-invalid @enderror" 
                                    id="foto" 
                                    name="foto"
                                    accept="image/jpeg,image/png,image/jpg,image/gif">
                                <label class="custom-file-label" for="foto">Pilih file baru (opsional)...</label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Format: JPEG, PNG, JPG, GIF. Max: 2MB. Kosongkan jika tidak ingin mengubah foto
                            </small>
                            @error('foto')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <!-- Preview foto baru -->
                            <div id="newPhotoPreview" class="photo-container mt-3" style="display: none;"></div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Status Toko -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-cog text-primary"></i> Pengaturan
                        </h5>

                        <div class="form-group">
                            <label for="status" class="font-weight-bold">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status"
                                required>
                                <option value="aktif" {{ old('status', $toko->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak_aktif" {{ old('status', $toko->status) === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @if($toko->kepalaToko)
                                <small class="form-text text-success">
                                    <i class="fas fa-info-circle"></i> Kepala Toko saat ini: <strong>{{ $toko->kepalaToko->name }}</strong>
                                </small>
                            @endif
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Buttons -->
                    <div class="d-flex" style="gap: 10px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Perbarui Toko
                        </button>
                        <a href="{{ route('superadmin.toko.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Form improvements */
        .form-control:focus,
        .custom-file-input:focus + .custom-file-label {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .custom-file-label::after {
            content: "Pilih";
            background-color: #4e73df;
            color: white;
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover:not(:disabled) {
            transform: translateY(-1px);
        }

        .form-group label {
            font-size: 0.95rem;
        }

        .invalid-feedback {
            font-size: 0.85rem;
        }

        iframe {
            max-width: 100%;
            height: 300px;
        }

        /* Photo Container */
        .photo-container {
            max-width: 300px;
        }

        .photo-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .photo-item img {
            width: 100%;
            height: auto;
            display: block;
        }

        .delete-photo-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #e74a3b;
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s;
            z-index: 10;
        }

        .delete-photo-btn:hover {
            background: #c9302c;
            transform: scale(1.1);
        }
    </style>

    <script>
        // Realtime Delete Existing Photo
        function deleteExistingPhoto() {
            const photoContainer = document.getElementById('existingPhotoContainer');
            if (photoContainer) {
                photoContainer.style.opacity = '0.5';
                photoContainer.style.transition = 'all 0.3s';
                setTimeout(() => {
                    photoContainer.remove();
                }, 300);
                
                // Remove keep_foto input
                const keepInput = document.getElementById('keep_foto');
                if (keepInput) {
                    keepInput.remove();
                }
            }
        }

        // Update label file input & preview new photo with delete
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const label = document.querySelector('.custom-file-label');
            const container = document.getElementById('newPhotoPreview');

            if (file) {
                label.textContent = file.name;
                container.style.display = 'block';

                const reader = new FileReader();
                reader.onload = function(event) {
                    container.innerHTML = `
                        <div class="photo-item">
                            <img src="${event.target.result}" alt="Preview">
                            <button type="button" class="delete-photo-btn" onclick="deleteNewPhoto()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                label.textContent = 'Pilih file baru (opsional)...';
                container.style.display = 'none';
                container.innerHTML = '';
            }
        });

        // Delete New Photo
        function deleteNewPhoto() {
            const fotoInput = document.getElementById('foto');
            fotoInput.value = '';
            
            const label = document.querySelector('.custom-file-label');
            label.textContent = 'Pilih file baru (opsional)...';
            
            const container = document.getElementById('newPhotoPreview');
            container.style.opacity = '0.5';
            container.style.transition = 'all 0.3s';
            setTimeout(() => {
                container.style.display = 'none';
                container.innerHTML = '';
                container.style.opacity = '1';
            }, 300);
        }
    </script>
</x-admin-layout>