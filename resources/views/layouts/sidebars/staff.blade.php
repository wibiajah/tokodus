{{-- resources/views/layouts/sidebars/staff.blade.php --}}

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