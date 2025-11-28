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
        <!-- Informasi Toko -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Toko</h6>
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

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Staff Admin</div>
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

            <div class="col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Orderan (Coming Soon)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> Anda belum ditugaskan ke toko manapun.
        </div>
        @endif
    </div>
</x-admin-layout>