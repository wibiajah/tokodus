<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-weight-bold h4 text-dark">Kelola Stok - Semua Produk Warehouse</h2>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill mr-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Info Toko --}}
            @if(auth()->user()->toko)
                <div class="card bg-primary text-white mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-1">
                                    <i class="bi bi-shop mr-2"></i>{{ auth()->user()->toko->nama_toko }}
                                </h4>
                                <p class="mb-0">
                                    <i class="bi bi-geo-alt mr-2"></i>{{ auth()->user()->toko->alamat ?? '-' }}
                                </p>
                            </div>
                            <div class="col-md-6 text-md-right">
                                <div class="display-4 font-weight-bold">
                                    {{ $products->where('is_in_my_toko', true)->where('is_active_in_toko', true)->where('is_active', true)->count() }}
                                </div>
                                <small>Produk Aktif di Toko</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Search & Filter --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('kepala-toko.stocks.index') }}">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">🔍 Cari Produk</label>
                                <input type="text" name="search" class="form-control" 
                                    placeholder="Cari berdasarkan nama atau SKU..." 
                                    value="{{ $search }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">🎯 Filter Status</label>
                                <select name="filter" class="form-control">
                                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Semua Produk</option>
                                    <option value="in_toko" {{ $filter === 'in_toko' ? 'selected' : '' }}>Produk di Toko Saya</option>
                                    <option value="not_in_toko" {{ $filter === 'not_in_toko' ? 'selected' : '' }}>Belum di Toko</option>
                                    <option value="inactive" {{ $filter === 'inactive' ? 'selected' : '' }}>Produk Nonaktif (Saya)</option>
                                    <option value="inactive_by_admin" {{ $filter === 'inactive_by_admin' ? 'selected' : '' }}>🚫 Dinonaktifkan Pusat</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="font-weight-bold d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Product List --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-box-seam mr-2"></i>Daftar Produk 
                        <span class="badge badge-secondary">{{ $products->count() }} produk</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 80px;">Foto</th>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Stok Warehouse</th>
                                    <th class="text-center">Stok Toko Saya</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 220px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr class="{{ !$product->is_active ? 'table-secondary' : '' }}">
                                        <td>
                                            @if($product->photos && count($product->photos) > 0)
                                                <img src="{{ asset('storage/' . $product->photos[0]) }}" 
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
                                            <div class="font-weight-bold">{{ $product->title }}</div>
                                            <code class="small">{{ $product->sku }}</code>
                                            @if(!$product->is_active)
                                                <br><span class="badge badge-danger mt-1">🚫 Dinonaktifkan Pusat</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->categories->count() > 0)
                                                <small class="text-muted">
                                                    {{ $product->categories->pluck('name')->join(', ') }}
                                                </small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="font-weight-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info badge-pill h6 mb-0">{{ $product->warehouse_stock }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($product->is_in_my_toko)
                                                <span class="badge badge-primary badge-pill h6 mb-0">{{ $product->my_stock }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(!$product->is_active)
                                                <span class="badge badge-danger">🚫 Nonaktif (Admin)</span>
                                            @elseif(!$product->is_in_my_toko)
                                                <span class="badge badge-secondary">Belum Ditambahkan</span>
                                            @elseif($product->is_active_in_toko)
                                                <span class="badge badge-success">✅ Aktif</span>
                                            @else
                                                <span class="badge badge-warning">🔒 Nonaktif (Saya)</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(!$product->is_active)
                                                {{-- Produk dinonaktifkan superadmin - tidak ada aksi --}}
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="bi bi-lock"></i> Tidak Tersedia
                                                </button>
                                            @elseif(!$product->is_in_my_toko)
                                                {{-- Belum ditambahkan --}}
                                                <form action="{{ route('kepala-toko.stocks.add-to-toko', $product) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" 
                                                        onclick="return confirm('Tambahkan produk ini ke toko Anda?')">
                                                        <i class="bi bi-plus-circle"></i> Tambah ke Toko
                                                    </button>
                                                </form>
                                            @elseif($product->is_active_in_toko)
                                                {{-- Sudah aktif --}}
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('kepala-toko.stocks.edit', $product) }}" 
                                                        class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Edit Stok
                                                    </a>
                                                    <form action="{{ route('kepala-toko.stocks.toggle-status', $product) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Nonaktifkan produk ini di toko Anda?')">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                {{-- Nonaktif --}}
                                                <form action="{{ route('kepala-toko.stocks.toggle-status', $product) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" 
                                                        onclick="return confirm('Aktifkan kembali produk ini?')">
                                                        <i class="bi bi-check-circle"></i> Aktifkan
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="display-1 mb-3">📦</div>
                                            <h5>Tidak ada produk ditemukan</h5>
                                            @if($search || $filter !== 'all')
                                                <a href="{{ route('kepala-toko.stocks.index') }}" class="btn btn-primary mt-3">
                                                    Reset Filter
                                                </a>
                                            @endif
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

  
    <style>
        /* Highlight row untuk produk nonaktif dari admin */
        .table-secondary {
            opacity: 0.7;
        }
        
        .badge-pill {
            padding: 0.5rem 0.75rem;
            font-size: 0.95rem;
        }
        
        /* Icon spacing */
        .bi {
            vertical-align: middle;
        }
    </style>

</x-admin-layout>