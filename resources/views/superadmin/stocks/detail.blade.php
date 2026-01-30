<x-admin-layout title="Detail Stok - {{ $product->title }}">
    <div class="container-fluid">
        
        @include('layouts.management-header', [
            'icon' => 'fas fa-boxes',
            'title' => 'Detail Stok Produk',
            'description' => 'Kelola distribusi stok varian ke toko',
            'buttonText' => 'Lihat History',
            'buttonRoute' => route('superadmin.stocks.history', $product),
            'buttonIcon' => 'fas fa-history',
        ])

        <!-- Product Info Card -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        @if($product->photos && count($product->photos) > 0)
                            <img src="{{ asset('storage/' . $product->photos[0]) }}" 
                                 alt="{{ $product->title }}" 
                                 class="img-thumbnail"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h4 class="mb-2">{{ $product->title }}</h4>
                        <p class="mb-1"><strong>SKU:</strong> <span class="badge badge-secondary">{{ $product->sku }}</span></p>
                        @if($product->tipe)
                            <p class="mb-0"><strong>Tipe:</strong> {{ $product->tipe }}</p>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-left border-primary pl-3">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Stok Pusat</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($product->total_stock) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border-left border-success pl-3">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Dialokasikan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($product->total_distributed_stock) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-triangle mr-2"></i>Error!</strong>
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="stockTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab">
            <i class="fas fa-chart-bar mr-1"></i> Overview Stok
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="distribute-tab" data-toggle="tab" href="#distribute" role="tab">
            <i class="fas fa-truck mr-1"></i> Distribusi Stok
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="edit-stock-tab" data-toggle="tab" href="#edit-stock" role="tab">
            <i class="fas fa-edit mr-1"></i> Edit Stok Pusat
        </a>
    </li>
