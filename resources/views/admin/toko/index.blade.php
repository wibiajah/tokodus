<x-admin-layout title="Manajemen Toko">
    <style>
        /* ===========================
           MODERN STORE HEADER & FILTER - SCOPED
        =========================== */
        .toko-management-page .tokos-header {
            background: linear-gradient(135deg, #224abe 0%, #224abe 100%);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .toko-management-page .tokos-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .toko-management-page .tokos-header p {
            margin: 0;
            opacity: 0.9;
        }

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
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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

        .toko-management-page .btn-add-toko {
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

        .toko-management-page .btn-add-toko:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
            color: white;
            text-decoration: none;
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

        /* Modern Card Design with Slide-Up Effect */
        .modern-store-card {
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

        .modern-store-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px -15px rgba(34, 74, 190, 0.3);
        }

        /* Image Container with Curved Bottom-Right Corner */
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
            background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);
        }

        .card-image-placeholder i {
            font-size: 64px;
            color: rgba(255, 255, 255, 0.3);
        }

        /* Status Badge Overlay */
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

        /* Sliding White Panel */
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

        .modern-store-card:hover .card-slide-panel {
            transform: translateY(calc(100% - 200px));
        }

        /* Title Section - Always Visible */
        .card-title-section {
            margin-bottom: 0;
        }

        .card-store-title {
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

        /* Divider Line */
        .card-divider {
            width: 100%;
            height: 1px;
            background: #e2e8f0;
            margin-bottom: 16px;
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
            max-height: 150px;
        }

        .info-row {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 11px;
            font-weight: 600;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 13px;
            font-weight: 500;
            color: #4A5568;
            line-height: 1.4;
        }

        /* Quick Actions Icons */
        .card-quick-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            padding-top: 0;
            border-top: none;
            margin-bottom: 16px;
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

        .quick-action-icon i {
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .toko-management-page .filter-grid {
                grid-template-columns: 1fr;
            }

            .modern-store-card {
                height: 300px;
            }
            
            .card-slide-panel {
                transform: translateY(calc(100% - 110px));
            }

            .modern-store-card:hover .card-slide-panel {
                transform: translateY(calc(100% - 220px));
            }
            
            .card-store-title {
                font-size: 15px;
            }
            
            .info-value {
                font-size: 12px;
            }
        }

        @media (max-width: 576px) {
            .modern-store-card {
                border-radius: 25px;
                height: 280px;
            }
            
            .card-image-wrapper {
                border-bottom-right-radius: 80px;
            }
            
            .card-slide-panel {
                border-radius: 25px;
                padding: 16px 20px 20px;
                transform: translateY(calc(100% - 100px));
            }

            .modern-store-card:hover .card-slide-panel {
                transform: translateY(calc(100% - 200px));
            }
        }
    </style>

    <div class="container-fluid toko-management-page">
        <!-- Header -->
        <div class="tokos-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-store"></i> Manajemen Toko</h1>
                    <p>Kelola semua toko dan informasi terkait</p>
                </div>
                <a href="{{ route('toko.create') }}" class="btn-add-toko">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Toko</span>
                </a>
            </div>
        </div>

       

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
                        @foreach($tokos->pluck('kepalaToko')->filter()->unique('id') as $kepala)
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
                    Menampilkan <strong id="resultCount">{{ count($tokos) }}</strong> dari <strong>{{ count($tokos) }}</strong> toko
                </div>
                <button class="btn-reset-filter" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Reset Filter
                </button>
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="row" id="tokoCardsContainer">
            @forelse($tokos as $toko)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4 toko-card-item" 
                     data-name="{{ strtolower($toko->nama_toko) }}" 
                     data-kepala="{{ strtolower($toko->kepalaToko?->name ?? '') }}"
                     data-status="{{ $toko->status }}"
                     data-created="{{ $toko->created_at->timestamp }}">
                    <div class="modern-store-card" onclick="openDetailModal({{ $toko->id }})">
                        <!-- Image Container with Curved Corner -->
                        <div class="card-image-wrapper">
                            @if($toko->foto)
                                <img src="{{ asset('storage/' . $toko->foto) }}" alt="{{ $toko->nama_toko }}">
                            @else
                                <div class="card-image-placeholder">
                                    <i class="fas fa-store"></i>
                                </div>
                            @endif
                            
                            <!-- Status Badge Overlay -->
                            <div class="card-status-overlay {{ $toko->status === 'aktif' ? 'status-active' : 'status-inactive' }}">
                                {{ $toko->status === 'aktif' ? '● Aktif' : '● Tidak Aktif' }}
                            </div>
                        </div>
                        
                        <!-- Sliding White Content Panel -->
                        <div class="card-slide-panel">
                            <!-- Title Section - Always Visible -->
                            <div class="card-title-section">
                                <h6 class="card-store-title">{{ Str::upper($toko->nama_toko) }}</h6>
                            </div>
                            
                            <!-- Divider Line -->
                            <div class="card-divider"></div>
                            
                            <!-- Hidden Info - Shows on Hover -->
                            <div class="card-hidden-info">
                                <div class="info-row">
                                    <span class="info-label">Kepala Toko</span>
                                    <span class="info-value">{{ $toko->kepalaToko?->name ?? 'Belum ditentukan' }}</span>
                                </div>
                                
                                @if($toko->alamat)
                                <div class="info-row">
                                    <span class="info-label">Alamat</span>
                                    <span class="info-value">{{ $toko->alamat }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Quick Actions Icons -->
                            <div class="card-quick-actions">
                                @if($toko->telepon)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $toko->telepon) }}" 
                                       target="_blank" 
                                       class="quick-action-icon"
                                       onclick="event.stopPropagation()"
                                       title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                                
                                @if($toko->email)
                                    <a href="mailto:{{ $toko->email }}" 
                                       class="quick-action-icon"
                                       onclick="event.stopPropagation()"
                                       title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                @endif
                                
                                @if($toko->googlemap)
                                    <a href="{{ $toko->googlemap }}" 
                                       target="_blank" 
                                       class="quick-action-icon"
                                       onclick="event.stopPropagation()"
                                       title="Google Maps">
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

        <!-- No Results Message (Hidden by default) -->
        <div class="no-results" id="noResults" style="display: none;">
            <i class="fas fa-search"></i>
            <h4>Tidak Ada Hasil</h4>
            <p>Tidak ditemukan toko yang sesuai dengan filter Anda</p>
        </div>
    </div>

    <!-- Include Modal -->
    @include('admin.toko.detailmodal', ['tokos' => $tokos])

    <!-- Pass data to JavaScript -->
    <script>
        window.tokosData = @json($tokos);
        window.kepalaTokosData = @json(\App\Models\User::where('role', 'kepala_toko')->get());
        window.csrfToken = '{{ csrf_token() }}';

        // Filter Function
        function filterTokos() {
            const nameFilter = document.getElementById('filterName').value.toLowerCase();
            const kepalaFilter = document.getElementById('filterKepala').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const dateFilter = document.getElementById('filterDate').value;
            
            const cards = Array.from(document.querySelectorAll('.toko-card-item'));
            const container = document.getElementById('tokoCardsContainer');
            const noResults = document.getElementById('noResults');
            
            let visibleCount = 0;

            // Filter cards
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
                container.style.display = 'flex';
                container.style.flexWrap = 'wrap';
                noResults.style.display = 'none';
            }
        }

        // Reset Filters
        function resetFilters() {
            document.getElementById('filterName').value = '';
            document.getElementById('filterKepala').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterDate').value = 'newest';
            filterTokos();
        }

        // Add event listeners
        document.getElementById('filterName').addEventListener('input', filterTokos);
        document.getElementById('filterKepala').addEventListener('change', filterTokos);
        document.getElementById('filterStatus').addEventListener('change', filterTokos);
        document.getElementById('filterDate').addEventListener('change', filterTokos);

        // Initial sort on page load
        document.addEventListener('DOMContentLoaded', function() {
            filterTokos();
        });

        // Function to open detail modal
        function openDetailModal(tokoId) {
            console.log('Opening modal for toko ID:', tokoId);
            showTokoDetail(tokoId);
        }
    </script>
</x-admin-layout>