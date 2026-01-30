<!-- Product Detail Modal -->
<div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content" style="border-radius: 25px; border: none; overflow: hidden; box-shadow: 0 15px 50px rgba(34, 74, 190, 0.3);">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient border-0" style="background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-box-open mr-2"></i>
                    Detail Informasi Produk
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9;">
                    <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
                </button>
            </div>
            
            <div class="modal-body p-0">
                <!-- Loading State -->
                <div id="loadingProductState" class="text-center py-5">
                    <div class="spinner-border" style="color: #224abe; width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only"></span>
                    </div>
                    <p class="mt-3 text-muted font-weight-500">Memuat data produk...</p>
                </div>

                <!-- Product Content -->
                <div id="productContent" style="display: none;">
                    <!-- Main Grid Layout -->
                    <div class="detail-grid-container">
                        <!-- LEFT COLUMN: Images & Basic Info -->
                        <div class="detail-left-column">
                            <!-- Header Section with Title & SKU -->
                            <div class="product-name-card">
                                <h3 class="product-name-title" id="detailTitle"></h3>
                                <div class="d-flex flex-wrap gap-2 align-items-center mt-2">
                                    <span class="badge bg-light text-dark border" id="detailSKU"></span>
                                    <span class="badge" id="detailStatus"></span>
                                    <span class="badge" id="detailTipe"></span>
                                </div>
                            </div>

                            <!-- Image Gallery Section -->
                            <div class="detail-image-gallery mt-3">
                                <!-- Main Image -->
                                <div class="main-image-container mb-3 rounded overflow-hidden" style="background: #f8f9fa;">
                                    <img id="mainProductImage" 
                                         src="" 
                                         alt="Product" 
                                         class="w-100"
                                         style="height: 400px; object-fit: contain;">
                                </div>
                                
                                <!-- Thumbnail Gallery -->
                                <div id="thumbnailGallery" class="thumbnail-gallery"></div>

                                <!-- Video Player -->
                                <div id="videoSection" class="mt-3" style="display: none;">
                                    <video id="productVideo" controls class="w-100 rounded" style="max-height: 300px;"></video>
                                </div>
                            </div>

                            <!-- Price Display Card -->
                            <div class="price-display mt-3">
                                <span class="price-label">Harga Produk</span>
                                <div id="priceContainer"></div>
                            </div>

                            <!-- Variant Stocks Distribution - Moved Here -->
                            <div id="variantStocksSection" class="mt-3" style="display: none;">
                                <div class="detail-label mb-3">
                                    <i class="fas fa-store"></i>
                                    Distribusi Stok per Toko
                                </div>
                                <div class="stock-distribution-container">
                                    <div id="variantStocksTable"></div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: Detailed Information -->
                        <div class="detail-right-column">
                            <!-- Categories & Tags Section -->
                            <div class="detail-section">
                                <div class="detail-label">
                                    <i class="fas fa-tags"></i>
                                    Kategori & Tag
                                </div>
                                <div class="mb-3">
                                    <div class="text-muted small mb-2" style="font-weight: 600;">Kategori:</div>
                                    <div id="detailCategories"></div>
                                </div>
                                <div>
                                    <div class="text-muted small mb-2" style="font-weight: 600;">Tags:</div>
                                    <div id="detailTags"></div>
                                </div>
                            </div>

                            <!-- Product Specifications Grid -->
                            <div class="detail-section">
                                <div class="detail-label">
                                    <i class="fas fa-info-circle"></i>
                                    Spesifikasi Produk
                                </div>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="spec-card">
                                            <div class="spec-icon">
                                                <i class="fas fa-ruler-combined"></i>
                                            </div>
                                            <div class="spec-content">
                                                <div class="spec-label">Ukuran</div>
                                                <div class="spec-value" id="detailUkuran">-</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="spec-card">
                                            <div class="spec-icon">
                                                <i class="fas fa-cube"></i>
                                            </div>
                                            <div class="spec-content">
                                                <div class="spec-label">Jenis Bahan</div>
                                                <div class="spec-value" id="detailBahan">-</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="spec-card">
                                            <div class="spec-icon">
                                                <i class="fas fa-print"></i>
                                            </div>
                                            <div class="spec-content">
                                                <div class="spec-label">Cetak</div>
                                                <div class="spec-value" id="detailCetak">-</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="spec-card">
                                            <div class="spec-icon">
                                                <i class="fas fa-magic"></i>
                                            </div>
                                            <div class="spec-content">
                                                <div class="spec-label">Finishing</div>
                                                <div class="spec-value" id="detailFinishing">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="detail-section">
                                <div class="detail-label">
                                    <i class="fas fa-align-left"></i>
                                    Deskripsi Produk
                                </div>
                                <div id="detailDescription" class="detail-value"></div>
                            </div>

                            <!-- Stock Summary -->
                            <div class="detail-section">
                                <div class="detail-label">
                                    <i class="fas fa-warehouse"></i>
                                    Ringkasan Stok
                                </div>
                                <div class="row g-3">
                                    <div class="col-4">
                                        <div class="text-center p-3 rounded" style="background: #e3f2fd;">
                                            <div class="h4 mb-1 text-primary fw-bold" id="stockPusat">0</div>
                                            <small class="text-muted">Stok Pusat</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-3 rounded" style="background: #e8f5e9;">
                                            <div class="h4 mb-1 text-success fw-bold" id="stockToko">0</div>
                                            <small class="text-muted">Stok Toko</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-3 rounded" style="background: #fff3e0;">
                                            <div class="h4 mb-1 text-warning fw-bold" id="stockTotal">0</div>
                                            <small class="text-muted">Total Stok</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Variants Section -->
                            <div class="detail-section">
                                <div class="detail-label">
                                    <i class="fas fa-palette"></i>
                                    Varian Produk
                                </div>
                                <div id="variantsContainer"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer with Action Buttons -->
            <div class="modal-footer bg-light action-button-group">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
                <a id="btnEditProduct" href="#" class="btn btn-warning">
                    <i class="fas fa-edit mr-1"></i> Edit Produk
                </a>
                <a id="btnDistributeStock" href="#" class="btn btn-success">
                    <i class="fas fa-box mr-1"></i> Distribusi Stok
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================
   MODAL STYLING - REFACTORED UI
   ============================================ */

