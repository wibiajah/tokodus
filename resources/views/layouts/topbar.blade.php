{{-- resources/views/layouts/topbar.blade.php --}}
<div class="topbar-custom">
    <div class="topbar-left">
        <button class="sidebar-toggle-btn" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="brand-section">
            <img href="{{ route('home') }}" src="{{ asset('Logo.svg') }}" alt="Logo" class="brand-logo">
           
        </div>
        
        <!-- Dashboard Label -->
        <div class="dashboard-label">
            <span class="dashboard-text">
                Dashboard 
            </span>
        </div>
    </div>

    <div class="topbar-center">
        <!-- Search Bar -->
        <div class="search-container">
            <button class="search-btn" id="searchBtn">
                <i class="fas fa-search"></i>
            </button>
            <input type="text" class="search-input" placeholder="Search" id="searchInput">
        </div>
    </div>

    <div class="topbar-right">
        <!-- Notifications -->
        <div class="dropdown dropdown-notif-x2024">
            <button class="notif-btn-x2024" data-toggle="dropdown" id="notificationBtn">
                <svg class="notif-icon-modern" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="notif-badge-modern" id="notificationCount" style="display: none;">0</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right shadow notif-dropdown-x2024">
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom notif-header-x2024">
                    <h6 class="m-0 font-weight-bold">Notifikasi</h6>
                    <button class="btn btn-sm btn-link text-primary p-0" id="markAllReadX2024">
                        <small>Tandai Semua Dibaca</small>
                    </button>
                </div>
                
                <div id="notificationList">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </div>
                </div>
                
                <div class="dropdown-divider m-0"></div>
                <a class="dropdown-item text-center small text-gray-500 py-2" href="{{ route('notifications.index') }}">
                    Lihat Semua Notifikasi
                </a>
            </div>
        </div>

          <!-- User Dropdown -->
        <div class="dropdown dropdown-user-x2024">
            <a class="user-dropdown" href="#" data-toggle="dropdown">
                <img src="{{ auth()->user()->foto_profil ? asset('storage/' . auth()->user()->foto_profil) : asset('img/default-avatar.png') }}" 
                     alt="User" class="user-avatar">
                <span class="user-name d-none d-sm-inline">{{ auth()->user()->name }}</span>
                <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow user-dropdown-menu-x2024">
                <a class="dropdown-item" href="{{ route('profile.show') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil Saya
                </a>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                    Edit Profil
                </a>
                <a class="dropdown-item" href="{{ route('profile.password.edit') }}">
                    <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                    Ubah Password
                </a>
                <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onclick="confirmLogout(event)">
    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
    Logout
</a>
            </div>
        </div>

    </div>
</div>

<form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    // konfirmasi logout
    function confirmLogout(event) {
    event.preventDefault();
    
    if (confirm('Yakin mau logout?')) {
        document.getElementById('logoutForm').submit();
    }
}
// Load notifications saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    
    // Auto-refresh setiap 30 detik
    setInterval(loadNotifications, 30000);
});

// Load notifications dari server
function loadNotifications() {
    fetch('{{ route("notifications.unread") }}')
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.count);
            displayNotifications(data.notifications);
        })
        .catch(error => console.error('Error loading notifications:', error));
}

// Update badge count
function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationCount');
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = 'inline-block';
    } else {
        badge.style.display = 'none';
    }
}

