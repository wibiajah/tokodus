<!-- Modal Detail Product -->
<div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content" style="border-radius: 25px; border: none; overflow: hidden;">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-2" style="background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);">
                <h5 class="modal-title text-white font-weight-bold">
                    <i class="fas fa-box-open mr-2"></i>Detail Informasi Produk
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9;">
                    <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body p-0" id="modalProductDetailContent">
                <!-- Loading State -->
                <div class="text-center py-5" id="loadingProductState">
                    <div class="spinner-border" style="color: #224abe; width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted font-weight-500">Memuat data produk...</p>
                </div>

                <!-- Content Container -->
                <div id="productContent" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Photo -->
<div class="modal fade" id="productPhotoPreviewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 800px;">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none;">
            <div class="modal-header py-3" style="background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);">
                <h6 class="modal-title mb-0 text-white font-weight-bold">
                    <i class="fas fa-images"></i> Preview Foto Produk
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.9;">
                    <span style="font-size: 1.8rem;">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 position-relative" style="background: #1a1a1a;">
                <div class="photo-container" style="min-height: 400px; max-height: 600px; overflow: auto; display: flex; align-items: center; justify-content: center;">
                    <img id="previewProductImage" src="" alt="Preview" style="width: 100%; height: auto; transition: transform 0.3s ease;">
                </div>
            </div>
            <div class="modal-footer py-3 justify-content-center" style="background: #f8f9fc;">
                <button type="button" class="btn btn-sm px-4" id="productZoomOut" style="background: white; border: 2px solid #e3e6f0; border-radius: 10px; color: #5a5c69;">
                    <i class="fas fa-search-minus"></i> Zoom Out
                </button>
                <button type="button" class="btn btn-sm px-4" id="productResetZoom" style="background: white; border: 2px solid #e3e6f0; border-radius: 10px; color: #5a5c69;">
                    <i class="fas fa-redo-alt"></i> Reset
                </button>
                <button type="button" class="btn btn-sm px-4" id="productZoomIn" style="background: white; border: 2px solid #e3e6f0; border-radius: 10px; color: #5a5c69;">
                    <i class="fas fa-search-plus"></i> Zoom In
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.modal-content {
    box-shadow: 0 15px 50px rgba(34, 74, 190, 0.3);
}

/* Main Layout Grid */
.detail-grid-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    background: white;
}

/* Left Column */
.detail-left-column {
    background: linear-gradient(135deg, #f8f9fc 0%, #eef2f7 100%);
    padding: 30px;
}

/* Right Column */
.detail-right-column {
    padding: 30px;
    background: white;
}

/* Image Gallery */
.detail-image-gallery {
    margin-bottom: 24px;
}

.main-image-container {
    position: relative;
    width: 100%;
    height: 320px;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 16px;
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
    object-fit: cover;
}

.detail-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.detail-placeholder i {
    font-size: 100px;
    color: rgba(255,255,255,0.25);
}

/* Thumbnail Gallery */
.thumbnail-gallery {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding-bottom: 8px;
}

.thumbnail-item {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    border: 3px solid transparent;
    transition: all 0.3s;
    flex-shrink: 0;
}

.thumbnail-item:hover,
.thumbnail-item.active {
    border-color: #224abe;
    transform: scale(1.05);
}

.thumbnail-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
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
    margin: 0 0 8px 0;
    letter-spacing: 0.02em;
}

.product-sku {
    font-family: monospace;
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 600;
}

/* Status & Price Section */
.status-price-section {
    display: flex;
    gap: 12px;
    align-items: center;
    margin-top: 16px;
    flex-wrap: wrap;
}

.status-badge-large {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 0.3px;
}

