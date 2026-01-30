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
                <form action="{{ route('superadmin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                value="{{ old('title', $product->title) }}"
                                placeholder="Contoh: Kartu Nama Premium"
                                required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SKU & Tipe (2 Kolom) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sku" class="font-weight-bold">
                                        SKU <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                        class="form-control @error('sku') is-invalid @enderror" 
                                        id="sku" 
                                        name="sku" 
                                        value="{{ old('sku', $product->sku) }}"
                                        placeholder="Contoh: KN-001"
                                        required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipe" class="font-weight-bold">
                                        Tipe <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('tipe') is-invalid @enderror" 
                                        id="tipe" 
                                        name="tipe"
                                        required>
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="innerbox" {{ old('tipe', $product->tipe) === 'innerbox' ? 'selected' : '' }}>Inner Box</option>
                                        <option value="masterbox" {{ old('tipe', $product->tipe) === 'masterbox' ? 'selected' : '' }}>Master Box</option>
                                    </select>
                                    @error('tipe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group">
                            <label for="description" class="font-weight-bold">Deskripsi Lengkap</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" 
                                name="description" 
                                rows="4"
                                placeholder="Jelaskan detail produk, keunggulan, dan spesifikasi...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Spesifikasi Produk -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-cogs text-primary"></i> Spesifikasi Produk
                        </h5>

                        <!-- Ukuran & Jenis Bahan (2 Kolom) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ukuran" class="font-weight-bold">Ukuran</label>
                                    <input type="text" 
                                        class="form-control @error('ukuran') is-invalid @enderror" 
                                        id="ukuran" 
                                        name="ukuran" 
                                        value="{{ old('ukuran', $product->ukuran) }}"
                                        placeholder="Contoh: A4, 9x5 cm, 80x200 cm">
                                    @error('ukuran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_bahan" class="font-weight-bold">Jenis Bahan</label>
                                    <input type="text" 
                                        class="form-control @error('jenis_bahan') is-invalid @enderror" 
                                        id="jenis_bahan" 
                                        name="jenis_bahan" 
                                        value="{{ old('jenis_bahan', $product->jenis_bahan) }}"
                                        placeholder="Contoh: Art Paper 260 gsm, Vinyl">
                                    @error('jenis_bahan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Cetak & Finishing (2 Kolom) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cetak" class="font-weight-bold">Cetak</label>
                                    <input type="text" 
                                        class="form-control @error('cetak') is-invalid @enderror" 
                                        id="cetak" 
                                        name="cetak" 
                                        value="{{ old('cetak', $product->cetak) }}"
                                        placeholder="Contoh: Digital Print, Offset">
                                    @error('cetak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="finishing" class="font-weight-bold">Finishing</label>
                                    <input type="text" 
                                        class="form-control @error('finishing') is-invalid @enderror" 
                                        id="finishing" 
                                        name="finishing" 
                                        value="{{ old('finishing', $product->finishing) }}"
                                        placeholder="Contoh: Laminating Doff, Glossy">
                                    @error('finishing')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Harga & Kategori -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-tags text-primary"></i> Harga & Kategori
                        </h5>

                        <!-- Harga Normal & Diskon (2 Kolom) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="font-weight-bold">
                                        Harga Normal <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" 
                                            class="form-control @error('price') is-invalid @enderror" 
                                            id="price" 
                                            name="price" 
                                            value="{{ old('price', $product->price) }}"
                                            placeholder="50000"
                                            min="0"
                                            step="0.01"
                                            required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_price" class="font-weight-bold">Harga Diskon (Opsional)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" 
                                            class="form-control @error('discount_price') is-invalid @enderror" 
                                            id="discount_price" 
                                            name="discount_price" 
                                            value="{{ old('discount_price', $product->discount_price) }}"
                                            placeholder="45000"
                                            min="0"
                                            step="0.01">
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Kosongkan jika tidak ada diskon
                                    </small>
                                    @error('discount_price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Kategori -->
                        <div class="form-group">
                            <label class="font-weight-bold">Kategori</label>
                            <div class="border rounded p-3" style="max-height: 250px; overflow-y: auto; background-color: #f8f9fc;">
                                @if($categories->isEmpty())
                                    <p class="text-muted mb-0"><i class="fas fa-info-circle"></i> Belum ada kategori tersedia</p>
                                @else
                                    @foreach($categories as $category)
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" 
                                                class="custom-control-input" 
                                                id="category{{ $category->id }}" 
                                                name="category_ids[]" 
                                                value="{{ $category->id }}"
                                                {{ in_array($category->id, old('category_ids', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="category{{ $category->id }}">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Pilih satu atau lebih kategori
                            </small>
                            @error('category_ids')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div class="form-group">
                            <label class="font-weight-bold">Tags (Opsional)</label>
                            <div class="d-flex align-items-center mb-2">
                                <input type="text" 
                                    class="form-control mr-2" 
                                    id="tag_input" 
                                    placeholder="Ketik tag dan tekan Enter atau klik +">
                                <button type="button" class="btn btn-success" onclick="addTag()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="tags_container" class="d-flex flex-wrap" style="gap: 8px;">
                                @if(old('tags'))
                                    @foreach(old('tags') as $tag)
                                        <span class="badge badge-primary badge-pill py-2 px-3" style="font-size: 0.9rem;">
                                            {{ $tag }}
                                            <button type="button" class="btn btn-sm p-0 ml-2 text-white" onclick="removeTag(this)" style="border: none; background: none; font-size: 1rem; line-height: 1;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <input type="hidden" name="tags[]" value="{{ $tag }}">
                                        </span>
                                    @endforeach
                                @elseif(is_array($product->tags) && count($product->tags) > 0)
                                    @foreach($product->tags as $tag)
                                        <span class="badge badge-primary badge-pill py-2 px-3" style="font-size: 0.9rem;">
                                            {{ $tag }}
                                            <button type="button" class="btn btn-sm p-0 ml-2 text-white" onclick="removeTag(this)" style="border: none; background: none; font-size: 1rem; line-height: 1;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <input type="hidden" name="tags[]" value="{{ $tag }}">
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Ketik tag dan tekan Enter atau klik tombol + untuk menambahkan
                            </small>
                            @error('tags')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Varian Produk -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-palette text-primary"></i> Varian Produk (Warna & Ukuran)
                        </h5>

                        <div id="variants-container">
                            @foreach($product->variants->whereNull('parent_id') as $colorIndex => $color)
                            <div class="variant-item card mb-3 shadow-sm" data-variant-index="{{ $colorIndex }}">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <strong><i class="fas fa-fill-drip"></i> Warna #{{ $colorIndex + 1 }}</strong>
                                    <button type="button" class="btn btn-sm btn-danger delete-variant-btn" onclick="deleteVariant(this)">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="colors[{{ $colorIndex }}][id]" value="{{ $color->id }}">
                                    
                                    <!-- Nama Warna & Foto -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Nama Warna <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                    class="form-control" 
                                                    name="colors[{{ $colorIndex }}][name]" 
                                                    value="{{ old('colors.'.$colorIndex.'.name', $color->name) }}"
                                                    placeholder="Contoh: Merah, Hitam"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Foto Warna</label>
                                                
                                                @if($color->photo)
                                                <div id="existingColorPhoto{{ $colorIndex }}" class="mb-2">
                                                    <img src="{{ asset('storage/' . $color->photo) }}" 
                                                         alt="{{ $color->name }}" 
                                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 2px solid #e3e6f0;">
                                                    <button type="button" class="btn btn-xs btn-danger ml-1" onclick="deleteExistingColorPhoto({{ $colorIndex }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <input type="hidden" name="colors[{{ $colorIndex }}][existing_photo]" value="{{ $color->photo }}" id="colorExistingPhoto{{ $colorIndex }}">
                                                @endif

                                                <div class="custom-file">
                                                    <input type="file" 
                                                        class="custom-file-input color-photo-input" 
                                                        name="colors[{{ $colorIndex }}][photo]"
                                                        accept="image/*"
                                                        data-color-index="{{ $colorIndex }}">
                                                    <label class="custom-file-label">{{ $color->photo ? 'Ubah foto...' : 'Pilih foto...' }}</label>
                                                </div>
                                                <div id="newColorPhotoPreview{{ $colorIndex }}" class="mt-2" style="display: none;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stok Pusat (jika tidak ada size) -->
                                    @if($color->children->isEmpty())
                                    <div class="form-group stock-input-group">
                                        <label class="font-weight-bold">Stok Pusat</label>
                                        <input type="number" 
                                            class="form-control" 
                                            name="colors[{{ $colorIndex }}][stock_pusat]" 
                                            value="{{ old('colors.'.$colorIndex.'.stock_pusat', $color->stock_pusat) }}"
                                            min="0">
                                    </div>
                                    @endif

                                    <!-- Ukuran/Size -->
                                    <div class="sizes-container mt-3" data-color-index="{{ $colorIndex }}">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="font-weight-bold mb-0"><i class="fas fa-ruler"></i> Ukuran</label>
                                            <button type="button" class="btn btn-sm btn-success add-size-btn" onclick="addSize({{ $colorIndex }})">
                                                <i class="fas fa-plus"></i> Tambah Ukuran
                                            </button>
                                        </div>

                                        @foreach($color->children as $sizeIndex => $size)
                                        <div class="size-item mb-2 p-3 border rounded bg-light" data-size-index="{{ $sizeIndex }}">
                                            <input type="hidden" name="colors[{{ $colorIndex }}][sizes][{{ $sizeIndex }}][id]" value="{{ $size->id }}">
                                            
                                            <div class="row align-items-end">
                                                <div class="col-md-3">
                                                    <label class="small">Nama Ukuran <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                        class="form-control form-control-sm" 
                                                        name="colors[{{ $colorIndex }}][sizes][{{ $sizeIndex }}][name]" 
                                                        value="{{ old('colors.'.$colorIndex.'.sizes.'.$sizeIndex.'.name', $size->name) }}"
                                                        placeholder="S, M, L"
                                                        required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="small">Stok Pusat</label>
                                                    <input type="number" 
                                                        class="form-control form-control-sm" 
                                                        name="colors[{{ $colorIndex }}][sizes][{{ $sizeIndex }}][stock_pusat]" 
                                                        value="{{ old('colors.'.$colorIndex.'.sizes.'.$sizeIndex.'.stock_pusat', $size->stock_pusat) }}"
                                                        min="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="small">Harga</label>
                                                    <input type="number" 
                                                        class="form-control form-control-sm" 
                                                        name="colors[{{ $colorIndex }}][sizes][{{ $sizeIndex }}][price]" 
                                                        value="{{ old('colors.'.$colorIndex.'.sizes.'.$sizeIndex.'.price', $size->price) }}"
                                                        min="0"
                                                        step="0.01">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small">Foto Size</label>
                                                    
                                                    @if($size->photo)
                                                    <div id="existingSizePhoto{{ $colorIndex }}_{{ $sizeIndex }}" class="mb-1">
                                                        <img src="{{ asset('storage/' . $size->photo) }}" 
                                                             alt="{{ $size->name }}" 
                                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 2px solid #e3e6f0;">
                                                        <button type="button" class="btn btn-xs btn-danger ml-1" onclick="deleteExistingSizePhoto({{ $colorIndex }}, {{ $sizeIndex }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="colors[{{ $colorIndex }}][sizes][{{ $sizeIndex }}][existing_photo]" value="{{ $size->photo }}" id="sizeExistingPhoto{{ $colorIndex }}_{{ $sizeIndex }}">
                                                    @endif

                                                    <input type="file" 
                                                        class="form-control-file form-control-sm" 
                                                        name="colors[{{ $colorIndex }}][sizes][{{ $sizeIndex }}][photo]"
                                                        accept="image/*">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-sm btn-danger btn-block" onclick="deleteSize(this, {{ $colorIndex }}, {{ $sizeIndex }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-primary" onclick="addVariant()">
                            <i class="fas fa-plus"></i> Tambah Warna Baru
                        </button>
                    </div>

                    <hr class="my-4">

                    <!-- Media Produk -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-images text-primary"></i> Media Produk
                        </h5>

                        <!-- Foto Produk -->
                        <div class="form-group">
                            <label for="photos" class="font-weight-bold">Foto Produk (Multi Upload)</label>
                            
                            <!-- Preview foto lama -->
                            @if($product->photos && count($product->photos) > 0)
                            <div id="existingPhotosContainer" class="row mb-3">
                                @foreach($product->photos as $index => $photo)
                                <div class="col-md-3 mb-2" id="existingPhoto{{ $index }}">
                                    <div class="photo-item">
                                        <img src="{{ asset('storage/' . $photo) }}" alt="Foto {{ $index + 1 }}">
                                        <button type="button" class="delete-photo-btn" onclick="deleteExistingProductPhoto({{ $index }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <input type="hidden" name="existing_photos[]" value="{{ $index }}" id="keepPhoto{{ $index }}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <div class="custom-file">
                                <input type="file" 
                                    class="custom-file-input @error('photos') is-invalid @enderror" 
                                    id="photos" 
                                    name="photos[]"
                                    accept="image/*"
                                    multiple>
                                <label class="custom-file-label" for="photos">Pilih foto baru (opsional)...</label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Format: JPEG, PNG, JPG, GIF. Max: 2MB per file. Bisa pilih multiple.
                            </small>
                            @error('photos')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <!-- Preview foto baru -->
                            <div id="newPhotosPreview" class="row mt-3"></div>
                        </div>

                        <!-- Video Produk -->
                        <div class="form-group">
                            <label for="video" class="font-weight-bold">Video Produk (Opsional)</label>
                            
                            @if($product->video)
                            <div id="existingVideoContainer" class="mb-3">
                                <video controls style="max-width: 400px; width: 100%; border-radius: 8px;">
                                    <source src="{{ asset('storage/' . $product->video) }}" type="video/mp4">
                                </video>
                                <div class="mt-2">
                                    <label>
                                        <input type="checkbox" name="remove_video" value="1"> Hapus video ini
                                    </label>
                                </div>
                            </div>
                            @endif

                            <div class="custom-file">
                                <input type="file" 
                                    class="custom-file-input @error('video') is-invalid @enderror" 
                                    id="video" 
                                    name="video"
                                    accept="video/*">
                                <label class="custom-file-label" for="video">{{ $product->video ? 'Ubah video...' : 'Pilih video...' }}</label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Format: MP4, AVI, MOV. Max: 20MB
                            </small>
                            @error('video')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Status Produk -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-cog text-primary"></i> Pengaturan
                        </h5>

                        <div class="form-group">
                            <label for="is_active" class="font-weight-bold">Status</label>
                            <select class="form-control @error('is_active') is-invalid @enderror" 
                                id="is_active" 
                                name="is_active"
                                required>
                                <option value="1" {{ old('is_active', $product->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $product->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Produk tidak aktif tidak akan tampil di toko
                            </small>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
        /* Form improvements */
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

        .form-group label {
            font-size: 0.95rem;
        }

        .invalid-feedback {
            font-size: 0.85rem;
        }

        /* Photo Container */
        .photo-container {
            max-width: 300px;
        }

        .photo-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .photo-item img {
            width: 100%;
            height: auto;
            display: block;
        }

        .delete-photo-btn {
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

        .delete-photo-btn:hover {
            background: #c9302c;
            transform: scale(1.1);
        }

        /* Variant Card */
        .variant-item {
            border: 1px solid #e3e6f0;
        }

        .size-item {
            background-color: #f8f9fc !important;
        }

        /* Select2 */
        .select2-container--default .select2-selection--multiple {
            border-color: #d1d3e2;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #4e73df;
        }

        /* Custom Checkbox */
        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .custom-control-label {
            cursor: pointer;
        }

        /* Tags Badge */
        #tags_container .badge {
            display: inline-flex;
            align-items: center;
        }

        #tags_container .badge button:hover {
            opacity: 0.8;
        }
    </style>

    <script>
        let variantIndex = {{ $product->variants->whereNull('parent_id')->count() }};
        let sizeIndexes = {};

        @foreach($product->variants->whereNull('parent_id') as $colorIndex => $color)
            sizeIndexes[{{ $colorIndex }}] = {{ $color->children->count() }};
        @endforeach

        // Initialize
        $(document).ready(function() {
            // Update custom file labels
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });

            // Tag input - press Enter to add
            $('#tag_input').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    addTag();
                }
            });
        });

        // Add Tag
        function addTag() {
            const input = $('#tag_input');
            const tag = input.val().trim();
            
            if (tag === '') {
                return;
            }
            
            // Check if tag already exists
            const existingTags = [];
            $('#tags_container input[name="tags[]"]').each(function() {
                existingTags.push($(this).val().toLowerCase());
            });
            
            if (existingTags.includes(tag.toLowerCase())) {
                alert('Tag sudah ada!');
                input.val('');
                return;
            }
            
            // Add tag badge
            const badge = `
                <span class="badge badge-primary badge-pill py-2 px-3" style="font-size: 0.9rem;">
                    ${tag}
                    <button type="button" class="btn btn-sm p-0 ml-2 text-white" onclick="removeTag(this)" style="border: none; background: none; font-size: 1rem; line-height: 1;">
                        <i class="fas fa-times"></i>
                    </button>
                    <input type="hidden" name="tags[]" value="${tag}">
                </span>
            `;
            
            $('#tags_container').append(badge);
            input.val('');
            input.focus();
        }

        // Remove Tag
        function removeTag(btn) {
            $(btn).closest('.badge').fadeOut(200, function() {
                $(this).remove();
            });
        }

        // Add Variant (Color)
        function addVariant() {
            sizeIndexes[variantIndex] = 0;
            
            const html = `
                <div class="variant-item card mb-3 shadow-sm" data-variant-index="${variantIndex}">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <strong><i class="fas fa-fill-drip"></i> Warna #${variantIndex + 1}</strong>
                        <button type="button" class="btn btn-sm btn-danger delete-variant-btn" onclick="deleteVariant(this)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nama Warna <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="colors[${variantIndex}][name]" placeholder="Contoh: Merah, Hitam" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Foto Warna</label>
                                    
                                    @if($color->photo)
                                    <div id="existingColorPhoto${variantIndex}" class="mb-2">
                                        <img src="" alt="Preview" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 2px solid #e3e6f0; display: none;">
                                        <button type="button" class="btn btn-xs btn-danger ml-1" onclick="deleteExistingColorPhoto(${variantIndex})" style="display: none;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    @endif

                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input color-photo-input" name="colors[${variantIndex}][photo]" accept="image/*" data-color-index="${variantIndex}">
                                        <label class="custom-file-label">Pilih foto...</label>
                                    </div>
                                    <div id="newColorPhotoPreview${variantIndex}" class="mt-2" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group stock-input-group">
                            <label class="font-weight-bold">Stok Pusat</label>
                            <input type="number" class="form-control" name="colors[${variantIndex}][stock_pusat]" value="0" min="0">
                        </div>

                        <div class="sizes-container mt-3" data-color-index="${variantIndex}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="font-weight-bold mb-0"><i class="fas fa-ruler"></i> Ukuran</label>
                                <button type="button" class="btn btn-sm btn-success add-size-btn" onclick="addSize(${variantIndex})">
                                    <i class="fas fa-plus"></i> Tambah Ukuran
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#variants-container').append(html);
            variantIndex++;
            
            // Re-bind file input
            bindColorPhotoInputs();
        }

        // Add Size
        function addSize(colorIndex) {
            if (!sizeIndexes[colorIndex]) {
                sizeIndexes[colorIndex] = 0;
            }
            
            const sizeIndex = sizeIndexes[colorIndex];
            const container = $(`.sizes-container[data-color-index="${colorIndex}"]`);
            
            // Hide stock input when first size is added
            const stockInput = container.closest('.card-body').find('.stock-input-group');
            if (sizeIndex === 0) {
                stockInput.hide();
            }
            
            const html = `
                <div class="size-item mb-2 p-3 border rounded bg-light" data-size-index="${sizeIndex}">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="small">Nama Ukuran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="colors[${colorIndex}][sizes][${sizeIndex}][name]" placeholder="S, M, L" required>
                        </div>
                        <div class="col-md-2">
                            <label class="small">Stok Pusat</label>
                            <input type="number" class="form-control form-control-sm" name="colors[${colorIndex}][sizes][${sizeIndex}][stock_pusat]" value="0" min="0">
                        </div>
                        <div class="col-md-2">
                            <label class="small">Harga</label>
                            <input type="number" class="form-control form-control-sm" name="colors[${colorIndex}][sizes][${sizeIndex}][price]" value="0" min="0" step="0.01">
                        </div>
                        <div class="col-md-3">
                            <label class="small">Foto Size</label>
                            <input type="file" class="form-control-file form-control-sm" name="colors[${colorIndex}][sizes][${sizeIndex}][photo]" accept="image/*">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-sm btn-danger btn-block" onclick="deleteSize(this, ${colorIndex}, ${sizeIndex})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            container.append(html);
            sizeIndexes[colorIndex]++;
        }

        // Delete Variant
        function deleteVariant(btn) {
            if (confirm('Yakin ingin menghapus warna ini beserta semua ukurannya?')) {
                $(btn).closest('.variant-item').fadeOut(300, function() {
                    $(this).remove();
                });
            }
        }

        // Delete Size
        function deleteSize(btn, colorIndex, sizeIndex) {
            const container = $(btn).closest('.sizes-container');
            $(btn).closest('.size-item').fadeOut(300, function() {
                $(this).remove();
                
                // Show stock input if no sizes left
                if (container.find('.size-item').length === 0) {
                    container.closest('.card-body').find('.stock-input-group').show();
                }
            });
        }

        // Delete Existing Color Photo
        function deleteExistingColorPhoto(colorIndex) {
            const photoContainer = $(`#existingColorPhoto${colorIndex}`);
            if (photoContainer.length) {
                photoContainer.fadeOut(300, function() {
                    $(this).remove();
                });
                $(`#colorExistingPhoto${colorIndex}`).remove();
            }
        }

        // Delete Existing Size Photo
        function deleteExistingSizePhoto(colorIndex, sizeIndex) {
            const photoContainer = $(`#existingSizePhoto${colorIndex}_${sizeIndex}`);
            if (photoContainer.length) {
                photoContainer.fadeOut(300, function() {
                    $(this).remove();
                });
                $(`#sizeExistingPhoto${colorIndex}_${sizeIndex}`).remove();
            }
        }

        // Delete Existing Product Photo
        function deleteExistingProductPhoto(index) {
            const photoContainer = $(`#existingPhoto${index}`);
            if (photoContainer.length) {
                photoContainer.fadeOut(300, function() {
                    $(this).remove();
                });
                $(`#keepPhoto${index}`).remove();
            }
        }

        // Handle Color Photo Preview
        function bindColorPhotoInputs() {
            $('.color-photo-input').off('change').on('change', function(e) {
                const file = e.target.files[0];
                const colorIndex = $(this).data('color-index');
                const previewContainer = $(`#newColorPhotoPreview${colorIndex}`);
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewContainer.html(`
                            <img src="${event.target.result}" 
                                 alt="Preview" 
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 2px solid #e3e6f0;">
                            <button type="button" class="btn btn-xs btn-danger ml-1" onclick="deleteNewColorPhoto(${colorIndex})">
                                <i class="fas fa-times"></i>
                            </button>
                        `).show();
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Delete New Color Photo
        function deleteNewColorPhoto(colorIndex) {
            const input = $(`.color-photo-input[data-color-index="${colorIndex}"]`);
            input.val('');
            input.siblings('.custom-file-label').html('Pilih foto...');
            
            const previewContainer = $(`#newColorPhotoPreview${colorIndex}`);
            previewContainer.fadeOut(300, function() {
                $(this).html('');
            });
        }

        // Handle Multiple Product Photos Preview
        $('#photos').on('change', function(e) {
            const files = e.target.files;
            const previewContainer = $('#newPhotosPreview');
            previewContainer.html('');
            
            if (files.length > 0) {
                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const html = `
                            <div class="col-md-3 mb-2">
                                <div class="photo-item">
                                    <img src="${event.target.result}" alt="Preview ${index + 1}">
                                </div>
                            </div>
                        `;
                        previewContainer.append(html);
                    };
                    reader.readAsDataURL(file);
                });
                
                $(this).siblings('.custom-file-label').html(`${files.length} file dipilih`);
            }
        });

        // Video file label
        $('#video').on('change', function() {
            const fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').html(fileName || 'Pilih video...');
        });

        // Initialize on page load
        bindColorPhotoInputs();
    </script>
</x-admin-layout>