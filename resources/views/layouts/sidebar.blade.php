{{-- resources/views/layouts/sidebar.blade.php --}}
<div class="sidebar-container" id="sidebarContainer">
    <!-- Sidebar Toggle Button (untuk expand/collapse) -->
    <div class="sidebar-header">
        <button class="sidebar-collapse-btn" id="sidebarCollapseBtn">
            <i class="fas fa-ellipsis-h"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        @if (auth()->user()->role === 'super_admin')
            <!-- Dashboard -->
            <a href="{{ route('superadmin.dashboard') }}" 
               class="nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}"
               title="Dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span class="nav-text">Dashboard</span>
            </a>

            <div class="sidebar-divider"></div>
            <div class="sidebar-heading">Manajemen Master</div>

            <!-- Manajemen Toko -->
            <a href="#" class="nav-item" data-toggle="collapse" data-target="#collapseToko" title="Manajemen Toko">
                <i class="fas fa-store"></i>
                <span class="nav-text">Manajemen Toko</span>
                <i class="fas fa-chevron-down nav-arrow"></i>
            </a>
            <div id="collapseToko" class="collapse nav-collapse">
                <h6 class="collapse-header">Menu Toko:</h6>
                <a class="collapse-item" href="{{ route('toko.index') }}">Daftar Toko</a>
                <a class="collapse-item" href="{{ route('toko.create') }}">Tambah Toko</a>
            </div>

            <!-- Manajemen User -->
            <a href="#" class="nav-item" data-toggle="collapse" data-target="#collapseUser" title="Manajemen User">
                <i class="fas fa-users-cog"></i>
                <span class="nav-text">Manajemen User</span>
                <i class="fas fa-chevron-down nav-arrow"></i>
            </a>
            <div id="collapseUser" class="collapse nav-collapse">
                <h6 class="collapse-header">Menu User:</h6>
                <a class="collapse-item" href="{{ route('user.index') }}">Daftar User</a>
                <a class="collapse-item" href="{{ route('user.create') }}">Tambah User</a>
            </div>
        @endif

        @if (auth()->user()->role === 'admin')
            <!-- Dashboard Admin -->
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               title="Dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span class="nav-text">Dashboard</span>
            </a>

            <div class="sidebar-divider"></div>
            <div class="sidebar-heading">Manajemen Master</div>

            <!-- Manajemen Toko -->
            <a href="#" class="nav-item" data-toggle="collapse" data-target="#collapseToko" title="Manajemen Toko">
                <i class="fas fa-store"></i>
                <span class="nav-text">Manajemen Toko</span>
                <i class="fas fa-chevron-down nav-arrow"></i>
            </a>
            <div id="collapseToko" class="collapse nav-collapse">
                <h6 class="collapse-header">Menu Toko:</h6>
                <a class="collapse-item" href="#">Daftar Toko</a>
                <a class="collapse-item" href="#">Tambah Toko</a>
            </div>

            <!-- Manajemen User -->
            <a href="#" class="nav-item" data-toggle="collapse" data-target="#collapseUser" title="Manajemen User">
                <i class="fas fa-users"></i>
                <span class="nav-text">Manajemen User</span>
                <i class="fas fa-chevron-down nav-arrow"></i>
            </a>
            <div id="collapseUser" class="collapse nav-collapse">
                <h6 class="collapse-header">Menu User:</h6>
                <a class="collapse-item" href="#">Daftar Admin</a>
                <a class="collapse-item" href="#">Daftar Kepala Toko</a>
                <a class="collapse-item" href="#">Daftar Staff Admin</a>
            </div>
        @endif

        @if (auth()->user()->role === 'kepala_toko')
            <!-- Dashboard Kepala Toko -->
            <a href="{{ route('kepala-toko.dashboard') }}" 
               class="nav-item {{ request()->routeIs('kepala-toko.dashboard') ? 'active' : '' }}"
               title="Dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span class="nav-text">Dashboard</span>
            </a>

            <div class="sidebar-divider"></div>
            <div class="sidebar-heading">Manajemen Toko</div>

            <a href="#" class="nav-item" title="Informasi Toko">
                <i class="fas fa-store"></i>
                <span class="nav-text">Informasi Toko</span>
            </a>

            <!-- Manajemen Staff -->
            <a href="#" class="nav-item" data-toggle="collapse" data-target="#collapseStaff" title="Manajemen Staff">
                <i class="fas fa-users"></i>
                <span class="nav-text">Manajemen Staff</span>
                <i class="fas fa-chevron-down nav-arrow"></i>
            </a>
            <div id="collapseStaff" class="collapse nav-collapse">
                <h6 class="collapse-header">Menu Staff:</h6>
                <a class="collapse-item" href="#">Daftar Staff</a>
                <a class="collapse-item" href="#">Tambah Staff</a>
            </div>

            <div class="sidebar-divider"></div>
            <div class="sidebar-heading">Transaksi</div>

            <a href="#" class="nav-item" title="Orderan">
                <i class="fas fa-shopping-cart"></i>
                <span class="nav-text">Orderan</span>
                <span class="badge badge-warning badge-counter">Soon</span>
            </a>

            <a href="#" class="nav-item" title="Laporan">
                <i class="fas fa-chart-line"></i>
                <span class="nav-text">Laporan</span>
                <span class="badge badge-warning badge-counter">Soon</span>
            </a>
        @endif

        @if (auth()->user()->role === 'staff_admin')
            <!-- Dashboard Staff -->
            <a href="{{ route('staff.dashboard') }}" 
               class="nav-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}"
               title="Dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span class="nav-text">Dashboard</span>
            </a>

            <div class="sidebar-divider"></div>
            <div class="sidebar-heading">Transaksi</div>

            <a href="#" class="nav-item" title="Orderan">
                <i class="fas fa-shopping-cart"></i>
                <span class="nav-text">Orderan</span>
                <span class="badge badge-warning badge-counter">Soon</span>
            </a>

            <a href="#" class="nav-item" title="Riwayat Orderan">
                <i class="fas fa-history"></i>
                <span class="nav-text">Riwayat Orderan</span>
                <span class="badge badge-warning badge-counter">Soon</span>
            </a>

            <div class="sidebar-divider"></div>
            <div class="sidebar-heading">Informasi</div>

            <a href="#" class="nav-item" title="Informasi Toko">
                <i class="fas fa-store"></i>
                <span class="nav-text">Informasi Toko</span>
            </a>
        @endif

        <div class="sidebar-divider"></div>
    </nav>
