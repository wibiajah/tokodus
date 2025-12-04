<x-admin-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">Manajemen Stok Toko</h2>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Info Toko --}}
            @if(auth()->user()->toko)
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-1">
                                    <i class="bi bi-shop me-2"></i>{{ auth()->user()->toko->nama_toko }}
                                </h4>
                                <p class="mb-0">
                                    <i class="bi bi-geo-alt me-2"></i>{{ auth()->user()->toko->alamat ?? '-' }}
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="display-6 fw-bold">
                                    {{ auth()->user()->toko->productStocks->sum('stock') ?? 0 }}
                                </div>
                                <small>Total Stok Toko</small>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Anda belum terdaftar di toko manapun. Hubungi administrator.
                </div>
            @endif

            {{-- Products List --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-box-seam me-2"></i>Daftar Produk
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Foto</th>
                                    <th>Produk</th>
                                    <th>SKU</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Stok Awal Tersedia</th>
                                    <th class="text-center">Stok Toko Saya</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    @php
                                        $myStock = $product->stockInToko(auth()->user()->toko_id);
                                        $stockValue = $myStock ? $myStock->stock : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($product->photos && count($product->photos) > 0)
                                                <img src="{{ Storage::url($product->photos[0]) }}" 
                                                    alt="{{ $product->title }}"
                                                    class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" 
                                                    style="width: 60px; height: 60px;">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $product->title }}</div>
                                            @if($product->categories->count() > 0)
                                                <small class="text-muted">
                                                    {{ $product->categories->pluck('name')->join(', ') }}
                                                </small>
                                            @endif
                                        </td>
                                        <td><code>{{ $product->sku }}</code></td>
                                        <td class="text-center">
                                            <div class="fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $remaining = $product->remaining_initial_stock;
                                                $badgeClass = $remaining > 50 ? 'success' : ($remaining > 0 ? 'warning' : 'danger');
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }} fs-6">{{ $remaining }}</span>
                                            <div><small class="text-muted">dari {{ $product->initial_stock }}</small></div>
                                        </td>
                                        <td class="text-center">
                                            @if($stockValue > 0)
                                                @php
                                                    $myBadgeClass = $stockValue > 10 ? 'primary' : ($stockValue > 0 ? 'warning' : 'secondary');
                                                @endphp
                                                <span class="badge bg-{{ $myBadgeClass }} fs-5">{{ $stockValue }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($myStock)
                                                @if($stockValue > 10)
                                                    <span class="badge bg-success">✅ Cukup</span>
                                                @elseif($stockValue > 0)
                                                    <span class="badge bg-warning">⚠️ Terbatas</span>
                                                @else
                                                    <span class="badge bg-danger">❌ Habis</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Belum Set</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($myStock)
                                                <a href="{{ route('kepala-toko.stocks.edit', $product) }}" 
                                                    class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i> Edit Stok
                                                </a>
                                            @else
                                                @if($remaining > 0)
                                                    <a href="{{ route('kepala-toko.stocks.create', $product) }}" 
                                                        class="btn btn-sm btn-primary">
                                                        <i class="bi bi-plus-circle"></i> Set Stok
                                                    </a>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        Stok Habis
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="display-1 mb-3">📦</div>
                                            <h5>Belum ada produk</h5>
                                            <p class="text-muted">Produk akan muncul setelah ditambahkan oleh admin</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>