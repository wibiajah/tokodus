<x-admin-layout title="Monitoring Stok Produk">
    <div class="container-fluid">
        
        @include('layouts.management-header', [
            'icon' => 'fas fa-warehouse',
            'title' => 'Monitoring Stok Produk',
            'description' => 'Pantau distribusi stok produk di semua toko secara real-time',
            'buttonText' => '',
            'buttonRoute' => '',
            'buttonIcon' => '',
        ])

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Produk
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $products->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Toko Aktif
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tokos->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-store fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Stok Pusat
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($products->sum(function($p) {
                                        return $p->variants->filter(function($v) {
                                            return !$v->hasChildren();
                                        })->sum('stock_pusat');
                                    })) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Dialokasikan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($products->sum(function($p) {
                                        return $p->variants->filter(function($v) {
                                            return !$v->hasChildren();
                                        })->sum(function($v) {
                                            return $v->stocks->sum('stock');
                                        });
                                    })) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck-loading fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-filter"></i> Filter & Pencarian
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold">Cari Produk</label>
                        <input type="text" id="filterName" class="form-control" placeholder="Nama produk atau SKU...">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold">Status Stok</label>
                        <select id="filterStatus" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="high">Stok Cukup (&gt;50)</option>
                            <option value="medium">Stok Terbatas (1-50)</option>
                            <option value="low">Stok Habis (0)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold">Urutkan</label>
                        <select id="filterSort" class="form-control">
                            <option value="name_asc">Nama A-Z</option>
                            <option value="name_desc">Nama Z-A</option>
                            <option value="stock_low">Stok Pusat Terendah</option>
                            <option value="stock_high">Stok Pusat Tertinggi</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan <strong id="resultCount">{{ $products->count() }}</strong> dari <strong>{{ $products->count() }}</strong> produk
                    </small>
                    <button class="btn btn-sm btn-outline-secondary" onclick="resetFilters()">
                        <i class="fas fa-redo"></i> Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table"></i> Daftar Produk
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="50">No</th>
                                <th width="80">Foto</th>
                                <th>Produk</th>
                                <th width="120">SKU</th>
                                <th class="text-center" width="120">Stok Pusat</th>
                                <th class="text-center" width="120">Dialokasikan</th>
                                <th class="text-center" width="100">Variants</th>
                                <th class="text-center" width="120">Toko</th>
                                <th class="text-center" width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="stockTableBody">
                            @forelse($products as $product)
                                @php
                                    // Calculate stock pusat total - HANYA LEAF VARIANTS
                                    $totalStockPusat = $product->variants->filter(function($v) {
                                        return !$v->hasChildren();
                                    })->sum('stock_pusat');

                                    // Calculate allocated stock
                                    $totalAllocated = $product->variants->filter(function($v) {
                                        return !$v->hasChildren();
                                    })->sum(function($v) {
                                        return $v->stocks->sum('stock');
                                    });

                                    // Count variants
                                    $variantCount = $product->variants->filter(function($v) {
                                        return !$v->hasChildren();
                                    })->count();

                                    // Count tokos
                                    $tokoIds = $product->variants->filter(function($v) {
                                        return !$v->hasChildren();
                                    })->flatMap(function($v) {
                                        return $v->stocks->pluck('toko_id');
                                    })->unique();
                                    $tokoCount = $tokoIds->count();

                                    $stockClass = $totalStockPusat > 50 ? 'success' : ($totalStockPusat > 0 ? 'warning' : 'danger');
                                @endphp
                                <tr class="stock-item" 
                                    data-name="{{ strtolower($product->title) }}" 
                                    data-sku="{{ strtolower($product->sku) }}" 
                                    data-stock="{{ $totalStockPusat }}">
                                    
                                    <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                                    
                                    <td class="text-center">
                                        @if($product->photos && count($product->photos) > 0)
                                            <img src="{{ asset('storage/' . $product->photos[0]) }}" 
                                                 alt="{{ $product->title }}" 
                                                 class="img-thumbnail"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-image fa-2x text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <strong>{{ $product->title }}</strong>
                                        @if($product->tipe)
                                            <br><small class="text-muted">{{ $product->tipe }}</small>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <span class="badge badge-secondary">{{ $product->sku }}</span>
                                    </td>
                                    
                                    <td class="text-center">
                                        <span class="badge badge-{{ $stockClass }} badge-pill px-3 py-2">
                                            {{ number_format($totalStockPusat) }}
                                        </span>
                                    </td>
                                    
                                    <td class="text-center">
                                        <span class="badge badge-{{ $totalAllocated > 0 ? 'info' : 'secondary' }} badge-pill px-3 py-2">
                                            {{ number_format($totalAllocated) }}
                                        </span>
                                    </td>
                                    
                                    <td class="text-center">
                                        <span class="badge badge-primary">
                                            {{ $variantCount }}
                                        </span>
                                    </td>
                                    
                                    <td class="text-center">
                                        @if($tokoCount > 0)
                                            <span class="badge badge-success">
                                                <i class="fas fa-store"></i> {{ $tokoCount }}
                                            </span>
                                        @else
                                            <span class="badge badge-light text-muted">
                                                <i class="fas fa-times"></i> 0
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <a href="{{ route('superadmin.stocks.detail', $product) }}" 
                                           class="btn btn-sm btn-primary"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Belum ada produk</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- No Results -->
        <div class="card shadow mb-4" id="noResults" style="display: none;">
            <div class="card-body text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak Ada Hasil</h5>
                <p class="text-muted mb-0">Tidak ditemukan produk yang sesuai dengan filter</p>
            </div>
        </div>

    </div>

    <script>
        // Filter Functions
        function filterStocks() {
            const nameFilter = document.getElementById('filterName').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const sortFilter = document.getElementById('filterSort').value;

            const items = Array.from(document.querySelectorAll('.stock-item'));
            const noResults = document.getElementById('noResults');
            const tableCard = document.querySelector('.card-body .table-responsive').closest('.card');

            let visibleCount = 0;

            items.forEach(item => {
                const name = item.getAttribute('data-name');
                const sku = item.getAttribute('data-sku');
                const stock = parseInt(item.getAttribute('data-stock'));

                const matchName = !nameFilter || name.includes(nameFilter) || sku.includes(nameFilter);

                let matchStatus = true;
                if (statusFilter === 'high') matchStatus = stock > 50;
                else if (statusFilter === 'medium') matchStatus = stock > 0 && stock <= 50;
                else if (statusFilter === 'low') matchStatus = stock === 0;

                if (matchName && matchStatus) {
                    item.style.display = 'table-row';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Sort
            const sortedItems = items.sort((a, b) => {
                if (sortFilter === 'name_asc') {
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                } else if (sortFilter === 'name_desc') {
                    return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
                } else if (sortFilter === 'stock_low') {
                    return parseInt(a.getAttribute('data-stock')) - parseInt(b.getAttribute('data-stock'));
                } else if (sortFilter === 'stock_high') {
                    return parseInt(b.getAttribute('data-stock')) - parseInt(a.getAttribute('data-stock'));
                }
                return 0;
            });

            // Re-append sorted items
            const tbody = document.getElementById('stockTableBody');
            sortedItems.forEach(item => {
                tbody.appendChild(item);
            });

            document.getElementById('resultCount').textContent = visibleCount;

            // Show/hide no results
            if (visibleCount === 0) {
                tableCard.style.display = 'none';
                noResults.style.display = 'block';
            } else {
                tableCard.style.display = 'block';
                noResults.style.display = 'none';
            }
        }

        function resetFilters() {
            document.getElementById('filterName').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterSort').value = 'name_asc';
            filterStocks();
        }

        document.getElementById('filterName').addEventListener('input', filterStocks);
        document.getElementById('filterStatus').addEventListener('change', filterStocks);
        document.getElementById('filterSort').addEventListener('change', filterStocks);
    </script>
</x-admin-layout>