</div>

<style>
    /* Sidebar Header with Toggle Button */
    .sidebar-header {
        padding: 1rem;
        display: flex;
        justify-content: flex-end;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    .sidebar-collapse-btn {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #fff;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .sidebar-collapse-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.05);
    }

    /* Sidebar Collapsed State */
    .sidebar-container.collapsed {
        width: 80px !important;
    }

    .sidebar-container.collapsed .sidebar-header {
        justify-content: center;
    }

    .sidebar-container.collapsed .nav-text,
    .sidebar-container.collapsed .nav-arrow,
    .sidebar-container.collapsed .sidebar-heading,
    .sidebar-container.collapsed .badge-counter {
        display: none !important;
    }

    .sidebar-container.collapsed .nav-item {
        justify-content: center;
        padding: 0.8rem;
        border-left: 3px solid transparent;
    }

    .sidebar-container.collapsed .nav-item i {
        margin: 0;
        font-size: 1.1rem;
    }

    .sidebar-container.collapsed .nav-collapse,
    .sidebar-container.collapsed .collapse.show {
        display: none !important;
    }

    /* Nav Item Styling */
    .nav-item {
        position: relative;
    }

    .nav-text {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .nav-arrow {
        margin-left: auto;
        font-size: 0.7rem;
        transition: transform 0.3s ease;
    }

    .nav-item[aria-expanded="true"] .nav-arrow {
        transform: rotate(180deg);
    }

    /* Badge Counter */
    .badge-counter {
        margin-left: auto;
    }

    /* Responsive - Desktop Layout */
    @media (min-width: 992px) {
        body {
            grid-template-columns: 260px 1fr;
            transition: grid-template-columns 0.3s ease;
        }

        body.sidebar-collapsed {
            grid-template-columns: 80px 1fr;
        }

        .sidebar-container {
            transition: width 0.3s ease;
        }

        .sidebar-collapse-btn {
            display: flex !important;
        }
    }

    /* Responsive - Mobile Layout */
    @media (max-width: 991px) {
        .sidebar-collapse-btn {
            display: none !important;
        }

        .sidebar-container.collapsed {
            width: 100% !important;
        }

        .sidebar-container.collapsed .nav-text,
        .sidebar-container.collapsed .nav-arrow,
        .sidebar-container.collapsed .sidebar-heading,
        .sidebar-container.collapsed .badge-counter {
            display: inline !important;
        }

        .sidebar-container.collapsed .nav-item {
            justify-content: flex-start;
            padding: 0.6rem 1.25rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
        const sidebarContainer = document.getElementById('sidebarContainer');
        const body = document.body;

        // Load saved state from localStorage
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed && window.innerWidth >= 992) {
            sidebarContainer.classList.add('collapsed');
            body.classList.add('sidebar-collapsed');
        }

        // Toggle collapse on button click
        if (sidebarCollapseBtn) {
            sidebarCollapseBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebarContainer.classList.toggle('collapsed');
                body.classList.toggle('sidebar-collapsed');
                
                // Save state to localStorage
                const isNowCollapsed = sidebarContainer.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isNowCollapsed);

                // Close all open collapses when collapsing
                if (isNowCollapsed) {
                    document.querySelectorAll('.nav-collapse.show').forEach(collapse => {
                        $(collapse).collapse('hide');
                    });
                }
            });
        }

        // Prevent collapse on mobile
        window.addEventListener('resize', function() {
            if (window.innerWidth < 992) {
                sidebarContainer.classList.remove('collapsed');
                body.classList.remove('sidebar-collapsed');
            } else {
                // Restore saved state on resize to desktop
                const savedState = localStorage.getItem('sidebarCollapsed') === 'true';
                if (savedState) {
                    sidebarContainer.classList.add('collapsed');
                    body.classList.add('sidebar-collapsed');
                }
            }
        });
    });
</script>