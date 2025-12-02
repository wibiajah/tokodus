<x-admin-layout title="Ubah Password">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 font-weight-bold text-dark">Ubah Password</h1>
                        <p class="text-muted mb-0">Perbarui kata sandi akun Anda untuk menjaga keamanan</p>
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

        <!-- Change Password Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-top: 4px solid #224abe;">
                    <div class="card-body p-4">
                        <form action="{{ route('profile.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Current Password -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">
                                    <i class="fas fa-lock" style="color: #224abe;"></i> Password Saat Ini <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="password" 
                                           id="currentPassword"
                                           name="current_password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           placeholder="Masukkan password saat ini"
                                           required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('currentPassword')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <!-- New Password -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">
                                    <i class="fas fa-key" style="color: #224abe;"></i> Password Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="password" 
                                           id="newPassword"
                                           name="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Masukkan password baru"
                                           required
                                           minlength="8">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('newPassword')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle" style="color: #224abe;"></i> Minimal 8 karakter
                                </small>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <!-- Password Strength Indicator -->
                                <div class="mt-3 p-3" style="background-color: #f8fafb; border-radius: 4px;">
                                    <small class="text-muted d-block mb-2"><strong>Kekuatan Password:</strong></small>
                                    <div class="progress" style="height: 6px;">
                                        <div id="passwordStrength" class="progress-bar" role="progressbar" 
                                             style="width: 0%; background-color: #224abe; transition: all 0.3s;" 
                                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small id="strengthText" class="text-muted d-block mt-2">-</small>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">
                                    <i class="fas fa-check-circle" style="color: #224abe;"></i> Konfirmasi Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="password" 
                                           id="confirmPassword"
                                           name="password_confirmation" 
                                           class="form-control" 
                                           placeholder="Ulangi password baru"
                                           required
                                           minlength="8">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPassword')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <small id="matchText" class="d-block mt-2"></small>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <!-- Security Tips -->
                            <div class="alert border-0 mb-4" style="background-color: #e7f3ff; border-left: 4px solid #224abe; color: #224abe;">
                                <strong>
                                    <i class="fas fa-shield-alt"></i> Tips Keamanan Password:
                                </strong>
                                <ul class="mb-0 mt-2 pl-4">
                                    <li>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                                    <li>Hindari menggunakan data pribadi (nama, tanggal lahir, dll)</li>
                                    <li>Jangan gunakan password yang sama dengan akun lain</li>
                                    <li>Perbarui password secara berkala (3-6 bulan)</li>
                                    <li>Jangan pernah bagikan password kepada orang lain</li>
                                </ul>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-lg font-weight-bold" style="background-color: #224abe; color: white; border: none; flex: 1;">
                                    <i class="fas fa-key"></i> Ubah Password
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
                            <i class="fas fa-question-circle" style="color: #224abe;"></i> Bantuan
                        </h5>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-2"><strong>Syarat Password:</strong></small>
                            <ul class="pl-3 text-muted" style="font-size: 13px;">
                                <li>Minimal 8 karakter</li>
                                <li>Berisi huruf besar (A-Z)</li>
                                <li>Berisi huruf kecil (a-z)</li>
                                <li>Berisi angka (0-9)</li>
                                <li>Berisi simbol (!@#$%)</li>
                            </ul>
                        </div>
                        <hr>
                        <div>
                            <small class="text-muted d-block"><strong>Contoh Password Kuat:</strong></small>
                            <small class="text-muted d-block">Aman@123Kuat</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            if (field.type === 'password') {
                field.type = 'text';
            } else {
                field.type = 'password';
            }
        }

        // Password strength checker
        const newPasswordInput = document.getElementById('newPassword');
        const strengthBar = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('strengthText');

        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let text = '';
            let color = '';

            if (password.length >= 8) strength += 20;
            if (password.length >= 12) strength += 20;
            if (/[a-z]/.test(password)) strength += 20;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 10;
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 10;

            if (strength < 20) {
                text = 'Sangat Lemah';
                color = '#dc3545';
            } else if (strength < 40) {
                text = 'Lemah';
                color = '#fd7e14';
            } else if (strength < 60) {
                text = 'Sedang';
                color = '#ffc107';
            } else if (strength < 80) {
                text = 'Kuat';
                color = '#20c997';
            } else {
                text = 'Sangat Kuat';
                color = '#28a745';
            }

            strengthBar.style.width = strength + '%';
            strengthBar.style.backgroundColor = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
        });

        // Password match checker
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const matchText = document.getElementById('matchText');

        confirmPasswordInput.addEventListener('input', function() {
            if (newPasswordInput.value === this.value && this.value !== '') {
                matchText.textContent = '✓ Password cocok';
                matchText.style.color = '#28a745';
            } else if (this.value !== '') {
                matchText.textContent = '✗ Password tidak cocok';
                matchText.style.color = '#dc3545';
            } else {
                matchText.textContent = '';
            }
        });
    </script>

</x-admin-layout>