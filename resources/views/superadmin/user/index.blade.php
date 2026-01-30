<x-admin-layout title="Manajemen User">
    <style>
   /* ===========================
   MODERN USER CARD STYLES - SCOPED
=========================== */
.user-management-page .users-header {
    background: #224abe;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    color: white;
    box-shadow: 0 10px 30px rgba(34, 74, 190, 0.3);
}

.user-management-page .users-header h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 10px 0;
}

.user-management-page .users-header p {
    margin: 0;
    opacity: 0.9;
}

/* Filter Section */
.user-management-page .filter-section {
    background: white;
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
}

.user-management-page .filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.user-management-page .filter-item {
    display: flex;
    flex-direction: column;
}

.user-management-page .filter-label {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.user-management-page .filter-label i {
    margin-right: 8px;
    color: #224abe;
}

.user-management-page .filter-input {
    padding: 12px 16px;
    border: 2px solid #e0e6ed;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s;
}

.user-management-page .filter-input:focus {
    outline: none;
    border-color: #224abe;
    box-shadow: 0 0 0 3px rgba(34, 74, 190, 0.1);
}

.user-management-page .filter-stats {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 15px;
    border-top: 2px solid #f0f0f0;
}

.user-management-page .filter-result {
    font-size: 14px;
    color: #666;
    font-weight: 500;
}

.user-management-page .filter-result strong {
    color: #224abe;
    font-size: 18px;
}

.user-management-page .btn-reset-filter {
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

.user-management-page .btn-reset-filter:hover {
    background: #224abe;
    border-color: #224abe;
    color: white;
}

.user-management-page .btn-add-user {
    background: #224abe;
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
    box-shadow: 0 4px 15px rgba(34, 74, 190, 0.4);
    text-decoration: none;
}

.user-management-page .btn-add-user:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(34, 74, 190, 0.5);
    color: white;
    text-decoration: none;
}

.user-management-page .no-results {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.user-management-page .no-results i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.3;
}

.user-management-page .no-results h4 {
    font-size: 20px;
    font-weight: 600;
    color: #666;
    margin-bottom: 10px;
}

.user-management-page .no-results p {
    font-size: 14px;
    color: #999;
}

/* ===========================
   DYNAMIC GRID SYSTEM - ZOOM AWARE
=========================== */
#userCardsContainer {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 24px;
    width: 100%;
    margin: 0;
}

.user-card-item {
    width: 100%;
    padding: 0;
    margin: 0;
}

/* Modern User Card Design - DESKTOP */
.modern-user-card {
    width: 100%;
    aspect-ratio: 5/6;
    min-height: 240px;
    max-height: 310px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 20px -8px rgba(34, 74, 190, 0.15);
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.modern-user-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px -10px rgba(34, 74, 190, 0.3);
}

/* Image Container with Curved Bottom-Right Corner */
.card-image-wrapper {
    height: 100%;
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    background-color: #E9D5FF;
    border-bottom-right-radius: 60px;
    overflow: hidden;
}

.card-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    user-select: none;
}

.card-image-wrapper::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(34, 74, 190, 0.1);
    mix-blend-mode: overlay;
    z-index: 1;
}

.card-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #224abe;
}

.card-image-placeholder i {
    font-size: 42px;
    color: rgba(255, 255, 255, 0.3);
}

/* Role Badge Overlay */
.card-role-overlay {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 10px;
    font-weight: 600;
    backdrop-filter: blur(10px);
    z-index: 10;
    letter-spacing: 0.5px;
    color: white;
}

.role-super-admin {
    background: rgba(220, 53, 69, 0.95);
}

.role-admin {
    background: rgba(40, 167, 69, 0.95);
}

.role-kepala-toko {
    background: rgba(0, 123, 255, 0.95);
}

.role-staff-admin {
    background: rgba(255, 193, 7, 0.95);
}

/* Sliding White Panel */
.card-slide-panel {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #FFFFFF;
    border-radius: 20px;
    padding: 14px 16px 16px;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 15;
    transform: translateY(calc(100% - 85px));
}

