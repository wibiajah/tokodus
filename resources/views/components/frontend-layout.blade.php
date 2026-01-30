{{-- resources/views/frontend/layouts/frontend-layout.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Tokodus | Solusi Packaging Anda' }}</title>

    <link href="{{ asset('frontend/assets/img/logo-icon.ico') }}" rel="icon" />

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet" />

    <!-- Feather Icon -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- SweetAlert2 untuk Notifikasi Verifikasi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- CSS Desktop -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/banner.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/product-card.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/mobile-responsive.css') }}" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <!-- ✅ CSS FOOTER 645 - KHUSUS FOOTER -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/footer/footer-645.css') }}" />



    <style>
        /* ========== ADDITIONAL MOBILE FIX ========== */
        * {
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }

        html,
        body {
            width: 100%;
            overflow-x: hidden;
            position: relative;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        input,
        textarea,
        select {
            font-size: 16px !important;
        }

        /* User Button Styles */
        .navbar-extra .user-button-container {
            position: relative;
            display: inline-block;
        }

        .navbar-extra .user-button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: var(--secondary);
            color: var(--contrast);
            border: 2px solid var(--contrast);
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            height: 40px;
            min-height: 40px;
        }

        .navbar-extra .user-name-text {
            flex: 1;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--contrast) !important;
            line-height: 1;
            display: flex;
            align-items: center;
        }

        .navbar-extra .sidebar-indicator {
            font-size: 0.9rem;
            margin-left: auto;
            transition: transform 0.3s ease;
            color: var(--contrast) !important;
            line-height: 1;
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .navbar-extra .user-button:hover {
            background: #e9078f;
            border-color: #ff1493;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(31, 67, 144, 0.3);
        }

        .navbar-extra .bi-person-circle {
            line-height: 1;
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .navbar-extra .user-avatar-small {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--contrast);
            flex-shrink: 0;
        }

        /* Notification Toast Styles */
        .notification-toast {
            position: fixed;
            top: 100px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            opacity: 0;
            transform: translateX(400px);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .notification-toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .notification-toast.hide {
            opacity: 0;
            transform: translateX(400px);
        }

        .notification-toast i {
            font-size: 1.2rem;
        }

        .notification-toast .notification-message {
            font-weight: 500;
            font-size: 0.95rem;
        }

        .notification-toast.success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .notification-toast.error {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        /* Cart Badge Styles */
        .navbar-extra #shopping-cart {
            position: relative;
            display: inline-block;
        }

        .navbar-extra .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            font-size: 0.7rem;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* ========== NAVBAR MOBILE FIX ========== */
        @media (max-width: 768px) {

            #hamburger-menu i,
            #hamburger-menu svg {
                color: #ffffff !important;
                stroke: #ffffff !important;
            }

            .navbar {
                justify-content: flex-start !important;
                gap: 5px !important;
                height: 60px !important;
                padding: 0.6rem 1rem !important;
            }

            #hamburger-menu {
                margin-right: 0 !important;
                margin-left: 0 !important;
                padding: 12px 10px !important;
            }

            .navbar-logo {
                margin-left: 0 !important;
                margin-right: auto !important;
            }

            .navbar-logo img.Logo {
                width: 30px !important;
                height: auto !important;
            }

            .navbar-logo span {
                font-size: inherit !important;
            }

            .navbar-logo {
                margin-left: -5px !important;
                gap: 6px !important;
            }
        }

        @media (max-width: 375px) {
            .navbar-logo img.Logo {
                width: 28px !important;
            }

            .navbar-logo {
                margin-left: 2px !important;
                gap: 6px !important;
            }

            #hamburger-menu {
                padding: 12px 8px !important;
            }
        }

        @media (min-width: 376px) and (max-width: 428px) {
            .navbar-logo img.Logo {
                width: 32px !important;
            }

            .navbar-logo {
                margin-left: 3px !important;
                gap: 7px !important;
            }
        }

        @media (min-width: 429px) and (max-width: 768px) {
            .navbar-logo img.Logo {
                width: 34px !important;
            }

            .navbar-logo {
                margin-left: 5px !important;
                gap: 8px !important;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .navbar-logo img.Logo {
                width: 40px !important;
            }
        }
    </style>
</head>

<body>

    <!-- Overlay untuk menutup sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Navbar -->
    <nav class="navbar">
        <!-- Hamburger Menu - Posisi Kiri -->
        <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>

        <!-- Logo & Nama - Rapat di Kiri -->
        <a href="{{ route('home') }}" class="navbar-logo" id="nav-logo">
            <img src="{{ asset('frontend/assets/img/logo-icon.png') }}" alt="Logo" width="40" class="Logo" />
            <span>Tokodus</span>
        </a>

        <div class="navbar-nav">
            <ul class="navbar-nav-menu">
                <li><a href="{{ route('home') }}#service">Services</a></li>
                <li><a href="{{ route('catalog') }}">Catalog</a></li>
                <li><a href="{{ route('home') }}#projects">Projects</a></li>
                <li><a href="{{ route('home') }}#resources">Resources</a></li>
                <li><a href="{{ route('home') }}#stores">Contact Us</a></li>
            </ul>
        </div>

        <div class="navbar-extra">
            <ul class="navbar-extra-menu">
                <a href="#" id="search"><i data-feather="search"></i></a>

                {{-- ✅ CART ICON dengan Badge --}}
                @auth('customer')
                    <a href="{{ route('customer.cart.index') }}" id="shopping-cart" class="position-relative">
                        <i data-feather="shopping-cart"></i>
                        @if (auth('customer')->user()->cart_count > 0)
                            <span class="cart-badge">{{ auth('customer')->user()->cart_count }}</span>
                        @endif
                    </a>
                @else
                    <a href="#" id="shopping-cart" onclick="openLoginModal()">
                        <i data-feather="shopping-cart"></i>
                    </a>
                @endauth

                {{-- CEK CUSTOMER LOGIN (guard: customer) --}}
                @auth('customer')
                    <li>
                        <div class="user-button-container">
                            <button class="user-button" id="userProfileBtn">
                                @if (auth('customer')->user()->foto_profil)
                                    <img src="{{ asset('storage/' . auth('customer')->user()->foto_profil) }}"
                                        alt="User" class="user-avatar-small"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                    <i class="bi bi-person-circle" style="display: none; font-size: 26px;"></i>
                                @else
                                    <i class="bi bi-person-circle" style="font-size: 26px;"></i>
                                @endif
                                <span class="user-name-text">{{ auth('customer')->user()->firstname }}</span>
                                <i class="fas fa-bars sidebar-indicator"></i>
                            </button>
                        </div>
                    </li>
                @endauth

                {{-- CEK STAFF/ADMIN LOGIN (guard: web) --}}
                @auth('web')
                    <li>
                        <div class="user-button-container">
                            <button class="user-button" id="userProfileBtn">
                                <img src="{{ auth()->user()->foto_profil ? asset('storage/' . auth()->user()->foto_profil) : asset('img/default-avatar.png') }}"
                                    alt="User" class="user-avatar-small">
                                <span class="user-name-text">{{ auth()->user()->name }}</span>
                                <i class="fas fa-bars sidebar-indicator"></i>
                            </button>
                        </div>
                    </li>
                @endauth

                {{-- GUEST (belum login) --}}
                @guest('customer')
                    @guest('web')
                        <li><a href="#" onclick="openLoginModal()" id="nav-get-in-touch">Login</a></li>
                    @endguest
                @endguest
            </ul>
        </div>
    </nav>

    {{-- Include Sidebar untuk Customer & Staff/Admin --}}
    @auth('customer')
        @include('components.sidebarhome')
    @endauth

    @auth('web')
        @include('components.sidebarhome')
    @endauth

    {{-- Include Login Modal untuk Guest --}}
    @guest('customer')
        @guest('web')
            @include('components.login-modal')
        @endguest
    @endguest

    <!-- Main Content -->
    {{ $slot }}

    <!-- ========================================
         ✅ FOOTER SECTION - PREFIX 645
         ======================================== -->
    <footer class="footer-645" id="footer-645">
        <div class="footer-645__container">
            <!-- Header Section -->
            <div class="footer-645__header">
                <p class="footer-645__tagline">Sedang Butuh Packaging seperti apa?</p>
                <a href="{{ route('home') }}#stores" class="footer-645__cta">Konsultasikan Sekarang</a>
            </div>

            <!-- Links Section -->
            <div class="footer-645__links">
                <!-- LEFT SIDE: Group 1 - Services & Privacy -->
                <div class="footer-645__group">
                    <!-- Column 1 - Services -->
                    <div class="footer-645__column">
                        <ul class="footer-645__list">
                            <li><a href="{{ route('home') }}#service" class="footer-645__link">Services</a></li>
                            <li><a href="{{ route('catalog') }}" class="footer-645__link">Catalog</a></li>
                            <li><a href="{{ route('home') }}#projects" class="footer-645__link">Projects</a></li>
                            <li><a href="{{ route('home') }}#resources" class="footer-645__link">Resources</a></li>
                            <li><a href="#" class="footer-645__link">Materials</a></li>
                            <li><a href="#" class="footer-645__link">Guidelines</a></li>
                            <li><a href="#" class="footer-645__link">FAQ</a></li>
                            <li><a href="{{ route('home') }}#stores" class="footer-645__link">Contact Us</a></li>
                        </ul>
                    </div>

                    <!-- Column 2 - Privacy -->
                    <div class="footer-645__column">
                        <ul class="footer-645__list">
                            <li><a href="#" class="footer-645__link">Privacy Policy</a></li>
                            <li><a href="#" class="footer-645__link">Terms & Conditions</a></li>
                            <li><a href="mailto:marketing@tokodus.com"
                                    class="footer-645__link">marketing@tokodus.com</a></li>
                        </ul>
                    </div>
                </div>

                <!-- RIGHT SIDE: Group 2 - Social & Newsletter -->
                <div class="footer-645__group">
                    <!-- Column 3 - Social & Career -->
                    <div class="footer-645__column">
                        <ul class="footer-645__list">
                            <li><a href="#" class="footer-645__link">Career</a></li>
                            <li><a href="{{ route('home') }}#stores" class="footer-645__link">Cabang Toko</a></li>
                            <li><a href="https://www.instagram.com/tokodusbdg/" class="footer-645__link"
                                    target="_blank" rel="noopener">Instagram</a></li>
                            <li><a href="https://www.facebook.com/tokodusbdg/" class="footer-645__link"
                                    target="_blank" rel="noopener">Facebook</a></li>
                            <li><a href="https://www.tiktok.com/@tokdus/" class="footer-645__link" target="_blank"
                                    rel="noopener">Tiktok</a></li>
                            <li><a href="https://www.youtube.com/@TokodusOfficial" class="footer-645__link"
                                    target="_blank" rel="noopener">Youtube</a></li>
                        </ul>
                    </div>

                    <!-- Column 4 - Newsletter -->
                    <div class="footer-645__column">
                        <form class="footer-645__form" id="footer-645-form">
                            @csrf
                            <input type="email" name="email" class="footer-645__input"
                                placeholder="Masukkan email Anda" required aria-label="Email untuk newsletter" />
                            <button type="submit" class="footer-645__btn">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="footer-645__bottom">
                <p class="footer-645__copyright">
                    Copyright &copy; <span class="footer-645__year"></span> Tokodus. All Rights Reserved
                </p>
                <div class="footer-645__brand">
                    <a href="{{ route('home') }}" class="footer-645__logo-link">
                        <img src="{{ asset('frontend/assets/img/logo-icon.png') }}" alt="Tokodus Logo"
                            class="footer-645__logo-img" loading="lazy" width="100" height="auto" />
                        <span class="footer-645__brand-name">Tokodus</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/6281120061333" class="whatsapp-float" target="_blank">
        <img src="{{ asset('frontend/assets/img/icon/cs.png') }}" alt="WhatsApp" />
    </a>

    <script>
        // Cek session flash message saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showNotification('{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showNotification('{{ session('error') }}', 'error');
            @endif
        });

        // ========== SWEETALERT2 KHUSUS LOGIN, LOGOUT & VERIFIKASI ========== //
        document.addEventListener('DOMContentLoaded', function() {
            // Success: Aktivasi, Login, Logout
            @if (session('success') &&
                    (str_contains(session('success'), 'Aktivasi') ||
                        str_contains(session('success'), 'verifikasi') ||
                        str_contains(session('success'), 'Selamat datang') ||
                        str_contains(session('success'), 'logout')))
                Swal.fire({
                    icon: 'success',
                    title: '✅ Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#10b981',
                    timer: 4000,
                    timerProgressBar: true
                });
            @endif

            // Info: Sudah terverifikasi
            @if (session('info') &&
                    (str_contains(session('info'), 'sudah terverifikasi') || str_contains(session('info'), 'verifikasi')))
                Swal.fire({
                    icon: 'info',
                    title: 'ℹ️ Informasi',
                    text: '{{ session('info') }}',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#3b82f6',
                    timer: 4000,
                    timerProgressBar: true
                });
            @endif

            // Error: Belum verifikasi, login gagal
            @if (session('error') &&
                    (str_contains(session('error'), 'belum diverifikasi') || str_contains(session('error'), 'Login Google gagal')))
                Swal.fire({
                    icon: 'error',
                    title: '❌ Perhatian!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#ef4444',
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif
        });

        // Cek localStorage untuk notifikasi logout success
        if (localStorage.getItem('logoutSuccess') === 'true') {
            localStorage.removeItem('logoutSuccess');
            Swal.fire({
                icon: 'success',
                title: '✅ Berhasil Logout!',
                text: 'Anda telah keluar dari akun.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#10b981',
                timer: 3000,
                timerProgressBar: true
            });
        }
    </script>
    <!-- JavaScript -->
    <script src="{{ asset('frontend/assets/js/script.js') }}" defer></script>

    <!-- ✅ FOOTER 645 JavaScript -->
    <script src="{{ asset('frontend/assets/js/footer/footer-645.js') }}" defer></script>

    <script>
        // Initialize Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Disable pinch zoom gestures
        document.addEventListener('gesturestart', function(e) {
            e.preventDefault();
        });

        // Disable double-tap zoom
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(e) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                e.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // ========== SIDEBAR MANAGEMENT - MENCEGAH TUMPUK ========== //
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerMenu = document.getElementById('hamburger-menu');
            const navbarNav = document.querySelector('.navbar-nav');
            const userProfileBtn = document.getElementById('userProfileBtn');
            const profileSidebar = document.querySelector('.floating-sidebar-home');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Function untuk close semua sidebar
            function closeAllSidebars() {
                if (navbarNav) navbarNav.classList.remove('active');
                if (profileSidebar) profileSidebar.classList.remove('active');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                document.body.classList.remove('menu-open');
            }

            // ✅ Hamburger Menu Toggle - Dropdown Animation
            if (hamburgerMenu && navbarNav) {
                hamburgerMenu.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Tutup profile sidebar jika terbuka
                    if (profileSidebar && profileSidebar.classList.contains('active')) {
                        profileSidebar.classList.remove('active');
                    }

                    // Toggle navigation sidebar dengan delay untuk animation
                    const isActive = navbarNav.classList.contains('active');

                    if (!isActive) {
                        navbarNav.classList.add('active');
                        document.body.classList.add('menu-open');
                        if (sidebarOverlay) sidebarOverlay.classList.add('active');
                    } else {
                        navbarNav.classList.remove('active');
                        document.body.classList.remove('menu-open');
                        if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                    }
                });

                // Close navigation menu when clicking nav links
                const navLinks = document.querySelectorAll('.navbar-nav-menu a');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        // Delay untuk smooth animation
                        setTimeout(() => {
                            closeAllSidebars();
                        }, 300);
                    });
                });
            }

            // ✅ User Profile Button - Dropdown Animation
            if (userProfileBtn && profileSidebar) {
                userProfileBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Tutup navigation sidebar jika terbuka
                    if (navbarNav && navbarNav.classList.contains('active')) {
                        navbarNav.classList.remove('active');
                    }

                    // Toggle profile sidebar
                    const isActive = profileSidebar.classList.contains('active');

                    if (!isActive) {
                        profileSidebar.classList.add('active');
                        document.body.classList.add('menu-open');
                        if (sidebarOverlay) sidebarOverlay.classList.add('active');
                    } else {
                        profileSidebar.classList.remove('active');
                        document.body.classList.remove('menu-open');
                        if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                    }
                });
            }

            // ✅ Close sidebar when clicking overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    closeAllSidebars();
                });
            }

            // ✅ Close sidebar when clicking outside
            document.addEventListener('click', function(event) {
                const isClickInsideNav = navbarNav && navbarNav.contains(event.target);
                const isClickInsideProfile = profileSidebar && profileSidebar.contains(event.target);
                const isClickHamburger = hamburgerMenu && hamburgerMenu.contains(event.target);
                const isClickUserBtn = userProfileBtn && userProfileBtn.contains(event.target);

                if (!isClickInsideNav && !isClickInsideProfile && !isClickHamburger && !isClickUserBtn) {
                    closeAllSidebars();
                }
            });

            // ✅ Close with ESC key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeAllSidebars();
                }
            });
        });

        // Function toggleSidebarHome untuk kompatibilitas
        function toggleSidebarHome() {
            const profileSidebar = document.querySelector('.floating-sidebar-home');
            const navbarNav = document.querySelector('.navbar-nav');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (navbarNav && navbarNav.classList.contains('active')) {
                navbarNav.classList.remove('active');
            }

            if (profileSidebar) {
                profileSidebar.classList.toggle('active');

                if (profileSidebar.classList.contains('active')) {
                    document.body.classList.add('menu-open');
                    if (sidebarOverlay) sidebarOverlay.classList.add('active');
                } else {
                    document.body.classList.remove('menu-open');
                    if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                }
            }
        }

        // Function untuk menampilkan notifikasi toast
        function showNotification(message, type = 'success') {
            const existingToast = document.querySelector('.notification-toast');
            if (existingToast) {
                existingToast.remove();
            }

            const toast = document.createElement('div');
            toast.className = `notification-toast ${type}`;

            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

            toast.innerHTML = `
                <i class="fas ${icon}"></i>
                <span class="notification-message">${message}</span>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('show');
            }, 100);

            setTimeout(() => {
                toast.classList.remove('show');
                toast.classList.add('hide');

                setTimeout(() => {
                    toast.remove();
                }, 400);
            }, 3000);
        }

        // Cek session flash message saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showNotification('{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showNotification('{{ session('error') }}', 'error');
            @endif
        });

        // Cek localStorage untuk notifikasi logout success
        if (localStorage.getItem('logoutSuccess') === 'true') {
            localStorage.removeItem('logoutSuccess');
            showNotification('Berhasil logout!', 'success');
        }
    </script>

</body>

</html>
