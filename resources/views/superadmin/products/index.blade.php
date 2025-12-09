<x-admin-layout>
    <x-slot name="header">
        <h2 class="h3 mb-0">{{ __('Manajemen Produk') }}</h2>
    </x-slot>

    <style>
        /* Product Management Page Scoped Styles */
        .product-management-page .products-header {
            background: linear-gradient(135deg, #224abe 0%, #224abe 100%);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .product-management-page .products-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .product-management-page .products-header p {
            margin: 0;
            opacity: 0.9;
        }

        /* Filter Section */
        .product-management-page .filter-section {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .product-management-page .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .product-management-page .filter-item {
            display: flex;
            flex-direction: column;
        }

        .product-management-page .filter-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .product-management-page .filter-label i {
            margin-right: 8px;
            color: #224abe;
        }

        .product-management-page .filter-input {
            padding: 12px 16px;
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .product-management-page .filter-input:focus {
            outline: none;
            border-color: #224abe;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .product-management-page .filter-stats {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
        }

        .product-management-page .filter-result {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .product-management-page .filter-result strong {
            color: #224abe;
            font-size: 18px;
        }

        .product-management-page .btn-reset-filter {
            background: #f8f9fa;
            border: 2px solid #e0e6ed;
            color: #666;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
        }

        .product-management-page .btn-reset-filter:hover {
            background: #224abe;
            border-color: #224abe;
            color: white;
        }

        .product-management-page .btn-add-product {
            background: linear-gradient(135deg, #224abe 0%, #224abe 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            border: none;
            font-size: 15px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            text-decoration: none;
        }

        .product-management-page .btn-add-product:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
            color: white;
            text-decoration: none;
        }

        .product-management-page .btn-view-mode {
            background: white;
            border: 2px solid #e0e6ed;
            color: #666;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .product-management-page .btn-view-mode:hover,
        .product-management-page .btn-view-mode.active {
            border-color: #224abe;
            background: #224abe;
            color: white;
            text-decoration: none;
        }

        /* Table Styles */
        .product-management-page .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .product-management-page .table {
            margin-bottom: 0;
        }

        .product-management-page .table thead {
            background: linear-gradient(135deg, #f8f9fc 0%, #eef2f7 100%);
        }

        .product-management-page .table thead th {
            font-weight: 700;
            color: #224abe;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 16px 12px;
            border: none;
        }

        .product-management-page .table tbody tr {
            transition: all 0.2s;
        }

        .product-management-page .table tbody tr:hover {
            background: #f8f9fc;
            cursor: pointer;
        }

        .product-management-page .table tbody td {
            padding: 16px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-management-page .action-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
            text-decoration: none;
            font-size: 1rem;
        }

        .product-management-page .action-icon:hover {
            transform: scale(1.1);
            text-decoration: none;
        }

        .product-management-page .action-icon.view {
            background: #e3f2fd;
            color: #2196f3;
        }

        .product-management-page .action-icon.edit {
            background: #fff3e0;
            color: #ff9800;
        }

        .product-management-page .action-icon.delete {
            background: #ffebee;
            color: #f44336;
        }

        /* Card Styles */
        .modern-product-card {
            width: 100%;
            background: white;
            border-radius: 30px;
            box-shadow: 0 20px 40px -15px rgba(34, 74, 190, 0.15);
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            height: 400px;
        }

        .modern-product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px -15px rgba(34, 74, 190, 0.3);
        }

        .card-image-wrapper {
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-color: #E9D5FF;
            border-bottom-right-radius: 100px;
            overflow: hidden;
        }

        .card-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            user-select: none;
        }

        .card-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);
        }

        .card-image-placeholder i {
            font-size: 64px;
            color: rgba(255, 255, 255, 0.3);
        }

        .card-status-overlay {
            position: absolute;
            top: 16px;
            right: 16px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            z-index: 10;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: rgba(34, 74, 190, 0.95);
            color: white;
        }

        .status-inactive {
            background: rgba(108, 117, 125, 0.95);
            color: white;
        }

        .card-discount-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            background: rgba(220, 53, 69, 0.95);
            color: white;
            z-index: 10;
        }

        .card-slide-panel {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #FFFFFF;
            border-radius: 30px;
            padding: 20px 24px 24px;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 15;
            transform: translateY(calc(100% - 120px));
        }

        .modern-product-card:hover .card-slide-panel {
            transform: translateY(calc(100% - 220px));
        }

        .card-title-section {
            margin-bottom: 0;
        }

        .card-product-title {
            color: #2d3748;
            font-weight: 800;
            font-size: 17px;
            letter-spacing: 0.02em;
            line-height: 1.3;
            margin: 0 0 8px 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-product-price {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .price-current {
            font-size: 18px;
            font-weight: 800;
            color: #224abe;
        }

        .price-original {
            font-size: 14px;
            text-decoration: line-through;
            color: #999;
        }

        .card-divider {
            width: 100%;
            height: 1px;
            background: #e2e8f0;
            margin-bottom: 16px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modern-product-card:hover .card-divider {
            opacity: 1;
        }

        .card-hidden-info {
            margin-bottom: 0;
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: opacity 0.3s ease 0.2s, max-height 0.4s ease;
        }

        .modern-product-card:hover .card-hidden-info {
            opacity: 1;
            max-height: 150px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .info-label {
            font-size: 11px;
            font-weight: 600;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 13px;
            font-weight: 700;
            color: #4A5568;
        }

        .stock-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }

        .stock-high {
            background: #d4edda;
            color: #155724;
        }

        .stock-medium {
            background: #fff3cd;
            color: #856404;
        }

        .stock-low {
            background: #f8d7da;
            color: #721c24;
        }

        .card-quick-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            padding-top: 0;
        }

        .quick-action-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 50%;
            color: #224abe;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .quick-action-icon:hover {
            background: #224abe;
            color: white;
            transform: scale(1.1);
            text-decoration: none;
        }

        .product-management-page .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .product-management-page .no-results i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            .product-management-page .filter-grid {
                grid-template-columns: 1fr;
            }

            .modern-product-card {
                height: 300px;
            }
            
            .card-slide-panel {
                transform: translateY(calc(100% - 110px));
            }

            .modern-product-card:hover .card-slide-panel {
                transform: translateY(calc(100% - 220px));
            }
        }
    </style>

    <div class="container-fluid product-management-page">
        {{-- Alert Success --}}
      

        <!-- Header -->
        <div class="products-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1><i class="fas fa-box-open"></i> Manajemen Produk</h1>
                    <p>Kelola semua produk dan informasi terkait</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="switchView('list')" class="btn-view-mode" id="btnListView">
                        <i class="fas fa-list"></i>
                        <span>List</span>
                    </button>
                    <button onclick="switchView('card')" class="btn-view-mode" id="btnCardView">
                        <i class="fas fa-th-large"></i>
                        <span>Card</span>
                    </button>
                    <a href="{{ route('superadmin.products.create') }}" class="btn-add-product">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Produk</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-grid">
                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-search"></i>
                        Cari Produk
                    </label>
                    <input type="text" id="filterName" class="filter-input" placeholder="Ketik nama produk atau SKU...">
                </div>

                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-tags"></i>
                        Filter Kategori
                    </label>
                    <select id="filterCategory" class="filter-input">
                        <option value="">Semua Kategori</option>
                        @foreach($products->pluck('categories')->flatten()->unique('id') as $category)
                            <option value="{{ strtolower($category->name) }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-toggle-on"></i>
                        Filter Status
                    </label>
                    <select id="filterStatus" class="filter-input">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-sort"></i>
                        Urutkan
                    </label>
                    <select id="filterSort" class="filter-input">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="name_asc">Nama A-Z</option>
                        <option value="name_desc">Nama Z-A</option>
                        <option value="price_asc">Harga Terendah</option>
                        <option value="price_desc">Harga Tertinggi</option>
                    </select>
                </div>
            </div>

            <div class="filter-stats">
                <div class="filter-result">
                     Menampilkan <strong id="resultCount">{{ $products->count() }}</strong> dari <strong>{{ $products->total() }}</strong> produk
                </div>
                <button class="btn-reset-filter" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Reset Filter
                </button>
            </div>
        </div>

        <!-- LIST VIEW -->
        <div id="listView" style="display: none;">
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Produk</th>
                                <th>SKU</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok Awal</th>
                                <th>Sisa Stok</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="listViewBody">
                            @forelse($products as $product)
                                <tr class="product-item" 
                                    data-name="{{ strtolower($product->title) }}"
                                    data-sku="{{ strtolower($product->sku) }}"
                                    data-categories="{{ strtolower($product->categories->pluck('name')->join(',')) }}"
                                    data-status="{{ $product->is_active ? '1' : '0' }}"
                                    data-created="{{ $product->created_at->timestamp }}"
                                    data-price="{{ $product->discount_price ?? $product->price }}"
                                    onclick="openProductDetail({{ $product->id }})">
                                    <td>
                                        @if($product->photos && count($product->photos) > 0)
                                            <img src="{{ asset('storage/' . $product->photos[0]) }}" 
                                                alt="{{ $product->title }}"
                                                class="rounded"
                                                style="width: 64px; height: 64px; object-fit: cover;">
                                        @else
                                            <div class="card-image-placeholder rounded" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $product->title }}</div>
                                        @if($product->tags)
                                            <div class="d-flex gap-1 mt-1 flex-wrap">
                                                @foreach(array_slice($product->tags, 0, 3) as $tag)
                                                    <span class="badge bg-primary bg-opacity-10  text-white">{{ $tag }} </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="font-monospace">{{ $product->sku }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @forelse($product->categories as $category)
                                                <span class="badge" style="background-color: #f3e8ff; color: #7c3aed;">
                                                    {{ $category->name }}
                                                </span>
                                            @empty
                                                <span class="text-muted small">-</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->discount_price)
                                            <div class="text-decoration-line-through text-muted small">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </div>
                                            <div class="fw-bold text-danger">
                                                Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                            </div>
                                        @else
                                            <div class="fw-semibold">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-25 text-white fw-semibold">
                                            {{ $product->initial_stock }}
                                        </span>
                                    </td>
                                    <td>
    @php
        // ✅ Pakai data yang sudah dihitung di controller
        $remaining = $product->remaining_stock_cached ?? 0;
        $badgeClass = $remaining > 50 ? 'success' : ($remaining > 0 ? 'warning' : 'danger');
    @endphp
    <span class="badge bg-{{ $badgeClass }} text-white fw-semibold">
        {{ $remaining }}
    </span>
