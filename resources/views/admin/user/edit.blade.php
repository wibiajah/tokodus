<x-admin-layout title="Edit User">
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
            <a href="{{ route('user.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        @if(session('confirm_replace'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Penggantian Kepala Toko</h5>
                <p>{!! session('confirm_replace')['message'] !!}</p>
                <hr>
                <form action="{{ route('user.update', $user) }}" method="POST" class="d-inline">
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
                <a href="{{ route('user.edit', $user) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Edit User</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('user.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Foto Profil -->
                    <div class="form-group">
                        <label for="foto_profil">Foto Profil</label>
                        
                        <!-- Current Photo -->
                        @if($user->foto_profil)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" style="max-width: 150px; border-radius: 8px;" id="currentPhoto">
                                <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox" class="custom-control-input" id="remove_foto" name="remove_foto" value="1">
                                    <label class="custom-control-label text-danger" for="remove_foto">
                                        <i class="fas fa-trash"></i> Hapus foto profil
                                    </label>
                                </div>
                            </div>
                        @endif

                        <!-- Upload New Photo -->
                        <div class="custom-file">
                            <input type="file" 
                                class="custom-file-input @error('foto_profil') is-invalid @enderror" 
                                id="foto_profil" 
                                name="foto_profil"
                                accept="image/jpeg,image/png,image/jpg">
                            <label class="custom-file-label" for="foto_profil">{{ $user->foto_profil ? 'Ganti foto...' : 'Pilih foto...' }}</label>
                            @error('foto_profil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            Format: JPG, JPEG, PNG. Maksimal 2MB
                        </small>
                        
                        <!-- Preview New Photo -->
                        <div class="mt-2">
                            <img id="preview" src="#" alt="Preview" style="display: none; max-width: 150px; border-radius: 8px;">
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', $user->name) }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', $user->email) }}"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
    <div class="form-group">
        <label for="no_telepon">No. Telepon</label>
        <input type="text" 
            class="form-control @error('no_telepon') is-invalid @enderror" 
            id="no_telepon" 
            name="no_telepon" 
            value="{{ old('no_telepon', $user->no_telepon) }}"
            placeholder="08123456789">
        @error('no_telepon')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">
            Format: 08123456789 atau +628123456789
        </small>
    </div>
</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password Baru</label>
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
                                <label for="password_confirmation">Konfirmasi Password Baru</label>
                                <input type="password" 
                                    class="form-control" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Role <span class="text-danger">*</span></label>
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
                                <label for="toko_id">Penempatan Toko</label>
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

                    <hr>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Preview foto profil baru
        const fotoInput = document.getElementById('foto_profil');
        const preview = document.getElementById('preview');
        const currentPhoto = document.getElementById('currentPhoto');
        const removeFoto = document.getElementById('remove_foto');
        const label = document.querySelector('.custom-file-label');

        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                label.textContent = file.name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (currentPhoto) currentPhoto.style.display = 'none';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                if (currentPhoto) currentPhoto.style.display = 'block';
                label.textContent = '{{ $user->foto_profil ? "Ganti foto..." : "Pilih foto..." }}';
            }
        });

        // Handle remove foto checkbox
        if (removeFoto) {
            removeFoto.addEventListener('change', function() {
                if (this.checked) {
                    if (currentPhoto) currentPhoto.style.opacity = '0.3';
                    fotoInput.disabled = true;
                } else {
                    if (currentPhoto) currentPhoto.style.opacity = '1';
                    fotoInput.disabled = false;
                }
            });
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