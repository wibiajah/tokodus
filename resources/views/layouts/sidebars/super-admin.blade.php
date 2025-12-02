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
<a href="{{ route('toko.index') }}" 
   class="nav-item {{ request()->routeIs('toko.*') ? 'active' : '' }}"
   title="Manajemen Toko">
    <i class="fas fa-store"></i>
    <span class="nav-text">Manajemen Toko</span>
</a>

<!-- Manajemen User -->
<a href="{{ route('user.index') }}" 
   class="nav-item {{ request()->routeIs('user.*') ? 'active' : '' }}"
   title="Manajemen User">
    <i class="fas fa-users-cog"></i>
    <span class="nav-text">Manajemen User</span>
</a>