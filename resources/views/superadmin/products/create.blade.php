<x-admin-layout>
    <x-slot name="header">
        <h2 class="h3 mb-0">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    {{-- Errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('superadmin.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
                        @csrf

                        {{-- Informasi Dasar --}}
                        <div class="mb-5">
                            <h3 class="h5 fw-semibold border-bottom pb-2 mb-4">📋 Informasi Dasar</h3>
                            
                            <div class="row g-3">
                                {{-- Title --}}
                                <div class="col-md-12">
                                    <label class="form-label fw-medium">
                                        Nama Produk <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title" value="{{ old('title') }}" required
                                        class="form-control"
                                        placeholder="Contoh: Kardus Ukuran Besar">
                                </div>

                                {{-- SKU --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">
                                        SKU / Kode Barang <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="sku" value="{{ old('sku') }}" required
                                        class="form-control"
                                        placeholder="Contoh: KRD-001">
                                </div>

                                {{-- Price --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">
                                        Harga <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="price" value="{{ old('price') }}" required min="0"
                                            class="form-control"
                                            placeholder="50000">
                                    </div>
                                </div>

                                {{-- Initial Stock --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">
                                        Stok Awal <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="initial_stock" value="{{ old('initial_stock') }}" required min="0"
                                        class="form-control"
                                        placeholder="100">
                                    <div class="form-text">Stok yang akan dibagi ke toko cabang</div>
                                </div>

                                {{-- Categories --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">
                                        Kategori (bisa lebih dari 1)
                                    </label>
                                    <select name="categories[]" multiple class="form-select" size="5">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Tahan Ctrl/Cmd untuk pilih lebih dari 1</div>
                                </div>
                            </div>
                        </div>

                        {{-- Media --}}
                        <div class="mb-5">
                            <h3 class="h5 fw-semibold border-bottom pb-2 mb-4">📸 Media Produk</h3>
                            
                            <div class="row g-3">
                                {{-- Photos --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">
                                        Foto Produk (bisa upload banyak)
                                    </label>
                                    <input type="file" name="photos[]" multiple accept="image/*" class="form-control">
                                    <div class="form-text">Max 2MB per foto, format: JPG, PNG, WEBP</div>
                                </div>

                                {{-- Video --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">
                                        Video Produk (opsional)
                                    </label>
                                    <input type="file" name="video" accept="video/*" class="form-control">
                                    <div class="form-text">Max 10MB, format: MP4, MOV, AVI</div>
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-5">
                            <h3 class="h5 fw-semibold border-bottom pb-2 mb-4">📝 Deskripsi</h3>
                            <textarea name="description" rows="6" class="form-control"
                                placeholder="Tulis deskripsi produk di sini...">{{ old('description') }}</textarea>
                        </div>

                        {{-- Variants --}}
                        <div class="mb-5">
                            <h3 class="h5 fw-semibold border-bottom pb-2 mb-4">🎨 Varian Produk</h3>
                            <div id="variants-container">
                                <div class="variant-item card mb-3">
                                    <div class="card-body bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-5">
                                                <input type="text" placeholder="Nama varian (contoh: Warna)"
                                                    class="variant-key form-control">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" placeholder="Nilai (contoh: Merah)"
                                                    class="variant-value form-control">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" onclick="removeVariant(this)"
                                                    class="btn btn-danger w-100">
                                                    🗑️ Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="addVariant()" class="btn btn-success">
                                ➕ Tambah Varian
                            </button>
                            <input type="hidden" name="variants" id="variants-data">
                            <div class="form-text mt-2">Contoh: Warna (Merah, Biru), Ukuran (S, M, L)</div>
                        </div>

                        {{-- Tags --}}
                        <div class="mb-5">
                            <h3 class="h5 fw-semibold border-bottom pb-2 mb-4">🏷️ Tags</h3>
                            <div id="tags-container" class="d-flex flex-wrap gap-2 mb-3">
                                <!-- Tags akan muncul di sini -->
                            </div>
                            <div class="input-group">
                                <input type="text" id="tag-input" placeholder="Tulis tag dan tekan Enter"
                                    class="form-control">
                                <button type="button" onclick="addTag()" class="btn btn-primary">
                                    Tambah Tag
                                </button>
                            </div>
                            <input type="hidden" name="tags" id="tags-data">
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                            <a href="{{ route('superadmin.products.index') }}" class="btn btn-secondary">
                                ← Batal
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                💾 Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===== VARIANTS MANAGEMENT =====
        function addVariant() {
            const container = document.getElementById('variants-container');
            const item = document.createElement('div');
            item.className = 'variant-item card mb-3';
            item.innerHTML = `
                <div class="card-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <input type="text" placeholder="Nama varian (contoh: Warna)"
                                class="variant-key form-control">
                        </div>
                        <div class="col-md-5">
                            <input type="text" placeholder="Nilai (contoh: Merah)"
                                class="variant-value form-control">
                        </div>
                        <div class="col-md-2">
                            <button type="button" onclick="removeVariant(this)"
                                class="btn btn-danger w-100">
                                🗑️ Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(item);
            attachVariantListeners(item);
        }

        function removeVariant(btn) {
            btn.closest('.variant-item').remove();
            updateVariantsData();
        }

        function attachVariantListeners(item) {
            const inputs = item.querySelectorAll('.variant-key, .variant-value');
            inputs.forEach(input => {
                input.addEventListener('input', updateVariantsData);
                input.addEventListener('change', updateVariantsData);
                input.addEventListener('blur', updateVariantsData);
            });
        }

        function updateVariantsData() {
            const items = document.querySelectorAll('.variant-item');
            const variants = {};
            
            items.forEach(item => {
                const key = item.querySelector('.variant-key').value.trim();
                const value = item.querySelector('.variant-value').value.trim();
                
                if (key && value) {
                    if (!variants[key]) {
                        variants[key] = [];
                    }
                    if (!variants[key].includes(value)) {
                        variants[key].push(value);
                    }
                }
            });
            
            const variantsJson = Object.keys(variants).length > 0 ? JSON.stringify(variants) : '';
            document.getElementById('variants-data').value = variantsJson;
            
            console.log('✅ Variants Updated:', variantsJson);
        }

        // ===== TAGS MANAGEMENT =====
        let tags = [];

        function addTag() {
            const input = document.getElementById('tag-input');
            const tag = input.value.trim();
            
            if (tag && !tags.includes(tag)) {
                tags.push(tag);
                renderTags();
                input.value = '';
            }
        }

        function removeTag(tagToRemove) {
            tags = tags.filter(t => t !== tagToRemove);
            renderTags();
        }

        function renderTags() {
            const container = document.getElementById('tags-container');
            container.innerHTML = tags.map(tag => {
                const escaped = escapeHtml(tag);
                return `
                    <span class="badge bg-primary d-inline-flex align-items-center gap-2 fs-6 py-2 px-3">
                        ${escaped}
                        <button type="button" onclick="removeTag(\`${escaped}\`)" 
                            class="btn-close btn-close-white" 
                            style="font-size: 0.6rem;" 
                            aria-label="Close"></button>
                    </span>
                `;
            }).join('');
            
            const tagsJson = tags.length > 0 ? JSON.stringify(tags) : '';
            document.getElementById('tags-data').value = tagsJson;
            
            console.log('✅ Tags Updated:', tagsJson);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ===== INITIALIZATION =====
        document.addEventListener('DOMContentLoaded', function() {
            // Attach listeners ke variant pertama
            const firstVariant = document.querySelector('.variant-item');
            if (firstVariant) {
                attachVariantListeners(firstVariant);
            }

            // Tag input - Enter key handler
            const tagInput = document.getElementById('tag-input');
            if (tagInput) {
                tagInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        addTag();
                    }
                });
            }

            // Form submit handler
            const form = document.getElementById('product-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Update data terakhir kali
                    updateVariantsData();
                    
                    const variantsData = document.getElementById('variants-data').value;
                    const tagsData = document.getElementById('tags-data').value;
                    
                    console.log('=== 🚀 FORM SUBMIT ===');
                    console.log('Variants:', variantsData);
                    console.log('Tags:', tagsData);
                    
                    // Validasi variant
                    const items = document.querySelectorAll('.variant-item');
                    let hasIncompleteVariant = false;
                    
                    items.forEach(item => {
                        const key = item.querySelector('.variant-key').value.trim();
                        const value = item.querySelector('.variant-value').value.trim();
                        
                        if ((key && !value) || (!key && value)) {
                            hasIncompleteVariant = true;
                        }
                    });
                    
                    if (hasIncompleteVariant) {
                        e.preventDefault();
                        alert('⚠️ Ada varian yang tidak lengkap!\n\nPastikan setiap varian memiliki:\n- Nama varian (contoh: Warna)\n- Nilai varian (contoh: Merah)\n\nAtau hapus varian yang kosong.');
                        return false;
                    }
                    
                    console.log('✅ Form validation passed!');
                });
            }
        });
    </script>

</x-admin-layout>