<x-guest-layout title="Login">
    @auth
        @if (auth()->user()->role === \App\Models\User::ROLES['Admin'])
            <script>
                window.location.href = "{{ route('admin.dashboard') }}";
            </script>
        @endif
        @if (auth()->user()->role === \App\Models\User::ROLES['User'])
            <script>
                window.location.href = "{{ route('user.dashboard') }}";
            </script>
        @endif
    @else
        <div class="min-vh-100 d-flex align-items-center position-relative bg-gradient-light">
            <!-- Enhanced Background -->
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-pattern"></div>

            <!-- Main Container -->
            <div class="container position-relative py-4">
                <div class="row justify-content-center">
                    <div class="col-12" style="max-width: 1100px;">
                        <!-- Completely Redesigned Card Layout -->
                        <div class="login-container">
                            <!-- Top Section with Company Info -->
                           

                            <!-- Two Cards Side by Side -->
                            <div class="row g-0">
                                <!-- Left Card: Illustration -->
                                <div class="col-lg-6 mb-4 mb-lg-0">
                                    <div
                                        class="illustration-card h-100 d-flex flex-column justify-content-center align-items-center p-4">
                                        
                                        <div class="welcome-text text-center">
                                            <h3 class="fw-bold welcome-title">Welcome Back!</h3>
                                            <p class="welcome-subtitle">We're glad to see you again</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Card: Login Form -->
                                <div class="col-lg-6">
                                    <div class="login-form-card h-100 p-4 p-lg-5">
                                        <h3 class="text-center mb-4 form-title">Sign In</h3>

                                        <form action="{{ route('login') }}" method="POST" class="floating-labels">
                                            @csrf
                                            @method('POST')

                                            <div class="form-group mb-4">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-envelope text-primary"></i>
                                                    </span>
                                                    <div class="form-floating flex-grow-1">
                                                        <input type="email"
                                                            class="form-control custom-input @error('email') is-invalid @enderror"
                                                            name="email" id="email" placeholder="Email"
                                                            value="{{ old('email') }}">
                                                        <label for="email">Email Address</label>
                                                    </div>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group mb-4">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-lock text-primary"></i>
                                                    </span>
                                                    <div class="form-floating flex-grow-1">
                                                        <input type="password"
                                                            class="form-control custom-input @error('password') is-invalid @enderror"
                                                            name="password" id="password" placeholder="Password">
                                                        <label for="password">Password</label>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none password-toggle"
                                                        style="z-index: 5;" onclick="togglePassword()">
                                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                                    </button>
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <div class="form-check custom-checkbox">
                                                    <input type="checkbox" class="form-check-input" name="remember"
                                                        id="remember">
                                                    <label class="form-check-label" for="remember">Remember me</label>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100 btn-lg mb-4 pulse-button">
                                                Sign In <i class="fas fa-sign-in-alt ms-2"></i>
                                            </button>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Modern Color Scheme */
            :root {

                --primary-color: #048bd4;
                --primary-hover: #0442d4;
                --secondary-color: #b22222;
                --success-color: #22c55e;
                --background-color: #f8fafc;
                --card-background: #ffffff;
                --text-primary: #1e293b;
                --text-secondary: #64748b;
            }

            /* Enhanced Background */
            .bg-gradient-light {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            }

            .bg-pattern {
                background-image: radial-gradient(#d4c704 0.5px, transparent 0.5px), radial-gradient(#d4c704 0.5px, #b22222 0.5px);

                background-size: 20px 20px;
                background-position: 0 0, 10px 10px;
                opacity: 0.05;
            }

            /* Completely Redesigned Container */
            .login-container {
                max-width: 1100px;
                margin: 0 auto;
            }

            /* Company Banner at Top */
            .company-banner {
                padding: 1.5rem;
                border-radius: 20px;
                margin-bottom: 2rem;
                background-color: rgba(255, 255, 255, 0.9);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            }

            .logo-container {
                position: relative;
                z-index: 1;
            }

            .logo-background {
                position: absolute;
                width: 150px;
                height: 150px;
                background: radial-gradient(circle, rgba(79, 70, 229, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
                border-radius: 50%;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: -1;
            }

            .logo-animate {
                height: 130px;
                position: relative;
                z-index: 2;
            }

            .company-name {
                font-weight: 700;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                margin-bottom: 0.5rem;
            }

            .company-address {
                color: var(--text-secondary);
                font-size: 0.95rem;
                margin-bottom: 0;
            }

            /* Two Cards Layout */
            .illustration-card,
            .login-form-card {
                border-radius: 20px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                height: 100%;
            }

            /* Left Card: Illustration */
            .illustration-card {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                padding: 2rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                position: relative;
            }

            .illustration-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
                opacity: 0.5;
            }

            .floating-illustration {
                max-width: 90%;
                max-height: 300px;
                filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.2));
                animation: float 8s ease-in-out infinite;
            }

            .welcome-text {
                color: white;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                position: relative;
                z-index: 2;
            }

            .welcome-title {
                font-size: 2.2rem;
                margin-bottom: 0.5rem;
            }

            .welcome-subtitle {
                font-size: 1.1rem;
                opacity: 0.9;
            }

            /* Right Card: Login Form */
            .login-form-card {
                background-color: white;
                padding: 2.5rem;
            }

            .form-title {
                color: var(--text-primary);
                font-weight: 700;
                margin-bottom: 2rem;
                position: relative;
            }

            .form-title:after {
                content: '';
                position: absolute;
                width: 50px;
                height: 3px;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                border-radius: 3px;
            }

            /* Enhanced Input Styling */
            .input-group-text {
                background-color: white;
                border-right: none;
                border-top-left-radius: 12px;
                border-bottom-left-radius: 12px;
                border: 2px solid #e2e8f0;
                border-right: none;
                padding-left: 1.25rem;
                padding-right: 0.75rem;
            }

            .custom-input {
                border-top-right-radius: 12px !important;
                border-bottom-right-radius: 12px !important;
                border-top-left-radius: 0 !important;
                border-bottom-left-radius: 0 !important;
                padding: 1.15rem 1rem;
                border: 2px solid #e2e8f0;
                border-left: none;
                font-size: 1rem;
                transition: all 0.3s ease;
                background-color: #ffffff;
            }

            .form-floating>label {
                padding-left: 0.75rem;
            }

            .custom-input:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
                border-left: none;
            }

            .custom-input:focus+label {
                color: var(--primary-color);
            }

            .input-group:focus-within .input-group-text {
                border-color: var(--primary-color);
            }

            /* Enhanced Button Styling */
            .pulse-button {
                border-radius: 12px;
                padding: 0.875rem 1.5rem;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                border: none;
                font-weight: 600;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .pulse-button:before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.1);
                transform: translateX(-100%);
                transition: transform 0.6s ease-out;
            }

            .pulse-button:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
            }

            .pulse-button:hover:before {
                transform: translateX(100%);
            }

            /* Enhanced Checkbox Styling */
            .custom-checkbox .form-check-input {
                border-radius: 6px;
                border: 2px solid #e2e8f0;
            }

            .custom-checkbox .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            /* Password Toggle */
            .password-toggle {
                color: var(--text-secondary);
                transition: color 0.3s ease;
                margin-right: 0.5rem;
                z-index: 10;
            }

            .password-toggle:hover {
                color: var(--primary-color);
            }

            /* Animations */
            @keyframes float {

                0%,
                100% {
                    transform: translateY(0) scale(1);
                }

                50% {
                    transform: translateY(-15px) scale(1.02);
                }
            }

            @keyframes logoFloat {

                0%,
                100% {
                    transform: translateY(0) scale(1);
                }

                50% {
                    transform: translateY(-8px) scale(1.05);
                }
            }

            .logo-animate {
                animation: logoFloat 6s ease-in-out infinite;
            }

            /* Responsive Styles */
            @media (max-width: 991.98px) {
                .company-banner {
                    margin-bottom: 1.5rem;
                    padding: 1rem;
                }

                .logo-animate {
                    height: 100px;
                }

                .company-name {
                    font-size: 1.5rem;
                }

                .company-address {
                    font-size: 0.85rem;
                }

                .illustration-card {
                    margin-bottom: 1.5rem;
                    padding: 1.5rem;
                }

                .welcome-title {
                    font-size: 1.8rem;
                }

                .welcome-subtitle {
                    font-size: 1rem;
                }

                .login-form-card {
                    padding: 1.5rem;
                }
            }
        </style>

        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const toggleIcon = document.getElementById('toggleIcon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                }
            }
        </script>
    @endauth
</x-guest-layout>
