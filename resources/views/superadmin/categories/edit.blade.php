<x-admin-layout title="Edit Kategori">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit text-primary"></i> Edit Kategori: {{ $category->name }}
            </h1>
            <a href="{{ route('superadmin.categories.index') }}" class="btn btn-secondary btn-icon-split shadow-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Form Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 bg-warning">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-edit"></i> Form Edit Kategori
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:</strong>
                                <ul class="mb-0 mt-2 pl-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <small>
                                <strong>Slug:</strong> <code>{{ $category->slug }}</code> |
                                <strong>Produk:</strong> {{ $category->products()->count() }}
                            </small>
                        </div>

                        <form action="{{ route('superadmin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Foto Kategori -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold text-dark mb-3">
                                    <i class="fas fa-image text-primary"></i> Foto Kategori
                                </h5>

                                <div class="form-group">
                                    <label for="photo" class="font-weight-bold">Foto Kategori</label>
                                    
                                    <!-- Preview foto lama dengan tombol hapus realtime -->
                                    @if($category->photo)
                                        <div id="existingPhotoContainer" class="photo-container mb-3">
                                            <div class="photo-item">
                                                <img src="{{ asset('storage/' . $category->photo) }}" 
                                                     alt="{{ $category->name }}">
                                                <button type="button" class="delete-photo-btn" onclick="deleteExistingPhoto()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <input type="hidden" name="keep_photo" value="1" id="keep_photo">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="custom-file">
                                        <input type="file" 
                                            class="custom-file-input @error('photo') is-invalid @enderror" 
                                            id="photo" 
                                            name="photo"
                                            accept="image/jpeg,image/png,image/jpg,image/gif">
                                        <label class="custom-file-label" for="photo">{{ $category->photo ? 'Ganti foto...' : 'Pilih foto...' }}</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Format: JPG, JPEG, PNG, GIF. Maksimal 2MB
                                    </small>
                                    @error('photo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    
                                    <!-- Preview foto baru -->
                                    <div id="newPhotoPreview" class="photo-container mt-3" style="display: none;"></div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Informasi Kategori -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold text-dark mb-3">
                                    <i class="fas fa-info-circle text-primary"></i> Informasi Kategori
                                </h5>

                                <!-- Nama Kategori -->
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">
                                        Nama Kategori <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                        class="form-control @error('name') is-invalid @enderror" 
                                        id="name"
                                        name="name" 
                                        value="{{ old('name', $category->name) }}"
                                        placeholder="Contoh: Kardus Besar"
                                        required
                                        autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="form-group">
                                    <label for="description" class="font-weight-bold">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                        id="description"
                                        name="description" 
                                        rows="4"
                                        placeholder="Deskripsi kategori (opsional)">{{ old('description', $category->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status Aktif -->
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-toggle-on text-primary"></i> Status
                                    </label>
                                    <select class="form-control @error('is_active') is-invalid @enderror" 
                                        id="is_active" 
                                        name="is_active">
                                        <option value="1" {{ old('is_active', $category->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('is_active', $category->is_active) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Kategori aktif akan ditampilkan di sistem
                                    </small>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Buttons -->
                            <div class="d-flex" style="gap: 10px;">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Update Kategori
                                </button>
                                <a href="{{ route('superadmin.categories.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delete Card -->
                <div class="card shadow-sm mb-4 border-left-danger">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-danger mb-2">
                            <i class="fas fa-trash"></i> Hapus Kategori
                        </h6>
                        <p class="small mb-3">
                            @if($category->products()->count() > 0)
                                Kategori ini digunakan oleh {{ $category->products()->count() }} produk.
                            @else
                                Kategori ini tidak digunakan produk manapun.
                            @endif
                        </p>
                        <form action="{{ route('superadmin.categories.destroy', $category) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin hapus kategori ini?\n\nProduk yang terhubung tidak akan terhapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Hapus Kategori
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4 border-left-info">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-info mb-3">
                            <i class="fas fa-info-circle"></i> Informasi
                        </h6>
                        <ul class="pl-3 mb-0">
                            <li class="mb-2">Slug akan diupdate otomatis jika nama berubah</li>
                            <li class="mb-2">Foto lama akan diganti jika upload foto baru</li>
                            <li class="mb-2">Klik tombol X pada foto untuk menghapus foto</li>
                            <li class="mb-2">Kategori tidak bisa dihapus jika masih digunakan produk</li>
                            <li class="mb-0">Perubahan status aktif akan mempengaruhi tampilan kategori</li>
                        </ul>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="card shadow-sm mb-4 border-left-primary">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-chart-bar"></i> Statistik
                        </h6>
                        <div class="mb-2">
                            <small class="text-muted">Jumlah Produk</small>
                            <h4 class="mb-0">{{ $category->products()->count() }}</h4>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Status</small>
                            <div>
                                @if($category->is_active)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i> Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
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
            background-color: #f6c23e;
            color: #333;
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover:not(:disabled) {
            transform: translateY(-1px);
        }

        /* Photo Container */
        .photo-container {
            max-width: 200px;
        }

        .photo-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 200px;
            height: 200px;
        }

        .photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        /* Info Card */
        .card-body ul li {
            line-height: 1.8;
        }
    </style>

    <script>
        // Realtime Delete Existing Photo
        function deleteExistingPhoto() {
            const photoContainer = document.getElementById('existingPhotoContainer');
            if (photoContainer) {
                photoContainer.style.opacity = '0.5';
                photoContainer.style.transition = 'all 0.3s';
                setTimeout(() => {
                    photoContainer.remove();
                }, 300);
                
                // Remove keep_photo input (signals backend to delete)
                const keepInput = document.getElementById('keep_photo');
                if (keepInput) {
                    keepInput.remove();
                }
            }
        }

        // Update label & preview new photo with delete
        const photoInput = document.getElementById('photo');
        const photoLabel = document.querySelector('.custom-file-label');
        const newPhotoContainer = document.getElementById('newPhotoPreview');

        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                photoLabel.textContent = file.name;
                newPhotoContainer.style.display = 'block';

                const reader = new FileReader();
                reader.onload = function(event) {
                    newPhotoContainer.innerHTML = `
                        <div class="photo-item">
                            <img src="${event.target.result}" alt="Preview">
                            <button type="button" class="delete-photo-btn" onclick="deleteNewPhoto()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                resetNewPhoto();
            }
        });

        // Delete New Photo
        function deleteNewPhoto() {
            photoInput.value = '';
            newPhotoContainer.style.opacity = '0.5';
            newPhotoContainer.style.transition = 'all 0.3s';
            
            setTimeout(() => {
                resetNewPhoto();
            }, 300);
        }

        function resetNewPhoto() {
            photoLabel.textContent = '{{ $category->photo ? "Ganti foto..." : "Pilih foto..." }}';
            newPhotoContainer.style.display = 'none';
            newPhotoContainer.innerHTML = '';
            newPhotoContainer.style.opacity = '1';
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            
            if (!name) {
                e.preventDefault();
                alert('Nama kategori harus diisi!');
                return false;
            }
            
            if (name.length < 3) {
                e.preventDefault();
                alert('Nama kategori minimal 3 karakter!');
                return false;
            }
        });

        // Auto-trim on blur
        document.getElementById('name').addEventListener('blur', function() {
            this.value = this.value.trim();
        });
    </script>
</x-admin-layout>