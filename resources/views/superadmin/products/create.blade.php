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
                <form id="productForm" action="{{ route('superadmin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Informasi Dasar -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-info-circle text-primary"></i> Informasi Dasar
                        </h5>

                        <!-- Nama Produk -->
                        <div class="form-group">
                            <label for="title" class="font-weight-bold">
                                Nama Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                class="form-control @error('title') is-invalid @enderror" 
                                id="title" 
                                name="title" 
                                value="{{ old('title') }}"
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
                                    <input type="text" 
                                        class="form-control @error('sku') is-invalid @enderror" 
                                        id="sku" 
                                        name="sku" 
                                        value="{{ old('sku') }}"
                                        placeholder="Contoh: KRD-001"
                                        required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Harga -->
                                <div class="form-group">
                                    <label for="price" class="font-weight-bold">
                                        Harga (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                        class="form-control @error('price') is-invalid @enderror" 
                                        id="price" 
                                        name="price" 
                                        value="{{ old('price') }}"
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
                            <div class="col-md-6">
                                <!-- Stok Awal -->
                                <div class="form-group">
                                    <label for="initial_stock" class="font-weight-bold">
                                        Stok Awal <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                        class="form-control @error('initial_stock') is-invalid @enderror" 
                                        id="initial_stock" 
                                        name="initial_stock" 
                                        value="{{ old('initial_stock') }}"
                                        min="0"
                                        placeholder="100"
                                        required>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Stok yang akan dibagi ke toko cabang
                                    </small>
                                    @error('initial_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Kategori -->
                                <div class="form-group">
                                    <label class="font-weight-bold">Kategori (bisa lebih dari 1)</label>
                                    <div class="category-wrapper">
                                        @forelse($categories as $category)
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                    class="custom-control-input" 
                                                    id="category_{{ $category->id }}" 
                                                    name="categories[]" 
                                                    value="{{ $category->id }}"
                                                    {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="category_{{ $category->id }}">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                        @empty
                                            <p class="text-muted mb-0">Belum ada kategori. <a href="{{ route('superadmin.categories.create') }}" target="_blank">Buat kategori</a></p>
                                        @endforelse
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Centang satu atau lebih kategori
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group">
                            <label for="description" class="font-weight-bold">Deskripsi Produk</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" 
                                name="description" 
                                rows="4"
                                placeholder="Jelaskan detail produk...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Varian Produk -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-layer-group text-primary"></i> Varian Produk
                        </h5>
                        
                        <div id="variantsContainer">
                            <!-- Variants will be added here dynamically -->
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addVariantBtn">
                            <i class="fas fa-plus"></i> Tambah Varian
                        </button>
                        
                        <!-- Hidden input to store JSON -->
                        <input type="hidden" name="variants" id="variantsInput">
                        <small class="form-text text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i> Contoh: Warna (Merah, Biru), Ukuran (S, M, L)
                        </small>
                    </div>

                    <hr class="my-4">

                    <!-- Tags -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-tags text-primary"></i> Tags
                        </h5>
                        
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" id="tagInput" placeholder="Ketik tag dan tekan Enter">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" id="addTagBtn">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Contoh: bestseller, diskon, terbaru</small>
                        </div>
                        
                        <div id="tagsContainer" class="tags-display"></div>
                        
                        <!-- Hidden input to store JSON -->
                        <input type="hidden" name="tags" id="tagsInput">
                    </div>

                    <hr class="my-4">

                    <!-- Media -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-images text-primary"></i> Media Produk
                        </h5>

                        <!-- Foto Produk -->
                        <div class="form-group">
                            <label for="photos" class="font-weight-bold">Foto Produk (Lebih Dari 1)</label>
                            
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('photos.*') is-invalid @enderror" id="photos" name="photos[]" accept="image/*" multiple>
                                <label class="custom-file-label" for="photos">Pilih foto...</label>
                            </div>
                            <small class="form-text text-muted">JPG, PNG, WebP. Max: 2MB per file</small>
                            @error('photos.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <!-- Preview photos -->
                            <div id="photosPreview" class="photo-grid mt-3" style="display: none;"></div>
                        </div>

                        <!-- Video Produk -->
                        <div class="form-group">
                            <label for="video" class="font-weight-bold">Video Produk (Opsional)</label>
                            
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('video') is-invalid @enderror" id="video" name="video" accept="video/*">
                                <label class="custom-file-label" for="video">Pilih video...</label>
                            </div>
                            <small class="form-text text-muted">MP4, MOV, AVI. Max: 10MB</small>
                            @error('video')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <!-- Preview video -->
                            <div id="videoPreview" class="video-container mt-3" style="display: none;"></div>
                        </div>
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
                                    <label>Harga</label>
                                    <div id="preview_price" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="preview-item">
                                    <label>Stok Awal</label>
                                    <div id="preview_stock" class="preview-value">-</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="preview-item">
                                    <label>Kategori</label>
                                    <div id="preview_categories" class="preview-value">-</div>
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

        /* Variants Styling */
        .variant-item {
            background: #f8f9fc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #e3e6f0;
        }

        .variant-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .variant-values {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .variant-value-tag {
            background: white;
            padding: 5px 10px;
            border-radius: 6px;
            border: 1px solid #d1d3e2;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.875rem;
        }

        .variant-value-tag .remove-btn {
            cursor: pointer;
            color: #e74a3b;
            font-weight: bold;
        }

        /* Tags Styling */
        .tags-display {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
            min-height: 40px;
            padding: 10px;
            background: #f8f9fc;
            border-radius: 8px;
        }

        .tag-item {
            background: #4e73df;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
        }

        .tag-item .remove-tag {
            cursor: pointer;
            font-weight: bold;
            opacity: 0.8;
        }

        .tag-item .remove-tag:hover {
            opacity: 1;
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

        .delete-photo-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #e74a3b;
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
            z-index: 10;
        }

        .delete-photo-btn:hover {
            background: #c9302c;
            transform: scale(1.1);
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

        .delete-video-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #e74a3b;
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s;
            z-index: 10;
        }

        .delete-video-btn:hover {
            background: #c9302c;
            transform: scale(1.1);
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
        // Variants Management
        let variants = {};
        
        document.getElementById('addVariantBtn').addEventListener('click', function() {
            const variantName = prompt('Nama varian (contoh: warna, ukuran):');
            if (variantName && variantName.trim()) {
                const name = variantName.trim().toLowerCase();
                if (!variants[name]) {
                    variants[name] = [];
                    renderVariants();
                }
            }
        });

        function renderVariants() {
            const container = document.getElementById('variantsContainer');
            container.innerHTML = '';
            
            Object.keys(variants).forEach(variantName => {
                const div = document.createElement('div');
                div.className = 'variant-item';
                div.innerHTML = `
                    <div class="variant-header">
                        <strong>${variantName}</strong>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeVariant('${variantName}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Masukkan nilai (contoh: Merah)" id="value_${variantName}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" onclick="addVariantValue('${variantName}')">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="variant-values" id="values_${variantName}">
                        ${variants[variantName].map((value, index) => `
                            <span class="variant-value-tag">
                                ${value}
                                <span class="remove-btn" onclick="removeVariantValue('${variantName}', ${index})">×</span>
                            </span>
                        `).join('')}
                    </div>
                `;
                container.appendChild(div);
            });
            
            updateVariantsInput();
        }

        function addVariantValue(variantName) {
            const input = document.getElementById(`value_${variantName}`);
            const value = input.value.trim();
            if (value && !variants[variantName].includes(value)) {
                variants[variantName].push(value);
                input.value = '';
                renderVariants();
            }
        }

        function removeVariant(variantName) {
            delete variants[variantName];
            renderVariants();
        }

        function removeVariantValue(variantName, index) {
            variants[variantName].splice(index, 1);
            renderVariants();
        }

        function updateVariantsInput() {
            document.getElementById('variantsInput').value = JSON.stringify(variants);
        }

        // Tags Management
        let tags = [];

        document.getElementById('addTagBtn').addEventListener('click', addTag);
        document.getElementById('tagInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTag();
            }
        });

        function addTag() {
            const input = document.getElementById('tagInput');
            const tag = input.value.trim().toLowerCase();
            if (tag && !tags.includes(tag)) {
                tags.push(tag);
                input.value = '';
                renderTags();
            }
        }

        function renderTags() {
            const container = document.getElementById('tagsContainer');
            container.innerHTML = tags.map((tag, index) => `
                <span class="tag-item">
                    ${tag}
                    <span class="remove-tag" onclick="removeTag(${index})">×</span>
                </span>
            `).join('');
            
            updateTagsInput();
        }

        function removeTag(index) {
            tags.splice(index, 1);
            renderTags();
        }

        function updateTagsInput() {
            document.getElementById('tagsInput').value = JSON.stringify(tags);
        }

        // Photos Management
        let photosArray = [];
        document.getElementById('photos').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const label = document.querySelector('label[for="photos"]');
            label.textContent = files.length > 0 ? `${files.length} file dipilih` : 'Pilih foto...';
            
            const container = document.getElementById('photosPreview');
            container.innerHTML = '';
            
            if (files.length > 0) {
                container.style.display = 'grid';
                photosArray = files;
                
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const div = document.createElement('div');
                        div.className = 'photo-item';
                        div.setAttribute('data-photo-index', index);
                        div.innerHTML = `
                            <img src="${event.target.result}">
                            <button type="button" class="delete-photo-btn" onclick="deletePhoto(${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        container.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                container.style.display = 'none';
            }
        });

        function deletePhoto(index) {
            const photoItem = document.querySelector(`[data-photo-index="${index}"]`);
            if (photoItem) {
                photoItem.style.opacity = '0.5';
                setTimeout(() => photoItem.remove(), 300);
                
                photosArray.splice(index, 1);
                updatePhotosInput();
            }
        }

        function updatePhotosInput() {
            const dataTransfer = new DataTransfer();
            photosArray.forEach(file => dataTransfer.items.add(file));
            document.getElementById('photos').files = dataTransfer.files;
            
            const label = document.querySelector('label[for="photos"]');
            label.textContent = photosArray.length > 0 ? `${photosArray.length} file dipilih` : 'Pilih foto...';
            
            const container = document.getElementById('photosPreview');
            container.innerHTML = '';
            if (photosArray.length > 0) {
                container.style.display = 'grid';
                photosArray.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const div = document.createElement('div');
                        div.className = 'photo-item';
                        div.setAttribute('data-photo-index', index);
                        div.innerHTML = `
                            <img src="${event.target.result}">
                            <button type="button" class="delete-photo-btn" onclick="deletePhoto(${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        container.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                container.style.display = 'none';
            }
        }

        // Video Management
        let videoFile = null;
        document.getElementById('video').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const label = document.querySelector('label[for="video"]');
            const container = document.getElementById('videoPreview');
            
            if (file) {
                videoFile = file;
                label.textContent = file.name;
                container.style.display = 'block';
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    container.innerHTML = `
                        <div class="video-item">
                            <video controls>
                                <source src="${event.target.result}" type="${file.type}">
                            </video>
                            <button type="button" class="delete-video-btn" onclick="deleteVideo()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                deleteVideo();
            }
        });

        function deleteVideo() {
            const input = document.getElementById('video');
            input.value = '';
            videoFile = null;
            
            const label = document.querySelector('label[for="video"]');
            label.textContent = 'Pilih video...';
            
            const container = document.getElementById('videoPreview');
            container.style.opacity = '0.5';
            setTimeout(() => {
                container.style.display = 'none';
                container.innerHTML = '';
                container.style.opacity = '1';
            }, 300);
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Preview Button Handler
        document.getElementById('btnPreview').addEventListener('click', function() {
            const form = document.getElementById('productForm');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Basic Info
            const title = document.getElementById('title').value;
            const sku = document.getElementById('sku').value;
            const price = document.getElementById('price').value;
            const stock = document.getElementById('initial_stock').value;
            const description = document.getElementById('description').value;
            
            // Categories
            const categoriesCheckboxes = document.querySelectorAll('input[name="categories[]"]:checked');
            const selectedCategories = Array.from(categoriesCheckboxes).map(cb => {
                return cb.parentElement.querySelector('label').textContent.trim();
            });
            
            document.getElementById('preview_title').textContent = title || '-';
            document.getElementById('preview_sku').textContent = sku || '-';
            document.getElementById('preview_price').textContent = price ? formatCurrency(price) : '-';
            document.getElementById('preview_stock').textContent = stock ? `${stock} unit` : '-';
            document.getElementById('preview_description').textContent = description || '-';
            document.getElementById('preview_categories').innerHTML = selectedCategories.length > 0 
                ? selectedCategories.map(cat => `<span class="badge badge-info mr-1">${cat}</span>`).join('') 
                : '-';

            // Variants
            if (Object.keys(variants).length > 0) {
                let variantsHtml = '<div class="variant-preview">';
                Object.keys(variants).forEach(variantName => {
                    variantsHtml += `
                        <div class="mb-2">
                            <strong>${variantName}:</strong> 
                            ${variants[variantName].map(v => `<span class="badge badge-secondary mr-1">${v}</span>`).join('')}
                        </div>
                    `;
                });
                variantsHtml += '</div>';
                document.getElementById('preview_variants').innerHTML = variantsHtml;
            } else {
                document.getElementById('preview_variants').innerHTML = '<span class="text-muted">Tidak ada varian</span>';
            }

            // Tags
            if (tags.length > 0) {
                document.getElementById('preview_tags').innerHTML = tags.map(tag => 
                    `<span class="badge badge-primary mr-1">${tag}</span>`
                ).join('');
            } else {
                document.getElementById('preview_tags').innerHTML = '<span class="text-muted">Tidak ada tags</span>';
            }

            // Photos
            if (photosArray.length > 0) {
                const photosContainer = document.getElementById('preview_photos');
                photosContainer.innerHTML = '';
                photosArray.forEach(file => {
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
            if (videoFile) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const video = document.getElementById('preview_video');
                    video.src = e.target.result;
                    document.getElementById('preview_video_container').style.display = 'block';
                    document.getElementById('preview_video_empty').style.display = 'none';
                };
                reader.readAsDataURL(videoFile);
            } else {
                document.getElementById('preview_video_container').style.display = 'none';
                document.getElementById('preview_video_empty').style.display = 'block';
            }

            document.getElementById('formCard').style.display = 'none';
            document.getElementById('previewCard').style.display = 'block';
            document.getElementById('previewCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        document.getElementById('btnEdit').addEventListener('click', function() {
            document.getElementById('formCard').style.display = 'block';
            document.getElementById('previewCard').style.display = 'none';
            document.getElementById('formCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        document.getElementById('btnSubmit').addEventListener('click', function() {
            if (confirm('Yakin data produk sudah benar? Data akan disimpan ke database!')) {
                document.getElementById('productForm').submit();
            }
        });

        // Form validation
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const price = parseFloat(document.getElementById('price').value);
            const stock = parseInt(document.getElementById('initial_stock').value);
            
            if (price <= 0) {
                e.preventDefault();
                alert('Harga harus lebih dari 0');
                return false;
            }
            
            if (stock < 0) {
                e.preventDefault();
                alert('Stok tidak boleh negatif');
                return false;
            }
        });
    </script>
</x-admin-layout>