</ul>

        <div class="tab-content" id="stockTabsContent">
            
            <!-- TAB 1: Overview Stok -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-table"></i> Breakdown Stok Per Varian
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Varian</th>
                                        <th class="text-center" width="120">Stok Pusat</th>
                                        @foreach($tokos as $toko)
                                            <th class="text-center" width="100">{{ $toko->nama_toko }}</th>
                                        @endforeach
                                        <th class="text-center" width="120">Total Toko</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stockOverview as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item['display_name'] }}</strong>
                                                @if($item['variant']->price)
                                                    <br><small class="text-muted">{{ $item['variant']->formatted_price }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-primary badge-pill px-3 py-2">
                                                    {{ number_format($item['stock_pusat']) }}
                                                </span>
                                            </td>
                                            @foreach($tokos as $toko)
                                                @php
                                                    $stock = $item['stocks_per_toko']->get($toko->id);
                                                    $stockQty = $stock ? $stock->stock : 0;
                                                @endphp
                                                <td class="text-center">
                                                    @if($stockQty > 0)
                                                        <span class="badge badge-success badge-pill px-3 py-2">
                                                            {{ number_format($stockQty) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                @php
                                                    $totalToko = $item['stocks_per_toko']->sum('stock');
                                                @endphp
                                                <strong class="text-info">{{ number_format($totalToko) }}</strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ 3 + $tokos->count() }}" class="text-center py-4">
                                                <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">Tidak ada data varian</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if(count($stockOverview) > 0)
                                    <tfoot class="thead-light">
                                        <tr>
                                            <td><strong>TOTAL</strong></td>
                                            <td class="text-center">
                                                <strong class="text-primary">{{ number_format($product->total_stock) }}</strong>
                                            </td>
                                            @foreach($tokos as $toko)
                                                @php
                                                    $tokoTotal = 0;
                                                    foreach($stockOverview as $item) {
                                                        $stock = $item['stocks_per_toko']->get($toko->id);
                                                        $tokoTotal += $stock ? $stock->stock : 0;
                                                    }
                                                @endphp
                                                <td class="text-center">
                                                    <strong>{{ number_format($tokoTotal) }}</strong>
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                <strong class="text-success">{{ number_format($product->total_distributed_stock) }}</strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="card shadow">
                    <div class="card-body">
                        <h6 class="font-weight-bold mb-3">Keterangan:</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <span class="badge badge-primary mr-2">Angka</span> Stok di Warehouse (Pusat)
                            </div>
                            <div class="col-md-4">
                                <span class="badge badge-success mr-2">Angka</span> Stok di Toko
                            </div>
                            <div class="col-md-4">
                                <span class="text-muted mr-2">-</span> Tidak ada stok
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Form Distribusi -->
            <div class="tab-pane fade" id="distribute" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-truck mr-2"></i> Form Distribusi Stok ke Toko
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superadmin.stocks.distribute.store', $product) }}" method="POST" id="distributeForm">
                            @csrf

                            <!-- Pilih Toko -->
                            <div class="form-group">
                                <label class="font-weight-bold">Pilih Toko Tujuan <span class="text-danger">*</span></label>
                                <select name="toko_id" id="tokoSelect" class="form-control form-control-lg" required>
                                    <option value="">-- Pilih Toko --</option>
                                    @foreach($tokos as $toko)
                                        <option value="{{ $toko->id }}" {{ old('toko_id') == $toko->id ? 'selected' : '' }}>
                                            {{ $toko->nama_toko }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <hr class="my-4">

                            <!-- Variants Selection -->
                            <div class="form-group">
                                <label class="font-weight-bold">Pilih Varian & Jumlah <span class="text-danger">*</span></label>
                                <small class="form-text text-muted mb-3">Tambahkan varian yang akan didistribusikan ke toko</small>
                            </div>

                            <div id="variantsContainer" class="mb-3">
                                <!-- Variant rows akan ditambahkan di sini via JS -->
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm" id="addVariantBtn">
                                <i class="fas fa-plus mr-1"></i> Tambah Varian
                            </button>

                            <hr class="my-4">

                            <!-- Notes -->
                            <div class="form-group">
                                <label class="font-weight-bold">Catatan (Opsional)</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Tambahkan catatan distribusi...">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Summary -->
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-2">Total Item Varian</h6>
                                            <h3 class="text-primary mb-0" id="totalItems">0</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-2">Total Quantity</h6>
                                            <h3 class="text-success mb-0" id="totalQuantity">0 pcs</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-lg mr-2">
                                    <i class="fas fa-check mr-2"></i>Distribusikan Stok
                                </button>
                                <a href="{{ route('superadmin.stocks.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB 3: Edit Stok Pusat -->
            <div class="tab-pane fade" id="edit-stock" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-warning text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-edit mr-2"></i> Edit Stok Pusat (Warehouse)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Catatan:</strong> Fitur ini untuk menambah, mengurangi, atau mengatur ulang stok di warehouse pusat.
                        </div>

                        <form action="{{ route('superadmin.stocks.update-stock', $product) }}" method="POST" id="editStockForm">
                            @csrf

                            <!-- Variants Selection -->
                            <div class="form-group">
                                <label class="font-weight-bold">Pilih Varian & Aksi <span class="text-danger">*</span></label>
                                <small class="form-text text-muted mb-3">Tambahkan varian yang akan diedit stoknya</small>
                            </div>

                            <div id="editVariantsContainer" class="mb-3">
                                <!-- Variant rows akan ditambahkan di sini via JS -->
                            </div>

                            <button type="button" class="btn btn-outline-warning btn-sm" id="addEditVariantBtn">
                                <i class="fas fa-plus mr-1"></i> Tambah Varian
                            </button>

                            <hr class="my-4">

                            <!-- Notes -->
                            <div class="form-group">
                                <label class="font-weight-bold">Catatan (Opsional)</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Alasan edit stok...">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Summary -->
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-2">Total Varian Diedit</h6>
                                            <h3 class="text-warning mb-0" id="editTotalItems">0</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-2">Total Perubahan</h6>
                                            <h3 class="text-success mb-0" id="editTotalChange">0 pcs</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning btn-lg mr-2">
                                    <i class="fas fa-save mr-2"></i>Simpan Perubahan Stok
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="resetEditStockForm()">
                                    <i class="fas fa-times mr-2"></i>Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

        </div>
        
    </div>

    <script>
        // Data variants dari backend
        const variants = {!! json_encode($leafVariants->map(function($variant) {
            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'display_name' => $variant->display_name,
                'stock_pusat' => $variant->stock_pusat,
                'price' => $variant->price,
                'type' => $variant->type
            ];
        })->values()) !!};
        
        let variantCounter = 0;
        let selectedVariants = new Set();

        document.getElementById('addVariantBtn').addEventListener('click', function() {
            addVariantRow();
        });

        function addVariantRow(selectedVariantId = null, quantity = 1) {
            const container = document.getElementById('variantsContainer');
            const rowId = `variant-row-${variantCounter}`;
            const currentCounter = variantCounter++;

            const row = document.createElement('div');
            row.className = 'card mb-3';
            row.id = rowId;
            row.innerHTML = `
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <label class="font-weight-bold small">Varian</label>
                            <select name="variants[${currentCounter}][variant_id]" 
                                    class="form-control variant-select" 
                                    required 
                                    data-row-id="${rowId}"
                                    onchange="handleVariantChange(this, '${rowId}')">
                                <option value="">-- Pilih Varian --</option>
                                ${variants.map(v => {
                                    const isDisabled = selectedVariants.has(v.id) && v.id != selectedVariantId;
                                    return `
                                        <option value="${v.id}" 
                                                data-stock="${v.stock_pusat}" 
                                                data-name="${v.display_name}"
                                                ${isDisabled ? 'disabled' : ''}
                                                ${selectedVariantId == v.id ? 'selected' : ''}>
                                            ${v.display_name} (Stok: ${v.stock_pusat})
                                        </option>
                                    `;
                                }).join('')}
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="font-weight-bold small">Jumlah</label>
                            <input type="number" 
                                   name="variants[${currentCounter}][quantity]" 
                                   class="form-control quantity-input" 
                                   min="1" 
                                   value="${quantity}" 
                                   required 
                                   oninput="validateQuantity(this, '${rowId}')">
                            <small class="form-text text-muted stock-info"></small>
                        </div>
                        <div class="col-md-2">
                            <label class="font-weight-bold small d-block">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-block" onclick="removeVariantRow('${rowId}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(row);

            if (selectedVariantId) {
                selectedVariants.add(parseInt(selectedVariantId));
                const select = row.querySelector('.variant-select');
                updateVariantInfo(select, rowId);
            }

            updateSummary();
        }

        function handleVariantChange(selectElement, rowId) {
            const oldValue = selectElement.getAttribute('data-old-value');
            const newValue = selectElement.value;

            if (oldValue) {
                selectedVariants.delete(parseInt(oldValue));
            }

            if (newValue && selectedVariants.has(parseInt(newValue))) {
                alert('Varian ini sudah dipilih! Pilih varian yang berbeda.');
                selectElement.value = oldValue || '';
                return;
            }

            if (newValue) {
                selectedVariants.add(parseInt(newValue));
                selectElement.setAttribute('data-old-value', newValue);
            }

            updateAllSelectOptions();
            updateVariantInfo(selectElement, rowId);
            updateSummary();
        }

        function updateAllSelectOptions() {
            const allSelects = document.querySelectorAll('.variant-select');
            
            allSelects.forEach(select => {
                const currentValue = select.value;
                const options = select.querySelectorAll('option');
                
                options.forEach(option => {
                    if (option.value === '') return;
                    
                    const variantId = parseInt(option.value);
                    if (selectedVariants.has(variantId) && variantId != currentValue) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            });
        }

        function removeVariantRow(rowId) {
            const row = document.getElementById(rowId);
            const select = row.querySelector('.variant-select');
            const variantId = select.value;

            if (variantId) {
                selectedVariants.delete(parseInt(variantId));
            }

            row.remove();
            updateAllSelectOptions();
            updateSummary();
        }

        function updateVariantInfo(selectElement, rowId) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const stockPusat = selectedOption.getAttribute('data-stock') || 0;
            const row = document.getElementById(rowId);
            const stockInfo = row.querySelector('.stock-info');
            const quantityInput = row.querySelector('.quantity-input');

            if (selectElement.value) {
                stockInfo.textContent = `Max: ${parseInt(stockPusat).toLocaleString()} pcs`;
                quantityInput.max = stockPusat;
                quantityInput.setAttribute('data-max-stock', stockPusat);

                if (parseInt(quantityInput.value) > parseInt(stockPusat)) {
                    quantityInput.value = stockPusat;
                    alert(`Jumlah tidak boleh melebihi stok pusat (${parseInt(stockPusat).toLocaleString()} pcs)`);
                }
            } else {
                stockInfo.textContent = '';
                quantityInput.max = '';
                quantityInput.removeAttribute('data-max-stock');
            }
        }

        function validateQuantity(input, rowId) {
            const maxStock = input.getAttribute('data-max-stock');
            const value = parseInt(input.value);

            if (maxStock && value > parseInt(maxStock)) {
                input.value = maxStock;
                alert(`Jumlah tidak boleh melebihi stok pusat (${parseInt(maxStock).toLocaleString()} pcs)`);
            }

            if (value < 1) {
                input.value = 1;
            }

            updateSummary();
        }

        function updateSummary() {
            const rows = document.querySelectorAll('#variantsContainer .card');
            let totalItems = 0;
            let totalQty = 0;

            rows.forEach(row => {
                const variantSelect = row.querySelector('.variant-select');
                const quantityInput = row.querySelector('.quantity-input');

                if (variantSelect.value && quantityInput.value) {
                    totalItems++;
                    totalQty += parseInt(quantityInput.value);
                }
            });

            document.getElementById('totalItems').textContent = totalItems;
            document.getElementById('totalQuantity').textContent = totalQty.toLocaleString() + ' pcs';
        }

        document.getElementById('distributeForm').addEventListener('submit', function(e) {
            const rows = document.querySelectorAll('#variantsContainer .card');
            
            if (rows.length === 0) {
                e.preventDefault();
                alert('Minimal tambahkan 1 varian untuk didistribusikan!');
                return;
            }

            const toko = document.getElementById('tokoSelect').value;
            if (!toko) {
                e.preventDefault();
                alert('Pilih toko tujuan terlebih dahulu!');
                return;
            }

            let hasEmptyVariant = false;
            rows.forEach(row => {
                const variantSelect = row.querySelector('.variant-select');
                if (!variantSelect.value) {
                    hasEmptyVariant = true;
                }
            });

            if (hasEmptyVariant) {
                e.preventDefault();
                alert('Pilih varian untuk semua item!');
                return;
            }

            if (!confirm('Yakin ingin mendistribusikan stok ini?')) {
                e.preventDefault();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            addVariantRow();
        });
    </script>

        <script>
        // ========================================
        // TAB 3: EDIT STOK PUSAT SCRIPT
        // ========================================

        let editVariantCounter = 0;
        let selectedEditVariants = new Set();

        document.getElementById('addEditVariantBtn').addEventListener('click', function() {
            addEditVariantRow();
        });

        function addEditVariantRow(selectedVariantId = null, action = 'add', quantity = 1) {
            const container = document.getElementById('editVariantsContainer');
            const rowId = `edit-variant-row-${editVariantCounter}`;
            const currentCounter = editVariantCounter++;

            const row = document.createElement('div');
            row.className = 'card mb-3';
            row.id = rowId;
            row.innerHTML = `
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="font-weight-bold small">Varian</label>
                        <select name="variants[${currentCounter}][variant_id]" 
                                class="form-control edit-variant-select" 
                                required 
                                data-row-id="${rowId}"
                                onchange="handleEditVariantChange(this, '${rowId}')">
                            <option value="">-- Pilih Varian --</option>
                            ${variants.map(v => {
                                const isDisabled = selectedEditVariants.has(v.id) && v.id != selectedVariantId;
                                return `
                                        <option value="${v.id}" 
                                                data-stock="${v.stock_pusat}" 
                                                data-name="${v.display_name}"
                                                ${isDisabled ? 'disabled' : ''}
                                                ${selectedVariantId == v.id ? 'selected' : ''}>
                                            ${v.display_name} (Stok: ${v.stock_pusat})
                                        </option>
                                    `;
                            }).join('')}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold small">Aksi</label>
                        <select name="variants[${currentCounter}][action]" 
                                class="form-control edit-action-select" 
                                required
                                onchange="updateEditSummary()">
                            <option value="add" ${action === 'add' ? 'selected' : ''}>➕ Tambah</option>
                            <option value="reduce" ${action === 'reduce' ? 'selected' : ''}>➖ Kurangi</option>
                            <option value="set" ${action === 'set' ? 'selected' : ''}>🔄 Set Baru</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold small">Jumlah</label>
                        <input type="number" 
                               name="variants[${currentCounter}][quantity]" 
                               class="form-control edit-quantity-input" 
                               min="0" 
                               value="${quantity}" 
                               required 
                               oninput="updateEditSummary()">
                        <small class="form-text text-muted edit-stock-info"></small>
                    </div>
                    <div class="col-md-2">
                        <label class="font-weight-bold small d-block">&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-block" onclick="removeEditVariantRow('${rowId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

            container.appendChild(row);

            if (selectedVariantId) {
                selectedEditVariants.add(parseInt(selectedVariantId));
                const select = row.querySelector('.edit-variant-select');
                updateEditVariantInfo(select, rowId);
            }

            updateEditSummary();
        }

        function handleEditVariantChange(selectElement, rowId) {
            const oldValue = selectElement.getAttribute('data-old-value');
            const newValue = selectElement.value;

            if (oldValue) {
                selectedEditVariants.delete(parseInt(oldValue));
            }

            if (newValue && selectedEditVariants.has(parseInt(newValue))) {
                alert('Varian ini sudah dipilih! Pilih varian yang berbeda.');
                selectElement.value = oldValue || '';
                return;
            }

            if (newValue) {
                selectedEditVariants.add(parseInt(newValue));
                selectElement.setAttribute('data-old-value', newValue);
            }

            updateAllEditSelectOptions();
            updateEditVariantInfo(selectElement, rowId);
            updateEditSummary();
        }

        function updateAllEditSelectOptions() {
            const allSelects = document.querySelectorAll('.edit-variant-select');

            allSelects.forEach(select => {
                const currentValue = select.value;
                const options = select.querySelectorAll('option');

                options.forEach(option => {
                    if (option.value === '') return;

                    const variantId = parseInt(option.value);
                    if (selectedEditVariants.has(variantId) && variantId != currentValue) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            });
        }

        function removeEditVariantRow(rowId) {
            const row = document.getElementById(rowId);
            const select = row.querySelector('.edit-variant-select');
            const variantId = select.value;

            if (variantId) {
                selectedEditVariants.delete(parseInt(variantId));
            }

            row.remove();
            updateAllEditSelectOptions();
            updateEditSummary();
        }

        function updateEditVariantInfo(selectElement, rowId) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const stockPusat = selectedOption.getAttribute('data-stock') || 0;
            const row = document.getElementById(rowId);
            const stockInfo = row.querySelector('.edit-stock-info');

            if (selectElement.value) {
                stockInfo.textContent = `Stok saat ini: ${parseInt(stockPusat).toLocaleString()} pcs`;
            } else {
                stockInfo.textContent = '';
            }
        }

        function updateEditSummary() {
            const rows = document.querySelectorAll('#editVariantsContainer .card');
            let totalItems = 0;
            let totalChange = 0;

            rows.forEach(row => {
                const variantSelect = row.querySelector('.edit-variant-select');
                const actionSelect = row.querySelector('.edit-action-select');
                const quantityInput = row.querySelector('.edit-quantity-input');

                if (variantSelect.value && quantityInput.value) {
                    totalItems++;
                    const qty = parseInt(quantityInput.value);
                    const action = actionSelect.value;

                    if (action === 'add') {
                        totalChange += qty;
                    } else if (action === 'reduce') {
                        totalChange -= qty;
                    }
                    // 'set' tidak dihitung karena bukan perubahan relatif
                }
            });

            document.getElementById('editTotalItems').textContent = totalItems;
            const changeText = totalChange >= 0 ? `+${totalChange.toLocaleString()}` : totalChange.toLocaleString();
            document.getElementById('editTotalChange').textContent = changeText + ' pcs';
        }

        function resetEditStockForm() {
            document.getElementById('editStockForm').reset();
            document.getElementById('editVariantsContainer').innerHTML = '';
            selectedEditVariants.clear();
            editVariantCounter = 0;
            addEditVariantRow();
        }

        document.getElementById('editStockForm').addEventListener('submit', function(e) {
            const rows = document.querySelectorAll('#editVariantsContainer .card');

            if (rows.length === 0) {
                e.preventDefault();
                alert('Minimal tambahkan 1 varian untuk diedit!');
                return;
            }

            let hasEmptyVariant = false;
            rows.forEach(row => {
                const variantSelect = row.querySelector('.edit-variant-select');
                if (!variantSelect.value) {
                    hasEmptyVariant = true;
                }
            });

            if (hasEmptyVariant) {
                e.preventDefault();
                alert('Pilih varian untuk semua item!');
                return;
            }

            if (!confirm('Yakin ingin menyimpan perubahan stok ini?')) {
                e.preventDefault();
            }
        });

        // Initialize dengan 1 row
        document.addEventListener('DOMContentLoaded', function() {
            addEditVariantRow();
        });
    </script>
</x-admin-layout>