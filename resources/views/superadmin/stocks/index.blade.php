<x-admin-layout>
    <x-slot name="header">
        <h2 class="h3 mb-0">{{ __('Monitoring Stok Real-Time') }}</h2>
    </x-slot>

    <style>
        /* Stock Management Page Scoped Styles */
        .stock-management-page .stock-header {
            background: linear-gradient(135deg, #224abe 0%, #224abe 100%);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        }

        .stock-management-page .stock-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .stock-management-page .stock-header p {
            margin: 0;
            opacity: 0.9;
        }

        /* Summary Cards */
        .stock-management-page .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stock-management-page .summary-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stock-management-page .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stock-management-page .summary-card-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stock-management-page .summary-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            flex-shrink: 0;
        }

        .stock-management-page .summary-icon.blue {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        }

        .stock-management-page .summary-icon.green {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        }

        .stock-management-page .summary-icon.yellow {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }

        .stock-management-page .summary-icon.purple {
            background: linear-gradient(135deg, #e9d5ff 0%, #d8b4fe 100%);
        }

        .stock-management-page .summary-info h3 {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stock-management-page .summary-info p {
            font-size: 28px;
            font-weight: 800;
            color: #111827;
            margin: 0;
        }

        /* Filter Section */
        .stock-management-page .filter-section {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .stock-management-page .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stock-management-page .filter-item {
            display: flex;
            flex-direction: column;
        }

        .stock-management-page .filter-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .stock-management-page .filter-label i {
            margin-right: 8px;
            color: #224abe;
        }

        .stock-management-page .filter-input {
            padding: 12px 16px;
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .stock-management-page .filter-input:focus {
            outline: none;
            border-color: #224abe;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .stock-management-page .filter-stats {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
        }

        .stock-management-page .filter-result {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .stock-management-page .filter-result strong {
            color: #224abe;
            font-size: 18px;
        }

        .stock-management-page .btn-reset-filter {
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

        .stock-management-page .btn-reset-filter:hover {
            background: #224abe;
            border-color: #224abe;
            color: white;
        }

        /* Table Styles */
        .stock-management-page .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .stock-management-page .table-wrapper {
            overflow-x: auto;
        }

        .stock-management-page .table {
            margin-bottom: 0;
        }

        .stock-management-page .table thead {
            background: linear-gradient(135deg, #f8f9fc 0%, #eef2f7 100%);
        }

        .stock-management-page .table thead th {
            font-weight: 700;
            color: #224abe;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 16px 12px;
            border: none;
            white-space: nowrap;
        }

        .stock-management-page .table tbody tr {
            transition: all 0.2s;
        }

        .stock-management-page .table tbody tr:hover {
            background: #f0fdf4;
        }

        .stock-management-page .table tbody td {
            padding: 16px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .stock-management-page .product-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 250px;
        }

        .stock-management-page .product-image {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .stock-management-page .product-placeholder {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stock-management-page .product-info h4 {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 4px 0;
        }

        .stock-management-page .product-info p {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
            font-family: monospace;
        }

        .stock-management-page .stock-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            display: inline-block;
            min-width: 60px;
            text-align: center;
        }

        .stock-management-page .stock-badge.high {
            background: #d1fae5;
            color: #065f46;
        }

        .stock-management-page .stock-badge.medium {
            background: #fef3c7;
            color: #92400e;
        }

        .stock-management-page .stock-badge.low {
            background: #fee2e2;
            color: #991b1b;
        }

        .stock-management-page .stock-badge.empty {
            background: #f3f4f6;
            color: #6b7280;
        }

        .stock-management-page .stock-badge.initial {
            background: #e0e7ff;
            color: #3730a3;
        }

        .stock-management-page .action-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            cursor: pointer;
        }

        .stock-management-page .action-btn.detail {
            background: #dbeafe;
            color: #1e40af;
        }

        .stock-management-page .action-btn.detail:hover {
            background: #1e40af;
            color: white;
            text-decoration: none;
            transform: translateX(3px);
        }

        /* No Results */
        .stock-management-page .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .stock-management-page .no-results i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            .stock-management-page .summary-cards {
                grid-template-columns: 1fr;
            }

            .stock-management-page .filter-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="container-fluid stock-management-page">
        {{-- Alert Success --}}
      

        <!-- Header -->
        <div class="stock-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1><i class="fas fa-chart-line"></i> Monitoring Stok Real-Time</h1>
                    <p>Pantau distribusi stok produk di semua toko secara real-time</p>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-card-content">
                    <div class="summary-icon blue">📦</div>
                    <div class="summary-info">
                        <h3>Total Produk</h3>
                        <p>{{ $products->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-card-content">
                    <div class="summary-icon green">🏪</div>
                    <div class="summary-info">
                        <h3>Total Toko</h3>
                        <p>{{ $tokos->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-card-content">
                    <div class="summary-icon yellow">📊</div>
                    <div class="summary-info">
                        <h3>Stok Awal Total</h3>
                        <p>{{ number_format($products->sum('initial_stock')) }}</p>
                    </div>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-card-content">
                    <div class="summary-icon purple">✅</div>
                    <div class="summary-info">
                        <h3>Sudah Dialokasikan</h3>
                        <p>{{ number_format($products->sum(fn($p) => $p->stocks->sum('stock'))) }}</p>
                    </div>
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
                        <i class="fas fa-exclamation-triangle"></i>
                        Filter Status Stok
                    </label>
                    <select id="filterStatus" class="filter-input">
                        <option value="">Semua Status</option>
                        <option value="high">Stok Cukup (&gt;50)</option>
                        <option value="medium">Stok Terbatas (1-50)</option>
                        <option value="low">Stok Habis (0)</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-sort"></i>
                        Urutkan
                    </label>
                    <select id="filterSort" class="filter-input">
                        <option value="name_asc">Nama A-Z</option>
                        <option value="name_desc">Nama Z-A</option>
                        <option value="stock_low">Stok Tersisa Terendah</option>
                        <option value="stock_high">Stok Tersisa Tertinggi</option>
                    </select>
                </div>
            </div>

            <div class="filter-stats">
                <div class="filter-result">
                    Menampilkan <strong id="resultCount">{{ count($products) }}</strong> dari <strong>{{ count($products) }}</strong> produk
                </div>
                <button class="btn-reset-filter" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Reset Filter
                </button>
            </div>
        </div>

        <!-- Stock Table -->
        <div class="table-container">
            <div class="table-wrapper">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Stok Awal</th>
                            <th class="text-center">Total Dialokasikan</th>
                            <th class="text-center" style="background: #dbeafe;">Sisa Stok</th>
                            <th class="text-center">Distribusi Toko</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="stockTableBody">
                        @forelse($products as $product)
                            @php
                                $totalAllocated = $product->stocks->sum('stock');
                                $remaining = $product->remaining_initial_stock;
                                $remainingClass = $remaining > 50 ? 'high' : ($remaining > 0 ? 'medium' : 'low');
                                $tokoCount = $product->stocks->count();
                                
                                // Prepare stock data for modal
                                $stocksData = $product->stocks->map(function($stock) use ($totalAllocated) {
                                    return [
                                        'toko_name' => $stock->toko->nama_toko ?? 'Unknown',
                                        'toko_status' => $stock->toko->status ?? 'unknown',
                                        'stock' => $stock->stock,
                                        'percentage' => $totalAllocated > 0 ? round(($stock->stock / $totalAllocated) * 100, 1) : 0
                                    ];
                                })->sortByDesc('stock')->values()->toArray();
                            @endphp
                            <tr class="stock-item" 
                                data-name="{{ strtolower($product->title) }}"
                                data-sku="{{ strtolower($product->sku) }}"
                                data-remaining="{{ $remaining }}"
                                data-product-id="{{ $product->id }}"
                                data-product-title="{{ $product->title }}"
                                data-product-sku="{{ $product->sku }}"
                                data-product-photo="{{ $product->photos && count($product->photos) > 0 ? asset('storage/' . $product->photos[0]) : '' }}"
                                data-initial-stock="{{ $product->initial_stock }}"
                                data-total-allocated="{{ $totalAllocated }}"
                                data-remaining-stock="{{ $remaining }}"
                                data-stocks="{{ json_encode($stocksData) }}">
                                
                                <td>
                                    <div class="product-cell">
                                        @if($product->photos && count($product->photos) > 0)
                                            <img src="{{ asset('storage/' . $product->photos[0]) }}" 
                                                alt="{{ $product->title }}"
                                                class="product-image">
                                        @else
                                            <div class="product-placeholder">📦</div>
                                        @endif
                                        <div class="product-info">
                                            <h4>{{ $product->title }}</h4>
                                            <p>{{ $product->sku }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="stock-badge initial">
                                        {{ number_format($product->initial_stock) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="stock-badge {{ $totalAllocated > 0 ? 'high' : 'empty' }}">
                                        {{ number_format($totalAllocated) }}
                                    </span>
                                </td>

                                <td class="text-center" style="background: #f0f9ff;">
                                    <span class="stock-badge {{ $remainingClass }}">
                                        {{ number_format($remaining) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if($tokoCount > 0)
                                        <span class="badge bg-success text-white">
                                            <i class="fas fa-store"></i> {{ $tokoCount }} Toko
                                        </span>
                                    @else
                                        <span class="badge bg-secondary text-white">
                                            <i class="fas fa-times"></i> Belum Dialokasikan
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <button type="button" 
                                            class="action-btn detail"
                                            onclick="showStockDetail(this)"
                                            data-product-id="{{ $product->id }}">
                                        <i class="fas fa-eye"></i>
                                        <span>Lihat Detail</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="no-results">
                                        <i class="fas fa-box-open"></i>
                                        <h4>Belum Ada Produk</h4>
                                        <p>Silakan tambah produk terlebih dahulu</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- No Results Message -->
        <div class="no-results" id="noResults" style="display: none;">
            <i class="fas fa-search"></i>
            <h4>Tidak Ada Hasil</h4>
            <p>Tidak ditemukan produk yang sesuai dengan filter Anda</p>
        </div>
    </div>

    {{-- Include Modal Detail --}}
    @include('superadmin.stocks.detail-modal')

    <script>
        // Filter Functions
        function filterStocks() {
            const nameFilter = document.getElementById('filterName').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const sortFilter = document.getElementById('filterSort').value;
            
            const items = Array.from(document.querySelectorAll('.stock-item'));
            const noResults = document.getElementById('noResults');
            const tableContainer = document.querySelector('.table-container');
            
            let visibleCount = 0;

            items.forEach(item => {
                const name = item.getAttribute('data-name');
                const sku = item.getAttribute('data-sku');
                const remaining = parseInt(item.getAttribute('data-remaining'));
                
                const matchName = !nameFilter || name.includes(nameFilter) || sku.includes(nameFilter);
                
                let matchStatus = true;
                if (statusFilter === 'high') matchStatus = remaining > 50;
                else if (statusFilter === 'medium') matchStatus = remaining > 0 && remaining <= 50;
                else if (statusFilter === 'low') matchStatus = remaining === 0;
                
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
                    return parseInt(a.getAttribute('data-remaining')) - parseInt(b.getAttribute('data-remaining'));
                } else if (sortFilter === 'stock_high') {
                    return parseInt(b.getAttribute('data-remaining')) - parseInt(a.getAttribute('data-remaining'));
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
                tableContainer.style.display = 'none';
                noResults.style.display = 'block';
            } else {
                tableContainer.style.display = 'block';
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