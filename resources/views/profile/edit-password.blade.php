<x-admin-layout title="Ubah Password">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-key text-primary"></i> Ubah Password
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
                            <i class="fas fa-lock"></i> Form Ubah Password
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Password Saat Ini -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold text-dark mb-3">
                                    <i class="fas fa-shield-alt text-primary"></i> Verifikasi
                                </h5>

                                <div class="form-group">
                                    <label for="current_password" class="font-weight-bold">
                                        Password Saat Ini <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                            id="current_password"
                                            name="current_password" 
                                            class="form-control @error('current_password') is-invalid @enderror" 
                                            placeholder="Masukkan password saat ini"
                                            required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Password Baru -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold text-dark mb-3">
                                    <i class="fas fa-key text-primary"></i> Password Baru
                                </h5>

                                <div class="form-group">
                                    <label for="password" class="font-weight-bold">
                                        Password Baru <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                            id="password"
                                            name="password" 
                                            class="form-control @error('password') is-invalid @enderror" 
                                            placeholder="Masukkan password baru"
                                            required
                                            minlength="8">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle"></i> Minimal 8 karakter
                                    </small>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror

                                    <!-- Password Strength Indicator -->
                                    <div class="mt-3 p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                                        <small class="text-muted d-block mb-2"><strong>Kekuatan Password:</strong></small>
                                        <div class="progress" style="height: 8px;">
                                            <div id="passwordStrength" class="progress-bar" role="progressbar" 
                                                style="width: 0%; transition: all 0.3s;" 
                                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small id="strengthText" class="text-muted d-block mt-2">-</small>
                                    </div>
                                </div>

                                <!-- Konfirmasi Password -->
                                <div class="form-group">
                                    <label for="password_confirmation" class="font-weight-bold">
                                        Konfirmasi Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                            id="password_confirmation"
                                            name="password_confirmation" 
                                            class="form-control" 
                                            placeholder="Ulangi password baru"
                                            required
                                            minlength="8">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <small id="matchText" class="d-block mt-2"></small>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Security Tips -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold text-dark mb-3">
                                    <i class="fas fa-shield-alt text-primary"></i> Tips Keamanan
                                </h5>
                                <div class="alert border-0" style="background-color: #e7f3ff;">
                                    <strong><i class="fas fa-lightbulb text-primary"></i> Tips Password Aman:</strong>
                                    <ul class="mb-0 mt-2 pl-4">
                                        <li>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                                        <li>Hindari menggunakan data pribadi (nama, tanggal lahir, dll)</li>
                                        <li>Jangan gunakan password yang sama dengan akun lain</li>
                                        <li>Perbarui password secara berkala (3-6 bulan)</li>
                                        <li>Jangan pernah bagikan password kepada orang lain</li>
                                    </ul>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Actions -->
                            <div class="d-flex" style="gap: 10px;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Ubah Password
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
                            <i class="fas fa-question-circle text-primary"></i> Bantuan
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
                            <code class="d-block mt-1">Aman@123Kuat</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Form improvements */
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
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

        .input-group-append .btn {
            border-color: #d1d3e2;
        }

        .input-group-append .btn:hover {
            background-color: #f8f9fc;
            transform: none;
        }
    </style>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                button.classList.remove('fa-eye');
                button.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                button.classList.remove('fa-eye-slash');
                button.classList.add('fa-eye');
            }
        }

        // Password strength checker
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('strengthText');

        passwordInput.addEventListener('input', function() {
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
            strengthText.style.fontWeight = 'bold';
        });

        // Password match checker
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const matchText = document.getElementById('matchText');

        confirmPasswordInput.addEventListener('input', function() {
            if (passwordInput.value === this.value && this.value !== '') {
                matchText.innerHTML = '<i class="fas fa-check-circle"></i> Password cocok';
                matchText.style.color = '#28a745';
                matchText.style.fontWeight = 'bold';
            } else if (this.value !== '') {
                matchText.innerHTML = '<i class="fas fa-times-circle"></i> Password tidak cocok';
                matchText.style.color = '#dc3545';
                matchText.style.fontWeight = 'bold';
            } else {
                matchText.textContent = '';
            }
        });

        passwordInput.addEventListener('input', function() {
            if (confirmPasswordInput.value !== '') {
                confirmPasswordInput.dispatchEvent(new Event('input'));
            }
        });
    </script>
</x-admin-layout>