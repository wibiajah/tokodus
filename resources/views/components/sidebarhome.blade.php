{{-- resources/views/components/sidebarhome.blade.php --}}
{{-- Sidebar Universal - Customer & Staff/Admin --}}

<!-- Sidebar Overlay -->
<div class="sidebar-overlay-home" id="sidebarOverlayHome" onclick="closeSidebarHome()"></div>

<!-- Floating Sidebar -->
<div class="floating-sidebar-home" id="floatingSidebarHome">
    <!-- Sidebar Content -->
    <div class="sidebar-content-home">
        <ul class="sidebar-menu-home">
            {{-- CEK CUSTOMER (guard: customer) --}}
            @auth('customer')
                <li class="sidebar-menu-item-home">
                    <a href="{{ route('customer.profile') }}" class="sidebar-menu-link-home">
                        <i class="fas fa-user"></i>
                        <span>Profil</span>
                    </a>
                </li>

                <li class="sidebar-menu-item-home">
                    <a href="{{ route('customer.cart.index') }}" class="sidebar-menu-link-home">
                        <div class="icon-wrapper-home">
                            <i class="fas fa-shopping-cart"></i>
                            {{-- ✅ REAL-TIME CART BADGE --}}
                            @if (auth('customer')->user()->cart_count > 0)
                                <span
                                    class="badge-home cart-count-sidebar">{{ auth('customer')->user()->cart_count }}</span>
                            @else
                                <span class="badge-home cart-count-sidebar" style="display: none;">0</span>
                            @endif
                        </div>
                        <span>Keranjang</span>
                    </a>
                </li>

                 <li class="sidebar-menu-item-home">
                    <a href="{{ route('customer.wishlist.index') }}" class="sidebar-menu-link-home">
                        <div class="icon-wrapper-home">
                            <i class="fas fa-heart"></i>
                            {{-- ✅ REAL-TIME WISHLIST BADGE --}}
                            <span class="badge-home wishlist-count-sidebar" style="display: none;">0</span>
                        </div>
                        <span>Wishlist</span>
                    </a>
                </li>

                <li class="sidebar-menu-item-home">
                    <a href="{{ route('customer.orders.index') }}" class="sidebar-menu-link-home">
                        <i class="fas fa-box"></i>
                        <span>Pesanan</span>
                    </a>
                </li>

                {{-- ✅ NEW - MENU ULASAN SAYA --}}
                <li class="sidebar-menu-item-home">
                    <a href="{{ route('customer.my-reviews') }}" class="sidebar-menu-link-home">
                        <i class="fas fa-star"></i>
                        <span>Ulasan Saya</span>
                    </a>
                </li>

                {{-- ⭐ LOGOUT UNTUK MOBILE - Di bawah Wishlist ⭐ --}}
                <li class="sidebar-menu-item-home sidebar-logout-item-mobile">
                    <button type="button" class="sidebar-menu-link-home logout-link-mobile"
                        onclick="handleLogoutHome(event)">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Keluar</span>
                    </button>
                </li>
            @endauth

            {{-- CEK STAFF/ADMIN (guard: web) --}}
            @auth('web')
                @if (in_array(auth()->user()->role, ['super_admin', 'admin', 'kepala_toko', 'staff_admin']))
                    @php
                        $dashboardRoute = match (auth()->user()->role) {
                            'super_admin' => route('superadmin.dashboard'),
                            'admin' => route('admin.dashboard'),
                            'kepala_toko' => route('kepala-toko.dashboard'),
                            'staff_admin' => route('staff.dashboard'),
                            default => route('home'),
                        };
                    @endphp
                    <li class="sidebar-menu-item-home">
                        <a href="{{ $dashboardRoute }}" class="sidebar-menu-link-home">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    {{-- ⭐ LOGOUT UNTUK STAFF/ADMIN MOBILE ⭐ --}}
                    <li class="sidebar-menu-item-home sidebar-logout-item-mobile">
                        <button type="button" class="sidebar-menu-link-home logout-link-mobile"
                            onclick="handleLogoutHome(event)">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Keluar</span>
                        </button>
                    </li>
                @endif
            @endauth
        </ul>
    </div>

    <!-- Logout Button - DESKTOP ONLY -->
    <div class="sidebar-logout-home sidebar-logout-desktop">
        <button class="logout-btn-home" onclick="handleLogoutHome(event)">
            <i class="fas fa-sign-out-alt"></i>
            <span>Keluar</span>
        </button>
    </div>
