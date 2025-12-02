{{-- resources/views/layouts/sidebar.blade.php --}}
<div class="sidebar-container" id="sidebarContainer">
    <!-- Sidebar Toggle Button (untuk expand/collapse) -->
    <div class="sidebar-header">
        <button class="sidebar-collapse-btn" id="sidebarCollapseBtn">
            <i class="fas fa-ellipsis-h"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        @if (auth()->user()->role === 'super_admin')
            @include('layouts.sidebars.super-admin')
        @elseif (auth()->user()->role === 'admin')
            @include('layouts.sidebars.admin')
        @elseif (auth()->user()->role === 'kepala_toko')
            @include('layouts.sidebars.kepala-toko')
        @elseif (auth()->user()->role === 'staff_admin')
            @include('layouts.sidebars.staff')
        @endif

        <div class="sidebar-divider"></div>
    </nav>
</div>

<style>
    /* Sidebar Header with Toggle Button */
    .sidebar-header {
        padding: 1rem;
        display: flex;
        justify-content: flex-end;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar-collapse-btn {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #fff;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .sidebar-collapse-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.05);
    }

    .sidebar-collapse-btn i {
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Sidebar Collapsed State */
    .sidebar-container {
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar-container.collapsed {
        width: 80px !important;
    }

    .sidebar-container.collapsed .sidebar-header {
        justify-content: center;
    }

    .sidebar-container.collapsed .sidebar-collapse-btn i {
        transform: rotate(180deg);
    }

    .sidebar-container.collapsed .nav-text,
    .sidebar-container.collapsed .nav-arrow,
    .sidebar-container.collapsed .sidebar-heading,
    .sidebar-container.collapsed .badge-counter {
        display: none !important;
    }

    .sidebar-container.collapsed .nav-item {
        justify-content: center;
        padding: 0.8rem;
        border-left: 3px solid transparent;
    }

    .sidebar-container.collapsed .nav-item i {
        margin: 0;
        font-size: 1.1rem;
    }

    .sidebar-container.collapsed .nav-collapse,
    .sidebar-container.collapsed .collapse.show {
        display: none !important;
    }

    /* Nav Item Styling */
    .nav-item {
        position: relative;
    }

    .nav-text {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .nav-arrow {
        margin-left: auto;
        font-size: 0.7rem;
        transition: transform 0.3s ease;
    }

    .nav-item[aria-expanded="true"] .nav-arrow {
        transform: rotate(180deg);
    }

    /* Badge Counter */
    .badge-counter {
        margin-left: auto;
    }

    /* Smooth hover effects */
    .nav-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: rgba(255, 255, 255, 0.5);
        transform: scaleY(0);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-item:hover::before {
        transform: scaleY(1);
    }

    .nav-item.active::before {
        background: #fff;
        transform: scaleY(1);
    }

    /* Sidebar divider animation */
    .sidebar-divider {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Responsive - Desktop Layout */
    @media (min-width: 992px) {
        body {
            grid-template-columns: 260px 1fr;
            transition: grid-template-columns 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.sidebar-collapsed {
            grid-template-columns: 80px 1fr;
        }

        .sidebar-container {
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-collapse-btn {
            display: flex !important;
        }
    }

    /* Responsive - Mobile Layout */
    @media (max-width: 991px) {
        .sidebar-collapse-btn {
            display: none !important;
        }

        .sidebar-container.collapsed {
            width: 100% !important;
        }

        .sidebar-container.collapsed .nav-text,
        .sidebar-container.collapsed .nav-arrow,
        .sidebar-container.collapsed .sidebar-heading,
        .sidebar-container.collapsed .badge-counter {
            display: inline !important;
        }

        .sidebar-container.collapsed .nav-item {
            justify-content: flex-start;
            padding: 0.6rem 1.25rem;
        }
    }

    /* Smooth scrollbar for sidebar */
    .sidebar-container {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
    }

    .sidebar-container::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-container::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-container::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }

    .sidebar-container::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
        const sidebarContainer = document.getElementById('sidebarContainer');
        const body = document.body;

        // Load saved state from localStorage
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed && window.innerWidth >= 992) {
            sidebarContainer.classList.add('collapsed');
            body.classList.add('sidebar-collapsed');
        }

        // Toggle collapse on button click
        if (sidebarCollapseBtn) {
            sidebarCollapseBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebarContainer.classList.toggle('collapsed');
                body.classList.toggle('sidebar-collapsed');
                
                // Save state to localStorage
                const isNowCollapsed = sidebarContainer.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isNowCollapsed);

                // Close all open collapses when collapsing
                if (isNowCollapsed) {
                    document.querySelectorAll('.nav-collapse.show').forEach(collapse => {
                        $(collapse).collapse('hide');
                    });
                }
            });
        }

        // Prevent collapse on mobile
        window.addEventListener('resize', function() {
            if (window.innerWidth < 992) {
                sidebarContainer.classList.remove('collapsed');
                body.classList.remove('sidebar-collapsed');
            } else {
                // Restore saved state on resize to desktop
                const savedState = localStorage.getItem('sidebarCollapsed') === 'true';
                if (savedState) {
                    sidebarContainer.classList.add('collapsed');
                    body.classList.add('sidebar-collapsed');
                }
            }
        });
    });
</script>