<x-admin-layout>
    <x-slot name="header">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-ticket-alt text-primary"></i> Tambah Voucher Baru
            </h1>
            <a href="{{ route('superadmin.vouchers.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="container-fluid">
        {{-- Alert Error --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <strong>Ada kesalahan!</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('superadmin.vouchers.store') }}" method="POST" id="voucherForm">
            @csrf

            <div class="row">
                {{-- Kolom Kiri --}}
                <div class="col-lg-8">
                    {{-- Informasi Dasar --}}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-info-circle"></i> Informasi Dasar
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nama Voucher <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       placeholder="Contoh: Diskon Akhir Tahun"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3"
                                          placeholder="Deskripsi singkat tentang voucher ini">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="distribution_type">Tipe Distribusi <span class="text-danger">*</span></label>
                                        <select class="form-control @error('distribution_type') is-invalid @enderror" 
                                                id="distribution_type" 
                                                name="distribution_type" 
                                                required>
                                            <option value="public" {{ old('distribution_type') == 'public' ? 'selected' : '' }}>
                                                Public (Pakai Kode)
                                            </option>
                                            <option value="private" {{ old('distribution_type') == 'private' ? 'selected' : '' }}>
                                                Private (Tanpa Kode)
                                            </option>
                                        </select>
                                        <small class="form-text text-muted">
                                            Public: Customer input kode. Private: Langsung ke customer tertentu.
                                        </small>
                                        @error('distribution_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6" id="code_field">
                                    <div class="form-group">
                                        <label for="code">Kode Voucher</label>
                                        <input type="text" 
                                               class="form-control @error('code') is-invalid @enderror" 
                                               id="code" 
                                               name="code" 
                                               value="{{ old('code') }}"
                                               placeholder="Kosongkan untuk auto-generate"
                                               style="text-transform: uppercase;">
                                        <small class="form-text text-muted">
                                            Kosongkan untuk generate otomatis
                                        </small>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pengaturan Diskon --}}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-tags"></i> Pengaturan Diskon
                            </h6>
                        </div>
                        <div class="card-body">
                            {{-- STEP 1: PILIH TIPE DISKON --}}
                            <div class="form-group">
                                <label for="discount_type">Tipe Diskon <span class="text-danger">*</span></label>
                                <select class="form-control @error('discount_type') is-invalid @enderror" 
                                        id="discount_type" 
                                        name="discount_type" 
                                        required>
                                    <option value="">-- Pilih Tipe Diskon --</option>
                                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>
                                        💰 Fixed - Potongan Nominal Rp (contoh: Rp 50.000)
                                    </option>
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                        📊 Percentage - Potongan Persen % (contoh: 10%)
                                    </option>
                                </select>
                                @error('discount_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info" id="discount_info" style="display: none;">
                                <i class="fas fa-info-circle"></i>
                                <span id="discount_info_text"></span>
                            </div>

                            {{-- UNTUK FIXED DISCOUNT --}}
                            <div id="fixed_discount_fields" style="display: none;">
                                <div class="form-group">
                                    <label for="discount_value_fixed">
                                        Nilai Diskon (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" 
                                               class="form-control @error('discount_value') is-invalid @enderror" 
                                               id="discount_value_fixed" 
                                               name="discount_value" 
                                               value="{{ old('discount_value') }}"
                                               step="1000"
                                               min="1000"
                                               placeholder="50000">
                                    </div>
                                    <small class="form-text text-muted">
                                        Contoh: 50000 = diskon Rp 50.000
                                    </small>
                                    @error('discount_value')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- UNTUK PERCENTAGE DISCOUNT --}}
                            <div id="percentage_discount_fields" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="discount_value_percentage">
                                                Persentase Diskon (%) <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       class="form-control @error('discount_value') is-invalid @enderror" 
                                                       id="discount_value_percentage" 
                                                       name="discount_value_percentage" 
                                                       value="{{ old('discount_value') }}"
                                                       step="1"
                                                       min="1"
                                                       max="100"
                                                       placeholder="10">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                Contoh: 10 = diskon 10%
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="max_discount">
                                                Maksimal Potongan (Rp) <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" 
                                                       class="form-control @error('max_discount') is-invalid @enderror" 
                                                       id="max_discount" 
                                                       name="max_discount" 
                                                       value="{{ old('max_discount') }}"
                                                       step="1000"
                                                       min="1000"
                                                       placeholder="100000">
                                            </div>
                                            <small class="form-text text-muted">
                                                Contoh: 100000 = maksimal potong Rp 100.000
                                            </small>
                                            @error('max_discount')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- CONTOH PERHITUNGAN --}}
                                <div class="alert alert-success">
                                    <strong>📝 Contoh Perhitungan:</strong><br>
                                    Jika customer belanja <strong>Rp 1.000.000</strong> dengan diskon <strong id="example_percent">10%</strong>:<br>
                                    • Perhitungan: Rp 1.000.000 × <span id="example_percent2">10%</span> = <strong id="example_discount">Rp 100.000</strong><br>
                                    • Tapi maksimal potongan: <strong id="example_max">Rp 100.000</strong><br>
                                    • Jadi customer dapat diskon: <strong id="final_discount">Rp 100.000</strong>
                                </div>
                            </div>

                            {{-- MINIMAL PEMBELIAN (UNTUK SEMUA TIPE) --}}
                            <div class="form-group" id="min_purchase_field" style="display: none;">
                                <label for="min_purchase">Minimal Pembelian (Rp)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" 
                                           class="form-control @error('min_purchase') is-invalid @enderror" 
                                           id="min_purchase" 
                                           name="min_purchase" 
                                           value="{{ old('min_purchase', 0) }}"
                                           step="1000"
                                           min="0"
                                           placeholder="100000">
                                </div>
                                <small class="form-text text-muted">
                                    Minimal total belanja untuk bisa pakai voucher. Kosongkan atau isi 0 jika tidak ada minimal.
                                </small>
                                @error('min_purchase')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Scope Produk --}}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-boxes"></i> Berlaku Untuk
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="scope">Scope Voucher <span class="text-danger">*</span></label>
                                <select class="form-control @error('scope') is-invalid @enderror" 
                                        id="scope" 
                                        name="scope" 
                                        required>
                                    <option value="all_products" {{ old('scope') == 'all_products' ? 'selected' : '' }}>
                                        Semua Produk
                                    </option>
                                    <option value="specific_products" {{ old('scope') == 'specific_products' ? 'selected' : '' }}>
                                        Produk Tertentu
                                    </option>
                                    <option value="specific_categories" {{ old('scope') == 'specific_categories' ? 'selected' : '' }}>
                                        Kategori Tertentu
                                    </option>
                                </select>
                                @error('scope')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Pilih Produk --}}
                            <div id="products_field" style="display: none;">
                                <label>Pilih Produk <span class="text-danger">*</span></label>
                                
                                @if($products->count() > 0)
                                    <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                        @foreach($products as $product)
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="product_{{ $product->id }}" 
                                                       name="products[]" 
                                                       value="{{ $product->id }}"
                                                       {{ in_array($product->id, old('products', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="product_{{ $product->id }}">
                                                    <strong>{{ $product->title }}</strong>
                                                    <small class="text-muted d-block">
                                                        SKU: {{ $product->sku }} | 
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    </small>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        Tidak ada produk yang tersedia. Silakan tambahkan produk terlebih dahulu.
                                    </div>
                                @endif
                            </div>

                            {{-- Pilih Kategori --}}
                            <div id="categories_field" style="display: none;">
                                <label>Pilih Kategori <span class="text-danger">*</span></label>
                                
                                @if($categories->count() > 0)
                                    <div class="border rounded p-3">
                                        @foreach($categories as $category)
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="category_{{ $category->id }}" 
                                                       name="categories[]" 
                                                       value="{{ $category->id }}"
                                                       {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="category_{{ $category->id }}">
                                                    <strong>{{ $category->name }}</strong>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        Tidak ada kategori yang tersedia.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="col-lg-4">
                    {{-- Periode & Status --}}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-calendar"></i> Periode & Status
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="start_date">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date') }}"
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="end_date">Tanggal Berakhir <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date') }}"
                                       required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    <strong>Aktifkan Voucher</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Limit Penggunaan --}}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-chart-bar"></i> Limit Penggunaan
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="usage_limit_total">Limit Total</label>
                                <input type="number" 
                                       class="form-control @error('usage_limit_total') is-invalid @enderror" 
                                       id="usage_limit_total" 
                                       name="usage_limit_total" 
                                       value="{{ old('usage_limit_total') }}"
                                       min="1"
                                       placeholder="Kosongkan untuk unlimited">
                                <small class="form-text text-muted">
                                    Total maksimal voucher bisa digunakan
                                </small>
                                @error('usage_limit_total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="usage_limit_per_customer">Limit Per Customer</label>
                                <input type="number" 
                                       class="form-control @error('usage_limit_per_customer') is-invalid @enderror" 
                                       id="usage_limit_per_customer" 
                                       name="usage_limit_per_customer" 
                                       value="{{ old('usage_limit_per_customer') }}"
                                       min="1"
                                       placeholder="Kosongkan untuk unlimited">
                                <small class="form-text text-muted">
                                    Maksimal berapa kali 1 customer bisa pakai
                                </small>
                                @error('usage_limit_per_customer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="can_combine" 
                                       name="can_combine" 
                                       value="1"
                                       {{ old('can_combine') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="can_combine">
                                    Bisa dikombinasi dengan voucher lain
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Target Customer --}}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-users"></i> Target Customer
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted mb-2">
                                Kosongkan jika voucher untuk semua customer. 
                                Pilih customer tertentu untuk membatasi akses.
                            </p>
                            <div style="max-height: 250px; overflow-y: auto;">
                                @foreach($customers as $customer)
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="customer_{{ $customer->id }}" 
                                               name="customers[]" 
                                               value="{{ $customer->id }}"
                                               {{ in_array($customer->id, old('customers', [])) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customer_{{ $customer->id }}">
                                            {{ $customer->full_name }}
                                            <small class="text-muted d-block">{{ $customer->email }}</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Simpan Voucher
                            </button>
                            <a href="{{ route('superadmin.vouchers.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ELEMENTS
        const discountType = document.getElementById('discount_type');
        const fixedFields = document.getElementById('fixed_discount_fields');
        const percentageFields = document.getElementById('percentage_discount_fields');
        const minPurchaseField = document.getElementById('min_purchase_field');
        const discountInfo = document.getElementById('discount_info');
        const discountInfoText = document.getElementById('discount_info_text');
        
        const discountValueFixed = document.getElementById('discount_value_fixed');
        const discountValuePercentage = document.getElementById('discount_value_percentage');
        const maxDiscount = document.getElementById('max_discount');

        const scopeSelect = document.getElementById('scope');
        const productsField = document.getElementById('products_field');
        const categoriesField = document.getElementById('categories_field');
        
        const distributionType = document.getElementById('distribution_type');
        const codeField = document.getElementById('code_field');
        const codeInput = document.getElementById('code');

        // ========================================
        // TOGGLE DISCOUNT TYPE
        // ========================================
        function toggleDiscountType() {
            const type = discountType.value;
            
            // Reset semua field
            fixedFields.style.display = 'none';
            percentageFields.style.display = 'none';
            minPurchaseField.style.display = 'none';
            discountInfo.style.display = 'none';
            
            // Clear required
            if (discountValueFixed) discountValueFixed.removeAttribute('required');
            if (discountValuePercentage) discountValuePercentage.removeAttribute('required');
            if (maxDiscount) maxDiscount.removeAttribute('required');
            
            if (type === 'fixed') {
                // FIXED DISCOUNT
                fixedFields.style.display = 'block';
                minPurchaseField.style.display = 'block';
                discountInfo.style.display = 'block';
                discountInfoText.textContent = 'Diskon Fixed: Customer akan mendapat potongan sesuai nominal yang Anda tentukan.';
                
                if (discountValueFixed) {
                    discountValueFixed.setAttribute('required', 'required');
                }
                
            } else if (type === 'percentage') {
                // PERCENTAGE DISCOUNT
                percentageFields.style.display = 'block';
                minPurchaseField.style.display = 'block';
                discountInfo.style.display = 'block';
                discountInfoText.textContent = 'Diskon Persentase: Customer akan mendapat potongan % dari total belanja, tapi dibatasi maksimal potongan.';
                
                if (discountValuePercentage) {
                    discountValuePercentage.setAttribute('required', 'required');
                }
                if (maxDiscount) {
                    maxDiscount.setAttribute('required', 'required');
                }
                
                updateExample();
            }
        }

        // ========================================
        // UPDATE CONTOH PERHITUNGAN
        // ========================================
        function updateExample() {
            const percent = discountValuePercentage.value || 10;
            const maxValue = maxDiscount.value || 100000;
            
            const calculatedDiscount = 1000000 * (percent / 100);
            const finalDiscount = Math.min(calculatedDiscount, maxValue);
            
            document.getElementById('example_percent').textContent = percent + '%';
            document.getElementById('example_percent2').textContent = percent + '%';
            document.getElementById('example_discount').textContent = 'Rp ' + calculatedDiscount.toLocaleString('id-ID');
            document.getElementById('example_max').textContent = 'Rp ' + parseFloat(maxValue).toLocaleString('id-ID');
            document.getElementById('final_discount').textContent = 'Rp ' + finalDiscount.toLocaleString('id-ID');
        }

        // ========================================
        // TOGGLE SCOPE FIELDS
        // ========================================
        function toggleScopeFields() {
            const scope = scopeSelect.value;
            
            productsField.style.display = 'none';
            categoriesField.style.display = 'none';
            
            if (scope === 'specific_products') {
                productsField.style.display = 'block';
            } else if (scope === 'specific_categories') {
                categoriesField.style.display = 'block';
            }
        }

        // ========================================
        // TOGGLE CODE FIELD
        // ========================================
        function toggleCodeField() {
            if (distributionType.value === 'private') {
                codeField.style.display = 'none';
                if (codeInput) codeInput.value = '';
            } else {
                codeField.style.display = 'block';
            }
        }

        // ========================================
        // EVENT LISTENERS
        // ========================================
        discountType.addEventListener('change', toggleDiscountType);
        scopeSelect.addEventListener('change', toggleScopeFields);
        distributionType.addEventListener('change', toggleCodeField);
        
        if (discountValuePercentage) {
            discountValuePercentage.addEventListener('input', updateExample);
        }
        if (maxDiscount) {
            maxDiscount.addEventListener('input', updateExample);
        }

        // Auto uppercase kode
        if (codeInput) {
            codeInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        }

        // ========================================
        // VALIDASI FORM SEBELUM SUBMIT
        // ========================================
        document.getElementById('voucherForm').addEventListener('submit', function(e) {
            const type = discountType.value;
            
            // Validasi discount type
            if (!type) {
                e.preventDefault();
                alert('❌ Silakan pilih tipe diskon terlebih dahulu!');
                discountType.focus();
                return false;
            }
            
            // Validasi FIXED discount
            if (type === 'fixed') {
                if (!discountValueFixed.value || discountValueFixed.value <= 0) {
                    e.preventDefault();
                    alert('❌ Silakan isi nilai diskon (Rp)!');
                    discountValueFixed.focus();
                    return false;
                }
            }
            
            // Validasi PERCENTAGE discount
            if (type === 'percentage') {
                if (!discountValuePercentage.value || discountValuePercentage.value <= 0) {
                    e.preventDefault();
                    alert('❌ Silakan isi persentase diskon (%)!');
                    discountValuePercentage.focus();
                    return false;
                }
                
                if (discountValuePercentage.value > 100) {
                    e.preventDefault();
                    alert('❌ Persentase diskon tidak boleh lebih dari 100%!');
                    discountValuePercentage.focus();
                    return false;
                }
                
                if (!maxDiscount.value || maxDiscount.value <= 0) {
                    e.preventDefault();
                    alert('❌ Maksimal potongan (Rp) wajib diisi untuk diskon persentase!');
                    maxDiscount.focus();
                    return false;
                }
            }
            
            // Validasi scope products
            const scope = scopeSelect.value;
            
            if (scope === 'specific_products') {
                const checkedProducts = document.querySelectorAll('input[name="products[]"]:checked');
                if (checkedProducts.length === 0) {
                    e.preventDefault();
                    alert('❌ Silakan pilih minimal 1 produk!');
                    productsField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
            }
            
            if (scope === 'specific_categories') {
                const checkedCategories = document.querySelectorAll('input[name="categories[]"]:checked');
                if (checkedCategories.length === 0) {
                    e.preventDefault();
                    alert('❌ Silakan pilih minimal 1 kategori!');
                    categoriesField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
            }
            
            // Sebelum submit, copy nilai percentage ke field discount_value
            if (type === 'percentage' && discountValuePercentage.value) {
                // Buat hidden input untuk discount_value
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'discount_value';
                hiddenInput.value = discountValuePercentage.value;
                this.appendChild(hiddenInput);
            }
            
            return true;
        });

        // ========================================
        // INITIAL STATE
        // ========================================
        toggleDiscountType();
        toggleScopeFields();
        toggleCodeField();
    });
    </script>

</x-admin-layout>