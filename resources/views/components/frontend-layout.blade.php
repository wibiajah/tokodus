{{-- resources/views/frontend/layouts/frontend-layout.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Tokodus | Solusi Packaging Anda' }}</title>
    
    <link href="{{ asset('frontend/assets/img/logo-icon.ico') }}" rel="icon" />
    
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet" />
    
    <!-- Feather Icon -->
    <script src="https://unpkg.com/feather-icons"></script>
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/banner.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/product-card.css') }}" />
    
    <style>
        /* User Dropdown Styles - CSS Spesifik untuk Dropdown */
        .navbar-extra .user-dropdown-container {
            position: relative;
            display: inline-block;
        }

        .navbar-extra .user-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 8px;
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
        }

        .navbar-extra .user-dropdown-btn:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--tertiary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(31, 67, 144, 0.3);
        }

        .navbar-extra .user-avatar-small {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--contrast);
        }

        .navbar-extra .user-name-text {
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--contrast) !important;
        }

        .navbar-extra .dropdown-arrow {
            font-size: 0.7rem;
            transition: transform 0.3s ease;
            color: var(--contrast) !important;
        }

        .navbar-extra .user-dropdown-container.active .dropdown-arrow {
            transform: rotate(180deg);
        }

        .navbar-extra .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: #ffffff;
            border: 2px solid var(--secondary);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            min-width: 180px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 1000;
            overflow: hidden;
            padding: 8px 0;
        }

        .navbar-extra .user-dropdown-container.active .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* CSS SUPER SPESIFIK untuk menu item */
        .navbar-extra .user-dropdown-menu .dropdown-menu-item {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 12px 16px !important;
            color: #000000 !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            font-family: 'Ubuntu', sans-serif !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            background: #ffffff !important;
            line-height: normal !important;
        }

        .navbar-extra .user-dropdown-menu .dropdown-menu-item:hover {
            background: #f5f5f5 !important;
            color: #000000 !important;
        }

        /* CSS SUPER SPESIFIK untuk text dalam menu */
        .navbar-extra .user-dropdown-menu .dropdown-menu-item span {
            color: #000000 !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            font-family: 'Ubuntu', sans-serif !important;
        }

        .navbar-extra .user-dropdown-menu .dropdown-menu-item:hover span {
            color: #000000 !important;
        }

        /* CSS SUPER SPESIFIK untuk icon */
        .navbar-extra .user-dropdown-menu .dropdown-menu-item i {
            width: 20px !important;
            text-align: center !important;
            color: #1f4390 !important;
            transition: all 0.2s ease !important;
            font-size: 1.1rem !important;
        }

        .navbar-extra .user-dropdown-menu .dropdown-menu-item.logout-item i {
            color: #e74a3b !important;
        }

        .navbar-extra .user-dropdown-menu .dropdown-divider {
            height: 1px;
            background: #e0e0e0;
            margin: 4px 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-extra .user-name-text {
                display: none;
            }
            
            .navbar-extra .user-dropdown-btn {
                padding: 8px 12px;
            }
        }

        @media only screen and (max-width: 440px) and (orientation: portrait) {
            .navbar-extra .user-dropdown-menu {
                min-width: 160px;
            }
            
            .navbar-extra .user-dropdown-menu .dropdown-menu-item {
                padding: 10px 14px !important;
                font-size: 0.85rem !important;
            }
            
            .navbar-extra .user-dropdown-menu .dropdown-menu-item span {
                font-size: 0.85rem !important;
            }
            
            .navbar-extra .user-dropdown-menu .dropdown-menu-item i {
                font-size: 0.9rem !important;
            }
        }
        /* Notification Toast Styles ikon, hapus bisa */
