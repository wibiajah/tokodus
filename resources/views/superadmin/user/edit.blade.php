<x-admin-layout title="Edit User">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-edit text-primary"></i> Edit User
            </h1>
            <a href="{{ route('superadmin.user.index') }}" class="btn btn-secondary btn-icon-split shadow-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- Confirmation Alert -->
        @if(session('confirm_replace'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Penggantian Kepala Toko</h5>
                <p>{!! session('confirm_replace')['message'] !!}</p>
                <hr>
                <form action="{{ route('superadmin.user.update', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="name" value="{{ old('name') }}">
                    <input type="hidden" name="email" value="{{ old('email') }}">
                    <input type="hidden" name="role" value="{{ old('role') }}">
                    <input type="hidden" name="toko_id" value="{{ old('toko_id') }}">
                    <input type="hidden" name="confirm_replace" value="1">
                    
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check"></i> Ya, Ganti Kepala Toko
                    </button>
                </form>
                <a href="{{ route('superadmin.user.edit', $user) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        @endif

        <!-- Form Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-edit"></i> Form Edit User: {{ $user->name }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('superadmin.user.update', $user) }}" method="POST" enctype="multipart/form-data">
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
                            @endif

                            <div class="custom-file">
                                <input type="file" 
                                    class="custom-file-input @error('foto_profil') is-invalid @enderror" 
                                    id="foto_profil" 
                                    name="foto_profil"
                                    accept="image/jpeg,image/png,image/jpg">
                                <label class="custom-file-label" for="foto_profil">{{ $user->foto_profil ? 'Ganti foto...' : 'Pilih foto...' }}</label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Format: JPG, JPEG, PNG. Maksimal 2MB
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" 
                                        class="form-control @error('name') is-invalid @enderror" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name', $user->name) }}"
                                        placeholder="Contoh: John Doe"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="font-weight-bold">Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email', $user->email) }}"
                                        placeholder="Contoh: user@email.com"
                                        required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="no_telepon" class="font-weight-bold">No. Telepon</label>
                            <input type="text" 
                                class="form-control @error('no_telepon') is-invalid @enderror" 
                                id="no_telepon" 
                                name="no_telepon" 
                                value="{{ old('no_telepon', $user->no_telepon) }}"
                                placeholder="Contoh: 08123456789">
                            @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Format: 08123456789 atau +628123456789
                            </small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Keamanan -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-lock text-primary"></i> Keamanan
                        </h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="font-weight-bold">Password Baru</label>
                                    <input type="password" 
                                        class="form-control @error('password') is-invalid @enderror" 
                                        id="password" 
                                        name="password" 
                                        placeholder="Kosongkan jika tidak ingin mengubah">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Minimal 8 karakter</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="font-weight-bold">Konfirmasi Password Baru</label>
                                    <input type="password" 
                                        class="form-control" 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Role & Penempatan -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-user-tag text-primary"></i> Role & Penempatan
                        </h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class="font-weight-bold">Role <span class="text-danger">*</span></label>
                                    <select class="form-control @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                        <option value="">-- Pilih Role --</option>
                                        @foreach($availableRoles as $key => $value)
                                            <option value="{{ $key }}" {{ old('role', $user->role) === $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="toko_id" class="font-weight-bold">Penempatan Toko</label>
                                    <select class="form-control @error('toko_id') is-invalid @enderror" 
                                        id="toko_id" 
                                        name="toko_id">
                                        <option value="">-- Head Office (Default) --</option>
                                        @foreach($tokos as $toko)
                                            @php
                                                $kepalaToko = $toko->kepalaToko;
                                                $hasKepala = $kepalaToko !== null;
                                                $isCurrentUser = $kepalaToko && $kepalaToko->id === $user->id;
                                            @endphp
                                            <option value="{{ $toko->id }}" 
                                                {{ old('toko_id', $user->toko_id) == $toko->id ? 'selected' : '' }}
                                                data-has-kepala="{{ ($hasKepala && !$isCurrentUser) ? 'true' : 'false' }}"
                                                data-kepala-name="{{ $hasKepala && !$isCurrentUser ? $kepalaToko->name : '' }}">
                                                {{ $toko->nama_toko }}
                                                @if($hasKepala && !$isCurrentUser)
                                                    (Kepala: {{ $kepalaToko->name }})
                                                @elseif($isCurrentUser)
                                                    (Kepala Toko Saat Ini)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('toko_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        💡 Kosongkan untuk menempatkan di <strong>Head Office</strong>
                                    </small>
                                    <div id="kepalaTokoWarning" class="alert alert-info mt-2" style="display: none;">
                                        <i class="fas fa-info-circle"></i>
                                        <span id="warningText"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Buttons -->
                    <div class="d-flex" style="gap: 10px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update User
                        </button>
                        <a href="{{ route('superadmin.user.index') }}" class="btn btn-secondary">
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

        /* Photo Container */
        .photo-container {
            max-width: 200px;
        }

        .photo-item {
            position: relative;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 150px;
            height: 150px;
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
                    photoContainer.remove();
                }, 300);
                
                // Remove keep_foto input (this signals backend to delete)
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

        // Validasi Kepala Toko
        const roleSelect = document.getElementById('role');
        const tokoSelect = document.getElementById('toko_id');
        const warningDiv = document.getElementById('kepalaTokoWarning');
        const warningText = document.getElementById('warningText');

        function checkKepalaToko() {
            const role = roleSelect.value;
            const selectedOption = tokoSelect.options[tokoSelect.selectedIndex];
            
            if (role === 'kepala_toko' && tokoSelect.value) {
                const hasKepala = selectedOption.getAttribute('data-has-kepala') === 'true';
                const kepalaName = selectedOption.getAttribute('data-kepala-name');
                
                if (hasKepala) {
                    warningText.textContent = `Toko ini sudah memiliki Kepala Toko: ${kepalaName}. Jika Anda melanjutkan, user tersebut akan dipindahkan ke Head Office.`;
                    warningDiv.style.display = 'block';
                } else {
                    warningDiv.style.display = 'none';
                }
            } else {
                warningDiv.style.display = 'none';
            }
        }

        roleSelect.addEventListener('change', checkKepalaToko);
        tokoSelect.addEventListener('change', checkKepalaToko);
        checkKepalaToko();
    </script>
</x-admin-layout>