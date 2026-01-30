{{-- resources/views/kepala-toko/products/index.blade.php --}}
<x-admin-layout title="Daftar Produk">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-box"></i> Daftar Produk
            </h1>
        </div>

        <!-- Filter & Search -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('kepala-toko.products.index') }}" method="GET">
                    <div class="row">
                        <!-- Search -->
                        <div class="col-md-4 mb-3">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Cari nama produk atau SKU..."
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Category Filter -->
                        <div class="col-md-3 mb-3">
                            <select name="category_id" class="form-control">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="col-md-3 mb-3">
                            <select name="sort" class="form-control">
                                <option value="latest" {{ $sortBy == 'latest' ? 'selected' : '' }}>
                                    Terbaru
                                </option>
                                <option value="name_asc" {{ $sortBy == 'name_asc' ? 'selected' : '' }}>
                                    Nama A-Z
                                </option>
                                <option value="name_desc" {{ $sortBy == 'name_desc' ? 'selected' : '' }}>
                                    Nama Z-A
                                </option>
                            </select>
                        </div>

                        <!-- Button -->
                        <div class="col-md-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card shadow h-100">
                    <!-- Product Image -->
                    <img src="{{ $product->thumbnail }}" 
                         class="card-img-top" 
                         alt="{{ $product->title }}"
                         style="height: 200px; object-fit: cover;">
                    
                    <div class="card-body d-flex flex-column">
                        <!-- Product Title -->
                        <h6 class="card-title font-weight-bold mb-2">
                            {{ Str::limit($product->title, 50) }}
                        </h6>
                        
                        <!-- SKU -->
                        <p class="text-muted mb-2">
                            <small>SKU: <code>{{ $product->sku }}</code></small>
                        </p>

                        <!-- Categories -->
                        @if($product->categories->count() > 0)
                        <p class="mb-2">
                            @foreach($product->categories->take(2) as $category)
                            <span class="badge badge-info">{{ $category->name }}</span>
                            @endforeach
                        </p>
                        @endif

                        <!-- Stock Info -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">Warehouse:</small>
                                <span class="badge badge-{{ $product->total_stock > 0 ? 'success' : 'danger' }}">
                                    {{ $product->total_stock }} pcs
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Toko Saya:</small>
                                <span class="badge badge-{{ $product->toko_stock > 0 ? 'primary' : 'secondary' }}">
                                    {{ $product->toko_stock }} pcs
                                </span>
                            </div>
                        </div>

                        <!-- Variants Count -->
                        <p class="text-muted mb-3">
                            <small>
                                <i class="fas fa-palette"></i>
                                {{ $product->variants->count() }} varian
                            </small>
                        </p>

                        <!-- Actions -->
                        <div class="mt-auto">
                            <a href="{{ route('kepala-toko.products.show', $product) }}" 
                               class="btn btn-info btn-sm btn-block mb-2">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            
                            @if($product->total_stock > 0)
                            <a href="{{ route('kepala-toko.stock-requests.create', $product) }}" 
                               class="btn btn-primary btn-sm btn-block">
                                <i class="fas fa-paper-plane"></i> Request Stok
                            </a>
                            @else
                            <button class="btn btn-secondary btn-sm btn-block" disabled>
                                <i class="fas fa-times"></i> Stok Habis
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
            </div>
            <div>
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada produk ditemukan</h5>
                <p class="text-muted">Coba ubah filter atau kata kunci pencarian Anda</p>
                <a href="{{ route('kepala-toko.products.index') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-redo"></i> Reset Filter
                </a>
            </div>
        </div>
        @endif
    </div>
</x-admin-layout>