{{-- resources/views/layouts/topbar.blade.php --}}
<div class="topbar-custom">
    <div class="topbar-left">
        <button class="sidebar-toggle-btn" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="brand-section">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="brand-logo">
            <a href="{{ route('home') }}" class="brand-text">TokoDus</a>
        </div>
        
        <!-- Dashboard Label -->
        <div class="dashboard-label">
            <span class="dashboard-text">
                Dashboard 
                @if(auth()->user()->isSuperAdmin())
                    (Super Admin)
                @elseif(auth()->user()->isAdmin())
                    (Admin)
                @elseif(auth()->user()->isKepalaToko())
                    (Kepala Toko)
                @elseif(auth()->user()->isStaffAdmin())
                    (Staff Admin)
                @endif
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
    <div class="dropdown">
        <button class="notification-btn" data-toggle="dropdown" id="notificationBtn">
            <i class="fas fa-bell"></i>
            <span class="notification-badge" id="notificationCount" style="display: none;">0</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" style="width: 350px; max-height: 400px; overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                <h6 class="m-0 font-weight-bold">Notifikasi</h6>
                <button class="btn btn-sm btn-link text-primary p-0" id="markAllRead">
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
    <div class="dropdown">
        <a class="user-dropdown" href="#" data-toggle="dropdown">
            <img src="{{ auth()->user()->coveruser ? asset('storage/' . auth()->user()->coveruser) : asset('logo.png') }}" 
                 alt="User" class="user-avatar">
            <span class="user-name d-none d-sm-inline">{{ auth()->user()->name }}</span>
            <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
            <a class="dropdown-item" href="{{ route('profile.show') }}">
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                Profil Saya
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
            </a>
        </div>
    </div>
</div>

<script>
// Load notifications saat halaman dimuat
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
            <a class="dropdown-item d-flex align-items-start py-3 border-bottom notification-item" 
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
document.getElementById('markAllRead').addEventListener('click', function(e) {
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
</script>

<style>
.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #e74a3b;
    color: white;
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 0.7rem;
    font-weight: bold;
    min-width: 18px;
    text-align: center;
}

.notification-item:hover {
    background-color: #f8f9fc;
}

.notification-item i {
    width: 30px;
    text-align: center;
}
</style>
</div>

<style>
    /* Update Topbar Styles */
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

    /* Notification Button */
    .notification-btn {
        color: #fff !important;
    }

    .notification-btn:hover {
        color: rgba(255, 255, 255, 0.8) !important;
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
                    // Example: window.location.href = '/search?q=' + encodeURIComponent(searchQuery);
                }
            }
        });
    });
</script>