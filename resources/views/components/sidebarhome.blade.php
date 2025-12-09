{{-- resources/views/components/sidebarhome.blade.php --}}
{{-- Sidebar ini hanya muncul di halaman home --}}

<!-- Sidebar Overlay -->
<div class="sidebar-overlay-home" id="sidebarOverlayHome" onclick="closeSidebarHome()"></div>

<!-- Floating Sidebar (Hanya untuk Home) -->
<div class="floating-sidebar-home" id="floatingSidebarHome">
    @auth
        <!-- Sidebar Content -->
        <div class="sidebar-content-home">
            <ul class="sidebar-menu-home">
                @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'kepala_toko', 'staff_admin']))
                    @php
                        $dashboardRoute = match(auth()->user()->role) {
                            'super_admin' => route('superadmin.dashboard'),
                            'admin' => route('admin.dashboard'),
                            'kepala_toko' => route('kepala-toko.dashboard'),
                            'staff_admin' => route('staff.dashboard'),
                            default => route('home')
                        };
                    @endphp
                    <li class="sidebar-menu-item-home">
                        <a href="{{ $dashboardRoute }}" class="sidebar-menu-link-home">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endif

                <li class="sidebar-menu-item-home">
                    <a href="#" class="sidebar-menu-link-home">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>

                <li class="sidebar-menu-item-home">
                    <a href="#" class="sidebar-menu-link-home">
                        <div class="icon-wrapper-home">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge-home">3</span>
                        </div>
                        <span>Keranjang</span>
                    </a>
                </li>

                <li class="sidebar-menu-item-home">
                    <a href="#" class="sidebar-menu-link-home">
                        <i class="fas fa-box"></i>
                        <span>Pesanan Saya</span>
                    </a>
                </li>

                <li class="sidebar-menu-item-home">
                    <a href="#" class="sidebar-menu-link-home">
                        <i class="fas fa-heart"></i>
                        <span>Wishlist</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Logout Button -->
        <div class="sidebar-logout-home">
            <button class="logout-btn-home" onclick="handleLogoutHome(event)">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar</span>
            </button>
        </div>
    @endauth
</div>

<style>
    /* ========== FLOATING SIDEBAR STYLES (HANYA UNTUK HOME) ========== */
    
    /* Sidebar Overlay */
    .sidebar-overlay-home {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 998;
    }

    .sidebar-overlay-home.active {
        opacity: 1;
        visibility: visible;
    }

    /* Floating Sidebar */
    .floating-sidebar-home {
        position: fixed;
        top: 85px; /* Turunkan posisi top agar tidak nyembul */
        right: -90px;
        width: 90px;
        height: calc(100vh - 155px); /* Sesuaikan height: 75px (top) + 80px (bottom WA) = 155px */
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fc 100%);
        box-shadow: -5px 0 25px rgba(0, 0, 0, 0.2);
        transition: right 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 999;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border-top-left-radius: 15px;
        border-bottom-left-radius: 15px;
    }

    .floating-sidebar-home.active {
        right: 0;
    }

    /* Sidebar Content */
    .sidebar-content-home {
        flex: 1;
        overflow: hidden;
        padding: 15px 0;
        display: flex;
        align-items: center;
    }

    /* Sidebar Menu */
    .sidebar-menu-home {
        list-style: none;
        padding: 0;
        margin: 0;
        width: 100%;
    }

    .sidebar-menu-item-home {
        margin: 0;
    }

    .sidebar-menu-link-home {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 15px 8px;
        color: #2d3748;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.65rem;
        transition: all 0.3s ease;
        position: relative;
        border-left: 3px solid transparent;
    }

    .sidebar-menu-link-home:hover {
        background: linear-gradient(90deg, rgba(31, 67, 144, 0.08) 0%, transparent 100%);
        border-left-color: #1f4390;
    }

    /* Icon Wrapper untuk Badge */
    .icon-wrapper-home {
        position: relative;
        display: inline-block;
    }

    .sidebar-menu-link-home i {
        font-size: 1.4rem;
        color: #1f4390;
        transition: all 0.3s ease;
    }

    .sidebar-menu-link-home:hover i {
        transform: scale(1.15);
    }

    .sidebar-menu-link-home > span {
        text-align: center;
        line-height: 1.2;
    }

    /* Badge di Icon */
    .sidebar-menu-link-home .badge-home {
        position: absolute;
        top: -8px;
        right: -10px;
        background: #e74a3b;
        color: white;
        padding: 2px 5px;
        border-radius: 8px;
        font-size: 0.6rem;
        font-weight: 600;
        min-width: 16px;
        text-align: center;
        line-height: 1;
    }

    /* Logout Button */
    .sidebar-logout-home {
        padding: 12px 10px 18px 10px;
        background: #f8f9fc;
        border-top: 2px solid #e0e0e0;
    }

    .logout-btn-home {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 12px 8px;
        background: linear-gradient(135deg, #e74a3b 0%, #d32f2f 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.65rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(231, 74, 59, 0.3);
    }

    .logout-btn-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(231, 74, 59, 0.4);
    }

    .logout-btn-home i {
        font-size: 1.3rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .floating-sidebar-home {
            width: 85px;
            right: -85px;
            top: 75px;
            height: calc(100vh - 155px);
        }

        .sidebar-menu-link-home {
            padding: 12px 6px;
            font-size: 0.6rem;
        }

        .sidebar-menu-link-home i {
            font-size: 1.2rem;
        }
    }

    @media only screen and (max-width: 440px) {
        .floating-sidebar-home {
            width: 75px;
            right: -75px;
            top: 75px;
            height: calc(100vh - 150px);
        }

        .sidebar-menu-link-home {
            padding: 10px 5px;
            font-size: 0.55rem;
        }

        .sidebar-menu-link-home i {
            font-size: 1.1rem;
        }

        .sidebar-logout-home {
            padding: 10px 8px 15px 8px;
        }

        .logout-btn-home {
            padding: 10px 6px;
            font-size: 0.6rem;
        }

        .logout-btn-home i {
            font-size: 1.1rem;
        }
    }
</style>

<script>
    // Toggle Floating Sidebar (Hanya untuk Home)
    function toggleSidebarHome() {
        const sidebar = document.getElementById('floatingSidebarHome');
        const overlay = document.getElementById('sidebarOverlayHome');
        
        if (sidebar && overlay) {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // Prevent body scroll when sidebar is open
            if (sidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
    }
    
    // Close Sidebar
    function closeSidebarHome() {
        const sidebar = document.getElementById('floatingSidebarHome');
        const overlay = document.getElementById('sidebarOverlayHome');
        
        if (sidebar && overlay) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    // Close sidebar with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeSidebarHome();
        }
    });
    
    // Logout function untuk sidebar home
    function handleLogoutHome(event) {
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
    }
</script>