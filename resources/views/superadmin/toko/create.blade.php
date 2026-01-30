<x-admin-layout title="Tambah Toko">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-store text-primary"></i> Tambah Toko Baru
            </h1>
            <a href="{{ route('superadmin.toko.index') }}" class="btn btn-secondary btn-icon-split shadow-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm mb-4" id="formCard">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-edit"></i> Form Tambah Toko
                </h6>
            </div>
            <div class="card-body p-4">
                <form id="tokoForm" action="{{ route('superadmin.toko.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

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
                            <input type="text" class="form-control @error('nama_toko') is-invalid @enderror"
                                id="nama_toko" name="nama_toko" value="{{ old('nama_toko') }}"
                                placeholder="Contoh: Toko Pusat" required>
                            @error('nama_toko')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="form-group">
                            <label for="alamat" class="font-weight-bold">Alamat Lengkap</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                placeholder="Contoh: Jl. Merdeka No. 123, Jakarta">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
    <label for="postal_code" class="font-weight-bold">Kode Pos</label>
    <input type="text" 
        class="form-control @error('postal_code') is-invalid @enderror" 
        id="postal_code" 
        name="postal_code" 
        value="{{ old('postal_code') }}"
        placeholder="Contoh: 40123"
        maxlength="10">
    <small class="form-text text-muted">
        <i class="fas fa-info-circle"></i> Kode pos lokasi toko (opsional)
    </small>
    @error('postal_code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                        <!-- Telepon & Email (2 Kolom) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telepon" class="font-weight-bold">Telepon</label>
                                    <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                        id="telepon" name="telepon" value="{{ old('telepon') }}"
                                        placeholder="Contoh: 021-1234567">
                                    @error('telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="font-weight-bold">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}"
                                        placeholder="Contoh: toko@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Lokasi -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-map-marker-alt text-primary"></i> Lokasi
                        </h5>

                        <!-- Google Maps Link -->
                        <div class="form-group">
                            <label for="googlemap" class="font-weight-bold">Link Google Maps</label>
                            <input type="url" class="form-control @error('googlemap') is-invalid @enderror"
                                id="googlemap" name="googlemap" value="{{ old('googlemap') }}"
                                placeholder="https://maps.google.com/?q=...">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Salin link dari Google Maps dengan tombol "Bagikan"
                            </small>
                            @error('googlemap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Google Maps Iframe -->
                        <div class="form-group">
                            <label for="googlemap_iframe" class="font-weight-bold">Iframe Google Maps (Opsional)</label>
                            <textarea class="form-control @error('googlemap_iframe') is-invalid @enderror" id="googlemap_iframe"
                                name="googlemap_iframe" rows="4"
                                placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
                                style="font-family: monospace; font-size: 0.9rem;">{{ old('googlemap_iframe') }}</textarea>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Salin kode iframe dari Google Maps → Bagikan →
                                Sematkan Peta
                            </small>
                            @error('googlemap_iframe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Koordinat GPS -->
                        <div class="form-group">
                            <label class="font-weight-bold">
                                Koordinat Toko (Klik di Peta) <span class="text-danger">*</span>
                            </label>

                            <!-- Map Container -->
                            <div id="map"
                                style="height: 400px; border-radius: 8px; border: 2px solid #e3e6f0; margin-bottom: 15px;">
                            </div>

                            <!-- Koordinat Display -->
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="latitude" class="font-weight-bold">Latitude</label>
                                    <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                                        id="latitude" name="latitude" value="{{ old('latitude') }}"
                                        placeholder="-6.900977" readonly required>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="longitude" class="font-weight-bold">Longitude</label>
                                    <input type="text"
                                        class="form-control @error('longitude') is-invalid @enderror" id="longitude"
                                        name="longitude" value="{{ old('longitude') }}" placeholder="107.618856"
                                        readonly required>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Klik lokasi toko Anda di peta untuk mengisi
                                koordinat
                            </small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Status & Kepala Toko -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-cog text-primary"></i> Pengaturan
                        </h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="font-weight-bold">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status"
                                        name="status">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="tidak_aktif"
                                            {{ old('status') === 'tidak_aktif' ? 'selected' : '' }} selected>Tidak
                                            Aktif</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Status otomatis Aktif jika ada Kepala Toko
                                    </small>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kepala_toko_id" class="font-weight-bold">Kepala Toko</label>
                                    <select class="form-control @error('kepala_toko_id') is-invalid @enderror"
                                        id="kepala_toko_id" name="kepala_toko_id">
                                        <option value="">-- Belum ada --</option>
                                        @foreach ($kepalaTokos as $kt)
                                            <option value="{{ $kt->id }}" data-name="{{ $kt->name }}"
                                                data-email="{{ $kt->email }}"
                                                {{ old('kepala_toko_id') == $kt->id ? 'selected' : '' }}>
                                                {{ $kt->name }} ({{ $kt->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Pilih dari daftar yang tersedia
                                    </small>
                                    @error('kepala_toko_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Media -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-image text-primary"></i> Foto Toko
                        </h5>

                        <!-- Foto Upload -->
                        <div class="form-group">
                            <label for="foto" class="font-weight-bold">Upload Foto</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('foto') is-invalid @enderror"
                                    id="foto" name="foto" accept="image/jpeg,image/png,image/jpg,image/gif">
                                <label class="custom-file-label" for="foto">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Format: JPEG, PNG, JPG, GIF. Max: 2MB
                            </small>
                            @error('foto')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <!-- Foto Preview with Delete -->
                            <div id="fotoPreviewContainer" class="photo-container mt-3" style="display: none;">
                                <div class="photo-item">
                                    <img id="fotoPreview" src="" alt="Preview">
                                    <button type="button" class="delete-photo-btn" onclick="deletePhoto()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Buttons -->
                    <div class="d-flex" style="gap: 10px;">
                        <button type="button" class="btn btn-primary" id="btnPreview">
                            <i class="fas fa-eye"></i> Preview Data
                        </button>
                        <a href="{{ route('superadmin.toko.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="card shadow-sm mb-4" id="previewCard" style="display: none;">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-eye"></i> Preview Data Toko
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle"></i> Periksa kembali data. Jika benar, klik <strong>Simpan ke
                        Database</strong>.
                </div>

                <!-- Preview Content -->
                <div class="mb-4">
                    <h5 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-info-circle text-primary"></i> Informasi Dasar
                    </h5>
                    <div class="preview-section">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>Nama Toko</label>
                                    <div id="preview_nama_toko" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>Telepon</label>
                                    <div id="preview_telepon" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>Email</label>
                                    <div id="preview_email" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>Alamat</label>
                                    <div id="preview_alamat" class="preview-value">-</div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
    <div class="preview-item">
        <label>Kode Pos</label>
        <div id="preview_postal_code" class="preview-value">-</div>
    </div>
</div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <h5 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-map-marker-alt text-primary"></i> Lokasi
                    </h5>
                    <div class="preview-section">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>Google Maps</label>
                                    <div id="preview_googlemap" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>Iframe Maps</label>
                                    <div id="preview_iframe" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>Koordinat GPS</label>
                                    <div id="preview_koordinat" class="preview-value">-</div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <h5 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-cog text-primary"></i> Pengaturan
                    </h5>
                    <div class="preview-section">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>Status</label>
                                    <div id="preview_status" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>Kepala Toko</label>
                                    <div id="preview_kepala_toko" class="preview-value">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <h5 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-image text-primary"></i> Foto Toko
                    </h5>
                    <div class="preview-section">
                        <div id="preview_foto_container" style="display: none;">
                            <img id="preview_foto" src="" alt="Preview Foto" class="preview-photo">
                        </div>
                        <div id="preview_foto_empty" class="text-muted">
                            <i class="fas fa-times-circle"></i> Tidak ada foto
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                <!-- Action Buttons -->
                <div class="d-flex" style="gap: 10px;">
                    <button type="button" class="btn btn-success" id="btnSubmit">
                        <i class="fas fa-check"></i> Simpan ke Database
                    </button>
                    <button type="button" class="btn btn-warning" id="btnEdit">
                        <i class="fas fa-edit"></i> Edit Data
                    </button>
                    <a href="{{ route('superadmin.toko.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Form Improvements */
        .form-control:focus,
        .custom-file-input:focus+.custom-file-label {
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

        /* Photo Container */
        .photo-container {
            max-width: 300px;
        }

        .photo-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .photo-item img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
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

        /* Preview Section */
        .preview-section {
            background: #f8f9fc;
            padding: 20px;
            border-radius: 8px;
        }

        .preview-item label {
            font-size: 0.875rem;
            color: #858796;
            margin-bottom: 5px;
            display: block;
        }

        .preview-value {
            font-size: 0.95rem;
            color: #5a5c69;
            font-weight: 500;
        }

        .preview-photo {
            max-width: 300px;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .invalid-feedback {
            font-size: 0.875rem;
        }
    </style>


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // ===========================
        // LEAFLET MAP INITIALIZATION
        // ===========================
        let map;
        let marker;

        // Default center: Bandung
        const defaultLat = -6.9175;
        const defaultLng = 107.6191;

        // Initialize map
        document.addEventListener('DOMContentLoaded', function() {
            // Create map
            map = L.map('map').setView([defaultLat, defaultLng], 13);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Add click event to map
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                // Update input fields
                document.getElementById('latitude').value = lat.toFixed(7);
                document.getElementById('longitude').value = lng.toFixed(7);

                // Remove old marker if exists
                if (marker) {
                    map.removeLayer(marker);
                }

                // Add new marker
                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);

                // Update coordinates when marker is dragged
                marker.on('dragend', function(e) {
                    const pos = marker.getLatLng();
                    document.getElementById('latitude').value = pos.lat.toFixed(7);
                    document.getElementById('longitude').value = pos.lng.toFixed(7);
                });
            });

            // If old values exist (validation error), set marker
            const oldLat = document.getElementById('latitude').value;
            const oldLng = document.getElementById('longitude').value;

            if (oldLat && oldLng) {
                const lat = parseFloat(oldLat);
                const lng = parseFloat(oldLng);

                map.setView([lat, lng], 15);
                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);

                marker.on('dragend', function(e) {
                    const pos = marker.getLatLng();
                    document.getElementById('latitude').value = pos.lat.toFixed(7);
                    document.getElementById('longitude').value = pos.lng.toFixed(7);
                });
            }
        });
    </script>
    <script>
        let photoFile = null;

        // Update label file input & preview with realtime delete
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileName = file?.name || 'Pilih file...';
            document.querySelector('.custom-file-label').textContent = fileName;

            if (file) {
                photoFile = file;
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.getElementById('fotoPreview');
                    img.src = event.target.result;
                    document.getElementById('fotoPreviewContainer').style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                deletePhoto();
            }
        });

        // Delete photo function
        function deletePhoto() {
            const input = document.getElementById('foto');
            input.value = '';
            photoFile = null;

            const label = document.querySelector('.custom-file-label');
            label.textContent = 'Pilih file...';

            const container = document.getElementById('fotoPreviewContainer');
            container.style.opacity = '0.5';
            container.style.transition = 'all 0.3s';
            setTimeout(() => {
                container.style.display = 'none';
                container.style.opacity = '1';
            }, 300);
        }

        // Preview Button Handler
        document.getElementById('btnPreview').addEventListener('click', function() {
            const form = document.getElementById('tokoForm');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const namaToko = document.getElementById('nama_toko').value;
            const alamat = document.getElementById('alamat').value;
            const postalCode = document.getElementById('postal_code').value;
            const telepon = document.getElementById('telepon').value;
            const email = document.getElementById('email').value;
            const googlemap = document.getElementById('googlemap').value;
            const googlemapIframe = document.getElementById('googlemap_iframe').value;
            const latitude = document.getElementById('latitude').value;
            const longitude = document.getElementById('longitude').value;
            const status = document.getElementById('status').value;
            const kepalaTokoSelect = document.getElementById('kepala_toko_id');
            const kepalaTokoId = kepalaTokoSelect.value;

            document.getElementById('preview_nama_toko').textContent = namaToko || '-';
            document.getElementById('preview_alamat').textContent = alamat || '-';
            document.getElementById('preview_postal_code').textContent = postalCode || '-'; 
            document.getElementById('preview_telepon').textContent = telepon || '-';
            document.getElementById('preview_email').textContent = email || '-';

            if (googlemap) {
                document.getElementById('preview_googlemap').innerHTML =
                    `<a href="${googlemap}" target="_blank" class="text-primary"><i class="fas fa-map-marked-alt"></i> Lihat Lokasi</a>`;
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

            if (latitude && longitude) {
                document.getElementById('preview_koordinat').innerHTML =
                    `<strong>Lat:</strong> ${latitude}<br><strong>Lng:</strong> ${longitude}`;
            } else {
                document.getElementById('preview_koordinat').innerHTML =
                    '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Belum diisi</span>';
            }

            let statusBadge = '';
            if (kepalaTokoId) {
                statusBadge =
                    '<span class="badge badge-success"><i class="fas fa-circle" style="font-size: 7px;"></i> Aktif</span>';
            } else if (status === 'aktif') {
                statusBadge =
                    '<span class="badge badge-success"><i class="fas fa-circle" style="font-size: 7px;"></i> Aktif</span>';
            } else {
                statusBadge =
                    '<span class="badge badge-secondary"><i class="fas fa-circle" style="font-size: 7px;"></i> Tidak Aktif</span>';
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

            if (photoFile) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview_foto').src = e.target.result;
                    document.getElementById('preview_foto_container').style.display = 'block';
                    document.getElementById('preview_foto_empty').style.display = 'none';
                }
                reader.readAsDataURL(photoFile);
            } else {
                document.getElementById('preview_foto_container').style.display = 'none';
                document.getElementById('preview_foto_empty').style.display = 'block';
            }

            document.getElementById('formCard').style.display = 'none';
            document.getElementById('previewCard').style.display = 'block';
            document.getElementById('previewCard').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });

        document.getElementById('btnEdit').addEventListener('click', function() {
            document.getElementById('formCard').style.display = 'block';
            document.getElementById('previewCard').style.display = 'none';
            document.getElementById('formCard').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });

        document.getElementById('btnSubmit').addEventListener('click', function() {
            if (confirm('Yakin data toko sudah benar? Data akan disimpan ke database!')) {
                document.getElementById('tokoForm').submit();
            }
        });
    </script>
</x-admin-layout>
