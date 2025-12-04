{{-- resources/views/layouts/sidebars/super-admin.blade.php --}}

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
<a href="{{ route('superadmin.toko.index') }}" 
   class="nav-item {{ request()->routeIs('superadmin.toko.*') ? 'active' : '' }}"
   title="Manajemen Toko">
    <i class="fas fa-store"></i>
    <span class="nav-text">Manajemen Toko</span>
</a>

<!-- Manajemen User -->
<a href="{{ route('superadmin.user.index') }}" 
   class="nav-item {{ request()->routeIs('superadmin.user.*') ? 'active' : '' }}"
   title="Manajemen User">
    <i class="fas fa-users-cog"></i>
    <span class="nav-text">Manajemen User</span>
</a>

<div class="sidebar-divider"></div>
<div class="sidebar-heading">Manajemen Produk</div>

@php
    $isProductActive = request()->routeIs('superadmin.products.*') || 
                       request()->routeIs('superadmin.categories.*') || 
                       request()->routeIs('superadmin.stocks.*') || 
                       request()->routeIs('superadmin.vouchers.*');
@endphp

<!-- Manajemen Produk -->
<a href="#" 
   class="nav-item {{ $isProductActive ? 'active' : '' }}" 
   data-toggle="collapse" 
   data-target="#collapseProduct" 
   aria-expanded="{{ $isProductActive ? 'true' : 'false' }}"
   title="Manajemen Produk">
    <i class="fas fa-box"></i>
    <span class="nav-text">Manajemen Produk</span>
    <i class="fas fa-chevron-down nav-arrow"></i>
</a>
<div id="collapseProduct" class="collapse nav-collapse {{ $isProductActive ? 'show' : '' }}">
    <a class="collapse-item {{ request()->routeIs('superadmin.products.*') ? 'active' : '' }}" 
       href="{{ route('superadmin.products.index') }}">
        Kelola Produk
    </a>
    <a class="collapse-item {{ request()->routeIs('superadmin.categories.*') ? 'active' : '' }}" 
       href="{{ route('superadmin.categories.index') }}">
        Kategori
    </a>
    <a class="collapse-item {{ request()->routeIs('superadmin.stocks.*') ? 'active' : '' }}" 
       href="{{ route('superadmin.stocks.index') }}">
        Inventori
    </a>
    <a class="collapse-item {{ request()->routeIs('superadmin.vouchers.*') ? 'active' : '' }}" 
       href="{{ route('superadmin.vouchers.index') }}">
        Voucher
    </a>
</div>