/* Modal Container */
#productDetailModal .modal-xl {
    max-width: 1200px;
}

.modal-content {
    box-shadow: 0 15px 50px rgba(34, 74, 190, 0.3);
}

/* Grid Layout */
.detail-grid-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    background: white;
}

/* Left Column - Images & Basic Info */
.detail-left-column {
    background: linear-gradient(135deg, #f8f9fc 0%, #eef2f7 100%);
    padding: 30px;
    border-right: 3px solid #e3e6f0;
}

/* Right Column - Detailed Info */
.detail-right-column {
    padding: 30px;
    background: white;
}

/* Product Name Card */
.product-name-card {
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(34, 74, 190, 0.1);
    margin-bottom: 20px;
}

.product-name-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #224abe;
    margin: 0 0 12px 0;
    letter-spacing: 0.02em;
}

/* Image Gallery */
.detail-image-gallery {
    margin-bottom: 20px;
}

.main-image-container {
    position: relative;
    width: 100%;
    height: 400px;
    border-radius: 20px;
    overflow: hidden;
    cursor: pointer;
    box-shadow: 0 10px 30px rgba(34, 74, 190, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.main-image-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(34, 74, 190, 0.25);
}

.main-image-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

/* Thumbnail Gallery */
.thumbnail-gallery {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.thumbnail-item {
    width: 80px;
    height: 80px;
    object-fit: cover;
    cursor: pointer;
    border: 3px solid transparent;
    border-radius: 12px;
    transition: all 0.3s;
    flex-shrink: 0;
}

.thumbnail-item:hover,
.thumbnail-item.active {
    border-color: #224abe;
    transform: scale(1.05);
}

/* Price Display */
.price-display {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
    padding: 20px;
    border-radius: 12px;
    border-left: 4px solid #224abe;
    box-shadow: 0 4px 12px rgba(34, 74, 190, 0.08);
}

.price-label {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
    margin-bottom: 8px;
}

.price-current {
    font-size: 1.75rem;
    font-weight: 800;
    color: #224abe;
}

.price-original {
    font-size: 1rem;
    text-decoration: line-through;
    color: #999;
    margin-right: 8px;
}

.discount-percentage {
    display: inline-block;
    background: #dc3545;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
}

/* Detail Sections */
.detail-section {
    margin-bottom: 28px;
    padding-bottom: 28px;
    border-bottom: 2px solid #f1f3f5;
}

.detail-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.detail-label {
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    color: #224abe;
    letter-spacing: 1px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.detail-label i {
    font-size: 1rem;
}

.detail-value {
    font-size: 1rem;
    color: #2d3748;
    font-weight: 500;
    line-height: 1.6;
    white-space: pre-wrap;
}

/* Specification Cards - Clean & Minimalist */
.spec-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
    height: 100%;
    margin-bottom: 8px;
}

.spec-card:hover {
    border-color: #224abe;
    box-shadow: 0 4px 16px rgba(34, 74, 190, 0.12);
    transform: translateY(-2px);
}

.spec-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.spec-icon i {
    font-size: 20px;
    color: white;
}

.spec-content {
    flex: 1;
    min-width: 0;
}

.spec-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.spec-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    word-wrap: break-word;
}

/* Badges - White Text for Better Readability */
.badge {
    color: #ffffff !important;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 20px;
}

.badge.bg-light {
    background: #6c757d !important;
    color: #ffffff !important;
}

.badge.bg-success {
    background: #28a745 !important;
}

.badge.bg-secondary {
    background: #6c757d !important;
}

.badge.bg-warning {
    background: #ffc107 !important;
    color: #000000 !important;
}

.badge.bg-primary {
    background: #224abe !important;
}

/* Tipe Badge - Black Text Override */
.badge.tipe-badge {
    color: #000000 !important;
    font-weight: 700 !important;
}

.badge.tipe-badge i {
    color: #000000 !important;
}

/* Category & Tag Badges */
.category-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin: 4px;
    background: #7c3aed;
    color: #ffffff;
    transition: all 0.2s;
}