</div>

<style>
    /* ========== FLOATING SIDEBAR STYLES ========== */

    /* Badge styling */
    .badge-home {
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
        transition: transform 0.2s ease;
    }

    /* ✅ ANIMATION untuk badge update */
    .badge-home.updated {
        animation: badgePulse 0.4s ease;
    }

    @keyframes badgePulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.3);
        }
    }

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
        z-index: 1000;
    }

    .sidebar-overlay-home.active {
        opacity: 1;
        visibility: visible;
    }

    /* Floating Sidebar - Desktop (Sebelah Kanan) */
    .floating-sidebar-home {
        position: fixed;
        top: 85px;
        right: -90px;
        width: 90px;
        height: calc(100vh - 155px);
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fc 100%);
        box-shadow: -5px 0 25px rgba(0, 0, 0, 0.2);
        transition: right 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1001;
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
        overflow-y: auto;
        overflow-x: hidden;
        padding: 15px 0;
        display: flex;
        align-items: center;
    }

    /* Scrollbar Styling */
    .sidebar-content-home::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-content-home::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .sidebar-content-home::-webkit-scrollbar-thumb {
        background: #1f4390;
        border-radius: 10px;
    }

    .sidebar-content-home::-webkit-scrollbar-thumb:hover {
        background: #163067;
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
        background: none;
        border: none;
        border-left: 3px solid transparent;
        width: 100%;
        cursor: pointer;
    }

    .sidebar-menu-link-home:hover {
        background: linear-gradient(90deg, transparent 0%, rgba(31, 67, 144, 0.08) 100%);
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

    .sidebar-menu-link-home>span {
        text-align: center;
        line-height: 1.2;
    }

    /* Hide Logout Item di Desktop */
    .sidebar-logout-item-mobile {
        display: none;
    }

    /* Logout Button Desktop */
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

    /* ========== RESPONSIVE MOBILE ========== */
    @media (max-width: 768px) {
        .floating-sidebar-home {
            width: 280px;
            max-width: 85%;
            right: -100%;
            top: 0;
            height: 100vh;
            border-radius: 0;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.3);
            padding-top: 80px;
        }

        .floating-sidebar-home.active {
            right: 0;
        }

        .sidebar-content-home {
            padding: 20px 0;
        }

        .sidebar-menu-home {
            width: 100%;
        }

        .sidebar-menu-item-home {
            border-bottom: 1px solid #e0e0e0;
        }

        .sidebar-menu-item-home:last-child {
            border-bottom: none;
        }

        .sidebar-menu-link-home {
            flex-direction: row;
            justify-content: flex-start;
            gap: 18px;
            padding: 18px 30px;
            font-size: 0.95rem;
            border-left: 4px solid transparent;
        }

        .sidebar-menu-link-home:hover {
            background: linear-gradient(90deg, rgba(31, 67, 144, 0.08) 0%, transparent 100%);
        }

        .sidebar-menu-link-home i {
            font-size: 1.5rem;
        }

        .sidebar-menu-link-home>span {
            text-align: left;
        }

        /* ⭐ SHOW Logout sebagai menu item di Mobile ⭐ */
        .sidebar-logout-item-mobile {
            display: block !important;
        }

        .logout-link-mobile {
            color: #e74a3b !important;
        }

        .logout-link-mobile i {
            color: #e74a3b !important;
        }

        .logout-link-mobile:hover {
            background: linear-gradient(90deg, rgba(231, 74, 59, 0.08) 0%, transparent 100%) !important;
            border-left-color: #e74a3b !important;
        }

        /* ⭐ HIDE Logout button desktop di Mobile ⭐ */
        .sidebar-logout-desktop {
            display: none !important;
        }
    }

    /* Small Mobile Screens */
    @media only screen and (max-width: 440px) {
        .floating-sidebar-home {
            width: 260px;
            max-width: 90%;
            padding-top: 75px;
        }

        .sidebar-menu-link-home {
            padding: 16px 25px;
            font-size: 0.9rem;
            gap: 15px;
        }

        .sidebar-menu-link-home i {
            font-size: 1.4rem;
        }
    }

    /* Extra Small Mobile */
    @media only screen and (max-width: 360px) {
        .floating-sidebar-home {
            width: 240px;
        }

        .sidebar-menu-link-home {
            padding: 14px 20px;
            font-size: 0.85rem;
            gap: 12px;
        }

        .sidebar-menu-link-home i {
            font-size: 1.3rem;
        }
    }
