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
            <div class="col-md-6 mb-4">
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

        <!-- Info Per Toko -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-bar"></i> Statistik Semua Toko
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse($allTokos as $tokoItem)
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card border-left-{{ $tokoItem->status === 'aktif' ? 'success' : 'secondary' }} shadow h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="font-weight-bold text-gray-800 mb-0">
                                                {{ $tokoItem->nama_toko }}
                                            </h6>
                                            <span class="badge badge-{{ $tokoItem->status === 'aktif' ? 'success' : 'secondary' }}">
                                                {{ $tokoItem->status === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </div>
                                        
                                        <hr class="my-2">
                                        
                                        <div class="row no-gutters">
                                            <div class="col-6">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    Kepala Toko
                                                </div>
                                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                                    @if($tokoItem->kepalaToko)
                                                        <i class="fas fa-user-tie text-info"></i> 1
                                                        <div class="text-xs text-muted mt-1">
                                                            {{ $tokoItem->kepalaToko->name }}
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="col-6">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Staff Admin
                                                </div>
                                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                                    <i class="fas fa-users text-warning"></i> {{ $tokoItem->staff_admin_count }}
                                                    @if($tokoItem->staff_admin_count > 0)
                                                        <div class="text-xs text-muted mt-1">
                                                            @foreach($tokoItem->staffAdmin->take(2) as $staff)
                                                                <div>{{ $staff->name }}</div>
                                                            @endforeach
                                                            @if($tokoItem->staff_admin_count > 2)
                                                                <div class="text-primary">+{{ $tokoItem->staff_admin_count - 2 }} lainnya</div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <hr class="my-2">
                                        
                                        <div class="text-center">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase">
                                                Total Pegawai
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-primary">
                                                {{ $tokoItem->users_count }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle"></i> Belum ada data toko
                                </div>
                            </div>
                            @endforelse
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