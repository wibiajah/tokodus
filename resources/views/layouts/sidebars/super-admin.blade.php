<!-- DASHBOARD -->
<a href="{{ route('superadmin.dashboard') }}" 
   class="nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}"
   title="Dashboard">
    <i class="fas fa-tachometer-alt"></i>
    <span class="nav-text">Dashboard</span>
</a>

<div class="sidebar-divider"></div>

<!-- MASTER -->
<div class="sidebar-heading">Master</div>

<a href="{{ route('superadmin.toko.index') }}" 
   class="nav-item {{ request()->routeIs('superadmin.toko.*') ? 'active' : '' }}"
   title="Manajemen Toko">
    <i class="fas fa-store"></i>
    <span class="nav-text">Manajemen Toko</span>
</a>

<a href="{{ route('superadmin.user.index') }}" 
   class="nav-item {{ request()->routeIs('superadmin.user.*') ? 'active' : '' }}"
   title="Manajemen User">
    <i class="fas fa-users-cog"></i>
    <span class="nav-text">Manajemen User</span>
</a>

<div class="sidebar-divider"></div>

<!-- PRODUK -->
<div class="sidebar-heading">Produk</div>

@php
$isProductActive =
    request()->routeIs('superadmin.categories.*') ||
    request()->routeIs('superadmin.stocks.*') ||
    request()->routeIs('superadmin.vouchers.*');
@endphp

<!-- PARENT MENU WITH DROPDOWN -->
<div class="nav-item-wrapper {{ $isProductActive ? 'active' : '' }}">
    <a href="{{ route('superadmin.products.index') }}" 
       class="nav-item"
       title="Manajemen Produk">
        <i class="fas fa-box"></i>
        <span class="nav-text">Manajemen Produk</span>
    </a>
    
    <button class="nav-dropdown-toggle" 
            data-toggle="collapse"
            data-target="#collapseProduct"
            aria-expanded="{{ $isProductActive ? 'true' : 'false' }}">
        <i class="fas fa-chevron-down"></i>
    </button>
</div>

<!-- DROPDOWN -->
<div id="collapseProduct" class="collapse nav-collapse {{ $isProductActive ? 'show' : '' }}">
    <a class="collapse-item {{ request()->routeIs('superadmin.categories.*') ? 'active' : '' }}"
       href="{{ route('superadmin.categories.index') }}">
        <i class="fas fa-tags"></i>
        <span>Kategori</span>
    </a>

    <a class="collapse-item {{ request()->routeIs('superadmin.stocks.*') ? 'active' : '' }}"
       href="{{ route('superadmin.stocks.index') }}">
        <i class="fas fa-warehouse"></i>
        <span>Inventori</span>
    </a>

    <a class="collapse-item {{ request()->routeIs('superadmin.vouchers.*') ? 'active' : '' }}"
       href="{{ route('superadmin.vouchers.index') }}">
        <i class="fas fa-ticket-alt"></i>
        <span>Voucher</span>
    </a>
</div>

<div class="sidebar-divider"></div>

<style>
/* Wrapper untuk parent menu + dropdown toggle */
.nav-item-wrapper {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
}

.nav-item-wrapper.active {
    background-color: rgba(255, 255, 255, 0.1);
}

.nav-item-wrapper .nav-item {
    flex: 1;
    padding: 0;
    margin: 0;
}

/* Dropdown toggle button */
.nav-dropdown-toggle {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.8);
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    margin-left: 0.5rem;
    transition: all 0.2s;
}

.nav-dropdown-toggle:hover {
    color: #fff;
}

.nav-dropdown-toggle i {
    transition: transform 0.2s;
    font-size: 0.75rem;
}

.nav-dropdown-toggle[aria-expanded="true"] i {
    transform: rotate(180deg);
}

/* HIDE dropdown toggle when sidebar collapsed */
.sidebar.collapsed .nav-dropdown-toggle {
    display: none;
}

/* Dropdown items styling */
.nav-collapse {
    padding-left: 1rem;
    margin-top: 0.25rem;
}

.collapse-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.2s;
    border-radius: 0.25rem;
    margin-bottom: 0.25rem;
}

.collapse-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: #fff;
    text-decoration: none;
}

.collapse-item.active {
    background-color: rgba(255, 255, 255, 0.15);
    color: #fff;
}

.collapse-item i {
    width: 1.25rem;
    margin-right: 0.75rem;
    font-size: 0.875rem;
}

.collapse-item span {
    font-size: 0.9rem;
}
</style>