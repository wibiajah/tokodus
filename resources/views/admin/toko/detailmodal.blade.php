<!-- Modal Detail Toko -->
<div class="modal fade" id="tokoDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content" style="border-radius: 25px; border: none; overflow: hidden;">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-2" style="background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);">
                <h5 class="modal-title text-white font-weight-bold">
                    <i class="fas fa-store-alt mr-2"></i>Detail Informasi Toko
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9;">
                    <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body p-0" id="modalDetailContent">
                <!-- Loading State -->
                <div class="text-center py-5" id="loadingState">
                    <div class="spinner-border" style="color: #224abe; width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted font-weight-500">Memuat data toko...</p>
                </div>

                <!-- Content Container (will be filled dynamically) -->
                <div id="tokoContent" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Foto -->
<div class="modal fade" id="photoPreviewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 800px;">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none;">
            <div class="modal-header py-3" style="background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);">
                <h6 class="modal-title mb-0 text-white font-weight-bold">
                    <i class="fas fa-images"></i> Preview Foto Toko
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.9;">
                    <span style="font-size: 1.8rem;">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 position-relative" style="background: #1a1a1a;">
                <div class="photo-container" style="min-height: 400px; max-height: 600px; overflow: auto; display: flex; align-items: center; justify-content: center;">
                    <img id="previewImage" src="" alt="Preview" style="width: 100%; height: auto; transition: transform 0.3s ease;">
                </div>
            </div>
            <div class="modal-footer py-3 justify-content-center" style="background: #f8f9fc;">
                <button type="button" class="btn btn-sm px-4" id="zoomOut" style="background: white; border: 2px solid #e3e6f0; border-radius: 10px; color: #5a5c69;">
                    <i class="fas fa-search-minus"></i> Zoom Out
                </button>
                <button type="button" class="btn btn-sm px-4" id="resetZoom" style="background: white; border: 2px solid #e3e6f0; border-radius: 10px; color: #5a5c69;">
                    <i class="fas fa-redo-alt"></i> Reset
                </button>
                <button type="button" class="btn btn-sm px-4" id="zoomIn" style="background: white; border: 2px solid #e3e6f0; border-radius: 10px; color: #5a5c69;">
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

.modal-xl {
    max-width: 1100px;
}

/* Main Layout Grid */
.detail-grid-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    background: white;
}

