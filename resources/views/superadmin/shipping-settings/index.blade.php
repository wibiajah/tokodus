<x-admin-layout title="Setting Ongkir">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Setting Ongkir</h2>
        </div>

        <!-- Alert Success -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <!-- Table Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Daftar Pengaturan Ongkir</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Nama</th>
                                <th width="15%">Tarif/KM</th>
                                <th width="15%">Min. Charge</th>
                                <th width="15%">Max. Jarak</th>
                                <th width="15%">Status</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shippingSettings as $index => $setting)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $setting->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $setting->slug }}</small>
                                </td>
                                <td>Rp {{ number_format($setting->price_per_km, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($setting->min_charge, 0, ',', '.') }}</td>
                                <td>{{ $setting->max_distance }} km</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-toggle-status {{ $setting->is_active ? 'btn-success' : 'btn-secondary' }}"
                                        data-id="{{ $setting->id }}"
                                        data-status="{{ $setting->is_active }}"
                                    >
                                        <i class="fas {{ $setting->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                        {{ $setting->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('superadmin.shipping-settings.edit', $setting->id) }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <i class="fas fa-info-circle"></i> Belum ada data setting ongkir
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-3 border-info">
            <div class="card-body">
                <h6 class="text-info"><i class="fas fa-info-circle"></i> Informasi</h6>
                <ul class="mb-0">
                    <li><strong>Tarif/KM:</strong> Biaya yang dikenakan per kilometer jarak pengiriman</li>
                    <li><strong>Min. Charge:</strong> Biaya minimal ongkir meskipun jarak sangat dekat</li>
                    <li><strong>Max. Jarak:</strong> Jarak maksimal yang bisa dilayani (dalam km)</li>
                    <li><strong>Status:</strong> Toggle untuk mengaktifkan/menonaktifkan metode pengiriman</li>
                </ul>
            </div>
        </div>
    </div>


    <script>
    $(document).ready(function() {
        // Toggle Status AJAX
        $('.btn-toggle-status').on('click', function() {
            const btn = $(this);
            const settingId = btn.data('id');
            const currentStatus = btn.data('status');
            
            if (confirm('Yakin ingin mengubah status?')) {
                $.ajax({
                    url: `/superadmin/shipping-settings/${settingId}/toggle-status`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update button
                            if (response.is_active) {
                                btn.removeClass('btn-secondary').addClass('btn-success');
                                btn.html('<i class="fas fa-check-circle"></i> Aktif');
                            } else {
                                btn.removeClass('btn-success').addClass('btn-secondary');
                                btn.html('<i class="fas fa-times-circle"></i> Nonaktif');
                            }
                            btn.data('status', response.is_active);
                            
                            // Show success message
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Gagal mengubah status!');
                    }
                });
            }
        });
    });
    </script>

</x-admin-layout>