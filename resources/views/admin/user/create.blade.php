<x-admin-layout title="Tambah User">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah User Baru</h1>
            <a href="{{ route('user.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Tambah User</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('user.store') }}" method="POST" id="createUserForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}"
                                    placeholder="Masukkan nama lengkap"
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
                                    value="{{ old('email') }}"
                                    placeholder="email@example.com"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Minimal 8 karakter"
                                    required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" 
                                    class="form-control" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    placeholder="Ulangi password"
                                    required>
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
                                        <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>
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
                                        @endphp
                                        <option value="{{ $toko->id }}" 
                                            {{ old('toko_id') == $toko->id ? 'selected' : '' }}
                                            data-has-kepala="{{ $hasKepala ? 'true' : 'false' }}"
                                            data-kepala-name="{{ $hasKepala ? $kepalaToko->name : '' }}">
                                            {{ $toko->nama_toko }}
                                            @if($hasKepala)
                                                (Kepala: {{ $kepalaToko->name }})
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
                                <!-- 🔥 Warning jika toko sudah punya kepala -->
                                <div id="kepalaTokoWarning" class="alert alert-warning mt-2" style="display: none;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span id="warningText"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
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
        // 🔥 Validasi jika role = kepala_toko dan toko sudah punya kepala
        const roleSelect = document.getElementById('role');
        const tokoSelect = document.getElementById('toko_id');
        const warningDiv = document.getElementById('kepalaTokoWarning');
        const warningText = document.getElementById('warningText');
        const form = document.getElementById('createUserForm');

        function checkKepalaToko() {
            const role = roleSelect.value;
            const selectedOption = tokoSelect.options[tokoSelect.selectedIndex];
            
            // Hanya cek jika role = kepala_toko DAN memilih toko cabang (bukan Head Office/kosong)
            if (role === 'kepala_toko' && tokoSelect.value) {
                const hasKepala = selectedOption.getAttribute('data-has-kepala') === 'true';
                const kepalaName = selectedOption.getAttribute('data-kepala-name');
                
                if (hasKepala) {
                    warningText.textContent = `Toko ini sudah memiliki Kepala Toko: ${kepalaName}. Pilih toko lain atau kosongkan untuk Head Office!`;
                    warningDiv.style.display = 'block';
                    return false;
                } else {
                    warningDiv.style.display = 'none';
                    return true;
                }
            } else {
                warningDiv.style.display = 'none';
                return true;
            }
        }

        roleSelect.addEventListener('change', checkKepalaToko);
        tokoSelect.addEventListener('change', checkKepalaToko);

        form.addEventListener('submit', function(e) {
            if (!checkKepalaToko()) {
                e.preventDefault();
                alert('Tidak dapat menambahkan Kepala Toko! Toko yang dipilih sudah memiliki Kepala Toko.');
            }
        });
    </script>
</x-admin-layout>