/* Left Column - Image & Basic Info */
.detail-left-column {
    background: linear-gradient(135deg, #f8f9fc 0%, #eef2f7 100%);
    padding: 30px;
}

/* Right Column - Details */
.detail-right-column {
    padding: 30px;
    background: white;
}

/* Image Container */
.detail-image-container {
    position: relative;
    width: 100%;
    height: 320px;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 24px;
    cursor: pointer;
    box-shadow: 0 10px 30px rgba(34, 74, 190, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.detail-image-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(34, 74, 190, 0.25);
}

.detail-image-container img {
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

/* Store Name Card */
.store-name-card {
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(34, 74, 190, 0.1);
    margin-bottom: 20px;
    text-align: center;
}

.store-name-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #224abe;
    margin: 0;
    letter-spacing: 0.02em;
}

/* Status Badge */
.status-badge-large {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 24px;
    border-radius: 30px;
    font-size: 0.95rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    margin-top: 12px;
    transition: all 0.2s ease;
}

.status-badge-large:hover {
    transform: scale(1.05);
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

.status-badge-large i {
    font-size: 8px;
}

/* Google Maps Container - PENTING! */
.maps-container {
    width: 100%;
    height: 250px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(34, 74, 190, 0.1);
    margin-bottom: 20px;
    position: relative;
    background: #f8f9fc;
}

.maps-container iframe {
    width: 100%;
    height: 100%;
    border: none;
    display: block;
}

/* Placeholder ketika tidak ada peta */
.no-map-placeholder {
    width: 100%;
    height: 250px;
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    margin-bottom: 20px;
    box-shadow: 0 5px 20px rgba(34, 74, 190, 0.1);
}

.no-map-placeholder i {
    font-size: 50px;
    margin-bottom: 12px;
    opacity: 0.5;
}

.no-map-placeholder small {
    font-size: 0.9rem;
    font-weight: 600;
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

.detail-label i {
    font-size: 0.85rem;
}

.detail-value {
    font-size: 1.05rem;
    color: #2d3748;
    font-weight: 500;
    line-height: 1.6;
    display: flex;
    align-items: center;
    gap: 10px;
}

.detail-value i {
    color: #224abe;
    font-size: 1.1rem;
}

.detail-value a {
    color: #224abe;
    text-decoration: none;
    transition: color 0.2s ease;
}

.detail-value a:hover {
    color: #1a3a9e;
    text-decoration: underline;
}

/* Kepala Toko Section */
.kepala-toko-section {
    background: linear-gradient(135deg, #f8f9fc 0%, #eef2f7 100%);
    padding: 24px;
    border-radius: 16px;
    margin-bottom: 24px;
    border: 2px solid #e3e6f0;
}

.info-card-kepala {
    background: white;
    padding: 18px 20px;
    border-radius: 12px;
    border: 2px solid #e3e6f0;
    transition: all 0.3s ease;
}

.info-card-kepala:hover {
    border-color: #224abe;
    box-shadow: 0 4px 15px rgba(34, 74, 190, 0.1);
}

.kepala-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 2px;
}

.kepala-role {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge-small {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.status-badge-small.active {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.status-badge-small.inactive {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
}

.status-badge-small i {
    font-size: 10px;
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

/* Photo Preview Modal */
#photoPreviewModal .photo-container {
    display: flex;
    align-items: center;
    justify-content: center;
}

#previewImage {
    transform-origin: center center;
    max-width: 100%;
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
    
    .store-name-title {
        font-size: 1.4rem;
    }
    
    .action-button-group {
        flex-direction: column;
        padding: 20px;
    }
    
    .maps-container,
    .no-map-placeholder {
        height: 200px;
    }
}
</style>

<script>
// Global variable to store toko data
let currentTokoData = null;

// Function to show toko detail
function showTokoDetail(tokoId) {
    const tokos = window.tokosData || [];
    currentTokoData = tokos.find(t => t.id === tokoId);
    
    if (!currentTokoData) {
        console.error('Toko not found:', tokoId);
        alert('Data toko tidak ditemukan!');
        return;
    }

    $('#tokoDetailModal').modal('show');
    $('#tokoContent').hide();
    $('#loadingState').show();
    
    setTimeout(() => {
        renderTokoDetail(currentTokoData);
        $('#loadingState').hide();
        $('#tokoContent').fadeIn();
    }, 300);
}

// Function to format phone number for WhatsApp
function formatPhoneForWhatsApp(phone) {
    return phone.replace(/[^0-9]/g, '');
}

// Function to format date
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

// Function to render toko detail
function renderTokoDetail(toko) {
    const csrfToken = window.csrfToken || '';
    
    const imageSrc = toko.foto ? `/storage/${toko.foto}` : '';
    const statusClass = toko.status === 'aktif' ? 'active' : 'inactive';
    const statusText = toko.status === 'aktif' ? 'Aktif' : 'Tidak Aktif';
    const safeNamaToko = (toko.nama_toko || '').replace(/'/g, "\\'");
    
    // ========================================
    // LOGIKA SEDERHANA UNTUK MENAMPILKAN PETA
    // ========================================
    let mapsSection = '';
    
    // CEK: Apakah ada iframe di database?
    if (toko.googlemap_iframe && toko.googlemap_iframe.trim() !== '') {
        // ✅ ADA IFRAME → TAMPILKAN GAMBAR PETA INTERAKTIF
        mapsSection = `
            <div class="maps-container">
                ${toko.googlemap_iframe}
            </div>
        `;
    } else {
        // ❌ TIDAK ADA IFRAME → TAMPILKAN PLACEHOLDER
        mapsSection = `
            <div class="no-map-placeholder">
                <i class="fas fa-map-marker-alt"></i>
                <small>Peta belum ditambahkan</small>
                ${toko.googlemap ? `
                    <a href="${toko.googlemap}" target="_blank" class="btn btn-sm btn-outline-primary mt-2" style="border-radius: 8px; padding: 6px 16px;">
                        <i class="fas fa-external-link-alt"></i> Buka di Google Maps
                    </a>
                ` : ''}
            </div>
        `;
    }
    
    const content = `
        <div class="detail-grid-container">
            <!-- LEFT COLUMN -->
            <div class="detail-left-column">
                <!-- Image -->
                <div class="detail-image-container" ${imageSrc ? `onclick="openPhotoPreview('${imageSrc}', '${safeNamaToko}')"` : ''}>
                    ${imageSrc ? 
                        `<img src="${imageSrc}" alt="${safeNamaToko}">` :
                        `<div class="detail-placeholder">
                            <i class="fas fa-store-alt"></i>
                        </div>`
                    }
                </div>

                <!-- Store Name Card -->
                <div class="store-name-card">
                    <h3 class="store-name-title">${toko.nama_toko || '-'}</h3>
                    <form action="/superadmin/toko/${toko.id}/toggle-status" method="POST" style="display: inline;">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <button type="submit" class="status-badge-large ${statusClass}" style="border: none; cursor: pointer;">
                            <i class="fas fa-circle"></i>
                            <span>${statusText}</span>
                        </button>
                    </form>
                </div>

                <!-- Google Maps Section -->
                ${mapsSection}
            </div>

            <!-- RIGHT COLUMN -->
            <div class="detail-right-column">
                <!-- Kepala Toko Section -->
                <div class="kepala-toko-section">
                    <div class="detail-label">
                        <i class="fas fa-user-tie"></i>
                        Kepala Toko
                    </div>
                    <div class="info-card-kepala">
                        ${toko.kepala_toko ? `
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="kepala-name">${toko.kepala_toko.name || '-'}</div>
                                    <div class="kepala-role">Kepala Toko</div>
                                </div>
                                <div class="status-badge-small active">
                                    <i class="fas fa-check-circle"></i> Aktif
                                </div>
                            </div>
                        ` : `
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="kepala-name text-muted">Tidak Ada Kepala Toko</div>
                                    <div class="kepala-role">Belum ditentukan</div>
                                </div>
                                <div class="status-badge-small inactive">
                                    <i class="fas fa-times-circle"></i> Kosong
                                </div>
                            </div>
                        `}
                    </div>
                    <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">
                        <i class="fas fa-info-circle"></i> Untuk mengubah kepala toko, silakan ke menu <strong>Manajemen User</strong>
                    </small>
                </div>

                <!-- Contact Information -->
                ${toko.telepon || toko.email ? `
                    <div class="detail-section">
                        <div class="detail-label">
                            <i class="fas fa-address-card"></i>
                            Informasi Kontak
                        </div>
                        ${toko.telepon ? `
                            <div class="info-card">
                                <div class="detail-value">
                                    <i class="fas fa-phone-alt"></i>
                                    <div>
                                        <div style="font-size: 0.75rem; color: #6c757d; margin-bottom: 2px;">Telepon</div>
                                        <a href="https://wa.me/${formatPhoneForWhatsApp(toko.telepon)}" target="_blank">
                                            ${toko.telepon}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        ` : ''}
                        ${toko.email ? `
                            <div class="info-card">
                                <div class="detail-value">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <div style="font-size: 0.75rem; color: #6c757d; margin-bottom: 2px;">Email</div>
                                        <a href="mailto:${toko.email}">${toko.email}</a>
                                    </div>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                ` : ''}

                <!-- Address -->
                ${toko.alamat ? `
                    <div class="detail-section">
                        <div class="detail-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Alamat Lengkap
                        </div>
                        <div class="info-card">
                            <div class="detail-value">
                                <i class="fas fa-location-arrow"></i>
                                <div>${toko.alamat}</div>
                            </div>
                        </div>
                        ${toko.googlemap ? `
                            <a href="${toko.googlemap}" target="_blank" class="btn btn-sm btn-outline-primary mt-2" style="border-radius: 8px;">
                                <i class="fas fa-directions"></i> Buka di Google Maps
                            </a>
                        ` : ''}
                    </div>
                ` : ''}

                <!-- Timestamps -->
                <div class="timestamp-info">
                    <div class="timestamp-item">
                        <div class="timestamp-label">Dibuat</div>
                        <div class="timestamp-value">
                            <i class="fas fa-calendar-plus" style="font-size: 0.75rem; color: #224abe;"></i>
                            ${formatDate(toko.created_at)}
                        </div>
                    </div>
                    <div class="timestamp-item">
                        <div class="timestamp-label">Diperbarui</div>
                        <div class="timestamp-value">
                            <i class="fas fa-calendar-check" style="font-size: 0.75rem; color: #224abe;"></i>
                            ${formatDate(toko.updated_at)}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-button-group">
            <a href="/superadmin/toko/${toko.id}/edit" class="modal-action-btn btn-edit">
                <i class="fas fa-edit"></i> Edit Toko
            </a>
            <button onclick="confirmDeleteFromModal(${toko.id})" class="modal-action-btn btn-delete">
                <i class="fas fa-trash-alt"></i> Hapus Toko
            </button>
        </div>

        <form id="delete-form-modal-${toko.id}" action="/superadmin/toko/${toko.id}" method="POST" style="display: none;">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        </form>
    `;
    
    document.getElementById('tokoContent').innerHTML = content;
}

// Delete confirmation from modal
function confirmDeleteFromModal(id) {
    if (confirm('⚠️ Apakah Anda yakin ingin menghapus toko ini?\n\nData yang terhapus tidak dapat dikembalikan!')) {
        document.getElementById('delete-form-modal-' + id).submit();
    }
}

// Photo preview functions
let currentZoom = 1;
const minZoom = 0.5;
const maxZoom = 3;
const zoomStep = 0.25;

function openPhotoPreview(imageUrl, storeName) {
    const previewImage = document.getElementById('previewImage');
    previewImage.src = imageUrl;
    previewImage.alt = storeName;
    currentZoom = 1;
    previewImage.style.transform = `scale(${currentZoom})`;
    $('#photoPreviewModal').modal('show');
}

document.getElementById('zoomIn').addEventListener('click', function() {
    if (currentZoom < maxZoom) {
        currentZoom += zoomStep;
        updateZoom();
    }
});

document.getElementById('zoomOut').addEventListener('click', function() {
    if (currentZoom > minZoom) {
        currentZoom -= zoomStep;
        updateZoom();
    }
});

document.getElementById('resetZoom').addEventListener('click', function() {
    currentZoom = 1;
    updateZoom();
});

function updateZoom() {
    const previewImage = document.getElementById('previewImage');
    previewImage.style.transform = `scale(${currentZoom})`;
    previewImage.style.cursor = currentZoom > 1 ? 'zoom-out' : 'zoom-in';
}

// Zoom with mouse wheel
document.getElementById('previewImage').addEventListener('wheel', function(e) {
    e.preventDefault();
    if (e.deltaY < 0 && currentZoom < maxZoom) {
        currentZoom += zoomStep;
    } else if (e.deltaY > 0 && currentZoom > minZoom) {
        currentZoom -= zoomStep;
    }
    updateZoom();
});

// Reset zoom when modal is closed
$('#photoPreviewModal').on('hidden.bs.modal', function () {
    currentZoom = 1;
    updateZoom();
});
</script>