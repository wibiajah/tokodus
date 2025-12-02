<!-- Modal Detail User -->
<div class="modal fade" id="userDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content" style="border-radius: 25px; border: none; overflow: hidden;">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-2" style="background: #224abe;">
                <h5 class="modal-title text-white font-weight-bold">
                    <i class="fas fa-user-circle mr-2"></i>Detail Informasi User
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
                    <p class="mt-3 text-muted font-weight-500">Memuat data user...</p>
                </div>

                <!-- Content Container (will be filled dynamically) -->
                <div id="userContent" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Foto -->
<div class="modal fade" id="photoPreviewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 800px;">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none;">
            <div class="modal-header py-3" style="background: #224abe;">
                <h6 class="modal-title mb-0 text-white font-weight-bold">
                    <i class="fas fa-images"></i> Preview Foto Profil
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
    background: #224abe;
    display: flex;
    align-items: center;
    justify-content: center;
}

.detail-placeholder i {
    font-size: 100px;
    color: rgba(255,255,255,0.25);
}

/* User Name Card */
.user-name-card {
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(34, 74, 190, 0.1);
    margin-bottom: 20px;
    text-align: center;
}

.user-name-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #224abe;
    margin: 0;
    letter-spacing: 0.02em;
}

/* Role Badge */
.role-badge-large {
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
    color: white;
}

.role-badge-large:hover {
    transform: scale(1.05);
}

