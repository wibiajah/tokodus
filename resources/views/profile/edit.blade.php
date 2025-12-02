<x-admin-layout title="Edit Profil">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 font-weight-bold text-dark">Edit Profil</h1>
                        <p class="text-muted mb-0">Perbarui informasi akun Anda</p>
                    </div>
                    <a href="{{ route('profile.show') }}" class="btn btn-sm" style="background-color: #f8f9fa; color: #224abe; border: 1px solid #224abe;">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0" role="alert" style="border-left: 4px solid #dc3545;">
                <i class="fas fa-exclamation-circle"></i> Terjadi kesalahan:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        <!-- Edit Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-top: 4px solid #224abe;">
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Foto Profil Section -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark mb-3">
                                    <i class="fas fa-image" style="color: #224abe;"></i> Foto Profil
                                </label>
                                <div class="text-center mb-4">
                                    <img id="previewFoto" 
                                         src="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : asset('img/default-avatar.png') }}" 
                                         alt="Foto Profil" 
                                         class="rounded-circle border" 
                                         style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #224abe;">
                                </div>
                                <div class="custom-file">
                                    <input type="file" 
                                           class="custom-file-input @error('foto_profil') is-invalid @enderror" 
                                           id="fotoProfil" 
                                           name="foto_profil"
                                           accept="image/*">
                                    <label class="custom-file-label" for="fotoProfil">Pilih Foto...</label>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle" style="color: #224abe;"></i> Format: JPG, PNG, GIF. Maksimal 2MB
                                </small>
                                @error('foto_profil')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                @if($user->foto_profil)
                                    <div class="mt-3 p-3" style="background-color: #f8fafb; border-radius: 4px;">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="remove_foto">
                                            <span class="custom-control-label text-muted">Hapus foto profil saat ini</span>
                                        </label>
                                    </div>
                                @endif
                            </div>

                            <hr class="my-4">

                            <!-- Nama -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark">
                                    <i class="fas fa-user" style="color: #224abe;"></i> Nama <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}"
                                       placeholder="Masukkan nama lengkap"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark">
                                    <i class="fas fa-envelope" style="color: #224abe;"></i> Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}"
                                       placeholder="Masukkan email"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- No Telepon -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark">
                                    <i class="fas fa-phone" style="color: #224abe;"></i> No. Telepon
                                </label>
                                <input type="text" 
                                       name="no_telepon" 
                                       class="form-control form-control-lg @error('no_telepon') is-invalid @enderror" 
                                       value="{{ old('no_telepon', $user->no_telepon) }}"
                                       placeholder="08XX-XXXX-XXXX">
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <!-- Info Readonly -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark">
                                        <i class="fas fa-user-tag" style="color: #224abe;"></i> Role
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           value="{{ \App\Models\User::ROLES[$user->role] ?? $user->role }}"
                                           disabled
                                           style="background-color: #f8fafb; color: #666;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark">
                                        <i class="fas fa-store" style="color: #224abe;"></i> Toko
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           value="{{ $user->toko->nama_toko ?? 'Head Office' }}"
                                           disabled
                                           style="background-color: #f8fafb; color: #666;">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Actions -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-lg font-weight-bold" style="background-color: #224abe; color: white; border: none; flex: 1;">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('profile.show') }}" class="btn btn-lg font-weight-bold" style="background-color: #f8f9fa; color: #666; border: 1px solid #ddd; flex: 1;">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="background-color: #f8fafb;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-lightbulb" style="color: #224abe;"></i> Informasi
                        </h5>
                        <div class="alert alert-info border-0" style="background-color: #e7f3ff; color: #224abe; padding: 12px;">
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

    <script>
        // Preview foto sebelum upload
        document.getElementById('fotoProfil').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('previewFoto').src = event.target.result;
                };
                reader.readAsDataURL(file);
                
                // Update label
                document.querySelector('.custom-file-label').textContent = file.name;
            }
        });
    </script>

</x-admin-layout>