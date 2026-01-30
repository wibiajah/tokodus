<x-admin-layout title="Manajemen Toko">
    <style>
        /* Filter Section */
        .toko-management-page .filter-section {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .toko-management-page .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .toko-management-page .filter-item {
            display: flex;
            flex-direction: column;
        }

        .toko-management-page .filter-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .toko-management-page .filter-label i {
            margin-right: 8px;
            color: #224abe;
        }

        .toko-management-page .filter-input {
            padding: 12px 16px;
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .toko-management-page .filter-input:focus {
            outline: none;
            border-color: #224abe;
            box-shadow: 0 0 0 3px rgba(34, 74, 190, 0.1);
        }

        .toko-management-page .filter-stats {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
        }

        .toko-management-page .filter-result {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .toko-management-page .filter-result strong {
            color: #224abe;
            font-size: 18px;
        }

        .toko-management-page .btn-reset-filter {
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

        .toko-management-page .btn-reset-filter:hover {
            background: #224abe;
            border-color: #224abe;
            color: white;
        }

        .toko-management-page .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .toko-management-page .no-results i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .toko-management-page .no-results h4 {
            font-size: 20px;
            font-weight: 600;
            color: #666;
            margin-bottom: 10px;
        }

        .toko-management-page .no-results p {
            font-size: 14px;
            color: #999;
        }

        /* ===========================
   DYNAMIC GRID SYSTEM - ZOOM AWARE
=========================== */
        #tokoCardsContainer {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            width: 100%;
            margin: 0;
        }

        .toko-card-item {
            width: 100%;
            padding: 0;
            margin: 0;
        }

        /* Modern Store Card Design - DESKTOP */
        .modern-store-card {
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

        .modern-store-card:hover {
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

        /* Status Badge Overlay */
        .card-status-overlay {
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

        .status-active {
            background: rgba(40, 167, 69, 0.95);
        }

        .status-inactive {
            background: rgba(108, 117, 125, 0.95);
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

        .modern-store-card:hover .card-slide-panel {
            transform: translateY(calc(100% - 160px));
        }

        /* Title Section - Always Visible */
        .card-title-section {
            margin-bottom: 0;
        }

        .card-store-title {
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

        .modern-store-card:hover .card-divider {
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

        .modern-store-card:hover .card-hidden-info {
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

        .quick-action-icon.email-icon:hover {
            background: #ea4335;
            color: white;
        }

        .quick-action-icon.maps-icon:hover {
            background: #4285f4;
            color: white;
        }

        .quick-action-icon i {
            font-size: 0.9rem;
        }

        /* ===========================
   MOBILE SIMPLE CARD DESIGN
=========================== */
        .mobile-simple-card {
            display: none;
        }

        /* Responsive Breakpoints */
        @media (min-width: 1600px) {
            #tokoCardsContainer {
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            }
        }

        @media (min-width: 1400px) and (max-width: 1599px) {
            #tokoCardsContainer {
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            }
        }

        @media (min-width: 1200px) and (max-width: 1399px) {
            #tokoCardsContainer {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }
        }

        @media (min-width: 992px) and (max-width: 1199px) {
            #tokoCardsContainer {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 20px;
            }

            .modern-store-card {
                min-height: 240px;
            }
        }

        /* ===========================
   TABLET MODE (768px - 991px)
=========================== */
        @media (min-width: 768px) and (max-width: 991px) {
            #tokoCardsContainer {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 18px;
            }

            .modern-store-card {
                min-height: 230px;
            }

            .toko-management-page .filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .toko-management-page .tokos-header {
                padding: 20px;
            }

            .toko-management-page .tokos-header h1 {
                font-size: 22px;
            }
        }

        /* ===========================
   MOBILE MODE - SIMPLE CARD (< 768px)
=========================== */
        @media (max-width: 767px) {
            .modern-store-card {
                display: none !important;
            }

            .mobile-simple-card {
                display: block !important;
            }

            #tokoCardsContainer {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

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

            .mobile-status-badge {
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

            .mobile-card-kepala {
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

            .mobile-action-btn.email {
                color: #ea4335;
            }

            .mobile-action-btn.maps {
                color: #4285f4;
            }

            .mobile-action-btn i {
                font-size: 12px;
            }

            .toko-management-page .tokos-header {
                padding: 16px;
                border-radius: 12px;
                margin-bottom: 16px;
            }

            .toko-management-page .tokos-header h1 {
                font-size: 18px;
                margin-bottom: 4px;
            }

            .toko-management-page .tokos-header p {
                font-size: 12px;
            }

            .toko-management-page .btn-add-toko {
                padding: 10px 16px;
                font-size: 12px;
                border-radius: 8px;
                gap: 6px;
            }

            .toko-management-page .btn-add-toko i {
                font-size: 12px;
            }

            .toko-management-page .filter-section {
                padding: 16px;
                border-radius: 12px;
                margin-bottom: 16px;
            }

            .toko-management-page .filter-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                margin-bottom: 12px;
            }

            .toko-management-page .filter-label {
                font-size: 12px;
            }

            .toko-management-page .filter-input {
                padding: 10px 12px;
                font-size: 13px;
                border-radius: 8px;
            }

            .toko-management-page .filter-stats {
                flex-direction: column;
                gap: 10px;
                align-items: stretch;
            }

            .toko-management-page .filter-result {
                font-size: 12px;
                text-align: center;
            }

            .toko-management-page .btn-reset-filter {
                width: 100%;
                padding: 10px;
                font-size: 12px;
            }

            .toko-management-page .no-results {
                padding: 40px 16px;
            }

            .toko-management-page .no-results i {
                font-size: 48px;
            }

            .toko-management-page .no-results h4 {
                font-size: 16px;
            }

            .toko-management-page .no-results p {
                font-size: 12px;
            }
        }

        @media (max-width: 399px) {
            #tokoCardsContainer {
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

            .mobile-card-kepala {
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

    <div class="container-fluid toko-management-page">
        <!-- Header -->
        <!-- GANTI JADI INI -->
        @include('layouts.management-header', [
            'icon' => 'fas fa-store',
            'title' => 'Manajemen Toko',
            'description' => 'Kelola semua toko dan informasi terkait',
            'buttonText' => 'Tambah Toko',
            'buttonRoute' => route('superadmin.toko.create'),
            'buttonIcon' => 'fas fa-plus-circle',
        ])

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-grid">
                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-search"></i>
                        Cari Nama Toko
                    </label>
                    <input type="text" id="filterName" class="filter-input" placeholder="Ketik nama toko...">
                </div>

                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-user-tie"></i>
                        Filter Kepala Toko
                    </label>
                    <select id="filterKepala" class="filter-input">
                        <option value="">Semua Kepala Toko</option>
                        @foreach ($tokos->pluck('kepalaToko')->filter()->unique('id') as $kepala)
                            <option value="{{ strtolower($kepala->name) }}">{{ $kepala->name }}</option>
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
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
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
                    Menampilkan <strong id="resultCount">{{ count($tokos) }}</strong> dari
                    <strong>{{ count($tokos) }}</strong> toko
                </div>
                <button class="btn-reset-filter" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Reset Filter
                </button>
            </div>
        </div>

        <!-- Cards Grid -->
        <div id="tokoCardsContainer">
            @forelse($tokos as $toko)
                <div class="toko-card-item" data-name="{{ strtolower($toko->nama_toko) }}"
                    data-kepala="{{ strtolower($toko->kepalaToko?->name ?? '') }}" data-status="{{ $toko->status }}"
                    data-created="{{ $toko->created_at->timestamp }}">

                    <!-- DESKTOP CARD -->
                    <div class="modern-store-card" onclick="openDetailModal({{ $toko->id }})">
                        <div class="card-image-wrapper">
                            @if ($toko->foto)
                                <img src="{{ asset('storage/' . $toko->foto) }}" alt="{{ $toko->nama_toko }}">
                            @else
                                <div class="card-image-placeholder">
                                    <i class="fas fa-store"></i>
                                </div>
                            @endif

                            <div
                                class="card-status-overlay {{ $toko->status === 'aktif' ? 'status-active' : 'status-inactive' }}">
                                {{ $toko->status === 'aktif' ? '● Aktif' : '● Tidak Aktif' }}
                            </div>
                        </div>

                        <div class="card-slide-panel">
                            <div class="card-title-section">
                                <h6 class="card-store-title">{{ Str::upper($toko->nama_toko) }}</h6>
                            </div>

                            <div class="card-divider"></div>

                            <div class="card-hidden-info">
                                <div class="info-row">
                                    <span class="info-label">Kepala Toko</span>
                                    <span
                                        class="info-value">{{ $toko->kepalaToko?->name ?? 'Belum ditentukan' }}</span>
                                </div>

                                @if ($toko->alamat)
                                    <div class="info-row">
                                        <span class="info-label">Alamat</span>
                                        <span class="info-value">{{ $toko->alamat }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="card-quick-actions">
                                @if ($toko->telepon)
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $toko->telepon);
                                        $waPhone =
                                            strpos($cleanPhone, '0') === 0
                                                ? '62' . substr($cleanPhone, 1)
                                                : $cleanPhone;
                                    @endphp
                                    <a href="https://wa.me/{{ $waPhone }}" target="_blank"
                                        class="quick-action-icon whatsapp-icon" onclick="event.stopPropagation()"
                                        title="Chat WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif

                                @if ($toko->email)
                                    <a href="mailto:{{ $toko->email }}" class="quick-action-icon email-icon"
                                        onclick="event.stopPropagation()" title="Kirim Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                @endif

                                @if ($toko->googlemap)
                                    <a href="{{ $toko->googlemap }}" target="_blank"
                                        class="quick-action-icon maps-icon" onclick="event.stopPropagation()"
                                        title="Buka Google Maps">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- MOBILE SIMPLE CARD -->
                    <div class="mobile-simple-card" onclick="openDetailModal({{ $toko->id }})">
                        <div class="mobile-card-image">
                            @if ($toko->foto)
                                <img src="{{ asset('storage/' . $toko->foto) }}" alt="{{ $toko->nama_toko }}">
                            @else
                                <div class="placeholder">
                                    <i class="fas fa-store"></i>
                                </div>
                            @endif

                            <div
                                class="mobile-status-badge {{ $toko->status === 'aktif' ? 'status-active' : 'status-inactive' }}">
                                {{ $toko->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                            </div>
                        </div>

                        <div class="mobile-card-content">
                            <h6 class="mobile-card-name">{{ Str::upper($toko->nama_toko) }}</h6>
                            <p class="mobile-card-kepala">{{ $toko->kepalaToko?->name ?? 'Belum ditentukan' }}</p>

                            <div class="mobile-card-actions">
                                @if ($toko->telepon)
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $toko->telepon);
                                        $waPhone =
                                            strpos($cleanPhone, '0') === 0
                                                ? '62' . substr($cleanPhone, 1)
                                                : $cleanPhone;
                                    @endphp
                                    <a href="https://wa.me/{{ $waPhone }}" target="_blank"
                                        class="mobile-action-btn whatsapp" onclick="event.stopPropagation()">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif

                                @if ($toko->email)
                                    <a href="mailto:{{ $toko->email }}" class="mobile-action-btn email"
                                        onclick="event.stopPropagation()">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                @endif

                                @if ($toko->googlemap)
                                    <a href="{{ $toko->googlemap }}" target="_blank" class="mobile-action-btn maps"
                                        onclick="event.stopPropagation()">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="no-results">
                        <i class="fas fa-store-slash"></i>
                        <h4>Belum Ada Data Toko</h4>
                        <p>Silakan tambah toko baru dengan klik tombol di atas</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- No Results Message -->
        <div class="no-results" id="noResults" style="display: none;">
            <i class="fas fa-search"></i>
            <h4>Tidak Ada Hasil</h4>
            <p>Tidak ditemukan toko yang sesuai dengan filter Anda</p>
        </div>
    </div>

    @include('superadmin.toko.detailmodal', ['tokos' => $tokos])

    <script>
        // ===========================
        // DATA & INITIALIZATION
        // ===========================
        window.tokosData = @json($tokos);
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
                const container = document.getElementById('tokoCardsContainer');
                if (!container) return;

                if (window.innerWidth <= 767) return;

                const containerWidth = container.offsetWidth;
                const zoomLevel = detectZoomLevel();

                let minCardWidth = 220;

                if (zoomLevel >= 1.5) {
                    minCardWidth = 180;
                } else if (zoomLevel >= 1.25) {
                    minCardWidth = 200;
                } else if (zoomLevel >= 1.1) {
                    minCardWidth = 240;
                } else if (zoomLevel >= 1.0) {
                    minCardWidth = 260;
                } else if (zoomLevel >= 0.9) {
                    minCardWidth = 240;
                } else if (zoomLevel >= 0.75) {
                    minCardWidth = 220;
                } else {
                    minCardWidth = 200;
                }

                container.style.gridTemplateColumns = `repeat(auto-fit, minmax(${minCardWidth}px, 1fr))`;

                currentZoomLevel = zoomLevel;
            }

            adjustGridBasedOnZoom();

            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(adjustGridBasedOnZoom, 150);
            });

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
        function filterTokos() {
            const nameFilter = document.getElementById('filterName').value.toLowerCase();
            const kepalaFilter = document.getElementById('filterKepala').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const dateFilter = document.getElementById('filterDate').value;

            const cards = Array.from(document.querySelectorAll('.toko-card-item'));
            const container = document.getElementById('tokoCardsContainer');
            const noResults = document.getElementById('noResults');

            let visibleCount = 0;

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const kepala = card.getAttribute('data-kepala');
                const status = card.getAttribute('data-status');

                const matchName = !nameFilter || name.includes(nameFilter);
                const matchKepala = !kepalaFilter || kepala.includes(kepalaFilter);
                const matchStatus = !statusFilter || status === statusFilter;

                if (matchName && matchKepala && matchStatus) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const sortedCards = cards.sort((a, b) => {
                const dateA = parseInt(a.getAttribute('data-created'));
                const dateB = parseInt(b.getAttribute('data-created'));

                if (dateFilter === 'newest') {
                    return dateB - dateA;
                } else {
                    return dateA - dateB;
                }
            });

            sortedCards.forEach(card => container.appendChild(card));

            document.getElementById('resultCount').textContent = visibleCount;

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
            document.getElementById('filterKepala').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterDate').value = 'newest';
            filterTokos();
        }

        document.getElementById('filterName').addEventListener('input', filterTokos);
        document.getElementById('filterKepala').addEventListener('change', filterTokos);
        document.getElementById('filterStatus').addEventListener('change', filterTokos);
        document.getElementById('filterDate').addEventListener('change', filterTokos);

        document.addEventListener('DOMContentLoaded', function() {
            filterTokos();
        });

        // ===========================
        // TOKO INTERACTION FUNCTIONS
        // ===========================
        function openDetailModal(tokoId) {
            console.log('Opening modal for toko ID:', tokoId);
            showTokoDetail(tokoId);
        }
    </script>
</x-admin-layout>