.status-badge-large.active {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.status-badge-large.inactive {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

/* Price Display */
.price-display {
    background: linear-gradient(135deg, #f8f9fc 0%, #eef2f7 100%);
    padding: 16px 20px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex: 1;
}

.price-label {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
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
}

.discount-percentage {
    display: inline-block;
    background: #dc3545;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    margin-left: 8px;
}

/* Detail Sections */
.detail-section {
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 2px solid #f1f3f5;
}

.detail-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.detail-label {
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    color: #224abe;
    letter-spacing: 1px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.detail-value {
    font-size: 1.05rem;
    color: #2d3748;
    font-weight: 500;
    line-height: 1.6;
}

/* Info Cards */
.info-card {
    background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
    padding: 16px;
    border-radius: 12px;
    border-left: 4px solid #224abe;
    margin-bottom: 12px;
    transition: all 0.2s ease;
}

.info-card:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(34, 74, 190, 0.1);
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #6c757d;
}

.info-value-inline {
    font-size: 0.95rem;
    font-weight: 700;
    color: #2d3748;
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

/* Categories & Tags */
.category-badge, .tag-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    margin: 4px;
}

.category-badge {
    background: #f3e8ff;
    color: #7c3aed;
}

.tag-badge {
    background: #e3f2fd;
    color: #2196f3;
}

/* Timestamps */
.timestamp-info {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #f1f3f5;
}

.timestamp-item {
    flex: 1;
    text-align: center;
    padding: 12px;
    background: #f8f9fc;
    border-radius: 10px;
}

.timestamp-label {
    font-size: 0.7rem;
    font-weight: 700;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.timestamp-value {
    font-size: 0.85rem;
    color: #2d3748;
    font-weight: 600;
}

/* Action Buttons */
.action-button-group {
    display: flex;
    gap: 12px;
    margin-top: 24px;
    padding: 24px 30px;
    background: #f8f9fc;
    border-top: 2px solid #e3e6f0;
}

.modal-action-btn {
    flex: 1;
    padding: 14px 20px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    border: 2px solid;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.modal-action-btn:hover {
    transform: translateY(-3px);
}

.btn-edit {
    background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);
    border-color: #224abe;
    color: white;
    box-shadow: 0 4px 15px rgba(34, 74, 190, 0.3);
}

.btn-edit:hover {
    box-shadow: 0 6px 20px rgba(34, 74, 190, 0.4);
    color: white;
}

.btn-delete {
    background: white;
    border-color: #dc3545;
    color: #dc3545;
}

.btn-delete:hover {
    background: #dc3545;
    color: white;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

/* Responsive */
@media (max-width: 992px) {
    .detail-grid-container {
        grid-template-columns: 1fr;
    }
    
    .detail-left-column {
        border-bottom: 3px solid #e3e6f0;
    }
    
    .timestamp-info {
        flex-direction: column;
    }
}

@media (max-width: 576px) {
    .detail-left-column,
    .detail-right-column {
        padding: 20px;
    }
    
    .product-name-title {
        font-size: 1.2rem;
    }
    
    .action-button-group {
        flex-direction: column;
        padding: 20px;
    }
}
</style>

<script>
let currentProductData = null;
let currentProductZoom = 1;
const productMinZoom = 0.5;
const productMaxZoom = 3;
const productZoomStep = 0.25;

// ✅ SOLUSI FINAL: Tambah fallback jika produk tidak ada di current page
function showProductDetail(productId) {
    // Load data dari current page
    const products = @json($products->items());
    currentProductData = products.find(p => p.id === productId);
    
    // ✅ FALLBACK: Jika produk tidak ada di current page, ambil dari DOM
    if (!currentProductData) {
        console.warn('Product not in current page data, extracting from DOM');
        currentProductData = extractProductFromDOM(productId);
        
        if (!currentProductData) {
            console.error('Product not found:', productId);
            alert('Data produk tidak ditemukan!');
            return;
        }
    }

    $('#productDetailModal').modal('show');
    $('#productContent').hide();
    $('#loadingProductState').show();
    
    setTimeout(() => {
        renderProductDetail(currentProductData);
        $('#loadingProductState').hide();
        $('#productContent').fadeIn();
    }, 300);
}

// ✅ FUNGSI BARU: Extract data dari DOM jika tidak ada di JSON
function extractProductFromDOM(productId) {
    let element;
    
    // Cari di list view
    element = document.querySelector(`#listViewBody tr[onclick*="openProductDetail(${productId})"]`);
    
    // Jika tidak ada, cari di card view
    if (!element) {
        element = document.querySelector(`#cardView .modern-product-card[onclick*="openProductDetail(${productId})"]`);
    }
    
    if (!element) {
        return null;
    }
    
    // Extract basic data dari DOM
    const isCardView = element.classList.contains('modern-product-card');
    
    if (isCardView) {
        // Extract dari card
        const title = element.querySelector('.card-product-title')?.textContent.trim() || '';
        const priceText = element.querySelector('.price-current')?.textContent.replace(/[^\d]/g, '') || '0';
        const stockText = element.querySelector('.stock-badge')?.textContent.match(/\d+/)?.[0] || '0';
        const photoSrc = element.querySelector('.card-image-wrapper img')?.src || '';
        const isActive = element.querySelector('.status-active') !== null;
        
        return {
            id: productId,
            title: title,
            sku: element.getAttribute('data-sku') || '-',
            price: parseInt(priceText),
            discount_price: null,
            initial_stock: 0,
            remaining_stock_cached: parseInt(stockText),
            photos: photoSrc ? [photoSrc.replace(/^.*\/storage\//, '')] : [],
            is_active: isActive,
            categories: [],
            tags: [],
            description: 'Data detail tidak tersedia. Silakan refresh halaman.',
            rating: 0,
            reviews_count: 0,
            stocks: [],
            created_at: null,
            updated_at: null
        };
    } else {
        // Extract dari table row
        const cells = element.querySelectorAll('td');
        const title = cells[1]?.querySelector('.fw-medium')?.textContent.trim() || '';
        const priceText = cells[4]?.textContent.replace(/[^\d]/g, '') || '0';
        const stockText = cells[6]?.textContent.match(/\d+/)?.[0] || '0';
        const photoSrc = cells[0]?.querySelector('img')?.src || '';
        const isActive = cells[8]?.textContent.includes('Aktif');
        
        return {
            id: productId,
            title: title,
            sku: cells[2]?.textContent.trim() || '-',
            price: parseInt(priceText),
            discount_price: null,
            initial_stock: parseInt(cells[5]?.textContent.match(/\d+/)?.[0] || '0'),
            remaining_stock_cached: parseInt(stockText),
            photos: photoSrc ? [photoSrc.replace(/^.*\/storage\//, '')] : [],
            is_active: isActive,
            categories: Array.from(cells[3]?.querySelectorAll('.badge') || []).map(b => ({
                id: 0,
                name: b.textContent.trim()
            })),
            tags: Array.from(cells[1]?.querySelectorAll('.badge') || []).map(b => b.textContent.trim()),
            description: 'Data detail tidak tersedia. Silakan refresh halaman.',
            rating: parseFloat(cells[7]?.textContent.match(/[\d.]+/)?.[0] || '0'),
            reviews_count: parseInt(cells[7]?.textContent.match(/\((\d+)\)/)?.[1] || '0'),
            stocks: [],
            created_at: null,
            updated_at: null
        };
    }
}


function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('id-ID', options);
}

function formatCurrency(value) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
}

// Update fungsi renderProductDetail untuk menampilkan stok per toko

function renderProductDetail(product) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    
    const photos = product.photos || [];
    const mainImage = photos.length > 0 ? `/storage/${photos[0]}` : '';
    const statusClass = product.is_active ? 'active' : 'inactive';
    const statusText = product.is_active ? 'Aktif' : 'Nonaktif';
    const safeName = (product.title || '').replace(/'/g, "\\'");
    
    // Calculate discount
    let discountPercentage = 0;
    if (product.discount_price && product.price) {
        discountPercentage = Math.round(((product.price - product.discount_price) / product.price) * 100);
    }
    
    // 🔥 STOK HEAD OFFICE (Pusat) = initial_stock
    const stokHeadOffice = parseInt(product.initial_stock) || 0;
    
    // 🔥 Hitung stok yang sudah didistribusikan ke toko-toko
    const stocks = product.stocks || [];
    let totalStokTerdistribusi = 0;
    let tokoStockHtml = '';
    
    // Debug log
    console.log('Product:', product.title);
    console.log('Initial Stock:', stokHeadOffice);
    console.log('Stocks:', stocks);
    
    if (stocks.length > 0) {
        stocks.forEach(stockItem => {
            totalStokTerdistribusi += stockItem.stock;
            
            // Tentukan class badge berdasarkan jumlah stok
            let stockBadgeClass = 'stock-high';
            let stockIcon = 'fa-check-circle';
            if (stockItem.stock <= 0) {
                stockBadgeClass = 'stock-low';
                stockIcon = 'fa-times-circle';
            } else if (stockItem.stock <= 10) {
                stockBadgeClass = 'stock-medium';
                stockIcon = 'fa-exclamation-circle';
            }
            
            tokoStockHtml += `
                <div class="info-card mb-2" style="transition: all 0.3s ease;">
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-store mr-2" style="color: #224abe;"></i>
                            <strong>${stockItem.toko ? stockItem.toko.nama_toko : 'Toko Tidak Diketahui'}</strong>
                        </span>
                        <span class="stock-badge ${stockBadgeClass}">
                            <i class="fas ${stockIcon} mr-1"></i>
                            ${stockItem.stock} Unit
                        </span>
                    </div>
                </div>
            `;
        });
    } else {
        tokoStockHtml = `
            <div class="alert alert-warning mb-0" style="border-radius: 12px; border-left: 4px solid #ffc107; background: #fff9e6;">
                <i class="fas fa-info-circle mr-2"></i>
                <span>Stok belum didistribusikan ke toko manapun. Semua stok masih di Head Office.</span>
            </div>
        `;
    }
    
    // 🔥 Sisa stok di Head Office (yang belum didistribusikan)
    // Pastikan kita hitung ulang untuk memastikan data benar
    const sisaStokHeadOffice = Math.max(0, stokHeadOffice - totalStokTerdistribusi);
    
    console.log('Total Terdistribusi:', totalStokTerdistribusi);
    console.log('Sisa di Head Office:', sisaStokHeadOffice);
    
    let headOfficeStockClass = 'stock-high';
    let headOfficeStockText = 'Stok Aman';
    if (sisaStokHeadOffice <= 0) {
        headOfficeStockClass = 'stock-low';
        headOfficeStockText = 'Sudah Habis Terdistribusi';
    } else if (sisaStokHeadOffice <= 50) {
        headOfficeStockClass = 'stock-medium';
        headOfficeStockText = 'Stok Terbatas';
    }
    
    // Thumbnail gallery
    let thumbnailsHtml = '';
    if (photos.length > 1) {
        thumbnailsHtml = '<div class="thumbnail-gallery">';
        photos.forEach((photo, index) => {
            const activeClass = index === 0 ? 'active' : '';
            thumbnailsHtml += `
                <div class="thumbnail-item ${activeClass}" onclick="changeMainImage('/storage/${photo}')">
                    <img src="/storage/${photo}" alt="Photo ${index + 1}">
                </div>
            `;
        });
        thumbnailsHtml += '</div>';
    }
    
    const content = `
        <div class="detail-grid-container">
            <!-- LEFT COLUMN -->
            <div class="detail-left-column">
                <!-- Image Gallery -->
                <div class="detail-image-gallery">
                    <div class="main-image-container" id="mainImageContainer" ${mainImage ? `onclick="openProductPhotoPreview('${mainImage}', '${safeName}')"` : ''}>
                        ${mainImage ? 
                            `<img src="${mainImage}" alt="${safeName}" id="mainProductImage">` :
                            `<div class="detail-placeholder">
                                <i class="fas fa-box-open"></i>
                            </div>`
                        }
                    </div>
                    ${thumbnailsHtml}
                </div>

                <!-- Product Name Card -->
                <div class="product-name-card">
                    <h3 class="product-name-title">${product.title || '-'}</h3>
                    <div class="product-sku">SKU: ${product.sku || '-'}</div>
                    
                    <!-- Status & Price -->
                    <div class="status-price-section">
                        <span class="status-badge-large ${statusClass}">
                            <i class="fas fa-circle"></i>
                            <span>${statusText}</span>
                        </span>
                    </div>
                </div>

                <!-- Price Display -->
                <div class="price-display">
                    <span class="price-label">Harga Produk</span>
                    ${product.discount_price ? `
                        <div>
                            <span class="price-original">${formatCurrency(product.price)}</span>
                            <span class="discount-percentage">-${discountPercentage}%</span>
                        </div>
                        <div class="price-current">${formatCurrency(product.discount_price)}</div>
                    ` : `
                        <div class="price-current">${formatCurrency(product.price)}</div>
                    `}
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="detail-right-column">
                <!-- 🔥 STOCK INFORMATION - REVISI FINAL -->
                <div class="detail-section">
                    <div class="detail-label">
                        <i class="fas fa-warehouse"></i>
                        Informasi Stok Produk
                    </div>
                    
                    <!-- Summary Card -->
                    <div class="info-card" style="background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%); border-left: 4px solid #224abe; margin-bottom: 20px;">
                        <div class="info-row mb-2">
                            <span class="info-label">
                                <i class="fas fa-boxes mr-1" style="color: #224abe;"></i>
                                Stok Awal (Head Office)
                            </span>
                            <span class="info-value-inline" style="color: #224abe; font-size: 1.1rem;">
                                <strong>${stokHeadOffice} Unit</strong>
                            </span>
                        </div>
                        <div class="info-row mb-2">
                            <span class="info-label">
                                <i class="fas fa-truck-loading mr-1" style="color: #28a745;"></i>
                                Sudah Terdistribusi ke Toko
                            </span>
                            <span class="info-value-inline" style="color: #28a745;">
                                <strong>${totalStokTerdistribusi} Unit</strong>
                            </span>
                        </div>
                        <div class="info-row" style="padding-top: 12px; border-top: 2px dashed #e3e6f0;">
                            <span class="info-label">
                                <i class="fas fa-box mr-1" style="color: #ffc107;"></i>
                                Sisa Stok di Head Office
                            </span>
                            <span class="stock-badge ${headOfficeStockClass}">
                                ${sisaStokHeadOffice} Unit
                            </span>
                        </div>
                    </div>

                    <!-- Stok Per Toko -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3" style="padding-bottom: 10px; border-bottom: 2px solid #f1f3f5;">
                            <span class="detail-label mb-0" style="font-size: 0.9rem;">
                                <i class="fas fa-store-alt mr-2"></i>
                                Distribusi Stok per Toko
                            </span>
                            <span class="badge badge-primary" style="border-radius: 20px; padding: 8px 16px; font-size: 0.85rem;">
                                ${stocks.length} Toko
                            </span>
                        </div>
                        ${tokoStockHtml}
                    </div>
                </div>

                <!-- Categories -->
                ${product.categories && product.categories.length > 0 ? `
                    <div class="detail-section">
                        <div class="detail-label">
                            <i class="fas fa-tags"></i>
                            Kategori
                        </div>
                        <div>
                            ${product.categories.map(cat => `
                                <span class="category-badge">${cat.name}</span>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}

                <!-- Tags -->
                ${product.tags && product.tags.length > 0 ? `
                    <div class="detail-section">
                        <div class="detail-label">
                            <i class="fas fa-hashtag"></i>
                            Tags
                        </div>
                        <div>
                            ${product.tags.map(tag => `
                                <span class="tag-badge">${tag}</span>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}

                <!-- Rating & Reviews -->
                <div class="detail-section">
                    <div class="detail-label">
                        <i class="fas fa-star"></i>
                        Rating & Ulasan
                    </div>
                    <div class="info-card">
                        <div class="info-row">
                            <span class="info-label">Rating</span>
                            <span class="info-value-inline">
                                <span class="text-warning">⭐</span> ${product.rating ? parseFloat(product.rating).toFixed(1) : '0.0'} / 5.0
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Jumlah Ulasan</span>
                            <span class="info-value-inline">${product.reviews_count || 0} Ulasan</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                ${product.description ? `
                    <div class="detail-section">
                        <div class="detail-label">
                            <i class="fas fa-align-left"></i>
                            Deskripsi Produk
                        </div>
                        <div class="detail-value">${product.description}</div>
                    </div>
                ` : ''}

                <!-- Timestamps -->
                <div class="timestamp-info">
                    <div class="timestamp-item">
                        <div class="timestamp-label">Dibuat</div>
                        <div class="timestamp-value">
                            <i class="fas fa-calendar-plus" style="font-size: 0.75rem; color: #224abe;"></i>
                            ${formatDate(product.created_at)}
                        </div>
                    </div>
                    <div class="timestamp-item">
                        <div class="timestamp-label">Diperbarui</div>
                        <div class="timestamp-value">
                            <i class="fas fa-calendar-check" style="font-size: 0.75rem; color: #224abe;"></i>
                            ${formatDate(product.updated_at)}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-button-group">
            <a href="/superadmin/products/${product.id}/edit" class="modal-action-btn btn-edit">
                <i class="fas fa-edit"></i> Edit Produk
            </a>
            <button onclick="confirmDeleteProductFromModal(${product.id})" class="modal-action-btn btn-delete">
                <i class="fas fa-trash-alt"></i> Hapus Produk
            </button>
        </div>

        <form id="delete-product-form-modal-${product.id}" action="/superadmin/products/${product.id}" method="POST" style="display: none;">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        </form>
    `;
    
    document.getElementById('productContent').innerHTML = content;
}

// Fungsi helper tetap sama
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('id-ID', options);
}

function formatCurrency(value) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
}

function changeMainImage(imageUrl) {
    const mainImage = document.getElementById('mainProductImage');
    if (mainImage) {
        mainImage.src = imageUrl;
    }
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail-item').forEach(thumb => {
        thumb.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
}

function confirmDeleteProductFromModal(id) {
    if (confirm('⚠️ Apakah Anda yakin ingin menghapus produk ini?\n\nData yang terhapus tidak dapat dikembalikan!')) {
        document.getElementById('delete-product-form-modal-' + id).submit();
    }
}

function openProductPhotoPreview(imageUrl, productName) {
    const previewImage = document.getElementById('previewProductImage');
    previewImage.src = imageUrl;
    previewImage.alt = productName;
    currentProductZoom = 1;
    previewImage.style.transform = `scale(${currentProductZoom})`;
    $('#productPhotoPreviewModal').modal('show');
}

document.getElementById('productZoomIn')?.addEventListener('click', function() {
    if (currentProductZoom < productMaxZoom) {
        currentProductZoom += productZoomStep;
        updateProductZoom();
    }
});

document.getElementById('productZoomOut')?.addEventListener('click', function() {
    if (currentProductZoom > productMinZoom) {
        currentProductZoom -= productZoomStep;
        updateProductZoom();
    }
});

document.getElementById('productResetZoom')?.addEventListener('click', function() {
    currentProductZoom = 1;
    updateProductZoom();
});

function updateProductZoom() {
    const previewImage = document.getElementById('previewProductImage');
    if (previewImage) {
        previewImage.style.transform = `scale(${currentProductZoom})`;
        previewImage.style.cursor = currentProductZoom > 1 ? 'zoom-out' : 'zoom-in';
    }
}

document.getElementById('previewProductImage')?.addEventListener('wheel', function(e) {
    e.preventDefault();
    if (e.deltaY < 0 && currentProductZoom < productMaxZoom) {
        currentProductZoom += productZoomStep;
    } else if (e.deltaY > 0 && currentProductZoom > productMinZoom) {
        currentProductZoom -= productZoomStep;
    }
    updateProductZoom();
});

$('#productPhotoPreviewModal').on('hidden.bs.modal', function () {
    currentProductZoom = 1;
    updateProductZoom();
});
</script>