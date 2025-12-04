<x-admin-layout title="Tambah Toko">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-store text-primary"></i> Tambah Toko Baru
            </h1>
            <a href="{{ route('.superadmin.toko.index') }}" class="btn btn-secondary btn-icon-split shadow-sm" style="border-radius: 8px;">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm mb-4" id="formCard" style="border-radius: 15px; border: none;">
            <div class="card-header py-3" style="background-color: #00ac17; border-radius: 15px 15px 0 0; border: none;">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-edit"></i> Form Tambah Toko
                </h6>
            </div>
            <div class="card-body p-4">
                <form id="tokoForm" action="{{ route('superadmin.toko.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Nama Toko -->
                    <div class="form-group mb-3">
                        <label for="nama_toko" class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                            <i class="fas fa-store text-primary"></i> Nama Toko <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                            class="form-control @error('nama_toko') is-invalid @enderror" 
                            id="nama_toko" 
                            name="nama_toko" 
                            value="{{ old('nama_toko') }}"
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
                            style="border-radius: 8px; border: 1px solid #e3e6f0; padding: 0.85rem; font-size: 1rem; resize: vertical;">{{ old('alamat') }}</textarea>
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
                                    value="{{ old('telepon') }}"
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
                                    value="{{ old('email') }}"
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
                            value="{{ old('googlemap') }}"
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
                        <textarea class="form-control @error('googlemap_iframe') is-invalid @enderror" 
                            id="googlemap_iframe" 
                            name="googlemap_iframe" 
                            rows="4"
                            placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
                            style="border-radius: 8px; border: 1px solid #e3e6f0; padding: 0.85rem; font-size: 0.9rem; resize: vertical; font-family: monospace;">{{ old('googlemap_iframe') }}</textarea>
                        <small class="form-text text-muted mt-2" style="font-size: 0.85rem;">
                            <i class="fas fa-info-circle"></i> Salin kode iframe dari Google Maps → Bagikan → Sematkan Peta. Jika diisi, titik peta akan muncul di halaman toko.
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
                        <div class="custom-file" style="border-radius: 8px; overflow: hidden;">
                            <input type="file" 
                                class="custom-file-input @error('foto') is-invalid @enderror" 
                                id="foto" 
                                name="foto"
                                accept="image/jpeg,image/png,image/jpg,image/gif">
                            <label class="custom-file-label" for="foto" style="border-radius: 8px;">
                                <i class="fas fa-cloud-upload-alt"></i> Pilih file...
                            </label>
                        </div>
                        <small class="form-text text-muted mt-2" style="font-size: 0.85rem;">
                            <i class="fas fa-info-circle"></i> Format: JPEG, PNG, JPG, GIF. Max: 2MB
                        </small>
                        @error('foto')
                            <div class="invalid-feedback d-block mt-2" style="font-size: 0.85rem;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                        <!-- Foto Preview -->
                        <div id="fotoPreviewContainer" style="display: none; margin-top: 1rem;">
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
                                    style="border-radius: 8px; border: 1px solid #e3e6f0; padding: 0.85rem; font-size: 0.95rem; min-height: 45px;">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak_aktif" {{ old('status') === 'tidak_aktif' ? 'selected' : '' }} selected>Tidak Aktif</option>
                                </select>
                                <small class="form-text text-muted mt-2" style="font-size: 0.85rem; display: block; word-wrap: break-word;">
                                    <i class="fas fa-info-circle"></i> Status otomatis Aktif jika ada Kepala Toko
                                </small>
                                @error('status')
                                    <div class="invalid-feedback d-block mt-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="kepala_toko_id" class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                                    <i class="fas fa-user-tie text-primary"></i> Kepala Toko
                                </label>
                                <select class="form-control @error('kepala_toko_id') is-invalid @enderror" 
                                    id="kepala_toko_id" 
                                    name="kepala_toko_id"
                                    style="border-radius: 8px; border: 1px solid #e3e6f0; padding: 0.85rem; font-size: 0.95rem; min-height: 45px;">
                                    <option value="">-- Belum ada --</option>
                                    @foreach($kepalaTokos as $kt)
                                        <option value="{{ $kt->id }}" 
                                            data-name="{{ $kt->name }}" 
                                            data-email="{{ $kt->email }}"
                                            {{ old('kepala_toko_id') == $kt->id ? 'selected' : '' }}>
                                            {{ $kt->name }} ({{ $kt->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted mt-2" style="font-size: 0.85rem; display: block; word-wrap: break-word;">
                                    <i class="fas fa-info-circle"></i> Pilih dari daftar yang tersedia
                                </small>
                                @error('kepala_toko_id')
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
                        <button type="button" class="btn btn-primary shadow-sm" id="btnPreview" style="border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.9rem;">
                            <i class="fas fa-eye"></i> Preview Data
                        </button>
                        <a href="{{ route('superadmin.toko.index') }}" class="btn btn-secondary shadow-sm" style="border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.9rem;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="card shadow-sm mb-4" id="previewCard" style="display: none; border-radius: 15px; border: none;">
            <div class="card-header py-3" style="background-color: #00ac17; border-radius: 15px 15px 0 0; border: none;">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-eye"></i> Preview Data Toko
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info mb-4" style="border-radius: 8px; font-size: 0.9rem;">
                    <i class="fas fa-info-circle"></i> Periksa kembali data. Jika benar, klik <strong>Simpan ke Database</strong>.
                </div>

                <div class="row mb-3">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                            <div class="mb-3">
                                <div class="text-muted mb-1" style="font-size: 0.85rem;">
                                    <i class="fas fa-store text-primary"></i> Nama Toko
                                </div>
                                <div id="preview_nama_toko" class="font-weight-bold text-dark" style="font-size: 0.95rem;">-</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted mb-1" style="font-size: 0.85rem;">
                                    <i class="fas fa-map-marker-alt text-primary"></i> Alamat
                                </div>
                                <div id="preview_alamat" class="text-dark" style="font-size: 0.9rem;">-</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted mb-1" style="font-size: 0.85rem;">
                                    <i class="fas fa-phone text-primary"></i> Telepon
                                </div>
                                <div id="preview_telepon" class="text-dark" style="font-size: 0.9rem;">-</div>
                            </div>
                            <div>
                                <div class="text-muted mb-1" style="font-size: 0.85rem;">
                                    <i class="fas fa-envelope text-primary"></i> Email
                                </div>
                                <div id="preview_email" class="text-dark" style="font-size: 0.9rem;">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                            <div class="mb-3">
                                <div class="text-muted mb-1" style="font-size: 0.85rem;">
                                    <i class="fas fa-map text-primary"></i> Google Maps
                                </div>
                                <div id="preview_googlemap" class="text-dark" style="font-size: 0.9rem;">-</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted mb-1" style="font-size: 0.85rem;">
                                    <i class="fas fa-code text-primary"></i> Iframe Maps
                                </div>
                                <div id="preview_iframe" class="text-dark" style="font-size: 0.9rem;">-</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted mb-1" style="font-size: 0.85rem;">
                                    <i class="fas fa-toggle-on text-primary"></i> Status
                                </div>
                                <div id="preview_status" style="font-size: 0.9rem;">-</div>
                            </div>
                            <div>
                                <div class="text-muted mb-1" style="font-size: 0.85rem;">
                                    <i class="fas fa-user-tie text-primary"></i> Kepala Toko
                                </div>
                                <div id="preview_kepala_toko" class="text-dark" style="font-size: 0.9rem;">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Foto Preview -->
                <div class="mb-3">
                    <div class="p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                        <div class="text-muted mb-2" style="font-size: 0.85rem;">
                            <i class="fas fa-image text-primary"></i> Foto Toko
                        </div>
                        <div id="preview_foto_container" style="display: none;">
                            <img id="preview_foto" src="" alt="Preview Foto" class="img-fluid rounded" style="max-height: 200px; object-fit: cover; border-radius: 8px;">
                        </div>
                        <div id="preview_foto_empty" class="text-muted" style="font-size: 0.9rem;">
                            <i class="fas fa-times-circle"></i> Tidak ada foto
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                <!-- Action Buttons -->
                <div class="d-flex" style="gap: 8px;">
                    <button type="button" class="btn btn-success shadow-sm" id="btnSubmit" style="border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.9rem;">
                        <i class="fas fa-check"></i> Simpan ke Database
                    </button>
                    <button type="button" class="btn btn-warning shadow-sm" id="btnEdit" style="border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.9rem;">
                        <i class="fas fa-edit"></i> Edit Data
                    </button>
                    <a href="{{ route('superadmin.toko.index') }}" class="btn btn-secondary shadow-sm" style="border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.9rem;">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
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
    </style>

    <script>
        // Update label file input & preview
        document.getElementById('foto').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Pilih file...';
            document.querySelector('.custom-file-label').textContent = fileName;

            if (e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.getElementById('fotoPreview');
                    img.src = event.target.result;
                    document.getElementById('fotoPreviewContainer').style.display = 'block';
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Preview Button Handler
        document.getElementById('btnPreview').addEventListener('click', function() {
            const form = document.getElementById('tokoForm');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const namaToko = document.getElementById('nama_toko').value;
            const alamat = document.getElementById('alamat').value;
            const telepon = document.getElementById('telepon').value;
            const email = document.getElementById('email').value;
            const googlemap = document.getElementById('googlemap').value;
            const googlemapIframe = document.getElementById('googlemap_iframe').value;
            const status = document.getElementById('status').value;
            const kepalaTokoSelect = document.getElementById('kepala_toko_id');
            const kepalaTokoId = kepalaTokoSelect.value;
            const foto = document.getElementById('foto').files[0];

            document.getElementById('preview_nama_toko').textContent = namaToko || '-';
            document.getElementById('preview_alamat').textContent = alamat || '-';
            document.getElementById('preview_telepon').textContent = telepon || '-';
            document.getElementById('preview_email').textContent = email || '-';
            
            if (googlemap) {
                document.getElementById('preview_googlemap').innerHTML = 
                    `<a href="${googlemap}" target="_blank" class="text-decoration-none"><i class="fas fa-map-marked-alt"></i> Lihat Lokasi</a>`;
            } else {
                document.getElementById('preview_googlemap').textContent = '-';
            }

            if (googlemapIframe) {
                document.getElementById('preview_iframe').innerHTML = 
                    '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Ada Iframe</span>';
            } else {
                document.getElementById('preview_iframe').innerHTML = 
                    '<span class="badge badge-secondary"><i class="fas fa-times-circle"></i> Tidak Ada</span>';
            }

            let statusBadge = '';
            if (kepalaTokoId) {
                statusBadge = '<span class="badge badge-success px-2 py-1"><i class="fas fa-circle" style="font-size: 7px;"></i> Aktif</span>';
            } else if (status === 'aktif') {
                statusBadge = '<span class="badge badge-success px-2 py-1"><i class="fas fa-circle" style="font-size: 7px;"></i> Aktif</span>';
            } else {
                statusBadge = '<span class="badge badge-secondary px-2 py-1"><i class="fas fa-circle" style="font-size: 7px;"></i> Tidak Aktif</span>';
            }
            document.getElementById('preview_status').innerHTML = statusBadge;

            if (kepalaTokoId) {
                const selectedOption = kepalaTokoSelect.options[kepalaTokoSelect.selectedIndex];
                const namaKepala = selectedOption.getAttribute('data-name');
                const emailKepala = selectedOption.getAttribute('data-email');
                document.getElementById('preview_kepala_toko').innerHTML = 
                    `<strong>${namaKepala}</strong><br><small class="text-muted">${emailKepala}</small>`;
            } else {
                document.getElementById('preview_kepala_toko').innerHTML = 
                    '<span class="text-muted">Belum ada</span>';
            }

            if (foto) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview_foto').src = e.target.result;
                    document.getElementById('preview_foto_container').style.display = 'block';
                    document.getElementById('preview_foto_empty').style.display = 'none';
                }
                reader.readAsDataURL(foto);
            } else {
                document.getElementById('preview_foto_container').style.display = 'none';
                document.getElementById('preview_foto_empty').style.display = 'block';
            }

            document.getElementById('formCard').style.display = 'none';
            document.getElementById('previewCard').style.display = 'block';
            document.getElementById('previewCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        document.getElementById('btnEdit').addEventListener('click', function() {
            document.getElementById('formCard').style.display = 'block';
            document.getElementById('previewCard').style.display = 'none';
            document.getElementById('formCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        document.getElementById('btnSubmit').addEventListener('click', function() {
            if (confirm('Yakin data toko sudah benar? Data akan disimpan ke database!')) {
                document.getElementById('tokoForm').submit();
            }
        });
    </script>
</x-admin-layout>