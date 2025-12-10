{{-- resources/views/components/login-modal.blade.php --}}
<div id="loginModal" class="auth-overlay-2">
    <div class="container-2" id="authContainer2">
        <button type="button" class="close-icon-2" onclick="closeLoginModal()">
            <i class="fas fa-times"></i>
        </button>

        <!-- FORM REGISTER -->
        <div class="form-container-2 sign-up-container-2">
            <form action="{{ route('register') }}" method="POST" novalidate>
                @csrf
                <h1>Buat Akun</h1>

                <div class="social-login-2">
                    <button type="button" class="social-btn-2 google-2" aria-label="Login with Google">
                        <i class="fab fa-google"></i>
                    </button>
                    <button type="button" class="social-btn-2 facebook-2" aria-label="Login with Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                </div>

                <div class="name-fields-2">
                    <input type="text" name="firstname" placeholder="Nama Depan" 
                           value="{{ old('firstname') }}" />
                    <input type="text" name="lastname" placeholder="Nama Belakang" 
                           value="{{ old('lastname') }}" />
                </div>

                <input type="text" name="username" placeholder="Username" 
                       value="{{ old('username') }}" required />

                <input type="email" name="email" placeholder="Email" 
                       value="{{ old('email') }}" required />

                <div class="password-field-2">
                    <input type="password" name="password" id="regPassword2" 
                           placeholder="Kata Sandi" required />
                    <span class="toggle-password-2" onclick="togglePassword2('regPassword2', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>

                <div class="password-field-2">
                    <input type="password" name="password_confirmation" id="regConfirmPassword2" 
                           placeholder="Konfirmasi Kata Sandi" required />
                    <span class="toggle-password-2" onclick="togglePassword2('regConfirmPassword2', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>

                @if ($errors->any() && (old('username') || old('firstname') || old('lastname')))
                    <div class="auth-error-2">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <button type="submit">Daftar</button>
            </form>
        </div>

        <!-- FORM LOGIN -->
        <div class="form-container-2 sign-in-container-2">
            <form action="{{ route('login') }}" method="POST" novalidate id="loginForm2">
                @csrf
                <h1>Masuk</h1>

                <div class="social-login-2">
                    <button type="button" class="social-btn-2 google-2" aria-label="Login with Google">
                        <i class="fab fa-google"></i>
                    </button>
                    <button type="button" class="social-btn-2 facebook-2" aria-label="Login with Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                </div>

                <input type="email" name="email" placeholder="Email" 
                       value="{{ old('email') }}" required id="emailInput2" />

                <div class="password-field-2">
                    <input type="password" name="password" id="loginPassword2" 
                           placeholder="Kata Sandi" required />
                    <span class="toggle-password-2" onclick="togglePassword2('loginPassword2', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>

                <div class="login-options-2">
                    <label class="remember-me-2">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                        Tetap masuk
                    </label>
                    <a href="#" class="forgot-password-2" onclick="event.preventDefault()">
                        Lupa kata sandi?
                    </a>
                </div>

                @if ($errors->has('email') && !old('username'))
                    <div class="auth-error-2" id="errorMessage2">
                        {{ $errors->first('email') }}
                        
                        @if (session('throttle_seconds'))
                            <div class="countdown-wrapper-2">
                                <i class="fas fa-clock"></i>
                                <span>Silakan tunggu <strong id="countdown2">{{ session('throttle_seconds') }}</strong> detik</span>
                            </div>
                        @endif
                    </div>
                @endif

                <button type="submit" id="loginButton2">Masuk</button>
            </form>
        </div>

        <!-- OVERLAY -->
        <div class="overlay-container-2">
            <div class="overlay-2">
                <div class="overlay-panel-2 overlay-left-2">
                    <h1>Selamat Datang Kembali!</h1>
                    <p>Untuk tetap terhubung, silakan masuk dengan akun Anda</p>
                    <button type="button" class="ghost-2" onclick="togglePanel2(false)">
                        Masuk
                    </button>
                </div>
                <div class="overlay-panel-2 overlay-right-2">
                    <h1>Halo, Teman Baru!</h1>
                    <p>Masukkan data pribadi Anda dan mulai perjalanan bersama kami</p>
                    <button type="button" class="ghost-2" onclick="togglePanel2(true)">
                        Daftar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-2: #ffffff;
    --secondary-2: #048bd4;
    --thirdary-2: #0442d4;
    --edition-2: #1e293b;
    --contrast-2: #ffffff;
}

.auth-overlay-2 {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0);
    backdrop-filter: blur(0px);
    justify-content: center;
    align-items: center;
    z-index: 99999;
    opacity: 0;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.auth-overlay-2.active {
    display: flex;
    opacity: 1;
    background: rgba(0, 0, 0, 0.65);
    backdrop-filter: blur(5px);
}

