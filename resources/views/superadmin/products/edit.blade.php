<x-admin-layout title="Edit Produk">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-box text-primary"></i> Edit Produk
            </h1>
            <a href="{{ route('superadmin.products.index') }}" class="btn btn-secondary btn-icon-split shadow-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-edit"></i> Form Edit Produk: {{ $product->title }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('superadmin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Dasar -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-info-circle text-primary"></i> Informasi Dasar
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title" class="font-weight-bold">Nama Produk <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $product->title) }}" placeholder="Contoh: Sepatu Sneakers Premium" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sku" class="font-weight-bold">SKU <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="PRD-001" required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="font-weight-bold">Deskripsi Produk</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Jelaskan detail produk...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="font-weight-bold">Harga (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="0.01" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="initial_stock" class="font-weight-bold">Stok Awal <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('initial_stock') is-invalid @enderror" id="initial_stock" name="initial_stock" value="{{ old('initial_stock', $product->initial_stock) }}" min="0" required>
                                    @if($product->total_distributed_stock > 0)
                                        <small class="form-text text-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Sudah dialokasikan: <strong>{{ $product->total_distributed_stock }}</strong> unit
                                        </small>
                                    @endif
                                    @error('initial_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Kategori -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-folder text-primary"></i> Kategori
                        </h5>
                        
                        <div class="form-group">
                            <div class="category-wrapper">
                                @forelse($categories as $category)
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="category_{{ $category->id }}" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="category_{{ $category->id }}">{{ $category->name }}</label>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">Belum ada kategori. <a href="{{ route('superadmin.categories.create') }}" target="_blank">Buat kategori</a></p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Varian Produk - UX Friendly -->
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
                    </div>

                    <hr class="my-4">

                    <!-- Tags - UX Friendly -->
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
                            <label for="photos" class="font-weight-bold">Foto Produk (Max 5)</label>
                            
                            <!-- Existing photos with realtime delete -->
                            <div id="existingPhotosContainer" class="photo-grid mb-3">
                                @if($product->photos && count($product->photos) > 0)
                                    @foreach($product->photos as $index => $photo)
                                        <div class="photo-item" data-photo-index="{{ $index }}">
                                            <img src="{{ asset('storage/' . $photo) }}" alt="Foto {{ $index + 1 }}">
                                            <button type="button" class="delete-photo-btn" onclick="deleteExistingPhoto({{ $index }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <input type="hidden" name="keep_photos[]" value="{{ $index }}" id="keep_photo_{{ $index }}">
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('photos.*') is-invalid @enderror" id="photos" name="photos[]" accept="image/*" multiple>
                                <label class="custom-file-label" for="photos">Tambah foto baru...</label>
                            </div>
                            <small class="form-text text-muted">JPG, PNG, WebP. Max: 2MB per file</small>
                            @error('photos.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <!-- Preview new photos -->
                            <div id="newPhotosPreview" class="photo-grid mt-3" style="display: none;"></div>
                        </div>

                        <!-- Video Produk -->
                        <div class="form-group">
                            <label for="video" class="font-weight-bold">Video Produk (Opsional)</label>
                            
                            <!-- Existing video with realtime delete -->
                            @if($product->video)
                                <div id="existingVideoContainer" class="video-container mb-3">
                                    <div class="video-item">
                                        <video controls>
                                            <source src="{{ asset('storage/' . $product->video) }}" type="video/mp4">
                                        </video>
                                        <button type="button" class="delete-video-btn" onclick="deleteExistingVideo()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <input type="hidden" name="keep_video" value="1" id="keep_video">
                                    </div>
                                </div>
                            @endif

                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('video') is-invalid @enderror" id="video" name="video" accept="video/*">
                                <label class="custom-file-label" for="video">Upload video baru...</label>
                            </div>
                            <small class="form-text text-muted">MP4, MOV, AVI. Max: 10MB</small>
                            @error('video')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <!-- Preview new video -->
                            <div id="newVideoPreview" class="video-container mt-3" style="display: none;"></div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Buttons -->
                    <div class="d-flex" style="gap: 10px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Perbarui Produk
                        </button>
                        <a href="{{ route('superadmin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .category-wrapper {
            max-height: 200px;
            overflow-y: auto;
            padding: 15px;
            background: #f8f9fc;
            border-radius: 8px;
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

        /* Form improvements */
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover:not(:disabled) {
            transform: translateY(-1px);
        }
    </style>

    <script>
        // Variants Management
        let variants = {};
        
        // Load existing variants
        const existingVariants = {!! json_encode($product->variants ?? []) !!};
        if (existingVariants && Object.keys(existingVariants).length > 0) {
            variants = existingVariants;
            renderVariants();
        }

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
        
        // Load existing tags
        const existingTags = {!! json_encode($product->tags ?? []) !!};
        if (existingTags && existingTags.length > 0) {
            tags = existingTags;
            renderTags();
        }

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

        // Photo Management - Realtime Delete
        function deleteExistingPhoto(index) {
            const photoItem = document.querySelector(`[data-photo-index="${index}"]`);
            if (photoItem) {
                photoItem.style.opacity = '0.5';
                photoItem.style.transition = 'all 0.3s';
                setTimeout(() => {
                    photoItem.remove();
                }, 300);
                
                // Remove from keep_photos
                const keepInput = document.getElementById(`keep_photo_${index}`);
                if (keepInput) {
                    keepInput.remove();
                }
            }
        }

        // Video Management - Realtime Delete
        function deleteExistingVideo() {
            const videoContainer = document.getElementById('existingVideoContainer');
            if (videoContainer) {
                videoContainer.style.opacity = '0.5';
                videoContainer.style.transition = 'all 0.3s';
                setTimeout(() => {
                    videoContainer.remove();
                }, 300);
                
                // Remove keep_video input
                const keepInput = document.getElementById('keep_video');
                if (keepInput) {
                    keepInput.remove();
                }
            }
        }

        // New Photos Preview with Delete
        let newPhotosArray = [];
        document.getElementById('photos').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const label = document.querySelector('label[for="photos"]');
            label.textContent = files.length > 0 ? `${files.length} file dipilih` : 'Tambah foto baru...';
            
            const container = document.getElementById('newPhotosPreview');
            container.innerHTML = '';
            
            if (files.length > 0) {
                container.style.display = 'grid';
                newPhotosArray = files;
                
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const div = document.createElement('div');
                        div.className = 'photo-item';
                        div.setAttribute('data-new-photo-index', index);
                        div.innerHTML = `
                            <img src="${event.target.result}">
                            <button type="button" class="delete-photo-btn" onclick="deleteNewPhoto(${index})">
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

        function deleteNewPhoto(index) {
            const photoItem = document.querySelector(`[data-new-photo-index="${index}"]`);
            if (photoItem) {
                photoItem.style.opacity = '0.5';
                photoItem.style.transition = 'all 0.3s';
                setTimeout(() => {
                    photoItem.remove();
                }, 300);
                
                // Remove from array and update file input
                newPhotosArray.splice(index, 1);
                updatePhotosInput();
            }
        }

        function updatePhotosInput() {
            const dataTransfer = new DataTransfer();
            newPhotosArray.forEach(file => dataTransfer.items.add(file));
            document.getElementById('photos').files = dataTransfer.files;
            
            // Update label
            const label = document.querySelector('label[for="photos"]');
            label.textContent = newPhotosArray.length > 0 ? `${newPhotosArray.length} file dipilih` : 'Tambah foto baru...';
            
            // Re-render preview
            const container = document.getElementById('newPhotosPreview');
            container.innerHTML = '';
            if (newPhotosArray.length > 0) {
                container.style.display = 'grid';
                newPhotosArray.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const div = document.createElement('div');
                        div.className = 'photo-item';
                        div.setAttribute('data-new-photo-index', index);
                        div.innerHTML = `
                            <img src="${event.target.result}">
                            <button type="button" class="delete-photo-btn" onclick="deleteNewPhoto(${index})">
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

        // New Video Preview with Delete
        document.getElementById('video').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const label = document.querySelector('label[for="video"]');
            const container = document.getElementById('newVideoPreview');
            
            if (file) {
                label.textContent = file.name;
                container.style.display = 'block';
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    container.innerHTML = `
                        <div class="video-item">
                            <video controls>
                                <source src="${event.target.result}" type="${file.type}">
                            </video>
                            <button type="button" class="delete-video-btn" onclick="deleteNewVideo()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                label.textContent = 'Upload video baru...';
                container.style.display = 'none';
                container.innerHTML = '';
            }
        });

        function deleteNewVideo() {
            const videoInput = document.getElementById('video');
            videoInput.value = '';
            
            const label = document.querySelector('label[for="video"]');
            label.textContent = 'Upload video baru...';
            
            const container = document.getElementById('newVideoPreview');
            container.style.opacity = '0.5';
            container.style.transition = 'all 0.3s';
            setTimeout(() => {
                container.style.display = 'none';
                container.innerHTML = '';
                container.style.opacity = '1';
            }, 300);
        }

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