.role-badge-large.super-admin {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.role-badge-large.admin {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.role-badge-large.kepala-toko {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.role-badge-large.staff-admin {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
}

.role-badge-large i {
    font-size: 8px;
}

/* Toko Section */
.toko-section {
    background: linear-gradient(135deg, #f8f9fc 0%, #eef2f7 100%);
    padding: 24px;
    border-radius: 16px;
    margin-bottom: 24px;
    border: 2px solid #e3e6f0;
}

.info-card-toko {
    background: white;
    padding: 18px 20px;
    border-radius: 12px;
    border: 2px solid #e3e6f0;
    transition: all 0.3s ease;
}

.info-card-toko:hover {
    border-color: #224abe;
    box-shadow: 0 4px 15px rgba(34, 74, 190, 0.1);
}

.toko-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 2px;
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
    color: #1a3a99;
    text-decoration: underline;
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

/* Contact Actions */
.contact-actions {
    display: flex;
    gap: 12px;
    margin-top: 12px;
}

.contact-btn {
    flex: 1;
    padding: 10px 16px;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid;
}

.contact-btn:hover {
    transform: translateY(-2px);
    text-decoration: none;
}

.contact-btn.email-btn {
    background: white;
    border-color: #224abe;
    color: #224abe;
}

.contact-btn.email-btn:hover {
    background: #224abe;
    color: white;
    box-shadow: 0 4px 15px rgba(34, 74, 190, 0.3);
}

.contact-btn.wa-btn {
    background: white;
    border-color: #25D366;
    color: #25D366;
}

.contact-btn.wa-btn:hover {
    background: #25D366;
    color: white;
    box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
}

.contact-btn.disabled-btn {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
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
    background: #224abe;
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
    
    .user-name-title {
        font-size: 1.4rem;
    }
    
    .action-button-group {
        flex-direction: column;
        padding: 20px;
    }
    
    .contact-actions {
        flex-direction: column;
    }
}
</style>

<script>
// Global variable to store user data
let currentUserData = null;

// Function to show user detail
function showUserDetail(userId) {
    const users = window.usersData || [];
    currentUserData = users.find(u => u.id === userId);
    
    if (!currentUserData) {
        console.error('User not found:', userId);
        alert('Data user tidak ditemukan!');
        return;
    }

    $('#userDetailModal').modal('show');
    $('#userContent').hide();
    $('#loadingState').show();
    
    setTimeout(() => {
        renderUserDetail(currentUserData);
        $('#loadingState').hide();
        $('#userContent').fadeIn();
    }, 300);
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

// Function to get role display name
function getRoleDisplayName(role) {
    const roles = {
        'super_admin': 'Super Admin',
        'admin': 'Admin',
        'kepala_toko': 'Kepala Toko',
        'staff_admin': 'Staff Admin'
    };
    return roles[role] || role;
}

// Function to get role class
function getRoleClass(role) {
    const roleClasses = {
        'super_admin': 'super-admin',
        'admin': 'admin',
        'kepala_toko': 'kepala-toko',
        'staff_admin': 'staff-admin'
    };
    return roleClasses[role] || '';
}

// Function to format phone for WhatsApp
function formatPhoneForWA(phone) {
    if (!phone) return '';
    const cleanPhone = phone.replace(/[^0-9]/g, '');
    return (cleanPhone.startsWith('0')) ? '62' + cleanPhone.substring(1) : cleanPhone;
}

// Function to render user detail
function renderUserDetail(user) {
    const csrfToken = window.csrfToken || '';
    
    // Cek foto_profil dari database
    const imageSrc = user.foto_profil ? `/storage/${user.foto_profil}` : '';
    const safeUserName = (user.name || '').replace(/'/g, "\\'");
    const roleClass = getRoleClass(user.role);
    const roleDisplay = getRoleDisplayName(user.role);
    const waPhone = formatPhoneForWA(user.no_telepon);
    
    const content = `
        <div class="detail-grid-container">
            <!-- LEFT COLUMN -->
            <div class="detail-left-column">
                <!-- Image -->
                <div class="detail-image-container" ${imageSrc ? `onclick="openPhotoPreview('${imageSrc}', '${safeUserName}')"` : ''}>
                    ${imageSrc ? 
                        `<img src="${imageSrc}" alt="${safeUserName}">` :
                        `<div class="detail-placeholder">
                            <i class="fas fa-user"></i>
                        </div>`
                    }
                </div>

                <!-- User Name Card -->
                <div class="user-name-card">
                    <h3 class="user-name-title">${user.name || '-'}</h3>
                    <span class="role-badge-large ${roleClass}">
                        <i class="fas fa-circle"></i>
                        <span>${roleDisplay}</span>
                    </span>
                </div>

                <!-- Toko Section -->
                <div class="toko-section">
                    <div class="detail-label">
                        <i class="fas fa-store"></i>
                        Penempatan Toko
                    </div>
                    <div class="info-card-toko">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="toko-name">${user.toko ? user.toko.nama_toko : 'Head Office'}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="detail-right-column">
                <!-- Contact Information -->
                <div class="detail-section">
                    <div class="detail-label">
                        <i class="fas fa-address-card"></i>
                        Informasi Kontak
                    </div>
                    
                    <!-- Email -->
                    <div class="info-card">
                        <div class="detail-value">
                            <i class="fas fa-envelope"></i>
                            <div style="flex: 1;">
                                <div style="font-size: 0.75rem; color: #6c757d; margin-bottom: 2px;">Email</div>
                                <a href="mailto:${user.email}">${user.email}</a>
                            </div>
                        </div>
                    </div>

                    <!-- No. Telepon -->
                    ${user.no_telepon ? `
                        <div class="info-card">
                            <div class="detail-value">
                                <i class="fas fa-phone"></i>
                                <div style="flex: 1;">
                                    <div style="font-size: 0.75rem; color: #6c757d; margin-bottom: 2px;">No. Telepon</div>
                                    <div>${user.formatted_no_telepon || user.no_telepon}</div>
                                </div>
                            </div>
                        </div>
                    ` : `
                        <div class="info-card" style="opacity: 0.6;">
                            <div class="detail-value">
                                <i class="fas fa-phone"></i>
                                <div style="flex: 1;">
                                    <div style="font-size: 0.75rem; color: #6c757d; margin-bottom: 2px;">No. Telepon</div>
                                    <div style="font-style: italic; color: #999;">Tidak tersedia</div>
                                </div>
                            </div>
                        </div>
                    `}

                    <!-- Contact Actions -->
                    <div class="contact-actions">
                        <a href="mailto:${user.email}" class="contact-btn email-btn">
                            <i class="fas fa-envelope"></i>
                            <span>Kirim Email</span>
                        </a>
                        ${user.no_telepon ? `
                            <a href="https://wa.me/${waPhone}" target="_blank" class="contact-btn wa-btn">
                                <i class="fab fa-whatsapp"></i>
                                <span>Chat WA</span>
                            </a>
                        ` : `
                            <span class="contact-btn wa-btn disabled-btn">
                                <i class="fab fa-whatsapp"></i>
                                <span>Chat WA</span>
                            </span>
                        `}
                    </div>
                </div>

                <!-- Role Information -->
                <div class="detail-section">
                    <div class="detail-label">
                        <i class="fas fa-user-tag"></i>
                        Role & Akses
                    </div>
                    <div class="info-card">
                        <div class="detail-value">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <div style="font-size: 0.75rem; color: #6c757d; margin-bottom: 2px;">Role Sistem</div>
                                <strong>${roleDisplay}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                ${user.toko ? `
                    <!-- Toko Details -->
                    <div class="detail-section">
                        <div class="detail-label">
                            <i class="fas fa-store-alt"></i>
                            Informasi Toko
                        </div>
                        <div class="info-card">
                            <div class="detail-value">
                                <i class="fas fa-building"></i>
                                <div>
                                    <div style="font-size: 0.75rem; color: #6c757d; margin-bottom: 2px;">Nama Toko</div>
                                    <div>${user.toko.nama_toko}</div>
                                </div>
                            </div>
                        </div>
                        ${user.toko.alamat ? `
                            <div class="info-card">
                                <div class="detail-value">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <div style="font-size: 0.75rem; color: #6c757d; margin-bottom: 2px;">Alamat Toko</div>
                                        <div>${user.toko.alamat}</div>
                                    </div>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                ` : ''}

                <!-- Timestamps -->
                <div class="timestamp-info">
                    <div class="timestamp-item">
                        <div class="timestamp-label">Dibuat</div>
                        <div class="timestamp-value">
                            <i class="fas fa-calendar-plus" style="font-size: 0.75rem; color: #224abe;"></i>
                            ${formatDate(user.created_at)}
                        </div>
                    </div>
                    <div class="timestamp-item">
                        <div class="timestamp-label">Diperbarui</div>
                        <div class="timestamp-value">
                            <i class="fas fa-calendar-check" style="font-size: 0.75rem; color: #224abe;"></i>
                            ${formatDate(user.updated_at)}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-button-group">
            <a href="/superadmin/user/${user.id}/edit" class="modal-action-btn btn-edit">
                <i class="fas fa-edit"></i> Edit User
            </a>
            ${user.id !== {{ auth()->id() }} ? `
                <button onclick="confirmDeleteFromModal(${user.id})" class="modal-action-btn btn-delete">
                    <i class="fas fa-trash-alt"></i> Hapus User
                </button>
            ` : ''}
        </div>

        <form id="delete-form-modal-${user.id}" action="/superadmin/user/${user.id}" method="POST" style="display: none;">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        </form>
    `;
    
    document.getElementById('userContent').innerHTML = content;
}

// Delete confirmation from modal
function confirmDeleteFromModal(id) {
    if (confirm('⚠️ Apakah Anda yakin ingin menghapus user ini?\n\nData yang terhapus tidak dapat dikembalikan!')) {
        document.getElementById('delete-form-modal-' + id).submit();
    }
}

// Photo preview functions
let currentZoom = 1;
const minZoom = 0.5;
const maxZoom = 3;
const zoomStep = 0.25;

function openPhotoPreview(imageUrl, userName) {
    const previewImage = document.getElementById('previewImage');
    previewImage.src = imageUrl;
    previewImage.alt = userName;
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