.container-2 {
    background-color: var(--contrast-2);
    border-radius: 16px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
    position: relative;
    overflow: hidden;
    width: 860px;
    max-width: 95%;
    min-height: 520px;
    transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: flex;
    transform: scale(0.7) translateY(50px) rotateX(10deg);
    opacity: 0;
}

.auth-overlay-2.active .container-2 {
    transform: scale(1) translateY(0) rotateX(0deg);
    opacity: 1;
}

.close-icon-2 {
    position: absolute;
    top: 20px;
    right: 24px;
    font-size: 22px;
    font-weight: 700;
    color: var(--thirdary-2);
    cursor: pointer;
    z-index: 101;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: rgba(255, 255, 255, 0.9);
    border: none;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    animation: slideInClose 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s both;
}

@keyframes slideInClose {
    0% {
        opacity: 0;
        transform: translateX(30px) rotate(-90deg) scale(0.5);
    }
    100% {
        opacity: 1;
        transform: translateX(0) rotate(0deg) scale(1);
    }
}

.close-icon-2:hover {
    transform: rotate(90deg) scale(1.1);
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.form-container-2 {
    position: absolute;
    top: 0;
    height: 100%;
    transition: all 0.7s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.sign-in-container-2 {
    left: 0;
    width: 50%;
    z-index: 2;
}

.sign-up-container-2 {
    left: 0;
    width: 50%;
    opacity: 0;
    z-index: 1;
    pointer-events: none;
}

.container-2.right-panel-active .sign-in-container-2 {
    transform: translateX(100%);
    opacity: 0;
    pointer-events: none;
}

.container-2.right-panel-active .sign-up-container-2 {
    transform: translateX(100%);
    opacity: 1;
    z-index: 5;
    pointer-events: auto;
}

.form-container-2 form {
    background-color: var(--contrast-2);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0 50px;
    height: 100%;
    text-align: center;
}

.form-container-2 form h1 {
    font-weight: 800;
    font-size: 30px;
    color: var(--edition-2);
    margin-bottom: 24px;
    transition: all 0.3s ease;
    animation: slideInTitle 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s both;
}

@keyframes slideInTitle {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-container-2 form input {
    background-color: #fff;
    border: 2px solid #e2e8f0;
    padding: 13px 16px;
    margin: 9px 0;
    width: 100%;
    border-radius: 10px;
    font-size: 14.5px;
    color: #333;
    outline: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: 'Ubuntu', -apple-system, BlinkMacSystemFont, sans-serif;
    animation: slideInInput 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}

.form-container-2 form input:nth-of-type(1) { animation-delay: 0.6s; }
.form-container-2 form input:nth-of-type(2) { animation-delay: 0.65s; }
.form-container-2 form input:nth-of-type(3) { animation-delay: 0.7s; }
.form-container-2 form input:nth-of-type(4) { animation-delay: 0.75s; }
.form-container-2 form input:nth-of-type(5) { animation-delay: 0.8s; }

.password-field-2 { animation: slideInInput 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.7s both; }
.password-field-2:nth-of-type(4) { animation-delay: 0.75s; }
.password-field-2:nth-of-type(5) { animation-delay: 0.8s; }

.name-fields-2 { animation: slideInInput 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.6s both; }

@keyframes slideInInput {
    0% {
        opacity: 0;
        transform: translateX(-30px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

.form-container-2 form input:focus {
    border-color: var(--secondary-2);
    box-shadow: 0 0 0 4px rgba(4, 139, 212, 0.12);
    transform: translateY(-1px);
}

.form-container-2 form button[type="submit"] {
    border-radius: 28px;
    border: none;
    background: linear-gradient(135deg, var(--secondary-2) 0%, var(--thirdary-2) 100%);
    color: var(--contrast-2);
    font-size: 15px;
    font-weight: 700;
    padding: 14px 50px;
    margin-top: 22px;
    letter-spacing: 0.8px;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(4, 139, 212, 0.3);
    position: relative;
    overflow: hidden;
    animation: bounceIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.9s both;
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3) translateY(50px);
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.95);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.form-container-2 form button[type="submit"]::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
}

.form-container-2 form button[type="submit"]:hover::before {
    left: 100%;
}

.form-container-2 form button[type="submit"]:hover {
    transform: translateY(-3px) scale(1.03);
    box-shadow: 0 8px 25px rgba(4, 139, 212, 0.45);
}

.form-container-2 form button[type="submit"]:active {
    transform: translateY(-1px) scale(0.98);
}

.form-container-2 form button[type="submit"]:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    background: #94a3b8 !important;
    transform: none !important;
    box-shadow: none !important;
}

.overlay-container-2 {
    position: absolute;
    top: 0;
    left: 50%;
    width: 50%;
    height: 100%;
    overflow: hidden;
    transition: transform 0.7s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    z-index: 100;
}

.container-2.right-panel-active .overlay-container-2 {
    transform: translateX(-100%);
}

.overlay-2 {
    background: linear-gradient(135deg, var(--edition-2) 0%, var(--secondary-2) 100%);
    background-size: cover;
    color: var(--contrast-2);
    position: relative;
    left: -100%;
    height: 100%;
    width: 200%;
    transform: translateX(0);
    transition: transform 0.7s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.container-2.right-panel-active .overlay-2 {
    transform: translateX(50%);
}

.overlay-panel-2 {
    position: absolute;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    top: 0;
    height: 100%;
    width: 50%;
    padding: 0 45px;
    transition: transform 0.7s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.overlay-left-2 {
    transform: translateX(-20%);
}

.container-2.right-panel-active .overlay-left-2 {
    transform: translateX(0);
}

.overlay-right-2 {
    right: 0;
}

.container-2.right-panel-active .overlay-right-2 {
    transform: translateX(20%);
}

.overlay-panel-2 h1 {
    font-size: 42px;
    font-weight: 800;
    margin-bottom: 12px;
    color: var(--primary-2);
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    line-height: 1.2;
    animation: slideInFromTop 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s both;
}

.overlay-panel-2 p {
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 24px;
    max-width: 280px;
    opacity: 0.95;
    animation: fadeInUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.6s both;
}

.ghost-2 {
    background-color: transparent;
    border: 2.5px solid #fff;
    color: #fff;
    border-radius: 28px;
    padding: 12px 45px;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 0.8px;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    animation: bounceInGhost 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.8s both;
}

@keyframes slideInFromTop {
    0% {
        opacity: 0;
        transform: translateY(-30px) scale(0.9);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes bounceInGhost {
    0% {
        opacity: 0;
        transform: scale(0.5);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.ghost-2:hover {
    background-color: #fff;
    color: var(--thirdary-2);
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
}

.ghost-2:active {
    transform: translateY(-1px) scale(1.02);
}

.password-field-2 {
    position: relative;
    width: 100%;
}

.password-field-2 input {
    width: 100%;
    padding-right: 45px !important;
}

.toggle-password-2 {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #64748b;
    font-size: 18px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    padding: 5px;
}

.toggle-password-2:hover {
    color: var(--thirdary-2);
    transform: translateY(-50%) scale(1.15);
}

.login-options-2 {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 12px;
    font-size: 14px;
    animation: fadeInUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.85s both;
}

.remember-me-2 {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--secondary-2);
    user-select: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.remember-me-2:hover {
    color: var(--thirdary-2);
}

.remember-me-2 input[type="checkbox"] {
    accent-color: var(--thirdary-2);
    width: 18px;
    height: 18px;
    cursor: pointer;
    margin: 0;
    transition: all 0.3s ease;
}

.forgot-password-2 {
    color: var(--thirdary-2);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.forgot-password-2:hover {
    text-decoration: underline;
    transform: translateX(3px);
    display: inline-block;
}

.social-login-2 {
    display: flex;
    justify-content: center;
    gap: 18px;
    width: 100%;
    margin-bottom: 24px;
    animation: fadeInUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.5s both;
}

@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.social-btn-2 {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 2px solid #e2e8f0;
    cursor: pointer;
    background-color: #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    padding: 0;
}

.social-btn-2:hover {
    transform: translateY(-4px) scale(1.08);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    border-color: #cbd5e1;
}

.social-btn-2:active {
    transform: translateY(-2px) scale(1.05);
}

.social-btn-2 i {
    font-size: 19px;
    transition: transform 0.3s ease;
}

.social-btn-2:hover i {
    transform: scale(1.1);
}

.social-btn-2.google-2 i {
    color: #DB4437;
}

.social-btn-2.facebook-2 i {
    color: #4267B2;
}

.name-fields-2 {
    display: flex;
    gap: 12px;
    width: 100%;
}

.name-fields-2 input {
    flex: 1;
}

.auth-error-2 {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
    padding: 14px 18px;
    border-radius: 10px;
    margin-top: 12px;
    font-size: 13.5px;
    width: 100%;
    text-align: center;
    border: 2px solid #fecaca;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.1);
    animation: errorShake 0.5s ease;
}

@keyframes errorShake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.countdown-wrapper-2 {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 2px solid #fecaca;
    font-size: 14px;
    color: #991b1b;
}

.countdown-wrapper-2 i {
    font-size: 17px;
    color: #dc2626;
    animation: pulseIcon 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.countdown-wrapper-2 strong {
    font-size: 20px;
    font-weight: 800;
    color: #dc2626;
    min-width: 30px;
    display: inline-block;
    text-align: center;
    animation: countPulse 1s ease infinite;
}

@keyframes pulseIcon {
    0%, 100% { 
        opacity: 1; 
        transform: scale(1);
    }
    50% { 
        opacity: 0.6; 
        transform: scale(0.95);
    }
}

@keyframes countPulse {
    0%, 100% { 
        transform: scale(1);
    }
    50% { 
        transform: scale(1.1);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .container-2 {
        width: 95%;
        min-height: 600px;
        border-radius: 12px;
    }

    .overlay-container-2 {
        display: none;
    }

    .form-container-2 {
        position: relative !important;
        width: 100% !important;
        left: 0 !important;
        opacity: 1 !important;
        transform: none !important;
        pointer-events: auto !important;
    }

    .sign-up-container-2 {
        display: none;
    }

    .container-2.right-panel-active .sign-up-container-2 {
        display: flex;
    }

    .container-2.right-panel-active .sign-in-container-2 {
        display: none;
    }

    .form-container-2 form {
        padding: 0 30px;
    }

    .form-container-2 form h1 {
        font-size: 26px;
    }
}

@media (max-width: 480px) {
    .form-container-2 form {
        padding: 0 24px;
    }

    .close-icon-2 {
        top: 16px;
        right: 16px;
        width: 34px;
        height: 34px;
        font-size: 18px;
    }
}
</style>

<script>
// Open Modal dengan Animasi Spektakuler
function openLoginModal() {
    const modal = document.getElementById('loginModal');
    const container = document.getElementById('authContainer2');
    
    // Set display first
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Force reflow
    void modal.offsetWidth;
    
    // Trigger animation
    requestAnimationFrame(() => {
        modal.classList.add('active');
    });
}

// Close Modal dengan Animasi Smooth
function closeLoginModal() {
    const modal = document.getElementById('loginModal');
    const container = document.getElementById('authContainer2');
    
    // Trigger exit animation
    modal.classList.remove('active');
    
    // Wait for animation to complete
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }, 500);
}

// Toggle Panel
function togglePanel2(showRegister) {
    const container = document.getElementById('authContainer2');
    if (showRegister) {
        container.classList.add('right-panel-active');
    } else {
        container.classList.remove('right-panel-active');
    }
}

// Toggle Password Visibility
function togglePassword2(inputId, toggleElement) {
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

// Close on Escape Key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('loginModal');
        if (modal.classList.contains('active')) {
            closeLoginModal();
        }
    }
});

// Close on Overlay Click
document.getElementById('loginModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeLoginModal();
    }
});

// Countdown Timer
@if (session('throttle_seconds'))
document.addEventListener('DOMContentLoaded', function() {
    let seconds = {{ session('throttle_seconds') }};
    const countdownEl = document.getElementById('countdown2');
    const loginButton = document.getElementById('loginButton2');
    const loginPasswordInput = document.getElementById('loginPassword2');
    const emailInput = document.getElementById('emailInput2');
    
    // Disable form saat countdown
    if (loginButton) loginButton.disabled = true;
    if (emailInput) emailInput.disabled = true;
    if (loginPasswordInput) loginPasswordInput.disabled = true;
    
    const interval = setInterval(() => {
        seconds--;
        
        if (countdownEl) {
            countdownEl.textContent = seconds;
        }

        if (seconds <= 0) {
            clearInterval(interval);
            
            // Enable form kembali
            if (loginButton) loginButton.disabled = false;
            if (emailInput) emailInput.disabled = false;
            if (loginPasswordInput) loginPasswordInput.disabled = false;
            
            // Hapus error message dengan animasi
            const errorMessage = document.getElementById('errorMessage2');
            if (errorMessage) {
                errorMessage.style.transition = 'all 0.4s ease';
                errorMessage.style.transform = 'translateY(-10px)';
                errorMessage.style.opacity = '0';
                setTimeout(() => errorMessage.remove(), 400);
            }
        }
    }, 1000);
});
@endif

// Auto show register form if old register data exists
@if (old('username') || old('firstname') || old('lastname'))
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        openLoginModal();
        setTimeout(() => togglePanel2(true), 100);
    }, 100);
});
@endif

// Auto show login modal if login error exists
@if ($errors->has('email') && !old('username'))
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => openLoginModal(), 100);
});
@endif
</script>