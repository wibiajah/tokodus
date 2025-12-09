{{-- Modal Detail Stok Per Produk --}}
<div class="modal fade" id="stockDetailModal" tabindex="-1" aria-labelledby="stockDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #224abe 0%, #1e3a8a 100%); color: white;">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-chart-pie fa-2x"></i>
                    <div>
                        <h5 class="modal-title mb-0" id="stockDetailModalLabel">Detail Distribusi Stok</h5>
                        <small class="opacity-75">Informasi lengkap alokasi stok per toko</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <!-- Product Info Header -->
                <div class="product-detail-header mb-4 p-4" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 16px; border-left: 5px solid #224abe;">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <img id="modalProductImage" src="" alt="Product" class="img-fluid rounded shadow-sm d-none" style="max-height: 120px; object-fit: cover;">
                            <div id="modalProductPlaceholder" class="d-none" style="width: 120px; height: 120px; margin: 0 auto; background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 48px;">📦</div>
                        </div>
                        <div class="col-md-10">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <h3 class="mb-1" id="modalProductTitle" style="color: #1e3a8a; font-weight: 800;">-</h3>
                                    <p class="text-muted mb-0" style="font-family: monospace; font-size: 14px;">
                                        <i class="fas fa-barcode"></i> SKU: <strong id="modalProductSKU">-</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock Summary Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="summary-box" style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 48px; height: 48px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                    📦
                                </div>
                                <div>
                                    <small class="text-uppercase text-muted d-block mb-1" style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px;">Stok Awal</small>
                                    <h4 class="mb-0" style="color: #1e40af; font-weight: 800;" id="modalInitialStock">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="summary-box" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 48px; height: 48px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                    ✅
                                </div>
                                <div>
                                    <small class="text-uppercase text-muted d-block mb-1" style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px;">Total Dialokasikan</small>
                                    <h4 class="mb-0" style="color: #065f46; font-weight: 800;" id="modalTotalAllocated">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="summary-box" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 48px; height: 48px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                    📊
                                </div>
                                <div>
                                    <small class="text-uppercase text-muted d-block mb-1" style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px;">Sisa Stok</small>
                                    <h4 class="mb-0" style="color: #92400e; font-weight: 800;" id="modalRemainingStock">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribution Table -->
                <div class="distribution-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0" style="color: #224abe; font-weight: 700;">
                            <i class="fas fa-store-alt"></i> Distribusi Per Toko
                        </h5>
                        <span class="badge bg-primary" id="modalTokoCount">0 Toko</span>
                    </div>

                    <div class="table-responsive" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: linear-gradient(135deg, #f8f9fc 0%, #eef2f7 100%);">
                                <tr>
                                    <th style="font-weight: 700; color: #224abe; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 16px;">
                                        <i class="fas fa-store"></i> Nama Toko
                                    </th>
                                    <th class="text-center" style="font-weight: 700; color: #224abe; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 16px;">
                                        <i class="fas fa-boxes"></i> Jumlah Stok
                                    </th>
                                    <th class="text-center" style="font-weight: 700; color: #224abe; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 16px;">
                                        <i class="fas fa-percentage "></i> Persentase
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="modalStockTableBody">
                                <!-- Data will be populated via JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div id="modalEmptyState" class="text-center py-5 d-none">
                        <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                        <h5 class="text-muted">Belum Ada Distribusi</h5>
                        <p class="text-muted mb-0">Produk ini belum dialokasikan ke toko manapun</p>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background: #f8f9fc; border-top: 2px solid #e5e7eb;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal Custom Styles */
    .modal-xl {
        max-width: 1200px;
    }

    #stockDetailModal .table tbody tr {
        transition: all 0.2s;
    }

    #stockDetailModal .table tbody tr:hover {
        background: #f0fdf4;
        transform: translateX(5px);
    }

    #stockDetailModal .stock-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        display: inline-block;
        min-width: 60px;
        text-align: center;
    }

    #stockDetailModal .stock-badge.high {
        background: #d1fae5;
        color: #065f46;
    }

    #stockDetailModal .stock-badge.medium {
        background: #fef3c7;
        color: #92400e;
    }

    #stockDetailModal .stock-badge.low {
        background: #fee2e2;
        color: #991b1b;
    }

    #stockDetailModal .stock-badge.empty {
        background: #f3f4f6;
        color: #6b7280;
    }

    /* Animation for modal */
    @keyframes slideInUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    #stockDetailModal.show .modal-content {
        animation: slideInUp 0.3s ease-out;
    }
</style>

<script>
    // Function to show stock detail (called from button in table row)
    function showStockDetail(button) {
        // Get data from the row
        const row = button.closest('tr');
        
        // Extract data from data attributes
        const productId = row.getAttribute('data-product-id');
        const productTitle = row.getAttribute('data-product-title');
        const productSku = row.getAttribute('data-product-sku');
        const productPhoto = row.getAttribute('data-product-photo');
        const initialStock = parseInt(row.getAttribute('data-initial-stock'));
        const totalAllocated = parseInt(row.getAttribute('data-total-allocated'));
        const remainingStock = parseInt(row.getAttribute('data-remaining-stock'));
        const stocksData = JSON.parse(row.getAttribute('data-stocks'));
        
        // Update product info
        document.getElementById('modalProductTitle').textContent = productTitle;
        document.getElementById('modalProductSKU').textContent = productSku;
        
        // Update product image
        const imgElement = document.getElementById('modalProductImage');
        const placeholderElement = document.getElementById('modalProductPlaceholder');
        
        if (productPhoto) {
            imgElement.src = productPhoto;
            imgElement.classList.remove('d-none');
            placeholderElement.classList.add('d-none');
        } else {
            imgElement.classList.add('d-none');
            placeholderElement.classList.remove('d-none');
        }
        
        // Update summary numbers
        document.getElementById('modalInitialStock').textContent = initialStock.toLocaleString('id-ID');
        document.getElementById('modalTotalAllocated').textContent = totalAllocated.toLocaleString('id-ID');
        document.getElementById('modalRemainingStock').textContent = remainingStock.toLocaleString('id-ID');
        document.getElementById('modalTokoCount').textContent = `${stocksData.length} Toko`;
        
        // Update stock distribution table
        const tbody = document.getElementById('modalStockTableBody');
        const emptyState = document.getElementById('modalEmptyState');
        tbody.innerHTML = '';
        
        if (stocksData.length === 0) {
            emptyState.classList.remove('d-none');
        } else {
            emptyState.classList.add('d-none');
            
            stocksData.forEach(stock => {
                const badgeClass = stock.stock > 10 ? 'high' : (stock.stock > 0 ? 'medium' : 'empty');
                const statusBadge = stock.toko_status === 'aktif' 
                    ? '<span class="badge bg-success ms-2 text-white" style="font-size: 10px;">Aktif</span>' 
                    : '<span class="badge bg-secondary ms-2 text-white" style="font-size: 10px;">Tidak Aktif</span>';
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td style="padding: 16px;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-store text-primary"></i>
                            <strong>${stock.toko_name}</strong>
                            ${statusBadge}
                        </div>
                    </td>
                    <td class="text-center" style="padding: 16px;">
                        <span class="stock-badge ${badgeClass}">
                            ${stock.stock.toLocaleString('id-ID')} unit
                        </span>
                    </td>
                    <td class="text-center" style="padding: 16px;">
                        <span class="badge bg-info text-white" style="font-size: 13px; padding: 6px 12px;">
                            ${stock.percentage}%
                        </span>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('stockDetailModal'));
        modal.show();
    }
</script>