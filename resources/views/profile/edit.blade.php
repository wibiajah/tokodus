<x-admin-layout title="Edit Profil">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-edit text-primary"></i> Edit Profil
            </h1>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-icon-split shadow-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="fas fa-exclamation-circle"></i> Terjadi kesalahan:</h5>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        <!-- Form Card -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 bg-primary">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-edit"></i> Form Edit Profil
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Foto Profil -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold text-dark mb-3">
                                    <i class="fas fa-user-circle text-primary"></i> Foto Profil
                                </h5>

                                <div class="form-group">
                                    <label for="foto_profil" class="font-weight-bold">Foto Profil</label>
                                    
                                    <!-- Preview foto lama dengan tombol hapus realtime -->
                                    @if($user->foto_profil)
                                        <div id="existingPhotoContainer" class="photo-container mb-3">
                                            <div class="photo-item">
                                                <img src="{{ asset('storage/' . $user->foto_profil) }}" 
                                                     alt="Foto Profil {{ $user->name }}">
                                                <button type="button" class="delete-photo-btn" onclick="deleteExistingPhoto()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <input type="hidden" name="keep_foto" value="1" id="keep_foto">
                                            </div>
                                        </div>
                                    @else
                                        <div class="photo-container mb-3">
                                            <div class="photo-item">
                                                <img src="{{ asset('img/default-avatar.png') }}" 
                                                     alt="Default Avatar">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="custom-file">
                                        <input type="file" 
                                            class="custom-file-input @error('foto_profil') is-invalid @enderror" 
                                            id="foto_profil" 
                                            name="foto_profil"
                                            accept="image/*">
                                        <label class="custom-file-label" for="foto_profil">{{ $user->foto_profil ? 'Ganti foto...' : 'Pilih foto...' }}</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Format: JPG, PNG, GIF. Maksimal 2MB
                                    </small>
                                    @error('foto_profil')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    
                                    <!-- Preview foto baru -->
                                    <div id="newPhotoPreview" class="photo-container mt-3" style="display: none;"></div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Informasi Dasar -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold text-dark mb-3">
                                    <i class="fas fa-info-circle text-primary"></i> Informasi Dasar
                                </h5>

                                <!-- Nama -->
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" 
                                        name="name" 
                                        class="form-control @error('name') is-invalid @enderror" 
                                        id="name"
                                        value="{{ old('name', $user->name) }}"
                                        placeholder="Masukkan nama lengkap"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email" class="font-weight-bold">Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                        name="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        id="email"
                                        value="{{ old('email', $user->email) }}"
                                        placeholder="Masukkan email"
                                        required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- No Telepon -->
                                <div class="form-group">
                                    <label for="no_telepon" class="font-weight-bold">No. Telepon</label>
                                    <input type="text" 
                                        name="no_telepon" 
                                        class="form-control @error('no_telepon') is-invalid @enderror" 
                                        id="no_telepon"
                                        value="{{ old('no_telepon', $user->no_telepon) }}"
                                        placeholder="08XX-XXXX-XXXX">
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Info Readonly -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold text-dark mb-3">
                                    <i class="fas fa-id-badge text-primary"></i> Informasi Akun
                                </h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Role</label>
                                            <input type="text" 
                                                class="form-control" 
                                                value="{{ \App\Models\User::ROLES[$user->role] ?? $user->role }}"
                                                disabled
                                                style="background-color: #f8f9fc;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Toko</label>
                                            <input type="text" 
                                                class="form-control" 
                                                value="{{ $user->toko->nama_toko ?? 'Head Office' }}"
                                                disabled
                                                style="background-color: #f8f9fc;">
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Role dan Toko tidak dapat diubah. Hubungi admin untuk perubahan ini.
                                </small>
                            </div>

                            <hr class="my-3">

                            <!-- Actions -->
                            <div class="d-flex" style="gap: 10px;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4" style="background-color: #f8f9fc;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-lightbulb text-primary"></i> Informasi
                        </h5>
                        <div class="alert alert-info border-0 mb-0" style="background-color: #e7f3ff;">
                            <small>
                                <strong>Catatan:</strong><br>
                                • Role dan Toko tidak dapat diubah<br>
                                • Hubungi admin untuk mengubah role<br>
                                • Gunakan nama yang jelas dan profesional<br>
                                • Pastikan email aktif dan dapat diakses
                            </small>
                        </div>
                    </div>
                </div>
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

        /* Photo Container */
        .photo-container {
            max-width: 200px;
            margin: 0 auto;
        }

        .photo-item {
            position: relative;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 150px;
            height: 150px;
            margin: 0 auto;
        }

        .photo-item img {
            width: 100%;
            height: 100%;
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
    </style>

    <script>
        // Realtime Delete Existing Photo
        function deleteExistingPhoto() {
            const photoContainer = document.getElementById('existingPhotoContainer');
            if (photoContainer) {
                photoContainer.style.opacity = '0.5';
                photoContainer.style.transition = 'all 0.3s';
                setTimeout(() => {
                    // Replace with default avatar
                    photoContainer.innerHTML = `
                        <div class="photo-item">
                            <img src="{{ asset('img/default-avatar.png') }}" alt="Default Avatar">
                        </div>
                    `;
                    photoContainer.style.opacity = '1';
                }, 300);
                
                // Remove keep_foto input to signal deletion
                const keepInput = document.getElementById('keep_foto');
                if (keepInput) {
                    keepInput.remove();
                }
            }
        }

        // Update label & preview new photo with delete
        const fotoInput = document.getElementById('foto_profil');
        const label = document.querySelector('.custom-file-label');

        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
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
                label.textContent = '{{ $user->foto_profil ? "Ganti foto..." : "Pilih foto..." }}';
                container.style.display = 'none';
                container.innerHTML = '';
            }
        });

        // Delete New Photo
        function deleteNewPhoto() {
            fotoInput.value = '';
            label.textContent = '{{ $user->foto_profil ? "Ganti foto..." : "Pilih foto..." }}';
            
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