</style>

<script>
    // Toggle Floating Sidebar
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

    // Logout function untuk sidebar
    function handleLogoutHome(event) {
        event.preventDefault();

        if (confirm('Anda yakin untuk logout?')) {
            @auth('customer')
                const logoutRoute = '{{ route('customer.logout') }}';
            @endauth

            @auth('web')
                const logoutRoute = '{{ route('logout') }}';
            @endauth

            fetch(logoutRoute, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        localStorage.setItem('logoutSuccess', 'true');
                        window.location.href = '{{ route('home') }}';
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

    // ========================================
    // ✅ REAL-TIME WISHLIST BADGE UPDATE
    // ========================================
    @auth('customer')
        /**
         * Update wishlist badge dengan animasi
         * @param {number} count - Jumlah wishlist
         */
        function updateWishlistBadgeSidebar(count) {
            const badge = document.querySelector('.wishlist-count-sidebar');

            if (badge) {
                badge.textContent = count;

                if (count > 0) {
                    badge.style.display = 'block';

                    // ✅ Trigger animation
                    badge.classList.remove('updated');
                    void badge.offsetWidth; // Force reflow
                    badge.classList.add('updated');

                    setTimeout(() => {
                        badge.classList.remove('updated');
                    }, 400);
                } else {
                    badge.style.display = 'none';
                }
            }

            console.log('💗 Wishlist badge updated:', count);
        }

        /**
         * Fetch wishlist count dari server
         */
        function fetchWishlistCount() {
            fetch('/customer/wishlist/count', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateWishlistBadgeSidebar(data.count);
                    }
                })
                .catch(error => {
                    console.error('Error fetching wishlist count:', error);
                });
        }

        // ✅ Load wishlist count saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎯 Initializing wishlist badge...');
            fetchWishlistCount();
        });

        // ✅ Listen to wishlist update events dari wishlist-handler.js
        document.addEventListener('wishlistUpdated', function(e) {
            console.log('💗 Wishlist event received:', e.detail);
            updateWishlistBadgeSidebar(e.detail.count);
        });

        // ✅ Make functions global untuk dipanggil dari luar
        window.updateWishlistBadgeSidebar = updateWishlistBadgeSidebar;
        window.fetchWishlistCount = fetchWishlistCount;
    @endauth

    // ========================================
    // ✅ REAL-TIME CART BADGE UPDATE
    // ========================================
    @auth('customer')
        /**
         * Update cart badge dengan animasi
         * @param {number} count - Jumlah cart items
         */
        function updateCartBadgeSidebar(count) {
            const badge = document.querySelector('.cart-count-sidebar');

            if (badge) {
                badge.textContent = count;

                if (count > 0) {
                    badge.style.display = 'block';

                    // ✅ Trigger animation
                    badge.classList.remove('updated');
                    void badge.offsetWidth; // Force reflow
                    badge.classList.add('updated');

                    setTimeout(() => {
                        badge.classList.remove('updated');
                    }, 400);
                } else {
                    badge.style.display = 'none';
                }
            }

            console.log('🛒 Cart badge updated:', count);
        }

        // ✅ Listen to cart update events
        document.addEventListener('cartUpdated', function(e) {
            console.log('🛒 Cart event received:', e.detail);
            updateCartBadgeSidebar(e.detail.count);
        });

        // ✅ Make function global
        window.updateCartBadgeSidebar = updateCartBadgeSidebar;
    @endauth
</script>
