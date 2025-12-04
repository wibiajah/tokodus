<x-admin-layout>
    <x-slot name="header">
        <h2 class="h3 mb-0">{{ __('Manajemen Produk') }}</h2>
    </x-slot>

    <div class="py-5">
        <div class="container-fluid">
            {{-- Alert Success --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    {{-- Header & Search --}}
                    <div class="row mb-4">
                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <form method="GET" action="{{ route('superadmin.products.index') }}">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                        placeholder="Cari produk..." 
                                        value="{{ request('search') }}">
                                    <button class="btn btn-secondary" type="submit">
                                        🔍 Cari
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6 text-lg-end">
                            <a href="{{ route('superadmin.products.create') }}" 
                                class="btn btn-primary fw-semibold">
                                ➕ Tambah Produk
                            </a>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Foto</th>
                                    <th>Produk</th>
                                    <th>SKU</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok Awal</th>
                                    <th>Sisa Stok</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                          @if($product->photos && count($product->photos) > 0)
                                            <img src="{{ asset('storage/' . $product->photos[0]) }}" 
                                                alt="{{ $product->title }}"
                                                class="rounded"
                                                style="width: 64px; height: 64px; object-fit: cover;">
                                        @else
                                            <div class="card-image-placeholder rounded" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $product->title }}</div>
                                            @if($product->tags)
                                                <div class="d-flex gap-1 mt-1 flex-wrap">
                                                    @foreach(array_slice($product->tags, 0, 3) as $tag)
                                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $tag }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="font-monospace">{{ $product->sku }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @forelse($product->categories as $category)
                                                    <span class="badge" style="background-color: #f3e8ff; color: #7c3aed;">
                                                        {{ $category->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-muted small">Tidak ada kategori</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td>
                                            @if($product->discount_price)
                                                <div class="text-decoration-line-through text-muted small">
                                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                                </div>
                                                <div class="fw-bold text-danger">
                                                    Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                                </div>
                                            @else
                                                <div class="fw-semibold">
                                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary bg-opacity-25 text-dark fw-semibold">
                                                {{ $product->initial_stock }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $remaining = $product->remaining_initial_stock;
                                                $badgeClass = $remaining > 50 ? 'success' : ($remaining > 0 ? 'warning' : 'danger');
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }} fw-semibold">
                                                {{ $remaining }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="text-warning">⭐</span>
                                                <span class="ms-1">{{ number_format($product->rating, 1) }}</span>
                                                <span class="ms-1 text-muted small">({{ $product->reviews_count }})</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('superadmin.products.show', $product) }}" 
                                                    class="text-primary text-decoration-none" 
                                                    title="Detail"
                                                    data-bs-toggle="tooltip">
                                                    👁️
                                                </a>
                                                <a href="{{ route('superadmin.products.edit', $product) }}" 
                                                    class="text-info text-decoration-none" 
                                                    title="Edit"
                                                    data-bs-toggle="tooltip">
                                                    ✏️
                                                </a>
                                                <form action="{{ route('superadmin.products.destroy', $product) }}" 
                                                    method="POST" 
                                                    class="d-inline"
                                                    onsubmit="return confirm('Yakin hapus produk ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="btn btn-link text-danger p-0 border-0" 
                                                        title="Hapus"
                                                        data-bs-toggle="tooltip">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <div class="display-1 mb-3">📦</div>
                                            <p class="fs-5 text-muted">Belum ada produk</p>
                                            <a href="{{ route('superadmin.products.create') }}" 
                                                class="btn btn-primary mt-3">
                                                Tambah Produk Pertama
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($products->hasPages())
                        <div class="mt-4">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>