<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Notifications -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @yield('count')
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="notificationsDropdown">
                <h6 class="dropdown-header">Notifikasi</h6>
                <!-- Isi Notifikasi -->
                <a class="dropdown-item" href="#">@yield('reminderMessage')</a>
            </div>
        </li>


        <!-- Nav Item - User Information -->
        <ul class="navbar-nav ml-auto">
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                    <!-- Tambahkan Gambar Cover User -->
                    <img class="img-profile rounded-circle"
                        src="{{ auth()->user()->coveruser ? asset('storage/' . auth()->user()->coveruser) : url('logo.png') }}"
                        alt="User Cover" width="40" height="40">

                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <!-- Tambahkan Tautan ke Show Profile -->
                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profil Saya
                    </a>
                    <div class="dropdown-divider"></div>
                    <!-- Logout -->
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
        <!-- Slot for reminder message -->
        @isset($reminderMessage)
            <li class="nav-item">
                <div class="alert alert-warning mb-0" role="alert">
                    {{ $reminderMessage }}
                </div>
            </li>
        @endisset

    </ul>

</nav>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Anda yakin ingin keluar?</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-primary" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>