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

        /* Modern User Card Design */
        .modern-user-card {
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

        .modern-user-card:hover {
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
            background: #224abe;
        }

        .card-image-placeholder i {
            font-size: 64px;
            color: rgba(255, 255, 255, 0.3);
        }

        /* Role Badge Overlay */
        .card-role-overlay {
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
            border-radius: 30px;
            padding: 20px 24px 24px;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 15;
            transform: translateY(calc(100% - 120px));
        }

        .modern-user-card:hover .card-slide-panel {
            transform: translateY(calc(100% - 200px));
        }

        /* Title Section - Always Visible */
        .card-title-section {
            margin-bottom: 0;
        }

        .card-user-title {
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
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .user-management-page .filter-grid {
                grid-template-columns: 1fr;
            }

            .modern-user-card {
                height: 300px;
            }
            
            .card-slide-panel {
                transform: translateY(calc(100% - 110px));
            }

            .modern-user-card:hover .card-slide-panel {
                transform: translateY(calc(100% - 220px));
            }
            
            .card-user-title {
                font-size: 15px;
            }
            
            .info-value {
                font-size: 12px;
            }
        }

        @media (max-width: 576px) {
            .modern-user-card {
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

            .modern-user-card:hover .card-slide-panel {
                transform: translateY(calc(100% - 200px));
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

        <!-- Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

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
        <div class="row" id="userCardsContainer">
            @forelse($users as $user)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4 user-card-item" 
                     data-name="{{ strtolower($user->name) }}" 
                     data-role="{{ $user->role }}"
                     data-toko="{{ strtolower($user->toko?->nama_toko ?? '') }}"
                     data-created="{{ $user->created_at->timestamp }}">
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
                                    <span class="info-label">Toko</span>
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

    <!-- Pass data to JavaScript -->
    <script>
        window.usersData = @json($users);
        window.csrfToken = '{{ csrf_token() }}';

        // Filter Function
        function filterUsers() {
            const nameFilter = document.getElementById('filterName').value.toLowerCase();
            const roleFilter = document.getElementById('filterRole').value;
            const tokoFilter = document.getElementById('filterToko').value.toLowerCase();
            const dateFilter = document.getElementById('filterDate').value;
            
            const cards = Array.from(document.querySelectorAll('.user-card-item'));
            const container = document.getElementById('superadmin.userCardsContainer');
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
                container.style.display = 'flex';
                container.style.flexWrap = 'wrap';
                noResults.style.display = 'none';
            }
        }

        // Reset Filters
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

        // Function to open detail modal
        function openUserDetailModal(userId) {
            console.log('Opening modal for user ID:', userId);
            showUserDetail(userId);
        }

        // Quick delete confirmation
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