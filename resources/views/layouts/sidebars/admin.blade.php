{{-- resources/views/layouts/sidebars/admin.blade.php --}}

<!-- Dashboard Admin -->
<a href="{{ route('admin.dashboard') }}" 
   class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
   title="Dashboard">
    <i class="fas fa-tachometer-alt"></i>
    <span class="nav-text">Dashboard</span>
</a>

{{-- 🔥 ADMIN TIDAK MEMILIKI AKSES KE TOKO DAN USER --}}
{{-- Menu Toko dan User dihapus dari sidebar admin --}}