// Display notifications
function displayNotifications(notifications) {
    const list = document.getElementById('notificationList');
    
    if (notifications.length === 0) {
        list.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-bell-slash fa-2x mb-2"></i>
                <p class="mb-0">Tidak ada notifikasi</p>
            </div>
        `;
        return;
    }
    
    list.innerHTML = notifications.map(notif => {
        const data = notif.data;
        const timeAgo = formatTimeAgo(notif.created_at);
        
        return `
            <a class="dropdown-item d-flex align-items-start py-3 border-bottom notif-item-x2024" 
               href="${data.url}" 
               data-id="${notif.id}"
               onclick="markAsRead('${notif.id}')">
                <div class="mr-3">
                    <i class="${data.icon}" style="font-size: 1.2rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="small font-weight-bold">${data.message}</div>
                    <div class="small text-muted">oleh ${data.actor_name} • ${timeAgo}</div>
                </div>
            </a>
        `;
    }).join('');
}

// Format waktu relative (time ago)
function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    if (seconds < 60) return seconds + ' seconds ago';
    
    const minutes = Math.floor(seconds / 60);
    if (minutes < 60) return minutes + ' minute' + (minutes > 1 ? 's' : '') + ' ago';
    
    const hours = Math.floor(minutes / 60);
    if (hours < 24) return hours + ' hour' + (hours > 1 ? 's' : '') + ' ago';
    
    const days = Math.floor(hours / 24);
    if (days < 30) return days + ' day' + (days > 1 ? 's' : '') + ' ago';
    
    const months = Math.floor(days / 30);
    if (months < 12) return months + ' month' + (months > 1 ? 's' : '') + ' ago';
    
    const years = Math.floor(months / 12);
    return years + ' year' + (years > 1 ? 's' : '') + ' ago';
}

// Mark single notification as read
function markAsRead(notifId) {
    fetch(`/notifications/${notifId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(() => {
        loadNotifications();
    });
}

// Mark all as read
document.getElementById('markAllReadX2024').addEventListener('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    fetch('{{ route("notifications.markAllRead") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(() => {
        loadNotifications();
    });
});

function handleLogout(e) {
    e.preventDefault();
    
    fetch('{{ route("logout") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(() => window.location.href = '{{ route("login") }}')
    .catch(() => window.location.href = '{{ route("login") }}');
}
</script>

<style>
/* ===== TOPBAR NOTIFICATION STYLES X2024 (MODERN) ===== */

/* Notification Icon Wrapper */
.notif-icon-wrapper {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Modern SVG Icon */
.notif-icon-modern {
    width: 22px;
    height: 22px;
    color: #fff;
    transition: all 0.2s ease;
}

.notif-btn-x2024:hover .notif-icon-modern {
    transform: scale(1.1);
}

.notif-btn-x2024:active .notif-icon-modern {
    transform: scale(0.95);
}

/* Simple Badge */
.notif-badge-modern {
    position: absolute !important;
    top: -2px !important;
    right: 0px !important;
    background: #dc3545 !important;
    color: white !important;
    border-radius: 50% !important;
    width: 20px !important;
    height: 20px !important;
    padding: 0 !important;
    font-size: 0.65rem !important;
    font-weight: 700 !important;
    text-align: center !important;
    line-height: 20px !important;
    border: 2px solid #4e73df !important;
    box-shadow: 0 2px 6px rgba(220, 53, 69, 0.4) !important;
    animation: simplePulseBadge 2s ease-in-out infinite !important;
}

@keyframes simplePulseBadge {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.08);
    }
}

/* Dropdown Container Fix */
.topbar-right .dropdown.dropdown-notif-x2024 {
    position: relative !important;
}

/* Dropdown Animation */
.topbar-right .dropdown.dropdown-notif-x2024 .dropdown-menu.notif-dropdown-x2024 {
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    width: 380px !important;
    max-width: 90vw !important;
    max-height: 500px !important;
    overflow-y: auto !important;
    margin-top: 0.8rem !important;
    opacity: 0 !important;
    transform: translateY(-20px) scale(0.9) !important;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    display: none !important;
    border: none !important;
    border-radius: 16px !important;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2), 0 0 1px rgba(0, 0, 0, 0.1) !important;
    backdrop-filter: blur(10px) !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .dropdown-menu.notif-dropdown-x2024.show {
    opacity: 1 !important;
    transform: translateY(0) scale(1) !important;
    display: block !important;
    animation: slideDownX2024Modern 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
}

@keyframes slideDownX2024Modern {
    0% {
        opacity: 0;
        transform: translateY(-25px) scale(0.85);
    }
    60% {
        opacity: 1;
        transform: translateY(8px) scale(1.02);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* User Dropdown Menu Animation */
.topbar-right .dropdown.dropdown-user-x2024 .dropdown-menu.user-dropdown-menu-x2024 {
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    opacity: 0 !important;
    transform: translateY(-20px) scale(0.9) !important;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    display: none !important;
    border: none !important;
    border-radius: 16px !important;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2), 0 0 1px rgba(0, 0, 0, 0.1) !important;
    backdrop-filter: blur(10px) !important;
    margin-top: 0.8rem !important;
}

.topbar-right .dropdown.dropdown-user-x2024 .dropdown-menu.user-dropdown-menu-x2024.show {
    opacity: 1 !important;
    transform: translateY(0) scale(1) !important;
    display: block !important;
    animation: slideDownX2024Modern 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
}

/* Header */
.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .notif-header-x2024 {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafb 100%) !important;
    animation: fadeInHeaderX2024Modern 0.6s ease-in-out !important;
}

@keyframes fadeInHeaderX2024Modern {
    from { 
        opacity: 0;
        transform: translateY(-10px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

/* Button Hover */
.topbar-right .dropdown.dropdown-notif-x2024 .notif-btn-x2024 {
    position: relative !important;
    transition: all 0.2s ease !important;
    padding: 0 8px !important;
    border-radius: 8px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    height: 40px !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .notif-btn-x2024:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .notif-btn-x2024:active {
    background-color: rgba(255, 255, 255, 0.08) !important;
}

/* Notification Items */
.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .dropdown-item.notif-item-x2024 {
    position: relative !important;
    overflow: hidden !important;
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    animation: slideInItemX2024Modern 0.5s ease-out backwards !important;
    border-left: 4px solid transparent !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .dropdown-item.notif-item-x2024:nth-child(1) { animation-delay: 0.05s !important; }
.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .dropdown-item.notif-item-x2024:nth-child(2) { animation-delay: 0.1s !important; }
.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .dropdown-item.notif-item-x2024:nth-child(3) { animation-delay: 0.15s !important; }
.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .dropdown-item.notif-item-x2024:nth-child(4) { animation-delay: 0.2s !important; }
.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .dropdown-item.notif-item-x2024:nth-child(5) { animation-delay: 0.25s !important; }

@keyframes slideInItemX2024Modern {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .dropdown-item.notif-item-x2024::before {
    content: '' !important;
    position: absolute !important;
    left: 0 !important;
    top: 0 !important;
    width: 0 !important;
    height: 100% !important;
    background: linear-gradient(90deg, rgba(78, 115, 223, 0.08) 0%, transparent 100%) !important;
    transition: width 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    z-index: 0 !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .dropdown-item.notif-item-x2024:hover::before {
    width: 100% !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .dropdown-item.notif-item-x2024:hover {
    background-color: #f5f7fb !important;
    transform: translateX(6px) !important;
    border-left-color: #4e73df !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .dropdown-item.notif-item-x2024 i {
    width: 32px !important;
    text-align: center !important;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    position: relative !important;
    z-index: 1 !important;
    font-size: 1.3rem !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .dropdown-item.notif-item-x2024:hover i {
    transform: scale(1.25) rotate(8deg) !important;
    color: #4e73df !important;
    filter: drop-shadow(0 2px 4px rgba(78, 115, 223, 0.3)) !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .dropdown-item.notif-item-x2024 .flex-grow-1 {
    position: relative !important;
    z-index: 1 !important;
}

/* Scrollbar */
.topbar-right .dropdown.dropdown-notif-x2024 .dropdown-menu.notif-dropdown-x2024::-webkit-scrollbar {
    width: 0px !important;
    display: none !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .dropdown-menu.notif-dropdown-x2024::-webkit-scrollbar-track {
    background: transparent !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .dropdown-menu.notif-dropdown-x2024::-webkit-scrollbar-thumb {
    background: transparent !important;
}

/* Mark All Button */
.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 #markAllReadX2024 {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 #markAllReadX2024:hover {
    transform: scale(1.06) !important;
    text-decoration: underline !important;
    color: #224abe !important;
}

.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 #markAllReadX2024:active {
    transform: scale(0.94) !important;
}

/* Empty State */
.topbar-right .dropdown.dropdown-notif-x2024 .notif-dropdown-x2024 .fa-bell-slash {
    animation: swingBellX2024Modern 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite !important;
    color: #ccc !important;
}

@keyframes swingBellX2024Modern {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(12deg); }
    75% { transform: rotate(-12deg); }
}

/* ===== EXISTING TOPBAR STYLES ===== */
.topbar-custom {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.topbar-center {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 2rem;
}

.sidebar-toggle-btn {
    color: #fff !important;
}

.sidebar-toggle-btn:hover {
    color: rgba(255, 255, 255, 0.8) !important;
}

.brand-text {
    color: #fff !important;
}

.dashboard-label {
    margin-left: 1.5rem;
    padding-left: 1.5rem;
    border-left: 2px solid rgba(255, 255, 255, 0.3);
}

.dashboard-text {
    font-size: 0.95rem;
    font-weight: 600;
    color: #fff;
    letter-spacing: 0.3px;
}

/* Search Container */
.search-container {
    position: relative;
    display: flex;
    align-items: center;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    padding: 0.5rem 0.8rem;
    transition: all 0.3s ease;
    width: 100%;
    max-width: 400px;
}

.search-container:hover {
    background-color: rgba(255, 255, 255, 0.25);
}

.search-btn {
    background-color: rgba(255, 255, 255, 0.9);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #4e73df;
    font-size: 0.85rem;
}

.search-btn:hover {
    background-color: #fff;
    transform: scale(1.05);
}

.search-input {
    border: none;
    background: transparent;
    outline: none;
    padding: 0.35rem 0.75rem;
    color: #fff;
    font-size: 0.9rem;
    width: 100%;
    flex: 1;
    transition: all 0.3s ease;
}

.search-input::placeholder {
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
}

.search-input:focus {
    background-color: rgba(255, 255, 255, 0.05);
}

/* Notification Button Base */
.notif-btn-x2024 {
    color: #fff !important;
    background: none !important;
    border: none !important;
    cursor: pointer !important;
}

.notif-btn-x2024:hover {
    color: rgba(255, 255, 255, 0.9) !important;
}

/* User Dropdown */
.user-dropdown {
    color: #fff !important;
}

.user-dropdown:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

.user-name {
    color: #fff !important;
}

.user-avatar {
    border: 2px solid rgba(255, 255, 255, 0.5);
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-label {
        display: none;
    }

    .topbar-center {
        padding: 0 1rem;
    }

    .search-container {
        max-width: 300px;
    }
    
    .topbar-right .dropdown.dropdown-notif-x2024 .dropdown-menu.notif-dropdown-x2024 {
        width: 320px !important;
        max-width: calc(100vw - 30px) !important;
    }
}

@media (max-width: 576px) {
    .topbar-center {
        padding: 0 0.5rem;
    }

    .search-container {
        padding: 0.4rem 0.6rem;
        max-width: 200px;
    }

    .search-btn {
        width: 28px;
        height: 28px;
        font-size: 0.75rem;
    }

    .search-input {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .topbar-right .dropdown.dropdown-notif-x2024 .dropdown-menu.notif-dropdown-x2024 {
        width: 300px !important;
        max-width: calc(100vw - 20px) !important;
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchBtn = document.getElementById('searchBtn');
        const searchInput = document.getElementById('searchInput');

        // Focus input when clicking search button
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            searchInput.focus();
        });

        // Handle search input (placeholder for future functionality)
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchQuery = this.value.trim();
                if (searchQuery) {
                    console.log('Search query:', searchQuery);
                    // TODO: Implement search functionality
                }
            }
        });
    });
</script>