.notification-toast {
    position: fixed;
    top: 20px;
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

/* Responsive */
@media (max-width: 768px) {
    .notification-toast {
        top: 10px;
        right: 10px;
        left: 10px;
        padding: 12px 16px;
    }
    
    .notification-toast .notification-message {
        font-size: 0.85rem;
    }
}
    </style>
</head>
<body>
    
    <!-- Navbar -->
    <nav class="navbar">
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
                <a href="#" id="shopping-cart"><i data-feather="shopping-cart"></i></a>
                
                @auth
                    <li>
                        <div class="user-dropdown-container" id="userDropdown">
                            {{-- Jika di halaman home, klik untuk buka sidebar --}}
                            @if(Route::currentRouteName() == 'home')
                                <button class="user-dropdown-btn" onclick="toggleSidebarHome()">
                                    <img src="{{ auth()->user()->foto_profil ? asset('storage/' . auth()->user()->foto_profil) : asset('img/default-avatar.png') }}" 
                                         alt="User" class="user-avatar-small">
                                    <span class="user-name-text">{{ auth()->user()->name }}</span>
                                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                                </button>
                            @else
                                {{-- Jika bukan di halaman home, gunakan dropdown biasa --}}
                                <button class="user-dropdown-btn" onclick="toggleUserDropdown(event)">
                                    <img src="{{ auth()->user()->foto_profil ? asset('storage/' . auth()->user()->foto_profil) : asset('img/default-avatar.png') }}" 
                                         alt="User" class="user-avatar-small">
                                    <span class="user-name-text">{{ auth()->user()->name }}</span>
                                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                                </button>
                                
                                <div class="user-dropdown-menu">
                                    @php
                                        $dashboardRoute = match(auth()->user()->role) {
                                            'super_admin' => route('superadmin.dashboard'),
                                            'admin' => route('admin.dashboard'),
                                            'kepala_toko' => route('kepala-toko.dashboard'),
                                            'staff_admin' => route('staff.dashboard'),
                                            default => route('home')
                                        };
                                    @endphp
                                    
                                    @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'kepala_toko', 'staff_admin']))
                                        <a href="{{ $dashboardRoute }}" class="dropdown-menu-item">
                                            <i class="fas fa-tachometer-alt"></i>
                                            <span>Dashboard</span>
                                        </a>
                                        
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    
                                    <a href="#" class="dropdown-menu-item">
                                        <i class="fas fa-user"></i>
                                        <span>Profile</span>
                                    </a>
                                    
                                    <a href="#" class="dropdown-menu-item">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span>Keranjang</span>
                                    </a>
                                    
                                    <a href="#" class="dropdown-menu-item">
                                        <i class="fas fa-box"></i>
                                        <span>Pesanan Saya</span>
                                    </a>
                                    
                                    <div class="dropdown-divider"></div>
                                    
                                    <a href="#" onclick="handleLogout(event)" class="dropdown-menu-item logout-item">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </li>
                @else
                    <li><a href="#" onclick="openLoginModal()" id="nav-get-in-touch">Login</a></li>
                @endauth
                
                <li><a href="#" id="hamburger-menu"><i data-feather="menu"></i></a></li>
            </ul>
        </div>
    </nav>

    {{-- Include Sidebar hanya untuk halaman home --}}
    @if(Route::currentRouteName() == 'home')
        @include('components.sidebarhome')
    @endif
    
       @guest
        @include('components.login-modal')
    @endguest
    <!-- Main Content -->
    {{ $slot }}
    
    <!-- Footer Section -->
    <div class="footer" id="footer">
        <div class="footer-container">
            <div class="footer-content">
                <p>Sedang Butuh Packaging seperti apa?</p>
                <a href="{{ route('home') }}#stores">Konsultasikan Sekarang</a>
            </div>

            <div class="footer-links">
                <!-- Grup 1: Link Utama -->
                <div class="footer-group">
                    <div class="footer-column">
                        <ul>
                            <li><a href="{{ route('home') }}#service">Services</a></li>
                            <li><a href="{{ route('catalog') }}">Catalog</a></li>
                            <li><a href="{{ route('home') }}#projects">Projects</a></li>
                            <li><a href="{{ route('home') }}#resources">Resources</a></li>
                            <li><a href="#">Materials</a></li>
                            <li><a href="#">Guidelines</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="{{ route('home') }}#stores">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="footer-column footer-privacy">
                        <ul>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms & Conditions</a></li>
                            <li><a href="mailto:marketing@tokodus.com">marketing@tokodus.com</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Grup 2: Link Career & Signup -->
                <div class="footer-group">
                    <div class="footer-column">
                        <ul>
                            <li><a href="#">Career</a></li>
                            <li><a href="{{ route('home') }}#stores">Cabang Toko</a></li>
                            <li>
                                <a href="https://www.instagram.com/tokodusbdg/" class="instagram" target="_blank" id="instagram">Instagram</a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/tokodusbdg/" class="facebook" target="_blank" id="facebook">Facebook</a>
                            </li>
                            <li>
                                <a href="https://www.tiktok.com/@tokdus/" class="tiktok" target="_blank" id="tiktok">Tiktok</a>
                            </li>
                            <li>
                                <a href="https://www.youtube.com/@TokodusOfficial" class="youtube" target="_blank" id="youtube">Youtube</a>
                            </li>
                        </ul>
                    </div>
                    <div class="footer-column footer-signup">
                        <form action="#" method="POST" class="signup-form">
                            @csrf
                            <input type="email" name="email" placeholder="Masukkan email Anda" required />
                            <button type="submit">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p id="copyright" class="copyright">
                    Copyright &copy; <span id="year">{{ date('Y') }}</span> Tokodus. All Rights Reserved
                </p>
                <!-- Footer Brand dan Logo -->
                <div class="footer-brand">
                    <a href="{{ route('home') }}" class="footer-logo" id="footer-logo">
                        <img src="{{ asset('frontend/assets/img/logo-icon.png') }}" alt="Tokodus Logo" class="footer-logo" />
                        <span class="footer-name">Tokodus</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Ends -->
    
    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/6281120061333" class="whatsapp-float" target="_blank">
        <img src="{{ asset('frontend/assets/img/icon/cs.png') }}" alt="WhatsApp" />
    </a>
    
    <!-- Hidden Logout Form -->
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <!-- JavaScript -->
    <script src="{{ asset('frontend/assets/js/script.js') }}" defer></script>
    
    <script>
        // Initialize Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Toggle User Dropdown (untuk halaman selain home)
        function toggleUserDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('active');
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
        
        // Logout function untuk dropdown (halaman selain home)
        function handleLogout(event) {
            event.preventDefault();
            
            // Konfirmasi sebelum logout
            if (confirm('Anda yakin untuk logout?')) {
                // Submit logout jika user klik OK
                fetch('{{ route("logout") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Tampilkan pesan sukses
                        alert('Berhasil logout!');
                        // Reload halaman home
                        window.location.href = '{{ route("home") }}';
                    } else {
                        alert('Terjadi kesalahan saat logout');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat logout');
                });
            }
            // Jika user klik Cancel, tidak terjadi apa-apa
        }

        //ini dia buat jalanin notifkasi Function untuk menampilkan notifikasi toast
