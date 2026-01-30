<x-admin-layout title="Edit Setting Ongkir">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Edit Setting Ongkir</h2>
            <a href="{{ route('superadmin.shipping-settings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">Form Edit {{ $shippingSetting->name }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superadmin.shipping-settings.update', $shippingSetting->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Nama -->
                            <div class="form-group">
                                <label for="name">Nama Metode Pengiriman <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', $shippingSetting->name) }}"
                                    required
                                >
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Contoh: Reguler, Instant, Express</small>
                            </div>

                            <!-- Tarif per KM -->
                            <div class="form-group">
                                <label for="price_per_km">Tarif per KM (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        class="form-control @error('price_per_km') is-invalid @enderror" 
                                        id="price_per_km" 
                                        name="price_per_km" 
                                        value="{{ old('price_per_km', $shippingSetting->price_per_km) }}"
                                        min="0"
                                        step="100"
                                        required
                                    >
                                    @error('price_per_km')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Biaya yang dikenakan per kilometer</small>
                            </div>

                            <!-- Minimal Charge -->
                            <div class="form-group">
                                <label for="min_charge">Minimal Charge (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        class="form-control @error('min_charge') is-invalid @enderror" 
                                        id="min_charge" 
                                        name="min_charge" 
                                        value="{{ old('min_charge', $shippingSetting->min_charge) }}"
                                        min="0"
                                        step="1000"
                                        required
                                    >
                                    @error('min_charge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Biaya minimal meskipun jarak sangat dekat</small>
                            </div>

                            <!-- Maksimal Jarak -->
                            <div class="form-group">
                                <label for="max_distance">Maksimal Jarak (KM) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input 
                                        type="number" 
                                        class="form-control @error('max_distance') is-invalid @enderror" 
                                        id="max_distance" 
                                        name="max_distance" 
                                        value="{{ old('max_distance', $shippingSetting->max_distance) }}"
                                        min="1"
                                        required
                                    >
                                    <div class="input-group-append">
                                        <span class="input-group-text">KM</span>
                                    </div>
                                    @error('max_distance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Jarak maksimal yang bisa dilayani</small>
                            </div>

                            <!-- Status Aktif -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input 
                                        type="checkbox" 
                                        class="custom-control-input" 
                                        id="is_active" 
                                        name="is_active" 
                                        value="1"
                                        {{ old('is_active', $shippingSetting->is_active) ? 'checked' : '' }}
                                    >
                                    <label class="custom-control-label" for="is_active">
                                        <strong>Aktifkan metode pengiriman ini</strong>
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Jika dinonaktifkan, metode ini tidak akan muncul di halaman checkout
                                </small>
                            </div>

                            <hr>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('superadmin.shipping-settings.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="col-md-4">
                <div class="card shadow-sm border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-eye"></i> Preview Tarif</h6>
                    </div>
                    <div class="card-body">
                        <h6 class="font-weight-bold" id="preview-name">{{ $shippingSetting->name }}</h6>
                        
                        <table class="table table-sm">
                            <tr>
                                <td>Tarif/KM:</td>
                                <td class="text-right">
                                    <strong id="preview-price">Rp {{ number_format($shippingSetting->price_per_km, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Min. Charge:</td>
                                <td class="text-right">
                                    <strong id="preview-min">Rp {{ number_format($shippingSetting->min_charge, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Max. Jarak:</td>
                                <td class="text-right">
                                    <strong id="preview-max"><span id="preview-distance">{{ $shippingSetting->max_distance }}</span> km</strong>
                                </td>
                            </tr>
                        </table>

                        <hr>

                        <h6 class="font-weight-bold">Contoh Perhitungan:</h6>
                        <div id="example-calculation">
                            <p class="mb-1">Jarak: <strong>12 km</strong></p>
                            <p class="mb-1">Biaya: 12 × <span id="calc-price">{{ number_format($shippingSetting->price_per_km, 0, ',', '.') }}</span> = <strong id="calc-result">Rp {{ number_format(12 * $shippingSetting->price_per_km, 0, ',', '.') }}</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card mt-3 border-warning">
                    <div class="card-body">
                        <h6 class="text-warning"><i class="fas fa-lightbulb"></i> Tips</h6>
                        <ul class="small mb-0">
                            <li>Sesuaikan tarif dengan jarak tempuh</li>
                            <li>Min. charge untuk cover biaya operasional</li>
                            <li>Max. jarak sesuai jangkauan layanan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
    <script>
    $(document).ready(function() {
        // Live Preview
        function updatePreview() {
            const name = $('#name').val();
            const pricePerKm = parseFloat($('#price_per_km').val()) || 0;
            const minCharge = parseFloat($('#min_charge').val()) || 0;
            const maxDistance = parseInt($('#max_distance').val()) || 0;

            // Update preview
            $('#preview-name').text(name);
            $('#preview-price').text('Rp ' + pricePerKm.toLocaleString('id-ID'));
            $('#preview-min').text('Rp ' + minCharge.toLocaleString('id-ID'));
            $('#preview-distance').text(maxDistance);

            // Update calculation example
            const exampleDistance = 12;
            const calculatedPrice = exampleDistance * pricePerKm;
            const finalPrice = Math.max(calculatedPrice, minCharge);
            
            $('#calc-price').text(pricePerKm.toLocaleString('id-ID'));
            $('#calc-result').text('Rp ' + finalPrice.toLocaleString('id-ID'));
        }

        // Trigger update on input change
        $('#name, #price_per_km, #min_charge, #max_distance').on('input', updatePreview);
    });
    </script>

</x-admin-layout>