.category-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.tag-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin: 4px;
    background: #2196f3;
    color: #ffffff;
    transition: all 0.2s;
}

.tag-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

/* Variant Cards */
.variant-card {
    border: 2px solid #e9ecef;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s;
    background: white;
}

.variant-card:hover {
    border-color: #224abe;
    box-shadow: 0 6px 20px rgba(34, 74, 190, 0.12);
}

.variant-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f1f3f5;
}

.variant-color-preview {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    object-fit: cover;
    border: 3px solid #e9ecef;
    flex-shrink: 0;
}

.variant-info {
    flex: 1;
}

.variant-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 6px;
}

.variant-stock-info {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    background: #e3f2fd;
    border-radius: 16px;
    font-size: 0.8rem;
    color: #224abe;
    font-weight: 600;
}

.size-section {
    margin-top: 12px;
}

.size-section-title {
    font-size: 0.75rem;
    font-weight: 700;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.size-items {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 10px;
}

.size-chip {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    border-radius: 10px;
    font-size: 0.85rem;
    transition: all 0.2s;
}

.size-chip:hover {
    background: #224abe;
    color: white;
    border-color: #224abe;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 74, 190, 0.2);
}

.size-chip:hover .size-price,
.size-chip:hover .size-stock {
    color: white;
    background: rgba(255, 255, 255, 0.2);
}

.size-photo {
    width: 35px;
    height: 35px;
    border-radius: 6px;
    object-fit: cover;
    border: 2px solid #dee2e6;
    flex-shrink: 0;
}

.size-details {
    display: flex;
    flex-direction: column;
    gap: 3px;
    flex: 1;
    min-width: 0;
}

.size-name {
    font-weight: 700;
    color: #2d3748;
    font-size: 0.9rem;
}

.size-price {
    font-weight: 600;
    color: #224abe;
    font-size: 0.8rem;
}

.size-stock {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 3px 8px;
    background: #224abe;
    color: white;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
}

/* Responsive Grid */
@media (max-width: 768px) {
    .size-items {
        grid-template-columns: 1fr;
    }
}

/* Action Buttons */
.action-button-group {
    display: flex;
    gap: 12px;
    padding: 20px 30px;
    background: #f8f9fc;
    border-top: 2px solid #e3e6f0;
}

.action-button-group .btn {
    flex: 1;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.action-button-group .btn:hover {
    transform: translateY(-2px);
}

/* Stock Badges */
.stock-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
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

/* Stock Distribution - Clean Card Style */
.stock-distribution-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.stock-distribution-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.2s;
}

.stock-distribution-item:hover {
    border-color: #224abe;
    transform: translateX(3px);
    box-shadow: 0 2px 8px rgba(34, 74, 190, 0.1);
}

.stock-toko-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.stock-toko-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stock-toko-icon i {
    color: white;
    font-size: 16px;
}

.stock-toko-details {
    flex: 1;
    min-width: 0;
}

.stock-toko-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 3px;
}

