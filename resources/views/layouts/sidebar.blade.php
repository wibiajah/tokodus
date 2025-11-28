{{-- resources/views/layouts/components/sidebar.blade.php --}}
<ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-3" href="{{ route('home') }}">
        <img src="{{ asset('logo') }}" alt="Logo" class="img-fluid" style="max-height: 60px;">
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    {{-- ============================================ --}}
    {{-- SUPER ADMIN MENU --}}
    {{-- ============================================ --}}
    @if (auth()->user()->role === 'super_admin')
        <!-- Dashboard -->
        <li class="nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('superadmin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Manajemen Master
        </div>

        <!-- Manajemen Toko -->
        <!-- Manajemen Toko -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseToko"
        aria-expanded="true" aria-controls="collapseToko">
        <i class="fas fa-fw fa-store"></i>
        <span>Manajemen Toko</span>
    </a>
    <div id="collapseToko" class="collapse" aria-labelledby="headingToko" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Menu Toko:</h6>
            <a class="collapse-item" href="{{ route('toko.index') }}">Daftar Toko</a>
            <a class="collapse-item" href="{{ route('toko.create') }}">Tambah Toko</a>
        </div>
    </div>
</li>

        <!-- Manajemen User -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser"
                aria-expanded="true" aria-controls="collapseUser">
                <i class="fas fa-fw fa-users-cog"></i>
                <span>Manajemen User</span>
            </a>
            <div id="collapseUser" class="collapse" aria-labelledby="headingUser" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Menu User:</h6>
                    <a class="collapse-item" href="#">Daftar Super Admin</a>
                    <a class="collapse-item" href="#">Daftar Admin</a>
                    <a class="collapse-item" href="#">Daftar Kepala Toko</a>
                    <a class="collapse-item" href="#">Daftar Staff Admin</a>
                </div>
            </div>
        </li>
    @endif

    {{-- ============================================ --}}
    {{-- ADMIN MENU --}}
    {{-- ============================================ --}}
    @if (auth()->user()->role === 'admin')
        <!-- Dashboard -->
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Manajemen Master
        </div>

        <!-- Manajemen Toko -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseToko"
                aria-expanded="true" aria-controls="collapseToko">
                <i class="fas fa-fw fa-store"></i>
                <span>Manajemen Toko</span>
            </a>
            <div id="collapseToko" class="collapse" aria-labelledby="headingToko" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Menu Toko:</h6>
                    <a class="collapse-item" href="#">Daftar Toko</a>
                    <a class="collapse-item" href="#">Tambah Toko</a>
                </div>
            </div>
        </li>

        <!-- Manajemen User (Tanpa Super Admin) -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser"
                aria-expanded="true" aria-controls="collapseUser">
                <i class="fas fa-fw fa-users"></i>
                <span>Manajemen User</span>
            </a>
            <div id="collapseUser" class="collapse" aria-labelledby="headingUser" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Menu User:</h6>
                    <a class="collapse-item" href="#">Daftar Admin</a>
                    <a class="collapse-item" href="#">Daftar Kepala Toko</a>
                    <a class="collapse-item" href="#">Daftar Staff Admin</a>
                </div>
            </div>
        </li>
    @endif

    {{-- ============================================ --}}
    {{-- KEPALA TOKO MENU --}}
    {{-- ============================================ --}}
    @if (auth()->user()->role === 'kepala_toko')
        <!-- Dashboard -->
        <li class="nav-item {{ request()->routeIs('kepala-toko.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kepala-toko.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Manajemen Toko
        </div>

        <!-- Informasi Toko -->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-fw fa-store"></i>
                <span>Informasi Toko</span>
            </a>
        </li>

        <!-- Manajemen Staff -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStaff"
                aria-expanded="true" aria-controls="collapseStaff">
                <i class="fas fa-fw fa-users"></i>
                <span>Manajemen Staff</span>
            </a>
            <div id="collapseStaff" class="collapse" aria-labelledby="headingStaff" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Menu Staff:</h6>
                    <a class="collapse-item" href="#">Daftar Staff</a>
                    <a class="collapse-item" href="#">Tambah Staff</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Transaksi
        </div>

        <!-- Orderan -->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-fw fa-shopping-cart"></i>
                <span>Orderan</span>
                <span class="badge badge-warning badge-counter ml-auto">Coming Soon</span>
            </a>
        </li>

        <!-- Laporan -->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Laporan</span>
                <span class="badge badge-warning badge-counter ml-auto">Coming Soon</span>
            </a>
        </li>
    @endif

    {{-- ============================================ --}}
    {{-- STAFF ADMIN MENU --}}
    {{-- ============================================ --}}
    @if (auth()->user()->role === 'staff_admin')
        <!-- Dashboard -->
        <li class="nav-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('staff.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Transaksi
        </div>

        <!-- Orderan -->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-fw fa-shopping-cart"></i>
                <span>Orderan</span>
                <span class="badge badge-warning badge-counter ml-auto">Coming Soon</span>
            </a>
        </li>

        <!-- Riwayat Orderan -->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-fw fa-history"></i>
                <span>Riwayat Orderan</span>
                <span class="badge badge-warning badge-counter ml-auto">Coming Soon</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Informasi
        </div>

        <!-- Informasi Toko -->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-fw fa-store"></i>
                <span>Informasi Toko</span>
            </a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>