</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="text-warning">⭐</span>
                                            <span class="ms-1">{{ number_format($product->rating, 1) }}</span>
                                            <span class="ms-1 text-muted small">({{ $product->reviews_count }})</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success text-white">Aktif</span>
                                        @else
                                            <span class="badge bg-danger text-white">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td onclick="event.stopPropagation()">
                                        <div class="d-flex gap-2">
                                            <a href="javascript:void(0)" 
                                                onclick="openProductDetail({{ $product->id }})"
                                                class="action-icon view" 
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('superadmin.products.edit', $product) }}" 
                                                class="action-icon edit" 
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('superadmin.products.destroy', $product) }}" 
                                                method="POST" 
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin hapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="action-icon delete border-0" 
                                                    title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div class="display-1 mb-3">📦</div>
                                        <p class="fs-5 text-muted">Belum ada produk</p>
                                        <a href="{{ route('superadmin.products.create') }}" 
                                            class="btn btn-primary mt-3">
                                            Tambah Produk Pertama
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- CARD VIEW -->
        <div class="row" id="cardView" style="display: none;">
            @forelse($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4 product-item" 
                     data-name="{{ strtolower($product->title) }}" 
                     data-sku="{{ strtolower($product->sku) }}"
                     data-categories="{{ strtolower($product->categories->pluck('name')->join(',')) }}"
                     data-status="{{ $product->is_active ? '1' : '0' }}"
                     data-created="{{ $product->created_at->timestamp }}"
                     data-price="{{ $product->discount_price ?? $product->price }}">
                   <div class="modern-product-card" onclick="openProductDetail({{ $product->id }})">
                        <div class="card-image-wrapper">
                            @if($product->photos && count($product->photos) > 0)
                                <img src="{{ asset('storage/' . $product->photos[0]) }}" alt="{{ $product->title }}">
                            @else
                                <div class="card-image-placeholder">
                                    <i class="fas fa-box-open"></i>
                                </div>
                            @endif
                            
                            <div class="card-status-overlay {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $product->is_active ? '● Aktif' : '● Nonaktif' }}
                            </div>

                            @if($product->discount_price)
                                @php
                                    $discount = round((($product->price - $product->discount_price) / $product->price) * 100);
                                @endphp
                                <div class="card-discount-badge">
                                    -{{ $discount }}%
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-slide-panel">
                            <div class="card-title-section">
                                <h6 class="card-product-title">{{ Str::upper($product->title) }}</h6>
                                <div class="card-product-price">
                                    <span class="price-current">
                                        Rp {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}
                                    </span>
                                    @if($product->discount_price)
                                        <span class="price-original">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-divider"></div>
                            
                            <div class="card-hidden-info">
                                <div class="info-row">
                                    <span class="info-label">SKU</span>
                                    <span class="info-value">{{ $product->sku }}</span>
                                </div>
                                
                                <div class="info-row">
    <span class="info-label">Stok Tersisa</span>
    @php
        // ✅ Pakai data yang sudah dihitung di controller
        $remaining = $product->remaining_stock_cached ?? 0;
        $stockClass = $remaining > 50 ? 'stock-high' : ($remaining > 0 ? 'stock-medium' : 'stock-low');
    @endphp
    <span class="stock-badge {{ $stockClass }}">
        {{ $remaining }} Unit
    </span>
</div>

                                <div class="info-row">
                                    <span class="info-label">Rating</span>
                                    <span class="info-value">
                                        ⭐ {{ number_format($product->rating, 1) }} ({{ $product->reviews_count }})
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-quick-actions">
                                <a href="javascript:void(0)" 
                                        onclick="event.preventDefault(); openProductDetail({{ $product->id }})"
                                   class="quick-action-icon"
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('superadmin.products.edit', $product) }}" 
                                   onclick="event.stopPropagation();"
                                   class="quick-action-icon"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('superadmin.products.destroy', $product) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="event.stopPropagation(); return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="quick-action-icon border-0" 
                                            title="Hapus"
                                            onclick="event.stopPropagation();"
                                            style="cursor: pointer;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="no-results">
                        <i class="fas fa-box-open"></i>
                        <h4>Belum Ada Produk</h4>
                        <p>Silakan tambah produk baru dengan klik tombol di atas</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- No Results Message -->
        <div class="no-results" id="noResults" style="display: none;">
            <i class="fas fa-search"></i>
            <h4>Tidak Ada Hasil</h4>
            <p>Tidak ditemukan produk yang sesuai dengan filter Anda</p>
        </div>
    </div>

    @include('superadmin.products.detailmodal', ['products' => $products])

  
