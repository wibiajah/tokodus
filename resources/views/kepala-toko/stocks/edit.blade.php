<x-admin-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-weight-bold h4 text-dark mb-0">Edit Stok Produk</h2>
            <a href="{{ route('kepala-toko.stocks.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill mr-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
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

            {{-- Product Info Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            @if($product->photos && count($product->photos) > 0)
                                <img src="{{ asset('storage/' . $product->photos[0]) }}" 
                                    alt="{{ $product->title }}"
                                    class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" 
                                    style="height: 150px;">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-10">
                            <h3 class="mb-2">{{ $product->title }}</h3>
                            <div class="row">
                                <div class="col-md-3">
                                    <small class="text-muted d-block">SKU</small>
                                    <code>{{ $product->sku }}</code>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Harga</small>
                                    <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Kategori</small>
                                    <span class="badge badge-info">
                                        {{ $product->categories->pluck('name')->join(', ') ?: '-' }}
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Toko</small>
                                    <strong>{{ auth()->user()->toko->nama_toko }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Edit Stock Form --}}
            <form action="{{ route('kepala-toko.stocks.update', $product) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-pencil-square mr-2"></i>Edit Stok Varian Produk
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle mr-2"></i>
                            <strong>Panduan:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Tambah:</strong> Menambah stok dari jumlah saat ini</li>
                                <li><strong>Kurangi:</strong> Mengurangi stok dari jumlah saat ini</li>
                                <li><strong>Set:</strong> Mengatur ulang stok ke jumlah tertentu</li>
                            </ul>
                        </div>

                        @php
                            $variantIndex = 0;
                        @endphp

                        @foreach($product->variants as $colorVariant)
                            @if($colorVariant->hasChildren())
                                {{-- Color variant dengan size children --}}
                                <div class="variant-group mb-4">
                                    <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                        @if($colorVariant->photo)
                                            <img src="{{ asset('storage/' . $colorVariant->photo) }}" 
                                                alt="{{ $colorVariant->name }}"
                                                class="rounded mr-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                        <h5 class="mb-0 text-primary">
                                            <i class="bi bi-palette mr-2"></i>{{ $colorVariant->name }}
                                        </h5>
                                    </div>

                                    @foreach($colorVariant->children as $sizeVariant)
                                        @php
                                            $currentStock = $sizeVariant->stocks->first();
                                            $warehouseStock = $sizeVariant->stock_pusat;
                                        @endphp

                                        @if($currentStock)
                                            <div class="row align-items-center mb-3 p-3 bg-light rounded">
                                                <input type="hidden" name="variants[{{ $variantIndex }}][variant_id]" value="{{ $sizeVariant->id }}">
                                                
                                                <div class="col-md-3">
                                                    <label class="font-weight-bold">
                                                        <i class="bi bi-rulers mr-1"></i>{{ $sizeVariant->name }}
                                                        @if($sizeVariant->price)
                                                            <small class="text-muted">(+Rp {{ number_format($sizeVariant->price, 0, ',', '.') }})</small>
                                                        @endif
                                                    </label>
                                                    <div class="small text-muted">
                                                        <strong>Stok Saat Ini:</strong> 
                                                        <span class="badge badge-primary">{{ $currentStock->stock }} pcs</span>
                                                    </div>
                                                    <div class="small text-muted">
                                                        <strong>Stok Warehouse:</strong> 
                                                        <span class="badge badge-info">{{ $warehouseStock }} pcs</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Tipe Aksi</label>
                                                    <select name="variants[{{ $variantIndex }}][action_type]" 
                                                            class="form-control action-type-select" 
                                                            data-index="{{ $variantIndex }}"
                                                            required>
                                                        <option value="add">➕ Tambah Stok</option>
                                                        <option value="reduce">➖ Kurangi Stok</option>
                                                        <option value="set">🔄 Set Ulang Stok</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Jumlah</label>
                                                    <input type="number" 
                                                           name="variants[{{ $variantIndex }}][stock]" 
                                                           class="form-control stock-input"
                                                           data-index="{{ $variantIndex }}"
                                                           data-current="{{ $currentStock->stock }}"
                                                           data-warehouse="{{ $warehouseStock }}"
                                                           min="0"
                                                           value="0"
                                                           required>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Hasil Akhir</label>
                                                    <div class="form-control bg-white font-weight-bold text-center result-display" 
                                                         id="result-{{ $variantIndex }}"
                                                         data-index="{{ $variantIndex }}">
                                                        {{ $currentStock->stock }} pcs
                                                    </div>
                                                    <small class="text-danger d-none warning-text" id="warning-{{ $variantIndex }}">
                                                        ⚠️ Melebihi stok warehouse!
                                                    </small>
                                                </div>
                                            </div>
                                            @php $variantIndex++; @endphp
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                {{-- Color variant tanpa size --}}
                                @php
                                    $currentStock = $colorVariant->stocks->first();
                                    $warehouseStock = $colorVariant->stock_pusat;
                                @endphp

                                @if($currentStock)
                                    <div class="row align-items-center mb-3 p-3 bg-light rounded">
                                        <input type="hidden" name="variants[{{ $variantIndex }}][variant_id]" value="{{ $colorVariant->id }}">
                                        
                                        <div class="col-md-3">
                                            <label class="font-weight-bold">
                                                @if($colorVariant->photo)
                                                    <img src="{{ asset('storage/' . $colorVariant->photo) }}" 
                                                        alt="{{ $colorVariant->name }}"
                                                        class="rounded mr-2" style="width: 24px; height: 24px; object-fit: cover;">
                                                @endif
                                                {{ $colorVariant->name }}
                                            </label>
                                            <div class="small text-muted">
                                                <strong>Stok Saat Ini:</strong> 
                                                <span class="badge badge-primary">{{ $currentStock->stock }} pcs</span>
                                            </div>
                                            <div class="small text-muted">
                                                <strong>Stok Warehouse:</strong> 
                                                <span class="badge badge-info">{{ $warehouseStock }} pcs</span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label>Tipe Aksi</label>
                                            <select name="variants[{{ $variantIndex }}][action_type]" 
                                                    class="form-control action-type-select" 
                                                    data-index="{{ $variantIndex }}"
                                                    required>
                                                <option value="add">➕ Tambah Stok</option>
                                                <option value="reduce">➖ Kurangi Stok</option>
                                                <option value="set">🔄 Set Ulang Stok</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>Jumlah</label>
                                            <input type="number" 
                                                   name="variants[{{ $variantIndex }}][stock]" 
                                                   class="form-control stock-input"
                                                   data-index="{{ $variantIndex }}"
                                                   data-current="{{ $currentStock->stock }}"
                                                   data-warehouse="{{ $warehouseStock }}"
                                                   min="0"
                                                   value="0"
                                                   required>
                                        </div>

                                        <div class="col-md-3">
                                            <label>Hasil Akhir</label>
                                            <div class="form-control bg-white font-weight-bold text-center result-display" 
                                                 id="result-{{ $variantIndex }}"
                                                 data-index="{{ $variantIndex }}">
                                                {{ $currentStock->stock }} pcs
                                            </div>
                                            <small class="text-danger d-none warning-text" id="warning-{{ $variantIndex }}">
                                                ⚠️ Melebihi stok warehouse!
                                            </small>
                                        </div>
                                    </div>
                                    @php $variantIndex++; @endphp
                                @endif
                            @endif
                        @endforeach

                        @if($variantIndex === 0)
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle mr-2"></i>
                                Tidak ada varian yang tersedia untuk diedit. Pastikan produk sudah ditambahkan ke toko Anda.
                            </div>
                        @endif
                    </div>

                    @if($variantIndex > 0)
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('kepala-toko.stocks.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle mr-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="bi bi-save mr-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to calculate result
            function calculateResult(index) {
                const actionSelect = document.querySelector(`select[data-index="${index}"]`);
                const stockInput = document.querySelector(`input[data-index="${index}"]`);
                const resultDiv = document.getElementById(`result-${index}`);
                const warningDiv = document.getElementById(`warning-${index}`);
                
                if (!actionSelect || !stockInput || !resultDiv) return;
                
                const actionType = actionSelect.value;
                const inputValue = parseInt(stockInput.value) || 0;
                const currentStock = parseInt(stockInput.dataset.current) || 0;
                const warehouseStock = parseInt(stockInput.dataset.warehouse) || 0;
                
                let newStock = currentStock;
                
                switch(actionType) {
                    case 'add':
                        newStock = currentStock + inputValue;
                        break;
                    case 'reduce':
                        newStock = Math.max(0, currentStock - inputValue);
                        break;
                    case 'set':
                        newStock = inputValue;
                        break;
                }
                
                // Update result display
                resultDiv.textContent = `${newStock} pcs`;
                
                // Check if exceeds warehouse stock for 'add' action
                const changeInStock = newStock - currentStock;
                if (changeInStock > 0 && changeInStock > warehouseStock) {
                    resultDiv.classList.add('text-danger', 'border-danger');
                    warningDiv.classList.remove('d-none');
                } else {
                    resultDiv.classList.remove('text-danger', 'border-danger');
                    warningDiv.classList.add('d-none');
                }
                
                // Color coding
                if (newStock > currentStock) {
                    resultDiv.classList.add('text-success');
                    resultDiv.classList.remove('text-warning');
                } else if (newStock < currentStock) {
                    resultDiv.classList.add('text-warning');
                    resultDiv.classList.remove('text-success');
                } else {
                    resultDiv.classList.remove('text-success', 'text-warning');
                }
            }
            
            // Add event listeners to all action selects
            document.querySelectorAll('.action-type-select').forEach(function(select) {
                select.addEventListener('change', function() {
                    const index = this.dataset.index;
                    calculateResult(index);
                });
            });
            
            // Add event listeners to all stock inputs
            document.querySelectorAll('.stock-input').forEach(function(input) {
                input.addEventListener('input', function() {
                    const index = this.dataset.index;
                    calculateResult(index);
                });
                
                // Initial calculation
                const index = input.dataset.index;
                calculateResult(index);
            });
            
            // Form validation before submit
            document.querySelector('form').addEventListener('submit', function(e) {
                let hasError = false;
                
                document.querySelectorAll('.stock-input').forEach(function(input) {
                    const index = input.dataset.index;
                    const actionSelect = document.querySelector(`select[data-index="${index}"]`);
                    const inputValue = parseInt(input.value) || 0;
                    const currentStock = parseInt(input.dataset.current) || 0;
                    const warehouseStock = parseInt(input.dataset.warehouse) || 0;
                    
                    if (actionSelect.value === 'add') {
                        if (inputValue > warehouseStock) {
                            hasError = true;
                            alert(`Stok yang ditambahkan melebihi stok warehouse yang tersedia (${warehouseStock} pcs)!`);
                            input.focus();
                        }
                    }
                });
                
                if (hasError) {
                    e.preventDefault();
                }
            });
        });
    </script>

</x-admin-layout>