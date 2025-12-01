{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ $title ?? 'TokoDus Admin' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('Logo.svg') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link href="{{ asset('assets/css/fontawesome.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }

        /* ============================================
           TOPBAR STYLES
           ============================================ */
        .topbar-custom {
            width: 100%;
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 0.5rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle-btn {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #5a5c69;
            cursor: pointer;
            transition: color 0.3s;
            padding: 0.35rem;
        }

        .sidebar-toggle-btn:hover {
            color: #224abe;
        }

        .brand-section {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .brand-logo {
            max-height: 35px;
            width: auto;
        }

        .brand-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: #224abe;
            text-decoration: none;
        }

        /* NOTIFICATION BUTTON X2024 - ADMIN BLADE */
        .notif-btn-x2024 {
            position: relative;
            background: none;
            border: none;
            font-size: 1.1rem;
            color: #5a5c69;
            cursor: pointer;
            padding: 0.35rem;
        }

        .notif-btn-x2024:hover {
            color: #224abe;
        }

        /* NOTIFICATION BADGE X2024 - ADMIN BLADE */
        .notif-badge-x2024 {
            position: absolute;
            top: -2px;
            right: -2px;
            background-color: #e74a3b;
            color: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            cursor: pointer;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            transition: background-color 0.3s;
            text-decoration: none;
            color: #5a5c69;
        }

        .user-dropdown:hover {
            background-color: #f8f9fc;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e3e6f0;
        }

        .user-name {
            font-size: 0.85rem;
            color: #5a5c69;
            font-weight: 600;
        }

        /* ============================================
           SIDEBAR STYLES
           ============================================ */
        .sidebar-container {
            width: 100%;
            background: linear-gradient(180deg, #224abe 0%, #1a3a9e 100%);
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .sidebar-container.show {
            max-height: 100vh;
            overflow-y: auto;
        }

        .sidebar-nav {
            padding: 0.5rem 0;
        }

        .sidebar-nav .nav-item {
            padding: 0.6rem 1.25rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-left-color: rgba(255, 255, 255, 0.5);
        }

        .sidebar-nav .nav-item.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
            border-left-color: #fff;
        }

        .sidebar-nav .nav-item i {
            width: 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        .sidebar-divider {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.15);
            margin: 1rem 0;
        }

        .sidebar-heading {
            font-size: 0.65rem;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            padding: 0 1.5rem;
        }

        .nav-collapse {
            background-color: rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
        }

        .collapse-item {
            padding: 0.5rem 1.5rem 0.5rem 3.5rem;
            display: block;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .collapse-item:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .collapse-header {
            padding: 0.5rem 1.5rem 0.5rem 3.5rem;
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            font-weight: 800;
        }

        .badge-counter {
            font-size: 0.6rem;
            padding: 0.25rem 0.5rem;
            margin-left: auto;
        }

        /* ============================================
           MAIN CONTENT
           ============================================ */
        .main-content {
            padding: 2rem;
            min-height: calc(100vh - 180px);
        }

        /* ============================================
           FOOTER
           ============================================ */
        .footer {
            background-color: #fff;
            border-top: 1px solid #e3e6f0;
            padding: 1.5rem 2rem;
            text-align: center;
            color: #858796;
            font-size: 0.8rem;
        }

        .footer a {
            color: #224abe;
            text-decoration: none;
            font-weight: 600;
        }

        /* ============================================
           ALERT NOTIFICATIONS
           ============================================ */
        .alert-container {
            position: fixed;
            top: 60px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }

        .alert-custom {
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease-out;
            padding: 1rem 1.25rem;
            border-left: 4px solid;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* ============================================
           RESPONSIVE - Desktop Layout
           ============================================ */
        @media (min-width: 992px) {
            body {
                display: grid;
                grid-template-columns: 260px 1fr;
                grid-template-rows: auto 1fr auto;
                min-height: 100vh;
            }

            .topbar-custom {
                grid-column: 1 / -1;
            }

            .sidebar-container {
                grid-column: 1;
                grid-row: 2;
                max-height: none;
                border: none;
                border-right: 1px solid rgba(255, 255, 255, 0.15);
                overflow-y: auto;
                position: sticky;
                top: 56px;
                height: calc(100vh - 56px);
            }

            .main-content {
                grid-column: 2;
                grid-row: 2;
                overflow-y: auto;
            }

            .footer {
                grid-column: 1 / -1;
                grid-row: 3;
            }

            .sidebar-toggle-btn {
                display: none;
            }
        }

        /* ============================================
           RESPONSIVE - Mobile Layout
           ============================================ */
        @media (max-width: 991px) {
            body {
                display: block;
            }

            .topbar-custom {
                position: sticky;
            }

            .main-content {
                min-height: auto;
                padding: 1rem;
            }

            .brand-text {
                font-size: 1rem;
            }

            .alert-container {
                left: 10px;
                right: 10px;
                max-width: none;
            }
        }

        .scroll-to-top {
            position: fixed;
            right: 1rem;
            bottom: 1rem;
            display: none;
            width: 2.75rem;
            height: 2.75rem;
            text-align: center;
            color: #fff;
            background: rgba(34, 74, 190, 0.5);
            line-height: 46px;
            border-radius: 50%;
            z-index: 1000;
        }

        .scroll-to-top:focus,
        .scroll-to-top:hover {
            color: white;
            background: #224abe;
        }
    </style>

    <!-- jQuery -->
    <script defer src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <!-- Bootstrap Bundle -->
    <script defer src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript -->
    <script defer src="{{ asset('assets/js/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts -->
    <script defer src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
</head>

<body id="page-top">

    <!-- TOPBAR -->
    @include('layouts.topbar')

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- Alert Notifications -->
        <div class="alert-container" id="alertContainer">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ $error }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endforeach
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle mr-2"></i>
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle mr-2"></i>
                    {{ session('info') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        {{ $slot }}
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <span>&copy; 2024 <a href="#" target="_blank">TokoDus</a>. All rights reserved.</span>
    </div>

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Logout</h5>
                    <button class="close" type="button" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">Anda yakin ingin keluar?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-primary" type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebarContainer = document.getElementById('sidebarContainer');

            // Toggle sidebar on mobile
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebarContainer.classList.toggle('show');
                });
            }

            // Close sidebar when clicking on a nav item (mobile)
            document.querySelectorAll('.sidebar-nav .nav-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    if (window.innerWidth < 992 && !this.hasAttribute('data-toggle')) {
                        sidebarContainer.classList.remove('show');
                    }
                });
            });

            // Close sidebar when window is resized to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebarContainer.classList.remove('show');
                }
            });

            // Auto dismiss success alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert-success');
            alerts.forEach(alert => {
                setTimeout(function() {
                    $(alert).alert('close');
                }, 5000);
            });
        });
    </script>

    @yield('scripts')
</body>

</html>