<x-admin-layout>
    <x-slot name="header">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-folder text-primary"></i> Manajemen Kategori
            </h1>
        </div>
    </x-slot>

    <div class="container-fluid">
        {{-- Alert Success --}}
        

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list"></i> Daftar Kategori
                </h6>
                <a href="{{ route('superadmin.categories.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Tambah Kategori
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Nama Kategori</th>
                                
                                <th width="250">Deskripsi</th>
                                <th width="130" class="text-center">Jumlah Produk</th>
                                <th width="100" class="text-center">Status</th>
                                <th width="120" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
    @forelse($categories as $index => $category)
        <tr>
            <td class="text-center text-muted">
                {{ $categories->firstItem() + $index }}
            </td>
            <td>
                <div class="d-flex align-items-center">
                    @if($category->photo)
                        <img src="{{ asset('storage/' . $category->photo) }}" 
                            alt="{{ $category->name }}"
                            class="rounded mr-3"
                            style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <div class="rounded mr-3 d-flex align-items-center justify-content-center bg-light" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-folder text-muted"></i>
                        </div>
                    @endif
                    <span class="font-weight-bold">{{ $category->name }}</span>
                </div>
            </td>
            <td>
                <div class="text-truncate" style="max-width: 250px;" title="{{ $category->description }}">
                    {{ $category->description ?? '-' }}
                </div>
            </td>
            <td class="text-center">
                <span class="badge badge-info badge-pill">
                    <i class="fas fa-box"></i> {{ $category->products_count }}
                </span>
            </td>
            <td class="text-center">
                @if($category->is_active)
                    <span class="badge badge-success">
                        <i class="fas fa-check-circle"></i> Aktif
                    </span>
                @else
                    <span class="badge badge-danger">
                        <i class="fas fa-times-circle"></i> Nonaktif
                    </span>
                @endif
            </td>
            <td class="text-center">
                <a href="{{ route('superadmin.categories.edit', $category) }}" 
                   class="btn btn-sm btn-warning btn-circle" 
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
                            class="btn btn-sm btn-danger btn-circle" 
                            title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center py-5">
                <div class="py-4">
                    <i class="fas fa-folder-open fa-4x text-gray-400 mb-3"></i>
                    <h5 class="text-gray-600">Belum ada kategori</h5>
                    <p class="text-gray-500 mb-3">Mulai dengan menambahkan kategori pertama Anda</p>
                    <a href="{{ route('superadmin.categories.create') }}" class="btn btn-primary">
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
                    <div class="d-flex justify-content-between align-items-center mt-3">
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
    </div>

    <style>
       

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .btn-circle {
            width: 32px;
            height: 32px;
            padding: 0;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .badge {
            padding: 0.4em 0.65em;
            font-size: 0.8rem;
        }

        .badge-pill {
            padding-right: 0.75em;
            padding-left: 0.75em;
        }
    </style>
</x-admin-layout>