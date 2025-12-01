<x-admin-layout title="Manajemen User">
    <style>
        /* ===========================
           MODERN USER CARD STYLES - SCOPED
        =========================== */
        .user-management-page .users-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
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
            color: #667eea;
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
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
            color: #667eea;
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
            background: #667eea;
            border-color: #667eea;
            color: white;
        }

        /* User Cards Grid */
        .user-management-page .user-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .user-management-page .user-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .user-management-page .user-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .user-management-page .user-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.15);
        }

        .user-management-page .user-card:hover::before {
            transform: scaleX(1);
        }

        .user-management-page .user-avatar-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 16px;
        }

        .user-management-page .user-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 42px;
            border: 4px solid #f0f0f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .user-management-page .user-name {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a !important;
            text-align: center;
            margin-bottom: 10px;
        }

        .user-management-page .user-role-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
            margin: 0 auto 20px;
            display: block;
            text-align: center;
            width: fit-content;
        }

        .user-management-page .badge-super-admin {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
        }

        .user-management-page .badge-admin {
            background: linear-gradient(135deg, #51cf66 0%, #37b24d 100%);
            color: white;
        }

        .user-management-page .badge-kepala-toko {
            background: linear-gradient(135deg, #339af0 0%, #228be6 100%);
            color: white;
        }

        .user-management-page .badge-staff-admin {
            background: linear-gradient(135deg, #ffd43b 0%, #fab005 100%);
            color: white;
        }

        .user-management-page .user-info-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .user-management-page .user-info-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .user-management-page .user-info-row:last-child {
            margin-bottom: 0;
        }

        .user-management-page .user-info-icon {
            font-size: 16px;
            color: #667eea;
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .user-management-page .user-info-label {
            font-size: 13px;
            font-weight: 600;
            color: #666;
            margin-right: 6px;
        }

        .user-management-page .user-info-value {
            font-size: 13px;
            color: #333;
            word-break: break-word;
        }

        .user-management-page .user-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .user-management-page .btn-action {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .user-management-page .btn-action i {
            font-size: 14px;
        }

        .user-management-page .btn-view {
            background: #e3f2fd;
            color: #1976d2;
        }

        .user-management-page .btn-view:hover {
            background: #1976d2;
            color: white;
        }

        .user-management-page .btn-edit {
            background: #fff3e0;
            color: #f57c00;
        }

        .user-management-page .btn-edit:hover {
            background: #f57c00;
            color: white;
        }

        .user-management-page .btn-delete {
            background: #ffebee;
            color: #d32f2f;
        }

        .user-management-page .btn-delete:hover {
            background: #d32f2f;
            color: white;
        }

        .user-management-page .btn-add-user {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        }

        .user-management-page .btn-add-user:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
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

        @media (max-width: 768px) {
            .user-management-page .user-cards-grid {
                grid-template-columns: 1fr;
            }

            .user-management-page .filter-grid {
                grid-template-columns: 1fr;
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
                <a href="{{ route('user.create') }}" class="btn-add-user">
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

        <!-- User Cards Grid -->
        <div class="user-cards-grid" id="userCardsContainer">
            @forelse($users as $user)
                <div class="user-card" 
                     data-name="{{ strtolower($user->name) }}" 
                     data-role="{{ $user->role }}"
                     data-created="{{ $user->created_at->timestamp }}">
                    
                    <!-- Avatar -->
                    <div class="user-avatar-wrapper">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <!-- Name -->
                    <h3 class="user-name">{{ $user->name }}</h3>

                    <!-- Role Badge -->
                    @if($user->role === 'super_admin')
                        <span class="user-role-badge badge-super-admin">Super Admin</span>
                    @elseif($user->role === 'admin')
                        <span class="user-role-badge badge-admin">Admin</span>
                    @elseif($user->role === 'kepala_toko')
                        <span class="user-role-badge badge-kepala-toko">Kepala Toko</span>
                    @else
                        <span class="user-role-badge badge-staff-admin">Staff Admin</span>
                    @endif

                    <!-- Info Box -->
                    <div class="user-info-box">
                        <div class="user-info-row">
                            <i class="fas fa-store user-info-icon"></i>
                            <span class="user-info-label">Toko:</span>
                            <span class="user-info-value">{{ $user->toko->nama_toko ?? '-' }}</span>
                        </div>

                        <div class="user-info-row">
                            <i class="fas fa-envelope user-info-icon"></i>
                            <span class="user-info-label">Email:</span>
                            <span class="user-info-value">{{ $user->email }}</span>
                        </div>

                        <div class="user-info-row">
                            <i class="fas fa-calendar user-info-icon"></i>
                            <span class="user-info-label">Dibuat:</span>
                            <span class="user-info-value">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="user-actions">
                        <a href="{{ route('user.show', $user) }}" class="btn-action btn-view" title="Detail">
                            <i class="fas fa-eye"></i>
                            <span>Detail</span>
                        </a>
                        
                        @if(auth()->user()->role === 'super_admin' || $user->role !== 'super_admin')
                            <a href="{{ route('user.edit', $user) }}" class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </a>
                            
                            @if($user->id !== auth()->id())
                                <button type="button" class="btn-action btn-delete" title="Hapus" 
                                    onclick="confirmDelete({{ $user->id }})">
                                    <i class="fas fa-trash"></i>
                                    <span>Hapus</span>
                                </button>

                                <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', $user) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="no-results" style="grid-column: 1 / -1;">
                    <i class="fas fa-users-slash"></i>
                    <h4>Belum Ada User</h4>
                    <p>Mulai tambahkan user baru untuk sistem Anda</p>
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

    <script>
        // Confirm Delete Function
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        // Filter Function
        function filterUsers() {
            const nameFilter = document.getElementById('filterName').value.toLowerCase();
            const roleFilter = document.getElementById('filterRole').value;
            const dateFilter = document.getElementById('filterDate').value;
            
            const cards = Array.from(document.querySelectorAll('.user-card'));
            const container = document.getElementById('userCardsContainer');
            const noResults = document.getElementById('noResults');
            
            let visibleCount = 0;

            // Filter cards
            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const role = card.getAttribute('data-role');
                
                const matchName = !nameFilter || name.includes(nameFilter);
                const matchRole = !roleFilter || role === roleFilter;
                
                if (matchName && matchRole) {
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

        // Reset Filters
        function resetFilters() {
            document.getElementById('filterName').value = '';
            document.getElementById('filterRole').value = '';
            document.getElementById('filterDate').value = 'newest';
            filterUsers();
        }

        // Add event listeners
        document.getElementById('filterName').addEventListener('input', filterUsers);
        document.getElementById('filterRole').addEventListener('change', filterUsers);
        document.getElementById('filterDate').addEventListener('change', filterUsers);

        // Initial sort on page load
        document.addEventListener('DOMContentLoaded', function() {
            filterUsers();
        });
    </script>

</x-admin-layout>