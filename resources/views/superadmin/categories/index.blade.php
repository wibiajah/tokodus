<x-admin-layout>
    <x-slot name="header">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-folder text-primary"></i> Manajemen Kategori
            </h1>
        </div>
    </x-slot>

    <style>
/* Category Management Page Scoped Styles */
.category-management-page .categories-header {
    background: linear-gradient(135deg, #224abe 0%, #224abe 100%);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.category-management-page .categories-header h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 10px 0;
}

.category-management-page .categories-header p {
    margin: 0;
    opacity: 0.9;
}

.category-management-page .btn-add-category {
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

.category-management-page .btn-add-category:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    color: white;
    text-decoration: none;
}

/* Table Styles */
.category-management-page .table-container {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
}

.category-management-page .table {
    margin-bottom: 0;
}

.category-management-page .table thead {
    background: linear-gradient(135deg, #f8f9fc 0%, #eef2f7 100%);
}

.category-management-page .table thead th {
    font-weight: 700;
    color: #224abe;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    padding: 16px 12px;
    border: none;
}

.category-management-page .table tbody tr {
    transition: all 0.2s;
}

.category-management-page .table tbody tr:hover {
    background: #f8f9fc;
    cursor: pointer;
}

.category-management-page .table tbody td {
    padding: 16px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}

.category-management-page .category-image-placeholder {
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);
    border-radius: 8px;
}

.category-management-page .category-image-placeholder i {
    font-size: 24px;
    color: rgba(255, 255, 255, 0.3);
}

.category-management-page .action-icon {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.3s;
    text-decoration: none;
    font-size: 1rem;
}

.category-management-page .action-icon:hover {
    transform: scale(1.1);
    text-decoration: none;
}

.category-management-page .action-icon.edit {
    background: #fff3e0;
    color: #ff9800;
}

.category-management-page .action-icon.delete {
    background: #ffebee;
    color: #f44336;
}

.category-management-page .no-results {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.category-management-page .no-results i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.3;
}

.category-management-page .badge {
    padding: 0.4em 0.65em;
    font-size: 0.8rem;
}

.category-management-page .badge-pill {
    padding-right: 0.75em;
    padding-left: 0.75em;
}
    </style>

    <div class="container-fluid category-management-page">
        {{-- Alert Success --}}
        

        <!-- Header -->
        <div class="categories-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1><i class="fas fa-folder"></i> Manajemen Kategori</h1>
                    <p>Kelola semua kategori produk</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('superadmin.categories.create') }}" class="btn-add-category">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Kategori</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th width="80">Foto</th>
                            <th>Nama Kategori</th>
                            <th width="300">Deskripsi</th>
                            <th width="130" class="text-center">Jumlah Produk</th>
                            <th width="100" class="text-center">Status</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                            <tr>
                                <td class="text-center text-muted fw-medium">
                                    {{ $categories->firstItem() + $index }}
                                </td>
                                <td>
                                    @if(isset($category->photo) && $category->photo)
                                        <img src="{{ asset('storage/' . $category->photo) }}" 
                                            alt="{{ $category->name }}"
                                            class="rounded"
                                            style="width: 64px; height: 64px; object-fit: cover;">
                                    @else
                                        <div class="category-image-placeholder">
                                            <i class="fas fa-folder"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;" title="{{ $category->description }}">
                                        {{ $category->description ?? '-' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info bg-opacity-25 text-white badge-pill fw-semibold">
                                        <i class="fas fa-box"></i> {{ $category->products_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($category->is_active)
                                        <span class="badge bg-success text-white">
                                            <i class="fas fa-check-circle"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-danger text-white">
                                            <i class="fas fa-times-circle"></i> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('superadmin.categories.edit', $category) }}" 
                                           class="action-icon edit" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('superadmin.categories.destroy', $category) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus kategori ini?\n\nProduk yang terhubung tidak akan terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="action-icon delete border-0" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="no-results">
                                        <i class="fas fa-folder-open"></i>
                                        <h4>Belum Ada Kategori</h4>
                                        <p class="mb-3">Mulai dengan menambahkan kategori pertama Anda</p>
                                        <a href="{{ route('superadmin.categories.create') }}" class="btn-add-category">
                                            <i class="fas fa-plus-circle"></i> Tambah Kategori Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($categories->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
                    <div class="text-muted small">
                        Menampilkan {{ $categories->firstItem() }} - {{ $categories->lastItem() }} 
                        dari {{ $categories->total() }} kategori
                    </div>
                    <div>
                        {{ $categories->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>