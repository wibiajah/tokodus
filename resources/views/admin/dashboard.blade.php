<x-admin-layout title="Dashboard Admin">
    <!-- Welcome Card -->
    <div class="container-fluid">
        <div class="card bg-success text-white shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="fw-semibold mb-1">Selamat datang, {{ auth()->user()->name }}!</h4>
                        <p class="mb-0">Anda telah login sebagai
                            <span class="badge bg-light text-success">Admin</span>
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <p class="mb-0">{{ now()->format('l, d F Y') }}</p>
                        <small class="opacity-75">{{ now()->format('H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Toko</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Toko::count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-store fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Kepala Toko</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'kepala_toko')->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Staff Admin</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'staff_admin')->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>