<script>
// 🔥 PERBAIKAN: Kirim data lengkap dengan relasi stocks
let currentView = 'list';

function switchView(view) {
    currentView = view;
    const listView = document.getElementById('listView');
    const cardView = document.getElementById('cardView');
    const btnListView = document.getElementById('btnListView');
    const btnCardView = document.getElementById('btnCardView');
    
    if (view === 'list') {
        listView.style.display = 'block';
        cardView.style.display = 'none';
        btnListView.classList.add('active');
        btnCardView.classList.remove('active');
    } else {
        listView.style.display = 'none';
        cardView.style.display = 'flex';
        cardView.style.flexWrap = 'wrap';
        btnListView.classList.remove('active');
        btnCardView.classList.add('active');
    }
    
    localStorage.setItem('productViewMode', view);
    filterProducts();
}

document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('productViewMode') || 'list';
    switchView(savedView);
});

function filterProducts() {
    const nameFilter = document.getElementById('filterName').value.toLowerCase();
    const categoryFilter = document.getElementById('filterCategory').value.toLowerCase();
    const statusFilter = document.getElementById('filterStatus').value;
    const sortFilter = document.getElementById('filterSort').value;
    
    const activeContainer = currentView === 'list' 
        ? document.getElementById('listViewBody') 
        : document.getElementById('cardView');
    const items = Array.from(activeContainer.querySelectorAll('.product-item'));
    
    const noResults = document.getElementById('noResults');
    const listView = document.getElementById('listView');
    const cardView = document.getElementById('cardView');
    
    let visibleCount = 0;

    items.forEach(item => {
        const name = item.getAttribute('data-name') || '';
        const sku = item.getAttribute('data-sku') || '';
        const categories = item.getAttribute('data-categories') || '';
        const status = item.getAttribute('data-status') || '';
        
        const matchName = !nameFilter || name.includes(nameFilter) || sku.includes(nameFilter);
        const matchCategory = !categoryFilter || categories.includes(categoryFilter);
        const matchStatus = !statusFilter || status === statusFilter;
        
        if (matchName && matchCategory && matchStatus) {
            item.style.display = currentView === 'list' ? 'table-row' : 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    const sortedItems = items.sort((a, b) => {
        if (sortFilter === 'newest') {
            return parseInt(b.getAttribute('data-created') || 0) - parseInt(a.getAttribute('data-created') || 0);
        } else if (sortFilter === 'oldest') {
            return parseInt(a.getAttribute('data-created') || 0) - parseInt(b.getAttribute('data-created') || 0);
        } else if (sortFilter === 'name_asc') {
            return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '');
        } else if (sortFilter === 'name_desc') {
            return (b.getAttribute('data-name') || '').localeCompare(a.getAttribute('data-name') || '');
        } else if (sortFilter === 'price_asc') {
            return parseFloat(a.getAttribute('data-price') || 0) - parseFloat(b.getAttribute('data-price') || 0);
        } else if (sortFilter === 'price_desc') {
            return parseFloat(b.getAttribute('data-price') || 0) - parseFloat(a.getAttribute('data-price') || 0);
        }
        return 0;
    });

    if (currentView === 'list') {
        const tbody = document.getElementById('listViewBody');
        sortedItems.forEach(item => {
            if (item.tagName === 'TR') {
                tbody.appendChild(item);
            }
        });
    } else {
        sortedItems.forEach(item => {
            if (item.classList.contains('col-xl-3')) {
                cardView.appendChild(item);
            }
        });
    }

    document.getElementById('resultCount').textContent = visibleCount;

    if (visibleCount === 0) {
        listView.style.display = 'none';
        cardView.style.display = 'none';
        noResults.style.display = 'block';
    } else {
        noResults.style.display = 'none';
        if (currentView === 'list') {
            listView.style.display = 'block';
        } else {
            cardView.style.display = 'flex';
        }
    }
}

function resetFilters() {
    document.getElementById('filterName').value = '';
    document.getElementById('filterCategory').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterSort').value = 'newest';
    filterProducts();
}

document.getElementById('filterName').addEventListener('input', filterProducts);
document.getElementById('filterCategory').addEventListener('change', filterProducts);
document.getElementById('filterStatus').addEventListener('change', filterProducts);
document.getElementById('filterSort').addEventListener('change', filterProducts);

// ✅ BUKA MODAL PRODUK - Panggil function dari detailmodal.blade.php
function openProductDetail(productId) {
    // Function showProductDetail() sudah ada di detailmodal.blade.php
    if (typeof showProductDetail === 'function') {
        showProductDetail(productId);
    } else {
        console.error('Function showProductDetail tidak ditemukan');
    }
}
</script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     t
</x-admin-layout>