{{-- resources/views/kepala-toko/stock-requests/create.blade.php --}}
<x-admin-layout title="Request Stok Produk">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus-circle"></i> Request Stok Produk
            </h1>
            <a href="{{ route('kepala-toko.stock-requests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Alert Messages -->
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        <div class="row">
            <!-- Product Info -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-box"></i> Informasi Produk
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        @if($product->thumbnail)
                        <img src="{{ $product->thumbnail }}" 
                             alt="{{ $product->title }}" 
                             class="img-fluid rounded mb-3"
                             style="max-height: 200px;">
                        @endif
                        
                        <h5 class="font-weight-bold">{{ $product->title }}</h5>
                        <p class="text-muted mb-1">SKU: <code>{{ $product->sku }}</code></p>
                        <p class="text-muted">
                            <i class="fas fa-warehouse"></i> 
                            Stok Warehouse: 
                            <strong>{{ $product->total_stock }} pcs</strong>
                        </p>

                        <hr>

                        <div class="text-left">
                            <p class="mb-1"><strong>Toko Anda:</strong></p>
                            <p class="text-dark">
                                <i class="fas fa-store"></i>
                                {{ auth()->user()->toko->nama_toko }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request Form -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-clipboard-list"></i> Pilih Varian & Jumlah
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kepala-toko.stock-requests.store', $product) }}" 
                              method="POST"
                              id="requestForm">
                            @csrf

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Petunjuk:</strong> 
                                Anda bisa request 1 varian atau lebih. Masukkan jumlah yang diinginkan (biarkan 0 jika tidak mau request varian tersebut).
                            </div>

                            <!-- Variants Selection -->
                            <div id="variants-container">
                                @if($leafVariants->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
    <tr>
        <th width="10%">Foto</th>
        <th width="25%">Varian</th>
        <th width="15%">Harga</th>
        <th width="15%">Stok Warehouse</th>
        <th width="15%">Stok Toko Anda</th>
        <th width="20%">Jumlah Request</th>
    </tr>
</thead>
                                        <tbody>
                                            @foreach($leafVariants as $variant)
                                            @php
                                                $currentStock = $currentStocks->get($variant->id);
                                                $tokoStock = $currentStock ? $currentStock->stock : 0;
                                            @endphp
                                            <tr id="variant-row-{{ $loop->index }}">
    <td class="text-center">
        @if($variant->photo)
        <img src="{{ asset('storage/' . $variant->photo) }}" 
             alt="{{ $variant->display_name }}" 
             class="img-thumbnail"
             style="width: 60px; height: 60px; object-fit: cover;">
        @else
        <div class="bg-light d-flex align-items-center justify-content-center" 
             style="width: 60px; height: 60px;">
            <i class="fas fa-image text-muted fa-2x"></i>
        </div>
        @endif
    </td>
    <td>
    @if($variant->parent_id)
        {{-- Ini ukuran (child), tampilkan warna parent --}}
       <div class="mb-1">
    <small class="text-muted">
        <i class="fas fa-palette"></i> Warna:
    </small>
    <strong class="text-dark">{{ $variant->parent->name }}</strong>
</div>
        <div>
            <small class="text-muted">
                <i class="fas fa-ruler"></i> Ukuran:
            </small>
            <strong>{{ $variant->name }}</strong>
        </div>
    @else
        {{-- Ini warna saja (tanpa ukuran) --}}
        <strong>{{ $variant->name }}</strong>
        <br>
        <small class="text-muted">
            <i class="fas fa-palette"></i> Warna
        </small>
    @endif
    <input type="hidden" 
           name="variants[{{ $loop->index }}][variant_id]" 
           value="{{ $variant->id }}">
</td>
    <td>
        @if($variant->price)
        <strong class="text-primary">
            Rp {{ number_format($variant->price, 0, ',', '.') }}
        </strong>
        @else
        <span class="text-muted">-</span>
        @endif
    </td>
    <td>
                                                    @if($variant->stock_pusat > 0)
                                                    <span class="badge badge-success">
                                                        {{ $variant->stock_pusat }} pcs
                                                    </span>
                                                    @else
                                                    <span class="badge badge-danger">Habis</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ $tokoStock }} pcs
                                                    </span>
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control form-control-sm quantity-input"
                                                           name="variants[{{ $loop->index }}][quantity]" 
                                                           min="0"
                                                           max="{{ $variant->stock_pusat }}"
                                                           data-max="{{ $variant->stock_pusat }}"
                                                           data-row-index="{{ $loop->index }}"
                                                           placeholder="0"
                                                           style="font-weight: bold;"
                                                           {{ $variant->stock_pusat > 0 ? '' : 'disabled' }}>
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="fas fa-info-circle"></i> Max: {{ $variant->stock_pusat }}
                                                    </small>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Produk ini belum memiliki varian. Silakan hubungi admin.
                                </div>
                                @endif
                            </div>

                            <!-- Notes -->
                            <div class="form-group mt-4">
                                <label for="notes">Catatan (Opsional)</label>
                                <textarea name="notes" 
                                          id="notes" 
                                          class="form-control" 
                                          rows="3"
                                          placeholder="Tambahkan catatan untuk request Anda..."
                                          maxlength="500"></textarea>
                                <small class="text-muted">Maksimal 500 karakter</small>
                            </div>

                            <!-- Summary -->
                            <div class="alert alert-light border">
                                <div class="row text-center">
                                    <div class="col-md-6">
                                        <strong>Total Varian Dipilih:</strong>
                                        <h4 class="mb-0">
                                            <span id="total-variants" class="badge badge-primary" style="font-size: 1.2rem;">0</span>
                                        </h4>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Total Quantity:</strong>
                                        <h4 class="mb-0">
                                            <span id="total-quantity" class="badge badge-success" style="font-size: 1.2rem;">0 pcs</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane"></i> Submit Request
                                </button>
                                <a href="{{ route('kepala-toko.stock-requests.index') }}" 
                                   class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
    // Gunakan vanilla JavaScript jika jQuery tidak tersedia
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        const totalVariantsEl = document.getElementById('total-variants');
        const totalQuantityEl = document.getElementById('total-quantity');
        
        // Update Summary Function
        function updateSummary() {
            let totalVariants = 0;
            let totalQuantity = 0;
            
            quantityInputs.forEach(function(input) {
                if (input.disabled) return;
                
                const quantity = parseInt(input.value) || 0;
                
                if (quantity > 0) {
                    totalVariants++;
                    totalQuantity += quantity;
                }
            });
            
            totalVariantsEl.textContent = totalVariants;
            totalQuantityEl.textContent = totalQuantity + ' pcs';
            
            console.log('Total Variants:', totalVariants, 'Total Quantity:', totalQuantity);
        }
        
        // Add event listeners to all quantity inputs
        quantityInputs.forEach(function(input) {
            const max = parseInt(input.getAttribute('data-max'));
            const rowIndex = input.getAttribute('data-row-index');
            const row = document.getElementById('variant-row-' + rowIndex);
            
            // Focus event - clear if value is 0
            input.addEventListener('focus', function() {
                if (this.value === '0' || this.value === '') {
                    this.value = '';
                    this.select(); // Select all text for easy replace
                }
            });
            
            // Blur event - set to 0 if empty
            input.addEventListener('blur', function() {
                if (this.value === '' || this.value === null) {
                    this.value = 0;
                    updateSummary();
                }
            });
            
            // Multiple events
            ['input', 'change', 'keyup'].forEach(function(eventType) {
                input.addEventListener(eventType, function() {
                    let val = parseInt(this.value);
                    
                    // Handle NaN or empty
                    if (isNaN(val) || this.value === '') {
                        // Don't auto-set to 0 while typing
                        val = 0;
                    }
                    
                    // Validasi max
                    if (val > max) {
                        this.value = max;
                        alert('Jumlah maksimal adalah ' + max + ' (stok warehouse tersedia)');
                    }
                    
                    // Validasi min
                    if (val < 0) {
                        this.value = 0;
                    }
                    
                    // Highlight row
                    if (val > 0) {
                        row.classList.add('table-success');
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        row.classList.remove('table-success');
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                    
                    // Update summary
                    updateSummary();
                });
            });
        });
        
        // Form validation
        const form = document.getElementById('requestForm');
        form.addEventListener('submit', function(e) {
            let hasSelection = false;
            let totalQty = 0;
            
            quantityInputs.forEach(function(input) {
                if (input.disabled) return;
                
                const quantity = parseInt(input.value) || 0;
                
                if (quantity > 0) {
                    hasSelection = true;
                    totalQty += quantity;
                }
            });
            
            if (!hasSelection || totalQty === 0) {
                e.preventDefault();
                alert('Masukkan jumlah minimal 1 pada varian yang ingin Anda request!');
                return false;
            }
            
            if (!confirm('Submit request untuk ' + totalQty + ' pcs?')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Initial update
        setTimeout(function() {
            updateSummary();
        }, 300);
    });
    </script>

</x-admin-layout>