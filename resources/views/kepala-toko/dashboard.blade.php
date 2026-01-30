<x-admin-layout title="Dashboard Kepala Toko">
    <!-- Welcome Card -->
    <div class="container-fluid">
        <div class="card bg-info text-white shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="fw-semibold mb-1">Selamat datang, {{ auth()->user()->name }}!</h4>
                        <p class="mb-0">
                            Anda telah login sebagai <span class="badge bg-light text-info">Kepala Toko</span>
                            <br>
                            <strong>{{ auth()->user()->toko->nama_toko ?? 'Toko Tidak Ditemukan' }}</strong>
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <p class="mb-0">{{ now()->format('l, d F Y') }}</p>
                        <small class="opacity-75">{{ now()->format('H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->toko)
        <!-- Informasi Toko Saya -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 bg-info text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-store"></i> Informasi Toko Saya
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Nama Toko:</strong></p>
                                <p>{{ auth()->user()->toko->nama_toko }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Alamat:</strong></p>
                                <p>{{ auth()->user()->toko->alamat ?? '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Telepon:</strong></p>
                                <p>{{ auth()->user()->toko->telepon ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards Toko Saya -->
        <div class="row mb-4">
            <div class="col-md-4 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Staff Admin Toko Saya</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ auth()->user()->toko->staffAdmin->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Orderan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $orderStats['total'] ?? 0 }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Order Selesai</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $orderStats['completed'] ?? 0 }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🆕 Quick Stats Orders -->
        @if($orderStats && $orderStats['total'] > 0)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header py-3 bg-info text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-chart-pie"></i> Statistik Orderan Toko Saya
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                </div>
                                <h5 class="font-weight-bold text-warning">{{ $orderStats['pending'] }}</h5>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <i class="fas fa-cog fa-2x text-info"></i>
                                </div>
                                <h5 class="font-weight-bold text-info">{{ $orderStats['processing'] }}</h5>
                                <p class="text-muted mb-0">Diproses</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <i class="fas fa-truck fa-2x text-primary"></i>
                                </div>
                                <h5 class="font-weight-bold text-primary">{{ $orderStats['shipped'] }}</h5>
                                <p class="text-muted mb-0">Dikirim</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                </div>
                                <h5 class="font-weight-bold text-success">{{ $orderStats['completed'] }}</h5>
                                <p class="text-muted mb-0">Selesai</p>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <a href="{{ route('kepala-toko.orders.index') }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Lihat Semua Orderan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @endif
        
    </div>
</x-admin-layout>