.modern-user-card:hover .card-slide-panel {
    transform: translateY(calc(100% - 160px));
}

/* Title Section - Always Visible */
.card-title-section {
    margin-bottom: 0;
}

.card-user-title {
    color: #2d3748;
    font-weight: 800;
    font-size: 13px;
    letter-spacing: 0.02em;
    line-height: 1.3;
    margin: 0 0 6px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Divider Line */
.card-divider {
    width: 100%;
    height: 1px;
    background: #e2e8f0;
    margin-bottom: 12px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modern-user-card:hover .card-divider {
    opacity: 1;
}

/* Hidden Info Section */
.card-hidden-info {
    margin-bottom: 0;
    opacity: 0;
    max-height: 0;
    overflow: hidden;
    transition: opacity 0.3s ease 0.2s, max-height 0.4s ease;
}

.modern-user-card:hover .card-hidden-info {
    opacity: 1;
    max-height: 100px;
}

.info-row {
    display: flex;
    flex-direction: column;
    margin-bottom: 8px;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-label {
    font-size: 9px;
    font-weight: 600;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 3px;
}

.info-value {
    font-size: 11px;
    font-weight: 500;
    color: #4A5568;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Quick Actions Icons */
.card-quick-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
    padding-top: 0;
    border-top: none;
    margin-bottom: 12px;
}

.quick-action-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 50%;
    color: #224abe;
    transition: all 0.3s ease;
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.quick-action-icon:hover {
    background: #224abe;
    color: white;
    transform: scale(1.1);
    text-decoration: none;
}

.quick-action-icon.whatsapp-icon:hover {
    background: #25D366;
    color: white;
}

.quick-action-icon.delete-icon:hover {
    background: #dc3545;
    color: white;
}

.quick-action-icon i {
    font-size: 0.9rem;
}

/* ===========================
   MOBILE SIMPLE CARD DESIGN
=========================== */
.mobile-simple-card {
    display: none; /* Hidden on desktop */
}

/* Responsive Breakpoints */
@media (min-width: 1600px) {
    #userCardsContainer {
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    }
}

@media (min-width: 1400px) and (max-width: 1599px) {
    #userCardsContainer {
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }
}

@media (min-width: 1200px) and (max-width: 1399px) {
    #userCardsContainer {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }
}

@media (min-width: 992px) and (max-width: 1199px) {
    #userCardsContainer {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
    }
    
    .modern-user-card {
        min-height: 240px;
    }
}

/* ===========================
   TABLET MODE (768px - 991px)
=========================== */
@media (min-width: 768px) and (max-width: 991px) {
    #userCardsContainer {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
    }
    
    .modern-user-card {
        min-height: 230px;
    }
    
    .user-management-page .filter-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .user-management-page .users-header {
        padding: 20px;
    }
    
    .user-management-page .users-header h1 {
        font-size: 22px;
    }
}