.stock-variant-name {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
}

.stock-amount {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: #28a745;
    color: white;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 700;
    flex-shrink: 0;
}

.stock-amount i {
    font-size: 0.75rem;
}

/* Empty State */
.stock-empty-state {
    text-align: center;
    padding: 30px 20px;
    background: #f8f9fa;
    border-radius: 12px;
    border: 2px dashed #dee2e6;
}

.stock-empty-state i {
    font-size: 40px;
    color: #dee2e6;
    margin-bottom: 12px;
}

.stock-empty-state p {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
/* Responsive Design */
@media (max-width: 1200px) {
    #productDetailModal .modal-xl {
        max-width: 95%;
    }
}

@media (max-width: 992px) {
    .detail-grid-container {
        grid-template-columns: 1fr;
    }
    
    .detail-left-column {
        border-right: none;
        border-bottom: 3px solid #e3e6f0;
    }
    
    .main-image-container {
        height: 350px;
    }
}

@media (max-width: 768px) {
    /* Modal Adjustments */
    .modal-content {
        border-radius: 20px !important;
    }
    
    .modal-header h5 {
        font-size: 1rem;
    }
    
    /* Columns */
    .detail-left-column,
    .detail-right-column {
        padding: 20px;
    }
    
    /* Product Name Card */
    .product-name-card {
        padding: 16px;
    }
    
    .product-name-title {
        font-size: 1.2rem;
    }
    
    /* Images */
    .main-image-container {
        height: 280px;
        border-radius: 15px;
    }
    
    .thumbnail-gallery {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .thumbnail-item {
        width: 70px;
        height: 70px;
    }
    
    /* Spec Cards - Stack Vertically on Tablet */
    .spec-card {
        padding: 16px;
    }
    
    .spec-icon {
        width: 40px;
        height: 40px;
    }
    
    .spec-icon i {
        font-size: 18px;
    }
    
    .spec-value {
        font-size: 1rem;
    }
    
    /* Variants */
    .variant-card {
        padding: 16px;
    }
    
    .variant-color-preview {
        width: 50px;
        height: 50px;
    }
    
    .size-items {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
    
    /* Action Buttons */
    .action-button-group {
        flex-wrap: wrap;
        padding: 15px;
    }
    
    .action-button-group .btn {
        flex: 1 1 calc(50% - 6px);
        min-width: 140px;
    }
}

@media (max-width: 576px) {
    /* Modal Full Height on Small Screens */
    #productDetailModal .modal-dialog {
        margin: 0.5rem;
        max-width: calc(100% - 1rem);
    }
    
    .modal-content {
        border-radius: 15px !important;
        max-height: calc(100vh - 1rem);
    }
    
    /* Header Compact */
    .modal-header {
        padding: 12px 15px;
    }
    
    .modal-header h5 {
        font-size: 0.95rem;
    }
    
    .modal-header .close {
        padding: 0.5rem;
        margin: -0.5rem -0.5rem -0.5rem auto;
    }
    
    /* Columns Ultra Compact */
    .detail-left-column,
    .detail-right-column {
        padding: 15px;
    }
    
    /* Product Name Card */
    .product-name-card {
        padding: 12px;
        margin-bottom: 12px;
    }
    
    .product-name-title {
        font-size: 1.1rem;
        line-height: 1.3;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 5px 10px;
    }
    
    /* Images */
    .main-image-container {
        height: 250px;
        border-radius: 12px;
        margin-bottom: 10px;
    }
    
    .thumbnail-gallery {
        gap: 8px;
    }
    
    .thumbnail-item {
        width: 60px;
        height: 60px;
        border-radius: 8px;
    }
    
    /* Price Display */
    .price-display {
        padding: 14px;
        margin-top: 12px;
    }
    
    .price-label {
        font-size: 0.7rem;
    }
    
    .price-current {
        font-size: 1.4rem;
    }
    
    .price-original {
        font-size: 0.9rem;
    }
    
    /* Detail Sections */
    .detail-section {
        margin-bottom: 20px;
        padding-bottom: 20px;
    }
    
    .detail-label {
        font-size: 0.7rem;
        margin-bottom: 12px;
    }
    
    .detail-label i {
        font-size: 0.9rem;
    }
    
    .detail-value {
        font-size: 0.95rem;
    }
    
    /* Spec Cards - Single Column */
    .spec-card {
        padding: 12px;
        margin-bottom: 8px;
    }
    
    .spec-icon {
        width: 36px;
        height: 36px;
    }
    
    .spec-icon i {
        font-size: 16px;
    }
    
    .spec-label {
        font-size: 0.7rem;
        margin-bottom: 6px;
    }
    
    .spec-value {
        font-size: 0.95rem;
    }
    
    /* Stock Summary - Adjust */
    .detail-section .row .col-4 > div {
        padding: 10px 8px;
    }
    
    .detail-section .row .col-4 h4 {
        font-size: 1.3rem;
    }
    
    .detail-section .row .col-4 small {
        font-size: 0.7rem;
    }
    
    /* Variants - Full Width */
    .variant-card {
        padding: 12px;
        margin-bottom: 15px;
    }
    
    .variant-header {
        margin-bottom: 12px;
        padding-bottom: 10px;
    }
    
    .variant-color-preview {
        width: 45px;
        height: 45px;
    }
    
    .variant-name {
        font-size: 1rem;
    }
    
    .variant-stock-info {
        font-size: 0.75rem;
        padding: 4px 10px;
    }
    
    .size-section-title {
        font-size: 0.7rem;
        margin-bottom: 8px;
    }
    
    .size-items {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .size-chip {
        padding: 10px 12px;
        font-size: 0.85rem;
    }
    
    .size-photo {
        width: 32px;
        height: 32px;
    }
    
    .size-name {
        font-size: 0.85rem;
    }
    
    .size-price {
        font-size: 0.75rem;
    }
    
    .size-stock {
        font-size: 0.7rem;
        padding: 3px 8px;
    }
    
    /* Stock Distribution */
    .stock-distribution-item {
        padding: 12px;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .stock-toko-info {
        width: 100%;
    }
    
    .stock-toko-icon {
        width: 36px;
        height: 36px;
    }
    
    .stock-toko-icon i {
        font-size: 14px;
    }
    
    .stock-toko-name {
        font-size: 0.9rem;
    }
    
    .stock-variant-name {
        font-size: 0.75rem;
    }
    
    .stock-amount {
        align-self: flex-end;
        font-size: 0.8rem;
        padding: 5px 12px;
    }
    
    /* Category & Tag Badges */
    .category-badge,
    .tag-badge {
        font-size: 0.75rem;
        padding: 6px 12px;
        margin: 3px;
    }
    
    /* Action Buttons - Stack Vertically */
    .action-button-group {
        flex-direction: column;
        padding: 12px;
        gap: 8px;
    }
    
    .action-button-group .btn {
        width: 100%;
        padding: 10px 16px;
        font-size: 0.9rem;
    }
}

/* Landscape Mobile (iPhone SE, etc) */
@media (max-width: 667px) and (orientation: landscape) {
    .modal-content {
        max-height: 95vh;
    }
    
    .main-image-container {
        height: 200px;
    }
    
    .detail-left-column,
    .detail-right-column {
        padding: 12px;
    }
}

/* Small Android Phones (320px-375px) */
@media (max-width: 375px) {
    .modal-header h5 {
        font-size: 0.85rem;
    }
    
    .product-name-title {
        font-size: 1rem;
    }
    
    .main-image-container {
        height: 220px;
    }
    
    .price-current {
        font-size: 1.2rem;
    }
    
    .spec-card {
        padding: 10px;
        gap: 10px;
    }
    
    .size-chip {
        padding: 8px 10px;
    }
    
    .action-button-group .btn {
        padding: 9px 14px;
        font-size: 0.85rem;
    }
}

/* iOS Safe Area (iPhone X and newer) */
@supports (padding: max(0px)) {
    .modal-content {
        padding-bottom: max(0px, env(safe-area-inset-bottom));
    }
    
    .action-button-group {
        padding-bottom: max(12px, calc(12px + env(safe-area-inset-bottom)));
    }
}

/* Smooth Scrolling */
.modal-body {
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
}

/* Fix for iOS Zoom on Input Focus */
@media (max-width: 576px) {
    input[type="text"],
    input[type="number"],
    textarea,
    select {
        font-size: 16px !important;
    }
}
</style>

<script>
// ============================================
// ORIGINAL JAVASCRIPT - TIDAK DIUBAH
// ============================================

function renderProductDetail(product) {
    // Basic Info
    document.getElementById('detailTitle').textContent = product.title;
    document.getElementById('detailSKU').innerHTML = `<i class="fas fa-barcode me-1"></i> ${product.sku}`;
    
    // Status Badge
    const statusBadge = document.getElementById('detailStatus');
    if (product.is_active) {
        statusBadge.className = 'badge bg-success';
        statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Aktif';
    } else {
        statusBadge.className = 'badge bg-secondary';
        statusBadge.innerHTML = '<i class="fas fa-times-circle mr-1"></i> Nonaktif';
    }
    
    // Tipe Badge
    const tipeBadge = document.getElementById('detailTipe');
    if (product.tipe === 'innerbox') {
        tipeBadge.className = 'badge tipe-badge';
        tipeBadge.style.background = '#e3f2fd';
        tipeBadge.style.color = '#000000';
        tipeBadge.style.fontWeight = '700';
        tipeBadge.innerHTML = '<i class="fas fa-box mr-1"></i> Inner Box';
    } else if (product.tipe === 'masterbox') {
        tipeBadge.className = 'badge tipe-badge';
        tipeBadge.style.background = '#f3e5f5';
        tipeBadge.style.color = '#000000';
        tipeBadge.style.fontWeight = '700';
        tipeBadge.innerHTML = '<i class="fas fa-boxes mr-1"></i> Master Box';
    } else {
        tipeBadge.style.display = 'none';
    }
    
    // Price
    const priceContainer = document.getElementById('priceContainer');
    
    if (product.discount_price) {
        const discount = Math.round(((product.price - product.discount_price) / product.price) * 100);
        priceContainer.innerHTML = `
            <div>
                <span class="price-original">Rp ${formatNumber(product.price)}</span>
                <span class="discount-percentage">-${discount}%</span>
            </div>
            <div class="price-current">Rp ${formatNumber(product.discount_price)}</div>
        `;
    } else {
        priceContainer.innerHTML = `<div class="price-current">Rp ${formatNumber(product.price)}</div>`;
    }
    
    // Images
    const mainImage = document.getElementById('mainProductImage');
    const thumbnailGallery = document.getElementById('thumbnailGallery');
    
    if (product.photos && product.photos.length > 0) {
        mainImage.src = `/storage/${product.photos[0]}`;
        
        thumbnailGallery.innerHTML = '';
        product.photos.forEach((photo, index) => {
            const thumb = document.createElement('img');
            thumb.src = `/storage/${photo}`;
            thumb.className = `thumbnail-item ${index === 0 ? 'active' : ''}`;
            thumb.onclick = () => {
                mainImage.src = `/storage/${photo}`;
                document.querySelectorAll('.thumbnail-item').forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
            };
            thumbnailGallery.appendChild(thumb);
        });
    } else {
        mainImage.src = '/images/no-image.png';
        thumbnailGallery.innerHTML = '<p class="text-muted small">Tidak ada foto</p>';
    }
    
    // Video
    const videoSection = document.getElementById('videoSection');
    const productVideo = document.getElementById('productVideo');
    
    if (product.video) {
        videoSection.style.display = 'block';
        productVideo.src = `/storage/${product.video}`;
    } else {
        videoSection.style.display = 'none';
    }
    
    // Categories
    const categoriesEl = document.getElementById('detailCategories');
    if (product.categories && product.categories.length > 0) {
        categoriesEl.innerHTML = product.categories.map(cat => 
            `<span class="category-badge">${cat.name}</span>`
        ).join('');
    } else {
        categoriesEl.innerHTML = '<span class="text-muted small">Tidak ada kategori</span>';
    }
    
    // Tags
    const tagsEl = document.getElementById('detailTags');
    if (product.tags && product.tags.length > 0) {
        tagsEl.innerHTML = product.tags.map(tag => 
            `<span class="tag-badge">${tag}</span>`
        ).join('');
    } else {
        tagsEl.innerHTML = '<span class="text-muted small">Tidak ada tag</span>';
    }
    
    // Product Info
    document.getElementById('detailUkuran').textContent = product.ukuran || '-';
    document.getElementById('detailBahan').textContent = product.jenis_bahan || '-';
    document.getElementById('detailCetak').textContent = product.cetak || '-';
    document.getElementById('detailFinishing').textContent = product.finishing || '-';
    
    // Description
    document.getElementById('detailDescription').textContent = product.description || 'Tidak ada deskripsi';
    
    // Stock Summary
    document.getElementById('stockPusat').textContent = formatNumber(product.stock_pusat || 0);
    document.getElementById('stockToko').textContent = formatNumber(product.stock_toko || 0);
    document.getElementById('stockTotal').textContent = formatNumber(product.total_stock || 0);
    
    // Variants
    renderVariants(product.variants);
    
    // Variant Stocks
    if (product.variantStocks && product.variantStocks.length > 0) {
        renderVariantStocks(product.variantStocks);
    }
    
    // Action Buttons
    document.getElementById('btnEditProduct').href = `/superadmin/products/${product.id}/edit`;
    document.getElementById('btnDistributeStock').href = `/superadmin/products/${product.id}/stock/distribute`;
}

function renderVariants(variants) {
    const container = document.getElementById('variantsContainer');
    
    if (!variants || variants.length === 0) {
        container.innerHTML = '<p class="text-muted">Tidak ada varian</p>';
        return;
    }
    
    container.innerHTML = variants.map(variant => {
        let html = `
            <div class="variant-card">
                <div class="variant-header">
        `;
        
        // Variant Photo
        if (variant.photo) {
            html += `<img src="/storage/${variant.photo}" class="variant-color-preview" alt="${variant.name}">`;
        } else {
            html += `<div class="variant-color-preview d-flex align-items-center justify-content-center bg-light" style="border: 3px dashed #dee2e6;">
                        <i class="fas fa-palette text-muted" style="font-size: 20px;"></i>
                    </div>`;
        }
        
        html += `
                    <div class="variant-info">
                        <div class="variant-name">${variant.name}</div>
                        <span class="variant-stock-info">
                            <i class="fas fa-box"></i>
                            <span>Stok: <strong>${formatNumber(variant.stock_pusat || 0)}</strong></span>
                        </span>
                    </div>
                </div>
        `;
        
        // Children (Sizes)
        if (variant.children && variant.children.length > 0) {
            html += `
                <div class="size-section">
                    <div class="size-section-title">
                        <i class="fas fa-ruler"></i>
                        <span>Ukuran Tersedia</span>
                    </div>
                    <div class="size-items">
            `;
            
            variant.children.forEach(size => {
                html += `
                    <div class="size-chip">
                        ${size.photo ? 
                            `<img src="/storage/${size.photo}" class="size-photo" alt="${size.name}">` : 
                            `<div class="size-photo d-flex align-items-center justify-content-center bg-light" style="border: 2px dashed #dee2e6;">
                                <i class="fas fa-image text-muted" style="font-size: 12px;"></i>
                            </div>`
                        }
                        <div class="size-details">
                            <span class="size-name">${size.name}</span>
                            <span class="size-price">Rp ${formatNumber(size.price)}</span>
                        </div>
                        <span class="size-stock">${size.stock_pusat}</span>
                    </div>
                `;
            });
            
            html += '</div></div>';
        } else if (variant.price) {
            html += `
                <div class="text-muted small mt-2">
                    <i class="fas fa-tag me-1"></i> 
                    Harga: <strong class="text-primary">Rp ${formatNumber(variant.price)}</strong>
                </div>
            `;
        }
        
        html += `
            </div>
        `;
        
        return html;
    }).join('');
}

function renderVariantStocks(variantStocks) {
    const container = document.getElementById('variantStocksTable');
    const section = document.getElementById('variantStocksSection');
    
    section.style.display = 'block';
    
    if (!variantStocks || variantStocks.length === 0) {
        container.innerHTML = `
            <div class="stock-empty-state">
                <i class="fas fa-box-open"></i>
                <p>Belum ada distribusi stok ke toko</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = variantStocks.map(vs => {
        // Fix: Ambil nama toko dengan benar
        const tokoName = vs.toko?.nama_toko || vs.toko?.name || 'Toko Tidak Diketahui';
        const variantName = vs.variant?.name || 'Varian Tidak Diketahui';
        const stock = vs.stock || 0;
        
        return `
            <div class="stock-distribution-item">
                <div class="stock-toko-info">
                    <div class="stock-toko-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="stock-toko-details">
                        <div class="stock-toko-name">${tokoName}</div>
                        <div class="stock-variant-name">
                            <i class="fas fa-palette"></i> ${variantName}
                        </div>
                    </div>
                </div>
                <div class="stock-amount">
                    <i class="fas fa-box"></i>
                    <span>${stock} Unit</span>
                </div>
            </div>
        `;
    }).join('');
}

function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}
</script>