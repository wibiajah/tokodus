{{-- resources/views/layouts/sidebars/kepala-toko.blade.php --}}

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