/* ===========================
   MOBILE MODE - SIMPLE CARD (< 768px)
=========================== */
@media (max-width: 767px) {
    /* Hide desktop card, show mobile card */
    .modern-user-card {
        display: none !important;
    }
    
    .mobile-simple-card {
        display: block !important;
    }
    
    /* Grid 2 kolom untuk mobile */
    #userCardsContainer {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    /* Mobile Simple Card Style */
    .mobile-simple-card {
        background: white;
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.3s ease;
        -webkit-tap-highlight-color: transparent;
    }
    
    .mobile-simple-card:active {
        transform: scale(0.97);
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
    }
    
    .mobile-card-image {
        width: 100%;
        aspect-ratio: 1/1;
        border-radius: 10px;
        overflow: hidden;
        background: #224abe;
        margin-bottom: 10px;
        position: relative;
    }
    
    .mobile-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .mobile-card-image .placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .mobile-card-image .placeholder i {
        font-size: 32px;
        color: rgba(255, 255, 255, 0.4);
    }
    
    .mobile-role-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 8px;
        font-weight: 700;
        color: white;
        backdrop-filter: blur(5px);
        text-transform: uppercase;
    }
    
    .mobile-card-content {
        text-align: center;
    }
    
    .mobile-card-name {
        font-size: 12px;
        font-weight: 700;
        color: #2d3748;
        margin: 0 0 4px 0;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 30px;
    }
    
    .mobile-card-toko {
        font-size: 10px;
        color: #718096;
        margin: 0 0 8px 0;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .mobile-card-actions {
        display: flex;
        gap: 6px;
        justify-content: center;
    }
    
    .mobile-action-btn {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        border-radius: 50%;
        color: #224abe;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        -webkit-tap-highlight-color: transparent;
    }
    
    .mobile-action-btn:active {
        transform: scale(0.9);
    }
    
    .mobile-action-btn.whatsapp {
        color: #25D366;
    }
    
    .mobile-action-btn.delete {
        color: #dc3545;
    }
    
    .mobile-action-btn i {
        font-size: 12px;
    }
    
    /* Header Mobile */
    .user-management-page .users-header {
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 16px;
    }
    
    .user-management-page .users-header h1 {
        font-size: 18px;
        margin-bottom: 4px;
    }
    
    .user-management-page .users-header p {
        font-size: 12px;
    }
    
    .user-management-page .btn-add-user {
        padding: 10px 16px;
        font-size: 12px;
        border-radius: 8px;
        gap: 6px;
    }
    
    .user-management-page .btn-add-user i {
        font-size: 12px;
    }
    
    /* Filter Mobile */
    .user-management-page .filter-section {
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 16px;
    }
    
    .user-management-page .filter-grid {
        grid-template-columns: 1fr;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .user-management-page .filter-label {
        font-size: 12px;
    }
    
    .user-management-page .filter-input {
        padding: 10px 12px;
        font-size: 13px;
        border-radius: 8px;
    }
    
    .user-management-page .filter-stats {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
    }
    
    .user-management-page .filter-result {
        font-size: 12px;
        text-align: center;
    }
    
    .user-management-page .btn-reset-filter {
        width: 100%;
        padding: 10px;
        font-size: 12px;
    }
    
    /* No Results Mobile */
    .user-management-page .no-results {
        padding: 40px 16px;
    }
    
    .user-management-page .no-results i {
        font-size: 48px;
    }
    
    .user-management-page .no-results h4 {
        font-size: 16px;
    }
    
    .user-management-page .no-results p {
        font-size: 12px;
    }
}

/* Extra Small Mobile (< 400px) */
@media (max-width: 399px) {
    #userCardsContainer {
        gap: 10px;
    }
    
    .mobile-simple-card {
        padding: 10px;
        border-radius: 10px;
    }
    
    .mobile-card-image {
        margin-bottom: 8px;
        border-radius: 8px;
    }
    
    .mobile-card-name {
        font-size: 11px;
        min-height: 28px;
    }
    
    .mobile-card-toko {
        font-size: 9px;
        margin-bottom: 6px;
    }
    
    .mobile-action-btn {
        width: 26px;
        height: 26px;
    }
    
    .mobile-action-btn i {
        font-size: 11px;
    }
}
    </style>

    <div class="container-fluid user-management-page">
        <!-- Header -->
        <div class="users-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-users-cog"></i> Manajemen User</h1>
                    <p>Kelola semua user dan hak akses sistem</p>
                </div>
                <a href="{{ route('superadmin.user.create') }}" class="btn-add-user">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah User</span>
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-grid">
                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-search"></i>
                        Cari Nama User
                    </label>
                    <input type="text" id="filterName" class="filter-input" placeholder="Ketik nama user...">
                </div>

                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-user-tag"></i>
                        Filter Role
                    </label>
                    <select id="filterRole" class="filter-input">
                        <option value="">Semua Role</option>
                        <option value="super_admin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="kepala_toko">Kepala Toko</option>
                        <option value="staff_admin">Staff Admin</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-store"></i>
                        Filter Toko
                    </label>
                    <select id="filterToko" class="filter-input">
                        <option value="">Semua Toko</option>
                        @foreach($users->pluck('toko')->filter()->unique('id') as $toko)
                            <option value="{{ strtolower($toko->nama_toko) }}">{{ $toko->nama_toko }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-sort"></i>
                        Urutkan Tanggal
                    </label>
                    <select id="filterDate" class="filter-input">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                    </select>
                </div>
            </div>

            <div class="filter-stats">
                <div class="filter-result">
                    Menampilkan <strong id="resultCount">{{ count($users) }}</strong> dari <strong>{{ count($users) }}</strong> user
                </div>
                <button class="btn-reset-filter" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Reset Filter
                </button>
            </div>
        </div>

        <!-- Cards Grid -->
        <div id="userCardsContainer">
            @forelse($users as $user)
                <div class="user-card-item" 
                     data-name="{{ strtolower($user->name) }}" 
                     data-role="{{ $user->role }}"
                     data-toko="{{ strtolower($user->toko?->nama_toko ?? '') }}"
                     data-created="{{ $user->created_at->timestamp }}">
                    
                    <!-- DESKTOP CARD -->
                    <div class="modern-user-card" onclick="openUserDetailModal({{ $user->id }})">
                        <!-- Image Container with Curved Corner -->
                        <div class="card-image-wrapper">
                            @if($user->foto_profil)
                                <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->name }}">
                            @else
                                <div class="card-image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            
                            <!-- Role Badge Overlay -->
                            <div class="card-role-overlay role-{{ str_replace('_', '-', $user->role) }}">
                                @if($user->role === 'super_admin')
                                    ● Super Admin
                                @elseif($user->role === 'admin')
                                    ● Admin
                                @elseif($user->role === 'kepala_toko')
                                    ● Kepala Toko
                                @else
                                    ● Staff Admin
                                @endif
                            </div>
                        </div>
                        
                        <!-- Sliding White Content Panel -->
                        <div class="card-slide-panel">
                            <!-- Title Section - Always Visible -->
                            <div class="card-title-section">
                                <h6 class="card-user-title">{{ Str::upper($user->name) }}</h6>
                            </div>
                            
                            <!-- Divider Line -->
                            <div class="card-divider"></div>
                            
                            <!-- Hidden Info - Shows on Hover -->
                            <div class="card-hidden-info">
                                <div class="info-row">
                                    <span class="info-label">Penempatan</span>
                                    <span class="info-value">{{ $user->toko?->nama_toko ?? 'Head Office' }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">No. Telepon</span>
                                    <span class="info-value">{{ $user->no_telepon ? $user->formatted_no_telepon : 'Tidak tersedia' }}</span>
                                </div>
                            </div>
                            
                            <!-- Quick Actions Icons -->
                            <div class="card-quick-actions">
                                <!-- Email Icon -->
                                <a href="mailto:{{ $user->email }}" 
                                   class="quick-action-icon"
                                   onclick="event.stopPropagation()"
                                   title="Kirim Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                
                                <!-- WhatsApp Icon -->
                                @if($user->no_telepon)
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $user->no_telepon);
                                        $waPhone = (strpos($cleanPhone, '0') === 0) ? '62' . substr($cleanPhone, 1) : $cleanPhone;
                                    @endphp
                                    <a href="https://wa.me/{{ $waPhone }}" 
                                       target="_blank"
                                       class="quick-action-icon whatsapp-icon"
                                       onclick="event.stopPropagation()"
                                       title="Chat WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @else
                                    <span class="quick-action-icon" 
                                          style="opacity: 0.3; cursor: not-allowed;"
                                          title="No. Telepon tidak tersedia">
                                        <i class="fab fa-whatsapp"></i>
                                    </span>
                                @endif
                                
                                <!-- Delete Icon -->
                                @if($user->id !== auth()->id())
                                    <button type="button"
                                       class="quick-action-icon delete-icon"
                                       onclick="event.stopPropagation(); confirmDeleteQuick({{ $user->id }})"
                                       title="Hapus User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <span class="quick-action-icon" 
                                          style="opacity: 0.3; cursor: not-allowed;"
                                          title="Tidak bisa hapus akun sendiri">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- MOBILE SIMPLE CARD -->
                    <div class="mobile-simple-card" onclick="openUserDetailModal({{ $user->id }})">
                        <div class="mobile-card-image">
                            @if($user->foto_profil)
                                <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->name }}">
                            @else
                                <div class="placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            
                            <div class="mobile-role-badge role-{{ str_replace('_', '-', $user->role) }}">
                                @if($user->role === 'super_admin')
                                    SA
                                @elseif($user->role === 'admin')
                                    ADM
                                @elseif($user->role === 'kepala_toko')
                                    KT
                                @else
                                    ST
                                @endif
                            </div>
                        </div>
                        
                        <div class="mobile-card-content">
                            <h6 class="mobile-card-name">{{ Str::upper($user->name) }}</h6>
                            <p class="mobile-card-toko">{{ $user->toko?->nama_toko ?? 'Head Office' }}</p>
                            
                            <div class="mobile-card-actions">
                                <a href="mailto:{{ $user->email }}" 
                                   class="mobile-action-btn"
                                   onclick="event.stopPropagation()">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                
                                @if($user->no_telepon)
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $user->no_telepon);
                                        $waPhone = (strpos($cleanPhone, '0') === 0) ? '62' . substr($cleanPhone, 1) : $cleanPhone;
                                    @endphp
                                    <a href="https://wa.me/{{ $waPhone }}" 
                                       target="_blank"
                                       class="mobile-action-btn whatsapp"
                                       onclick="event.stopPropagation()">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                                
                                @if($user->id !== auth()->id())
                                    <button type="button"
                                       class="mobile-action-btn delete"
                                       onclick="event.stopPropagation(); confirmDeleteQuick({{ $user->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="no-results">
                        <i class="fas fa-users-slash"></i>
                        <h4>Belum Ada Data User</h4>
                        <p>Silakan tambah user baru dengan klik tombol di atas</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- No Results Message (Hidden by default) -->
        <div class="no-results" id="noResults" style="display: none;">
            <i class="fas fa-search"></i>
            <h4>Tidak Ada Hasil</h4>
            <p>Tidak ditemukan user yang sesuai dengan filter Anda</p>
        </div>
    </div>

    <!-- Include Modal -->
    @include('superadmin.user.detailmodal', ['superadmin.users' => $users])

    <script>
        // ===========================
        // DATA & INITIALIZATION
        // ===========================
        window.usersData = @json($users);
        window.csrfToken = '{{ csrf_token() }}';

        // ===========================
        // ZOOM DETECTION & DYNAMIC GRID
        // ===========================
        (function() {
            'use strict';
            
            let currentZoomLevel = 1;
            let resizeTimeout;
            
            function detectZoomLevel() {
                const devicePixelRatio = window.devicePixelRatio || 1;
                const screenWidth = screen.width;
                const windowWidth = window.outerWidth;
                const widthRatio = windowWidth / screenWidth;
                const zoomLevel = Math.round((devicePixelRatio / widthRatio) * 100) / 100;
                return zoomLevel;
            }
            
            function adjustGridBasedOnZoom() {
                const container = document.getElementById('userCardsContainer');
                if (!container) return;
                
                // Skip adjustment on mobile
                if (window.innerWidth <= 767) return;
                
                const containerWidth = container.offsetWidth;
                const zoomLevel = detectZoomLevel();
                
                let minCardWidth = 220;
                
                // Sesuaikan minCardWidth berdasarkan zoom level
                if (zoomLevel >= 1.5) {
                    minCardWidth = 180;
                } else if (zoomLevel >= 1.25) {
                    minCardWidth = 200;
                } else if (zoomLevel >= 1.1) {
                    minCardWidth = 240;
                } else if (zoomLevel >= 1.0) {
                    minCardWidth = 260; // Di zoom 100%, card lebih besar = 4 kolom
                } else if (zoomLevel >= 0.9) {
                    minCardWidth = 240;
                } else if (zoomLevel >= 0.75) {
                    minCardWidth = 220; // Di zoom 75%, bisa 5 kolom
                } else {
                    minCardWidth = 200;
                }
                
                // Update grid template
                container.style.gridTemplateColumns = `repeat(auto-fit, minmax(${minCardWidth}px, 1fr))`;
                
                currentZoomLevel = zoomLevel;
            }
            
            // Jalankan saat load
            adjustGridBasedOnZoom();
            
            // Jalankan saat resize atau zoom
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(adjustGridBasedOnZoom, 150);
            });
            
            // Deteksi perubahan zoom dengan polling (untuk browser yang tidak trigger resize saat zoom)
            setInterval(function() {
                const newZoomLevel = detectZoomLevel();
                if (Math.abs(newZoomLevel - currentZoomLevel) > 0.05) {
                    adjustGridBasedOnZoom();
                }
            }, 500);
        })();

        // ===========================
        // FILTER FUNCTIONS
        // ===========================
        function filterUsers() {
            const nameFilter = document.getElementById('filterName').value.toLowerCase();
            const roleFilter = document.getElementById('filterRole').value;
            const tokoFilter = document.getElementById('filterToko').value.toLowerCase();
            const dateFilter = document.getElementById('filterDate').value;
            
            const cards = Array.from(document.querySelectorAll('.user-card-item'));
            const container = document.getElementById('userCardsContainer');
            const noResults = document.getElementById('noResults');
            
            let visibleCount = 0;

            // Filter cards
            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const role = card.getAttribute('data-role');
                const toko = card.getAttribute('data-toko');
                
                const matchName = !nameFilter || name.includes(nameFilter);
                const matchRole = !roleFilter || role === roleFilter;
                const matchToko = !tokoFilter || toko.includes(tokoFilter);
                
                if (matchName && matchRole && matchToko) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Sort by date
            const sortedCards = cards.sort((a, b) => {
                const dateA = parseInt(a.getAttribute('data-created'));
                const dateB = parseInt(b.getAttribute('data-created'));
                
                if (dateFilter === 'newest') {
                    return dateB - dateA;
                } else {
                    return dateA - dateB;
                }
            });

            // Re-append sorted cards
            sortedCards.forEach(card => container.appendChild(card));

            // Update result count
            document.getElementById('resultCount').textContent = visibleCount;

            // Show/hide no results message
            if (visibleCount === 0) {
                container.style.display = 'none';
                noResults.style.display = 'block';
            } else {
                container.style.display = 'grid';
                noResults.style.display = 'none';
            }
        }

        function resetFilters() {
            document.getElementById('filterName').value = '';
            document.getElementById('filterRole').value = '';
            document.getElementById('filterToko').value = '';
            document.getElementById('filterDate').value = 'newest';
            filterUsers();
        }

        // Add event listeners
        document.getElementById('filterName').addEventListener('input', filterUsers);
        document.getElementById('filterRole').addEventListener('change', filterUsers);
        document.getElementById('filterToko').addEventListener('change', filterUsers);
        document.getElementById('filterDate').addEventListener('change', filterUsers);

        // Initial sort on page load
        document.addEventListener('DOMContentLoaded', function() {
            filterUsers();
        });

        // ===========================
        // USER INTERACTION FUNCTIONS
        // ===========================
        function openUserDetailModal(userId) {
            console.log('Opening modal for user ID:', userId);
            showUserDetail(userId);
        }

        function confirmDeleteQuick(userId) {
            if (confirm('⚠️ Apakah Anda yakin ingin menghapus user ini?\n\nData yang terhapus tidak dapat dikembalikan!')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/superadmin/user/${userId}`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = window.csrfToken;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-admin-layout>