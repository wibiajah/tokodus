<x-guest-layout title="Login">
    <div class="auth-overlay">
        <div class="container" id="authContainer">
            <button type="button" class="close-icon" onclick="window.history.back()">
                <i class="fas fa-times"></i>
            </button>

            <!-- FORM REGISTER -->
            <div class="form-container sign-up-container">
                <form action="{{ route('register') }}" method="POST" novalidate>
                    @csrf
                    <h1>Buat Akun</h1>

                    <div class="social-login">
                        <button type="button" class="social-btn google" aria-label="Login with Google">
                            <i class="fab fa-google"></i>
                        </button>
                        <button type="button" class="social-btn facebook" aria-label="Login with Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                    </div>

                    <div class="name-fields">
                        <input type="text" name="firstname" placeholder="Nama Depan" 
                               value="{{ old('firstname') }}" />
                        <input type="text" name="lastname" placeholder="Nama Belakang" 
                               value="{{ old('lastname') }}" />
                    </div>

                    <input type="text" name="username" placeholder="Username" 
                           value="{{ old('username') }}" required />

                    <input type="email" name="email" placeholder="Email" 
                           value="{{ old('email') }}" required />

                    <div class="password-field">
                        <input type="password" name="password" id="regPassword" 
                               placeholder="Kata Sandi" required />
                        <span class="toggle-password" onclick="togglePassword('regPassword', this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    <div class="password-field">
                        <input type="password" name="password_confirmation" id="regConfirmPassword" 
                               placeholder="Konfirmasi Kata Sandi" required />
                        <span class="toggle-password" onclick="togglePassword('regConfirmPassword', this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    @if ($errors->any() && (old('username') || old('firstname') || old('lastname')))
                        <div class="auth-error">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <button type="submit">Daftar</button>
                </form>
            </div>

            <!-- FORM LOGIN -->
            <div class="form-container sign-in-container">
                <form action="{{ route('login') }}" method="POST" novalidate id="loginForm">
                    @csrf
                    <h1>Masuk</h1>

                    <div class="social-login">
                        <button type="button" class="social-btn google" aria-label="Login with Google">
                            <i class="fab fa-google"></i>
                        </button>
                        <button type="button" class="social-btn facebook" aria-label="Login with Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                    </div>

                    <input type="email" name="email" placeholder="Email" 
                           value="{{ old('email') }}" required id="emailInput" />

                    <div class="password-field">
                        <input type="password" name="password" id="loginPassword" 
                               placeholder="Kata Sandi" required />
                        <span class="toggle-password" onclick="togglePassword('loginPassword', this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    <div class="login-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                            Tetap masuk
                        </label>
                        <a href="#" class="forgot-password" onclick="event.preventDefault()">
                            Lupa kata sandi?
                        </a>
                    </div>

                    @if ($errors->has('email') && !old('username'))
                        <div class="auth-error" id="errorMessage">
                            {{ $errors->first('email') }}
                            
                            {{-- ✅ COUNTDOWN TIMER --}}
                                  @if (session('throttle_seconds'))
                                    <div class="countdown-wrapper">
                                        <i class="fas fa-clock"></i>
                                        <span>Silakan tunggu <strong id="countdown">{{ session('throttle_seconds') }}</strong> detik</span>
                                    </div>
                                @endif
                        </div>
                    @endif

                    <button type="submit" id="loginButton">Masuk</button>
                </form>
            </div>

            <!-- OVERLAY -->
            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h1>Selamat Datang Kembali!</h1>
                        <p>Untuk tetap terhubung, silakan masuk dengan akun Anda</p>
                        <button type="button" class="ghost" onclick="togglePanel(false)">
                            Masuk
                        </button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h1>Halo, Teman Baru!</h1>
                        <p>Masukkan data pribadi Anda dan mulai perjalanan bersama kami</p>
                        <button type="button" class="ghost" onclick="togglePanel(true)">
                            Daftar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <style>
            :root {
                --primary: #ffffff;
                --secondary: #048bd4;
                --thirdary: #0442d4;
                --edition: #1e293b;
                --contrast: #ffffff;
            }

            .auth-error {
                    background-color: #fee2e2;
                    color: #991b1b;
                    padding: 12px 15px;
                    border-radius: 8px;
                    margin-top: 10px;
                    font-size: 13px;
                    width: 100%;
                    text-align: center;
                    border: 1px solid #fecaca;
                }

                .countdown-wrapper {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    margin-top: 8px;
                    padding-top: 8px;
                    border-top: 1px solid #fecaca;
                    font-size: 14px;
                    color: #991b1b;
                }

                .countdown-wrapper i {
                    font-size: 16px;
                    color: #dc2626;
                    animation: pulse 1.5s ease-in-out infinite;
                }

                .countdown-wrapper strong {
                    font-size: 18px;
                    font-weight: 700;
                    color: #dc2626;
                    min-width: 25px;
                    display: inline-block;
                    text-align: center;
                }

                @keyframes pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.5; }
                }

                /* Style untuk button disabled */
                button[type="submit"]:disabled {
                    opacity: 0.5 !important;
                    cursor: not-allowed !important;
                    background-color: #94a3b8 !important;
                }

                button[type="submit"]:disabled:hover {
                    transform: none !important;
                    background-color: #94a3b8 !important;
                }

            .auth-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }

            .container {
                background-color: var(--contrast);
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
                position: relative;
                overflow: hidden;
                width: 860px;
                max-width: 95%;
                min-height: 520px;
                transition: all 0.6s ease-in-out;
                display: flex;
            }

            .close-icon {
                position: absolute;
                top: 18px;
                right: 22px;
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--thirdary);
                cursor: pointer;
                z-index: 101;
                transition: transform 0.2s ease;
                background: none;
                border: none;
            }
            .close-icon:hover {
                transform: scale(1.1);
            }

            .form-container {
                position: absolute;
                top: 0;
                height: 100%;
                transition: all 0.6s ease-in-out;
            }

            .sign-in-container {
                left: 0;
                width: 50%;
                z-index: 2;
            }

            .sign-up-container {
                left: 0;
                width: 50%;
                opacity: 0;
                z-index: 1;
            }

            .container.right-panel-active .sign-in-container {
                transform: translateX(100%);
                opacity: 0;
            }

            .container.right-panel-active .sign-up-container {
                transform: translateX(100%);
                opacity: 1;
                z-index: 5;
            }

            form {
                background-color: var(--contrast);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 0 50px;
                height: 100%;
                text-align: center;
                transition: all 0.3s ease;
            }

            form h1 {
                font-weight: 800;
                font-size: 28px;
                color: var(--edition);
                margin-bottom: 20px;
            }

            form input {
                background-color: #fff;
                border: 1px solid #e2e8f0;
                padding: 12px 15px;
                margin: 8px 0;
                width: 100%;
                border-radius: 8px;
                font-size: 14px;
                color: #333;
                outline: none;
                transition: all 0.2s;
            }
            form input:focus {
                border-color: var(--secondary);
                box-shadow: 0 0 0 3px rgba(4, 139, 212, 0.1);
            }

            form button[type="submit"] {
                border-radius: 25px;
                border: none;
                background-color: var(--secondary);
                color: var(--contrast);
                font-size: 14px;
                font-weight: 600;
                padding: 12px 45px;
                margin-top: 20px;
                letter-spacing: 0.5px;
                cursor: pointer;
                transition: all 0.2s ease-in-out;
            }
            form button[type="submit"]:hover {
                background-color: var(--thirdary);
                transform: scale(1.05);
            }

            .overlay-container {
                position: absolute;
                top: 0;
                left: 50%;
                width: 50%;
                height: 100%;
                overflow: hidden;
                transition: transform 0.6s ease-in-out;
                z-index: 100;
            }

            .container.right-panel-active .overlay-container {
                transform: translateX(-100%);
            }

            .overlay {
                background: linear-gradient(to right, var(--edition), var(--secondary));
                background-repeat: no-repeat;
                background-size: cover;
                color: var(--contrast);
                position: relative;
                left: -100%;
                height: 100%;
                width: 200%;
                transform: translateX(0);
                transition: transform 0.6s ease-in-out;
            }

            .container.right-panel-active .overlay {
                transform: translateX(50%);
            }

            .overlay-panel {
                position: absolute;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                top: 0;
                height: 100%;
                width: 50%;
                padding: 0 40px;
                transition: transform 0.6s ease-in-out;
            }

            .overlay-left {
                transform: translateX(-20%);
            }
            .container.right-panel-active .overlay-left {
                transform: translateX(0);
            }

            .overlay-right {
                right: 0;
            }
            .container.right-panel-active .overlay-right {
                transform: translateX(20%);
            }

            .overlay-panel h1 {
                font-size: 40px;
                font-weight: 700;
                margin-bottom: 10px;
                color: var(--primary);
            }
            .overlay-panel p {
                font-size: 14px;
                line-height: 1.5;
                margin-bottom: 20px;
                max-width: 260px;
            }

            button.ghost {
                background-color: transparent;
                border: 2px solid #fff;
                color: #fff;
                border-radius: 25px;
                padding: 10px 40px;
                font-size: 14px;
                font-weight: 600;
                letter-spacing: 0.5px;
                cursor: pointer;
                transition: all 0.3s ease-in-out;
            }

            button.ghost:hover {
                background-color: #fff;
                color: var(--thirdary);
                transform: scale(1.05);
            }

            .password-field {
                position: relative;
                width: 100%;
            }
            .password-field input {
                width: 100%;
                padding-right: 40px;
            }
            .toggle-password {
                position: absolute;
                top: 50%;
                right: 12px;
                transform: translateY(-50%);
                cursor: pointer;
                color: #555;
                font-size: 18px;
            }
            .toggle-password:hover {
                color: var(--thirdary);
            }

            .login-options {
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 10px;
                font-size: 14px;
            }

            .remember-me {
                display: flex;
                align-items: center;
                gap: 6px;
                color: var(--secondary);
                user-select: none;
                cursor: pointer;
            }

            .remember-me input[type="checkbox"] {
                accent-color: var(--thirdary);
                width: 16px;
                height: 16px;
                cursor: pointer;
                margin: 0;
            }

            .forgot-password {
                color: var(--thirdary);
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s;
            }
            .forgot-password:hover {
                text-decoration: underline;
            }

            .social-login {
                display: flex;
                justify-content: center;
                gap: 15px;
                width: 100%;
                margin-bottom: 20px;
            }

            .social-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 45px;
                height: 45px;
                border-radius: 50%;
                border: 1px solid #ddd;
                cursor: pointer;
                background-color: #fff;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                padding: 0;
            }
            .social-btn:hover {
                transform: scale(1.05);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
            .social-btn i {
                font-size: 18px;
            }
            .social-btn.google i {
                color: #DB4437;
            }
            .social-btn.facebook i {
                color: #4267B2;
            }

            .name-fields {
                display: flex;
                gap: 10px;
                width: 100%;
            }

            .name-fields input {
                flex: 1;
            }

            .auth-error {
                background-color: #fee;
                color: #c33;
                padding: 10px;
                border-radius: 8px;
                margin-top: 10px;
                font-size: 13px;
                width: 100%;
                text-align: center;
            }

            @media (max-width: 768px) {
                .container {
                    width: 95%;
                    min-height: 600px;
                }

                .overlay-container {
                    display: none;
                }

                .form-container {
                    position: relative !important;
                    width: 100% !important;
                    left: 0 !important;
                    opacity: 1 !important;
                    transform: none !important;
                }

                .sign-up-container {
                    display: none;
                }

                .container.right-panel-active .sign-up-container {
                    display: flex;
                }

                .container.right-panel-active .sign-in-container {
                    display: none;
                }
            }
        </style>

   <script>
        function togglePanel(showRegister) {
        const container = document.getElementById('authContainer');
        if (showRegister) {
            container.classList.add('right-panel-active');
        } else {
            container.classList.remove('right-panel-active');
        }
    }

    function togglePassword(inputId, toggleElement) {
        const passwordInput = document.getElementById(inputId);
        const icon = toggleElement.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // ✅ COUNTDOWN TIMER LOGIC - DIPERBAIKI
    @if (session('throttle_seconds'))
        document.addEventListener('DOMContentLoaded', function() {
            let seconds = {{ session('throttle_seconds') }};
            const countdownEl = document.getElementById('countdown');
            const loginButton = document.getElementById('loginButton');
            const loginPasswordInput = document.getElementById('loginPassword');
            const emailInput = document.getElementById('emailInput');
            
            // Disable form saat countdown
            if (loginButton) {
                loginButton.disabled = true;
            }
            if (emailInput) {
                emailInput.disabled = true;
            }
            if (loginPasswordInput) {
                loginPasswordInput.disabled = true;
            }
            
            const interval = setInterval(() => {
                seconds--;
                
                if (countdownEl) {
                    countdownEl.textContent = seconds;
                }

                if (seconds <= 0) {
                    clearInterval(interval);
                    
                    // Enable form kembali
                    if (loginButton) {
                        loginButton.disabled = false;
                    }
                    if (emailInput) {
                        emailInput.disabled = false;
                    }
                    if (loginPasswordInput) {
                        loginPasswordInput.disabled = false;
                    }
                    
                    // Hapus error message
                    const errorMessage = document.getElementById('errorMessage');
                    if (errorMessage) {
                        errorMessage.style.transition = 'opacity 0.3s ease';
                        errorMessage.style.opacity = '0';
                        setTimeout(() => errorMessage.remove(), 300);
                    }
                }
            }, 1000);
        });
    @endif

    // Jika ada error validasi pada register, tampilkan form register
    @if (old('username') || old('firstname') || old('lastname'))
        document.addEventListener('DOMContentLoaded', function() {
            togglePanel(true);
        });
    @endif
    </script>
</x-guest-layout>