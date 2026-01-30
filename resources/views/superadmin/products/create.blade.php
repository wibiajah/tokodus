<x-admin-layout title="Tambah Produk">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-box text-primary"></i> Tambah Produk Baru
            </h1>
            <a href="{{ route('superadmin.products.index') }}" class="btn btn-secondary btn-icon-split shadow-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm mb-4" id="formCard">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-edit"></i> Form Tambah Produk
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('superadmin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf

                    <!-- Informasi Dasar Produk -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-info-circle text-primary"></i> Informasi Dasar
                        </h5>

                        <!-- Title -->
                        <div class="form-group">
                            <label for="title" class="font-weight-bold">
                                Nama Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                class="form-control @error('title') is-invalid @enderror" 
                                id="title"
                                placeholder="Contoh: Kardus Ukuran Besar"
                                required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- SKU -->
                                <div class="form-group">
                                    <label for="sku" class="font-weight-bold">
                                        SKU / Kode Barang <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="sku" value="{{ old('sku') }}" 
                                        class="form-control @error('sku') is-invalid @enderror"
                                        id="sku"
                                        placeholder="Contoh: KRD-001"
                                        required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Price -->
                                <div class="form-group">
                                    <label for="price" class="font-weight-bold">
                                        Harga Normal (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="price" value="{{ old('price') }}" 
                                        class="form-control @error('price') is-invalid @enderror"
                                        id="price"
                                        min="0" 
                                        step="0.01"
                                        placeholder="50000"
                                        required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <!-- Ukuran -->
                                <div class="form-group">
                                    <label for="ukuran" class="font-weight-bold">Ukuran</label>
                                    <input type="text" name="ukuran" value="{{ old('ukuran') }}" 
                                        class="form-control @error('ukuran') is-invalid @enderror"
                                        id="ukuran"
                                        placeholder="Misal: 30x40cm, A4, dll">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Format: P x L x T (cm)
                                    </small>
                                    @error('ukuran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Jenis Bahan -->
                                <div class="form-group">
                                    <label for="jenis_bahan" class="font-weight-bold">Jenis Bahan</label>
                                    <input type="text" name="jenis_bahan" value="{{ old('jenis_bahan') }}" 
                                        class="form-control @error('jenis_bahan') is-invalid @enderror"
                                        id="jenis_bahan"
                                        placeholder="Misal: Art Paper, HVS, dll">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Opsional
                                    </small>
                                    @error('jenis_bahan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Tipe -->
                                <div class="form-group">
                                    <label for="tipe" class="font-weight-bold">
                                        Tipe <span class="text-danger">*</span>
                                    </label>
                                    <select name="tipe" class="form-control @error('tipe') is-invalid @enderror" id="tipe" required>
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="innerbox" {{ old('tipe') == 'innerbox' ? 'selected' : '' }}>Inner Box</option>
                                        <option value="masterbox" {{ old('tipe') == 'masterbox' ? 'selected' : '' }}>Master Box</option>
                                    </select>
                                    @error('tipe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Cetak -->
                                <div class="form-group">
                                    <label for="cetak" class="font-weight-bold">Cetak</label>
                                    <input type="text" name="cetak" value="{{ old('cetak') }}" 
                                        class="form-control @error('cetak') is-invalid @enderror"
                                        id="cetak"
                                        placeholder="Misal: 1 Sisi, 2 Sisi, Full Color">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Jenis metode cetak (opsional)
                                    </small>
                                    @error('cetak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Finishing -->
                                <div class="form-group">
                                    <label for="finishing" class="font-weight-bold">Finishing</label>
                                    <input type="text" name="finishing" value="{{ old('finishing') }}" 
                                        class="form-control @error('finishing') is-invalid @enderror"
                                        id="finishing"
                                        placeholder="Misal: Laminasi Doff, Glossy, dll">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Jenis finishing (opsional)
                                    </small>
                                    @error('finishing')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="font-weight-bold">Deskripsi Produk</label>
                            <textarea name="description" rows="4" 
                                class="form-control @error('description') is-invalid @enderror"
                                id="description"
                                placeholder="Jelaskan detail produk...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Discount Price -->
                                <div class="form-group">
                                    <label for="discount_price" class="font-weight-bold">Harga Diskon (Opsional)</label>
                                    <input type="number" name="discount_price" value="{{ old('discount_price') }}" 
                                        class="form-control @error('discount_price') is-invalid @enderror"
                                        id="discount_price"
                                        min="0" 
                                        step="0.01"
                                        placeholder="40000">
                                    <small class="form-text text-muted">Kosongkan jika tidak ada diskon</small>
                                    @error('discount_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Categories -->
                                <div class="form-group">
                                    <label class="font-weight-bold">Kategori (bisa lebih dari 1)</label>
                                    <div class="category-wrapper">
                                        @foreach($categories as $category)
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" 
                                                    class="custom-control-input" 
                                                    id="category_{{ $category->id }}"
                                                    {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="category_{{ $category->id }}">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Centang satu atau lebih kategori
                                    </small>
                                    @error('category_ids')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="form-group">
                            <label class="font-weight-bold">Tags (Opsional)</label>
                            <div id="tagsContainer" class="mb-2"></div>
                            <button type="button" onclick="addTag()" 
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Tambah Tag
                            </button>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Tambahkan tag untuk memudahkan pencarian produk (opsional)
                            </small>
                            @error('tags')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Photos -->
                        <div class="form-group">
                            <label for="photos" class="font-weight-bold">Foto Produk (Lebih Dari 1)</label>
                            <div class="custom-file">
                                <input type="file" name="photos[]" accept="image/*" multiple
                                    class="custom-file-input @error('photos') is-invalid @enderror" 
                                    id="photosInput">
                                <label class="custom-file-label" for="photosInput">Pilih foto...</label>
                            </div>
                            <small class="form-text text-muted">JPG, PNG, WebP. Max: 2MB per file (max 5 foto)</small>
                            @error('photos')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="photosPreview" class="photo-grid mt-3" style="display: none;"></div>
                        </div>

                        <!-- Video -->
                        <div class="form-group">
                            <label for="video" class="font-weight-bold">Video Produk (Opsional)</label>
                            <div class="custom-file">
                                <input type="file" name="video" accept="video/*" 
                                    class="custom-file-input @error('video') is-invalid @enderror" 
                                    id="videoInput">
                                <label class="custom-file-label" for="videoInput">Pilih video...</label>
                            </div>
                            <small class="form-text text-muted">MP4, MOV, AVI. Max: 10MB (max 50MB)</small>
                            @error('video')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="videoPreviewContainer" class="mt-3"></div>
                        </div>

                        <!-- Is Active -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="is_active" value="1" 
                                    class="custom-control-input" 
                                    id="is_active"
                                    {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold" for="is_active">
                                    Produk Aktif
                                </label>
                            </div>
                            <small class="form-text text-muted">Centang untuk menampilkan produk di website</small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Varian Produk -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-layer-group text-primary"></i> Varian Produk
                            </h5>
                            <button type="button" onclick="addColor()" 
                                class="btn btn-primary btn-sm shadow-sm">
                                <i class="fas fa-plus"></i> Tambah Warna
                            </button>
                        </div>

                        <div id="colorsContainer"></div>

                        @error('colors')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-3">

                    <!-- Buttons -->
                    <div class="d-flex" style="gap: 10px;">
                        <button type="button" class="btn btn-primary" id="btnPreview">
                            <i class="fas fa-eye"></i> Preview Data
                        </button>
                        <a href="{{ route('superadmin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="card shadow-sm mb-4" id="previewCard" style="display: none;">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-eye"></i> Preview Data Produk
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle"></i> Periksa kembali data. Jika benar, klik <strong>Simpan ke Database</strong>.
                </div>

                <!-- Preview Content -->
                <div class="mb-4">
                    <h5 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-info-circle text-primary"></i> Informasi Dasar
                    </h5>
                    <div class="preview-section">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>Nama Produk</label>
                                    <div id="preview_title" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>SKU</label>
                                    <div id="preview_sku" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="preview-item">
                                    <label>Harga Normal</label>
                                    <div id="preview_price" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="preview-item">
                                    <label>Harga Diskon</label>
                                    <div id="preview_discount_price" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="preview-item">
                                    <label>Kategori</label>
                                    <div id="preview_categories" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="preview-item">
                                    <label>Ukuran</label>
                                    <div id="preview_ukuran" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="preview-item">
                                    <label>Jenis Bahan</label>
                                    <div id="preview_jenis_bahan" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="preview-item">
                                    <label>Tipe</label>
                                    <div id="preview_tipe" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>Cetak</label>
                                    <div id="preview_cetak" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="preview-item">
                                    <label>Finishing</label>
                                    <div id="preview_finishing" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="preview-item">
                                    <label>Deskripsi</label>
                                    <div id="preview_description" class="preview-value">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <h5 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-layer-group text-primary"></i> Varian Produk
                    </h5>
                    <div class="preview-section">
                        <div id="preview_variants" class="preview-value">-</div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <h5 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-tags text-primary"></i> Tags
                    </h5>
                    <div class="preview-section">
                        <div id="preview_tags" class="preview-value">-</div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <h5 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-images text-primary"></i> Media Produk
                    </h5>
                    <div class="preview-section">
                        <!-- Foto Preview -->
                        <div class="mb-3">
                            <label class="d-block mb-2">Foto Produk</label>
                            <div id="preview_photos_container" style="display: none;">
                                <div id="preview_photos" class="photo-grid"></div>
                            </div>
                            <div id="preview_photos_empty" class="text-muted">
                                <i class="fas fa-times-circle"></i> Tidak ada foto
                            </div>
                        </div>

                        <!-- Video Preview -->
                        <div>
                            <label class="d-block mb-2">Video Produk</label>
                            <div id="preview_video_container" style="display: none;">
                                <video id="preview_video" controls class="preview-video"></video>
                            </div>
                            <div id="preview_video_empty" class="text-muted">
                                <i class="fas fa-times-circle"></i> Tidak ada video
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                <!-- Action Buttons -->
                <div class="d-flex" style="gap: 10px;">
                    <button type="button" class="btn btn-success" id="btnSubmit">
                        <i class="fas fa-check"></i> Simpan ke Database
                    </button>
                    <button type="button" class="btn btn-warning" id="btnEdit">
                        <i class="fas fa-edit"></i> Edit Data
                    </button>
                    <a href="{{ route('superadmin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Form Improvements */
        .form-control:focus,
        .custom-file-input:focus + .custom-file-label {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .custom-file-label::after {
            content: "Pilih";
            background-color: #4e73df;
            color: white;
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover:not(:disabled) {
            transform: translateY(-1px);
        }

        /* Category Wrapper */
        .category-wrapper {
            max-height: 200px;
            overflow-y: auto;
            padding: 15px;
            background: #f8f9fc;
            border-radius: 8px;
            border: 1px solid #e3e6f0;
        }

        .custom-control-label {
            cursor: pointer;
            user-select: none;
        }

        .custom-control-input:checked ~ .custom-control-label {
            color: #4e73df;
            font-weight: 500;
        }

        /* Tags Container */
        .tag-row {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
        }

        .tag-row input {
            flex: 1;
        }

        /* Color Row Styling */
        .color-row {
            background: #f8f9fc;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .sizes-container {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .size-row {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
        }

        /* Photo Grid */
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
        }

        .photo-item {
            position: relative;
            width: 100%;
            padding-top: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .photo-item img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Video Container */
        .video-container {
            max-width: 400px;
        }

        .video-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .video-item video {
            width: 100%;
            max-height: 300px;
            display: block;
        }

        /* Preview Section */
        .preview-section {
            background: #f8f9fc;
            padding: 20px;
            border-radius: 8px;
        }

        .preview-item label {
            font-size: 0.875rem;
            color: #858796;
            margin-bottom: 5px;
            display: block;
        }

        .preview-value {
            font-size: 0.95rem;
            color: #5a5c69;
            font-weight: 500;
        }

        .preview-video {
            max-width: 400px;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .invalid-feedback {
            font-size: 0.875rem;
        }
    </style>

   <script>
    // =================================================================
    // PHOTOS PREVIEW (MULTIPLE)
    // =================================================================
    document.getElementById('photosInput')?.addEventListener('change', function(e) {
        const preview = document.getElementById('photosPreview');
        const label = document.querySelector('label[for="photosInput"]');
        preview.innerHTML = '';
        
        const files = Array.from(e.target.files).slice(0, 5);
        if (files.length > 0) {
            label.textContent = `${files.length} file dipilih`;
            preview.style.display = 'grid';
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'photo-item';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    div.appendChild(img);
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        } else {
            label.textContent = 'Pilih foto...';
            preview.style.display = 'none';
        }
    });

    // =================================================================
    // VIDEO PREVIEW
    // =================================================================
    document.getElementById('videoInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const container = document.getElementById('videoPreviewContainer');
        const label = document.querySelector('label[for="videoInput"]');
        
        if (file) {
            label.textContent = file.name;
            container.innerHTML = `
                <div class="video-item">
                    <video class="w-100" controls style="max-width: 400px; max-height: 300px; border-radius: 8px;">
                        <source src="${URL.createObjectURL(file)}" type="${file.type}">
                    </video>
                </div>
            `;
        } else {
            label.textContent = 'Pilih video...';
            container.innerHTML = '';
        }
    });

    // =================================================================
    // TAGS MANAGEMENT
    // =================================================================
    function addTag() {
        const container = document.getElementById('tagsContainer');
        const html = `
            <div class="tag-row">
                <input type="text" name="tags[]" 
                    class="form-control form-control-sm"
                    placeholder="Contoh: brosur, flyer, cetak cepat"
                    required>
                <button type="button" onclick="removeTag(this)" 
                    class="btn btn-sm btn-danger">
                    🗑️
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function removeTag(btn) {
        btn.closest('.tag-row').remove();
    }

    // =================================================================
    // COLOR MANAGEMENT
    // =================================================================
    function getNextColorIndex() {
        const container = document.getElementById('colorsContainer');
        return container.children.length;
    }

    function addColor() {
        const container = document.getElementById('colorsContainer');
        const colorIndex = getNextColorIndex();
        
        const html = `
            <div class="color-row" data-color-index="${colorIndex}">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="font-weight-bold mb-0">Warna #${colorIndex + 1}</h6>
                    <button type="button" onclick="removeColor(this)" 
                        class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="font-weight-bold text-sm">Nama Warna *</label>
                        <input type="text" name="colors[${colorIndex}][name]" 
                            class="form-control form-control-sm" required 
                            placeholder="Misal: Merah, Biru, Hitam">
                    </div>

                    <div class="col-md-4">
                        <label class="font-weight-bold text-sm">Foto Warna</label>
                        <div class="custom-file">
                            <input type="file" name="colors[${colorIndex}][photo]" accept="image/*"
                                class="custom-file-input" id="color_photo_${colorIndex}"
                                onchange="updateColorPhotoLabel(this)">
                            <label class="custom-file-label" for="color_photo_${colorIndex}">Pilih foto</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="font-weight-bold text-sm">Stok</label>
                        <input type="number" name="colors[${colorIndex}][stock_pusat]" 
                            class="form-control form-control-sm color-stock" 
                            data-color-index="${colorIndex}" min="0" value="0"
                            placeholder="Kosongkan jika ada ukuran">
                        <small class="text-muted">*Otomatis disabled jika ada ukuran</small>
                    </div>
                </div>

                <div class="sizes-container" data-color-index="${colorIndex}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="font-weight-bold text-sm mb-0">Ukuran untuk warna ini:</label>
                        <button type="button" onclick="addSize(${colorIndex})" 
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus"></i> Tambah Ukuran
                        </button>
                    </div>
                    <div id="sizes_${colorIndex}"></div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function removeColor(btn) {
        if (confirm('Hapus warna ini?')) {
            btn.closest('.color-row').remove();
            reindexAllColors();
        }
    }

    function reindexAllColors() {
        const container = document.getElementById('colorsContainer');
        const colorRows = container.querySelectorAll('.color-row');
        
        colorRows.forEach((row, newIndex) => {
            const oldIndex = row.dataset.colorIndex;
            row.dataset.colorIndex = newIndex;
            row.querySelector('h6').textContent = `Warna #${newIndex + 1}`;
            
            // Update all input names
            row.querySelectorAll('input, select, textarea').forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(`colors[${oldIndex}]`, `colors[${newIndex}]`);
                }
                if (input.dataset.colorIndex !== undefined) {
                    input.dataset.colorIndex = newIndex;
                }
                if (input.id) {
                    input.id = input.id.replace(`_${oldIndex}`, `_${newIndex}`);
                }
            });

            // Update labels
            row.querySelectorAll('label[for]').forEach(label => {
                if (label.htmlFor) {
                    label.htmlFor = label.htmlFor.replace(`_${oldIndex}`, `_${newIndex}`);
                }
            });
            
            // Update sizes container
            const sizesContainer = row.querySelector('.sizes-container');
            if (sizesContainer) {
                sizesContainer.dataset.colorIndex = newIndex;
                const sizeRows = sizesContainer.querySelectorAll('.size-row');
                sizeRows.forEach((sizeRow, sizeIndex) => {
                    sizeRow.querySelectorAll('input').forEach(input => {
                        if (input.name) {
                            input.name = input.name.replace(`colors[${oldIndex}]`, `colors[${newIndex}]`);
                            input.name = input.name.replace(/sizes\[\d+\]/, `sizes[${sizeIndex}]`);
                        }
                        if (input.id) {
                            input.id = input.id.replace(`_${oldIndex}_`, `_${newIndex}_`);
                        }
                    });
                    sizeRow.querySelectorAll('label[for]').forEach(label => {
                        if (label.htmlFor) {
                            label.htmlFor = label.htmlFor.replace(`_${oldIndex}_`, `_${newIndex}_`);
                        }
                    });
                });
            }
            
            // Update addSize button
            const addSizeBtn = row.querySelector('[onclick^="addSize"]');
            if (addSizeBtn) {
                addSizeBtn.setAttribute('onclick', `addSize(${newIndex})`);
            }
        });
    }

    function updateColorPhotoLabel(input) {
        const label = input.nextElementSibling;
        const fileName = input.files[0]?.name || 'Pilih foto';
        label.textContent = fileName.length > 25 ? fileName.substring(0, 25) + '...' : fileName;
    }

    // =================================================================
    // SIZE MANAGEMENT
    // =================================================================
    function addSize(colorIdx) {
        const container = document.querySelector(`#sizes_${colorIdx}`);
        const sizeIndex = container.children.length;
        const html = `
            <div class="size-row" style="display: grid; grid-template-columns: 1fr 1.5fr 100px 120px 40px; gap: 8px; margin-bottom: 8px; align-items: center;">
                <input type="text" name="colors[${colorIdx}][sizes][${sizeIndex}][name]" 
                    class="form-control form-control-sm" required 
                    placeholder="Misal: S, M, L, XL">
                
                <div class="custom-file">
                    <input type="file" name="colors[${colorIdx}][sizes][${sizeIndex}][photo]" 
                        accept="image/*" class="custom-file-input" 
                        id="size_photo_${colorIdx}_${sizeIndex}"
                        onchange="updateSizePhotoLabel(this)">
                    <label class="custom-file-label" for="size_photo_${colorIdx}_${sizeIndex}" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                        Foto ukuran (opsional)
                    </label>
                </div>
                
                <input type="number" name="colors[${colorIdx}][sizes][${sizeIndex}][stock_pusat]" 
                    class="form-control form-control-sm" required min="0" value="0"
                    placeholder="Stok">
                
                <input type="number" name="colors[${colorIdx}][sizes][${sizeIndex}][price]" 
                    class="form-control form-control-sm" min="0" step="0.01"
                    placeholder="Harga (opt)">
                
                <button type="button" onclick="removeSize(this, ${colorIdx})" 
                    class="btn btn-sm btn-danger">
                    🗑️
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        toggleColorStock(colorIdx);
    }

    function removeSize(btn, colorIdx) {
        btn.closest('.size-row').remove();
        
        // Reindex sizes
        const container = document.querySelector(`#sizes_${colorIdx}`);
        const sizeRows = container.querySelectorAll('.size-row');
        sizeRows.forEach((row, newIndex) => {
            row.querySelectorAll('input').forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/sizes\[\d+\]/, `sizes[${newIndex}]`);
                }
                if (input.id) {
                    const matches = input.id.match(/size_photo_(\d+)_\d+/);
                    if (matches) {
                        input.id = `size_photo_${matches[1]}_${newIndex}`;
                    }
                }
            });
            row.querySelectorAll('label[for]').forEach(label => {
                if (label.htmlFor) {
                    const matches = label.htmlFor.match(/size_photo_(\d+)_\d+/);
                    if (matches) {
                        label.htmlFor = `size_photo_${matches[1]}_${newIndex}`;
                    }
                }
            });
        });
        
        toggleColorStock(colorIdx);
    }

    function toggleColorStock(colorIdx) {
        const sizesContainer = document.querySelector(`#sizes_${colorIdx}`);
        const colorStock = document.querySelector(`.color-stock[data-color-index="${colorIdx}"]`);
        const hasSizes = sizesContainer && sizesContainer.children.length > 0;
        
        if (colorStock) {
            colorStock.disabled = hasSizes;
            if (hasSizes) {
                colorStock.value = 0;
                colorStock.classList.add('bg-light');
            } else {
                colorStock.classList.remove('bg-light');
            }
        }
    }

    function updateSizePhotoLabel(input) {
        const label = input.nextElementSibling;
        const fileName = input.files[0]?.name || 'Foto ukuran (opsional)';
        label.textContent = fileName.length > 20 ? fileName.substring(0, 20) + '...' : fileName;
    }

    // =================================================================
    // PREVIEW FUNCTIONALITY
    // =================================================================
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    document.getElementById('btnPreview')?.addEventListener('click', function() {
        const form = document.getElementById('productForm');
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Basic info
        document.getElementById('preview_title').textContent = document.getElementById('title').value || '-';
        document.getElementById('preview_sku').textContent = document.getElementById('sku').value || '-';
        document.getElementById('preview_price').textContent = document.getElementById('price').value ? formatCurrency(document.getElementById('price').value) : '-';
        document.getElementById('preview_discount_price').textContent = document.getElementById('discount_price').value ? formatCurrency(document.getElementById('discount_price').value) : '-';
        document.getElementById('preview_description').textContent = document.getElementById('description').value || '-';
        document.getElementById('preview_ukuran').textContent = document.getElementById('ukuran').value || '-';
        document.getElementById('preview_jenis_bahan').textContent = document.getElementById('jenis_bahan').value || '-';
        
        const tipe = document.getElementById('tipe').value;
        let tipeHtml = '-';
        if (tipe === 'innerbox') {
            tipeHtml = '<span class="badge badge-info"><i class="fas fa-box mr-1"></i>Inner Box</span>';
        } else if (tipe === 'masterbox') {
            tipeHtml = '<span class="badge" style="background-color: #7b1fa2; color: white;"><i class="fas fa-boxes mr-1"></i>Master Box</span>';
        }
        document.getElementById('preview_tipe').innerHTML = tipeHtml;
        
        document.getElementById('preview_cetak').textContent = document.getElementById('cetak').value || '-';
        document.getElementById('preview_finishing').textContent = document.getElementById('finishing').value || '-';

        // Categories
        const categoriesCheckboxes = document.querySelectorAll('input[name="category_ids[]"]:checked');
        const selectedCategories = Array.from(categoriesCheckboxes).map(cb => {
            return cb.parentElement.querySelector('label').textContent.trim();
        });
        document.getElementById('preview_categories').innerHTML = selectedCategories.length > 0 
            ? selectedCategories.map(cat => `<span class="badge badge-info mr-1">${cat}</span>`).join('') 
            : '-';

        // Variants (Colors with Sizes and Photos)
        const colors = document.querySelectorAll('.color-row');
        if (colors.length > 0) {
            let variantsHtml = '<div class="variant-preview">';
            colors.forEach(colorRow => {
                const colorName = colorRow.querySelector('input[name*="[name]"]').value;
                const sizeRows = colorRow.querySelectorAll('.size-row');
                
                variantsHtml += `<div class="mb-3">
                    <strong class="d-block mb-2">${colorName}:</strong>`;
                
                if (sizeRows.length > 0) {
                    variantsHtml += '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px;">';
                    sizeRows.forEach(row => {
                        const sizeName = row.querySelector('input[name*="[name]"]').value;
                        const sizeStock = row.querySelector('input[name*="[stock_pusat]"]').value;
                        const sizePrice = row.querySelector('input[name*="[price]"]').value;
                        const photoInput = row.querySelector('input[type="file"][name*="[photo]"]');
                        
                        variantsHtml += `<div class="border rounded p-2" style="background: #f8f9fc;">
                            <strong class="d-block mb-1 text-center">${sizeName}</strong>`;
                        
                        // Preview foto ukuran jika ada
                        if (photoInput && photoInput.files.length > 0) {
                            const photoUrl = URL.createObjectURL(photoInput.files[0]);
                            variantsHtml += `<img src="${photoUrl}" 
                                style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px; margin-bottom: 8px;">`;
                        } else {
                            variantsHtml += `<div style="width: 100%; height: 100px; background: #e0e0e0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 0.75rem; margin-bottom: 8px;">
                                <i class="fas fa-image"></i> Tanpa foto
                            </div>`;
                        }
                        
                        variantsHtml += `
                            <small class="d-block text-muted">Stok: ${sizeStock}</small>
                            <small class="d-block text-muted">Harga: ${sizePrice ? formatCurrency(sizePrice) : '-'}</small>
                        </div>`;
                    });
                    variantsHtml += '</div>';
                } else {
                    variantsHtml += '<span class="text-muted">Tanpa ukuran</span>';
                }
                variantsHtml += '</div>';
            });
            variantsHtml += '</div>';
            document.getElementById('preview_variants').innerHTML = variantsHtml;
        } else {
            document.getElementById('preview_variants').innerHTML = '<span class="text-muted">Tidak ada varian</span>';
        }

        // Tags
        const tags = Array.from(document.querySelectorAll('input[name="tags[]"]')).map(i => i.value).filter(Boolean);
        if (tags.length > 0) {
            document.getElementById('preview_tags').innerHTML = tags.map(tag => 
                `<span class="badge badge-primary mr-1">${tag}</span>`
            ).join('');
        } else {
            document.getElementById('preview_tags').innerHTML = '<span class="text-muted">Tidak ada tags</span>';
        }

        // Photos
        const photosInput = document.getElementById('photosInput');
        if (photosInput.files.length > 0) {
            const photosContainer = document.getElementById('preview_photos');
            photosContainer.innerHTML = '';
            Array.from(photosInput.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'photo-item';
                    div.innerHTML = `<img src="${e.target.result}">`;
                    photosContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
            document.getElementById('preview_photos_container').style.display = 'block';
            document.getElementById('preview_photos_empty').style.display = 'none';
        } else {
            document.getElementById('preview_photos_container').style.display = 'none';
            document.getElementById('preview_photos_empty').style.display = 'block';
        }

        // Video
        const videoInput = document.getElementById('videoInput');
        if (videoInput.files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const video = document.getElementById('preview_video');
                video.src = e.target.result;
                document.getElementById('preview_video_container').style.display = 'block';
                document.getElementById('preview_video_empty').style.display = 'none';
            };
            reader.readAsDataURL(videoInput.files[0]);
        } else {
            document.getElementById('preview_video_container').style.display = 'none';
            document.getElementById('preview_video_empty').style.display = 'block';
        }

        // Show preview card
        document.getElementById('formCard').style.display = 'none';
        document.getElementById('previewCard').style.display = 'block';
        document.getElementById('previewCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    document.getElementById('btnEdit')?.addEventListener('click', function() {
        document.getElementById('formCard').style.display = 'block';
        document.getElementById('previewCard').style.display = 'none';
        document.getElementById('formCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    document.getElementById('btnSubmit')?.addEventListener('click', function() {
        if (confirm('Yakin data produk sudah benar? Data akan disimpan ke database!')) {
            document.getElementById('productForm').submit();
        }
    });

    // =================================================================
    // FORM VALIDATION
    // =================================================================
    document.getElementById('productForm')?.addEventListener('submit', function(e) {
        const colors = document.querySelectorAll('.color-row');
        if (colors.length === 0) {
            e.preventDefault();
            alert('Minimal harus ada 1 warna!');
            return false;
        }
    });

    // =================================================================
    // AUTO ADD 1 COLOR ON PAGE LOAD
    // =================================================================
    document.addEventListener('DOMContentLoaded', function() {
        addColor();
    });
</script>
</x-admin-layout>