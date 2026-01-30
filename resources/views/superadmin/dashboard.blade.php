<x-admin-layout title="Dashboard Super Admin">
    <!-- Welcome Card -->
    <div class="container-fluid">
        <div class="card bg-gradient-primary text-white shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="fw-semibold mb-1">Selamat datang, {{ auth()->user()->name }}!</h4>
                        <p class="mb-0">Anda telah login sebagai
                            <span class="badge bg-light text-primary">Super Admin</span>
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
            <div class="col-md-3 mb-4">
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

            <div class="col-md-3 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Admin</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::whereIn('role', ['super_admin', 'admin'])->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users-cog fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
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

            <div class="col-md-3 mb-4">
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

        <!-- 🚚 SHIPPING STATISTICS SECTION -->
        <div class="row">
            <div class="col-12 mb-3">
                <h5 class="text-gray-800 mb-0">
                    <i class="fas fa-shipping-fast text-primary"></i> Statistik Ongkir
                </h5>
                <hr>
            </div>
        </div>

        <!-- Shipping Stats Cards -->
        <div class="row">
            <!-- Total Revenue Ongkir -->
            <div class="col-md-3 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Revenue Ongkir</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($shippingStats['total_revenue'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Reguler -->
            <div class="col-md-3 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Orders Reguler</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($shippingStats['total_reguler']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Instant -->
            <div class="col-md-3 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Orders Instant</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($shippingStats['total_instant']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bolt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rata-rata Jarak -->
            <div class="col-md-3 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Avg. Jarak</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($shippingStats['avg_distance'], 1) }} km
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-map-marked-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart & Table Row -->
        <div class="row">
            <!-- Shipping Trend Chart -->
            <div class="col-lg-7 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-line"></i> Trend Revenue Ongkir (6 Bulan Terakhir)
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="shippingTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Tokos by Shipping Revenue -->
            <div class="col-lg-5 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-trophy"></i> Top 5 Toko (Revenue Ongkir)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Toko</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($shippingStats['top_tokos'] as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->toko->nama_toko ?? '-' }}</td>
                                        <td class="text-right font-weight-bold text-success">
                                            Rp {{ number_format($item->total_shipping, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
    // Shipping Trend Chart
    const trendData = @json($shippingStats['trend']);
    
    const labels = trendData.map(item => {
        const date = new Date(item.month + '-01');
        return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
    });
    
    const revenues = trendData.map(item => item.total);
    
    const ctx = document.getElementById('shippingTrendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue Ongkir (Rp)',
                data: revenues,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
    </script>

</x-admin-layout>