function showNotification(message, type = 'success') {
    // Hapus notifikasi yang ada jika ada
    const existingToast = document.querySelector('.notification-toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Buat elemen notifikasi
    const toast = document.createElement('div');
    toast.className = `notification-toast ${type}`;
    
    // Icon berdasarkan tipe
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    toast.innerHTML = `
        <i class="fas ${icon}"></i>
        <span class="notification-message">${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    // Trigger animasi muncul
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Auto hide setelah 3 detik
    setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.add('hide');
        
        // Hapus dari DOM setelah animasi selesai
        setTimeout(() => {
            toast.remove();
        }, 400);
    }, 3000);
}

// Cek session flash message saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        showNotification('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showNotification('{{ session('error') }}', 'error');
    @endif
});

// Update fungsi logout untuk menampilkan notifikasi
function handleLogout(event) {
    event.preventDefault();
    
    // Konfirmasi sebelum logout
    if (confirm('Anda yakin untuk logout?')) {
        // Submit logout jika user klik OK
        fetch('{{ route("logout") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                // Simpan flag logout success di localStorage
                localStorage.setItem('logoutSuccess', 'true');
                // Redirect ke home
                window.location.href = '{{ route("home") }}';
            } else {
                showNotification('Terjadi kesalahan saat logout', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat logout', 'error');
        });
    }
}

// Cek localStorage untuk notifikasi logout success
if (localStorage.getItem('logoutSuccess') === 'true') {
    localStorage.removeItem('logoutSuccess');
    showNotification('Berhasil logout!', 'success');
}
    </script>
    
</body>
</html>