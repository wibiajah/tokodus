<x-admin-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h3 mb-0">{{ __('Detail Produk: ') . $product->title }}</h2>
            <div>
                @if($product->is_active)
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-danger">Nonaktif</span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            {{-- Back Button --}}
            <div class="mb-4">
                <a href="{{ route('superadmin.products.index') }}"
                    class="btn btn-secondary">
                    ← Kembali ke Daftar Produk
                </a>
                <a href="{{ route('superadmin.products.edit', $product) }}"
                    class="btn btn-primary">
                    ✏️ Edit Produk
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                {{-- Kolom Kiri: Info Utama --}}
                <div class="col-lg-8">
                    {{-- Foto Produk --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="card-title mb-3">📸 Galeri Produk</h4>
                            @if($product->photos && count($product->photos) > 0)
                                <div class="row g-3">
                                    @foreach($product->photos as $photo)
                                        <div class="col-md-4">
                                            <img src="{{ asset('storage/' . $photo) }}" 
                                                alt="{{ $product->title }}"
                                                class="img-fluid rounded shadow-sm"
                                                style="width: 100%; height: 250px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 bg-light rounded">
                                    <span class="display-1">🖼️</span>
                                    <p class="text-muted mt-3">Tidak ada foto</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Video Produk --}}
                    @if($product->video)
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h4 class="card-title mb-3">🎥 Video Produk</h4>
                                <div class="ratio ratio-16x9">
                                    <video controls class="rounded w-100">
                                        <source src="{{ asset('storage/' . $product->video) }}" type="video/mp4">
                                        Browser Anda tidak mendukung video.
                                    </video>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Deskripsi Produk --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="card-title mb-3">📝 Deskripsi Produk</h4>
                            @if($product->description)
                                <div class="text-muted" style="white-space: pre-line;">{{ $product->description }}</div>
                            @else
                                <p class="text-muted">Tidak ada deskripsi</p>
                            @endif
                        </div>
                    </div>

                    {{-- Variants --}}
                    @if($product->variants && count($product->variants) > 0)
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h4 class="card-title mb-3">🎨 Varian Produk</h4>
                                <div class="row g-3">
                                    @foreach($product->variants as $variant)
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 bg-light">
                                                <strong>{{ $variant['name'] ?? 'Variant' }}:</strong>
                                                <div class="d-flex flex-wrap gap-2 mt-2">
                                                    @if(isset($variant['options']))
                                                        @foreach($variant['options'] as $option)
                                                            <span class="badge bg-secondary">{{ $option }}</span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Reviews --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-0">⭐ Ulasan Produk</h4>
                                <div>
                                    <span class="badge bg-warning text-dark fs-6">
                                        {{ number_format($product->rating, 1) }} ⭐
                                    </span>
                                    <span class="text-muted small">({{ $product->reviews->count() }} ulasan)</span>
                                </div>
                            </div>

                            @if($product->reviews->count() > 0)
                                <div class="list-group">
                                    @foreach($product->reviews->take(5) as $review)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                                                    <div class="text-warning small">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                ⭐
                                                            @else
                                                                ☆
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-0 text-muted">{{ $review->review }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                @if($product->reviews->count() > 5)
                                    <div class="text-center mt-3">
                                        <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua Ulasan</a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-4 bg-light rounded">
                                    <span class="display-4">💬</span>
                                    <p class="text-muted mt-2">Belum ada ulasan</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Distribusi Stok per Toko --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="card-title mb-3">🏪 Distribusi Stok per Toko</h4>

                            @if($product->stocks->count() > 0)
                                <div class="row g-3">
                                    @foreach($product->stocks as $stock)
                                        <div class="col-md-6">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div>
                                                            <h5 class="card-title mb-1">{{ $stock->toko->nama_toko }}</h5>
                                                            <p class="text-muted small mb-0">{{ $stock->toko->alamat }}</p>
                                                            @if($stock->toko->kepala_toko)
                                                                <p class="text-muted small mb-0">
                                                                    Kepala: {{ $stock->toko->kepala_toko->name }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="display-6 text-primary fw-bold">{{ $stock->stock }}</div>
                                                            <small class="text-muted">unit</small>
                                                        </div>
                                                    </div>

                                                    {{-- Progress Bar --}}
                                                    @php
                                                        $percentage = $product->initial_stock > 0 
                                                            ? ($stock->stock / $product->initial_stock * 100) 
                                                            : 0;
                                                        $progressClass = $stock->stock > 10 ? 'success' : ($stock->stock > 0 ? 'warning' : 'danger');
                                                    @endphp
                                                    <div class="progress mb-2" style="height: 8px;">
                                                        <div class="progress-bar bg-{{ $progressClass }}" 
                                                            style="width: {{ min($percentage, 100) }}%"></div>
                                                    </div>
                                                    <small class="text-muted">{{ number_format($percentage, 1) }}% dari stok awal</small>

                                                    {{-- Status Badge --}}
                                                    <div class="mt-3">
                                                        @if($stock->stock > 10)
                                                            <span class="badge bg-success">✅ Stok Cukup</span>
                                                        @elseif($stock->stock > 0)
                                                            <span class="badge bg-warning text-dark">⚠️ Stok Terbatas</span>
                                                        @else
                                                            <span class="badge bg-danger">❌ Stok Habis</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 bg-light rounded">
                                    <span class="display-3">🏪</span>
                                    <p class="text-muted mt-3 mb-1">Belum ada toko yang mengambil stok</p>
                                    <small class="text-muted">Kepala Toko atau Staff dapat mengalokasikan stok dari dashboard mereka</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Voucher Terkait --}}
                    @if($product->vouchers->count() > 0)
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h4 class="card-title mb-3">🎫 Voucher Terkait</h4>
                                <div class="row g-3">
                                    @foreach($product->vouchers as $voucher)
                                        <div class="col-md-6">
                                            <div class="card border-primary">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $voucher->code }}</h5>
                                                    <p class="card-text">
                                                        @if($voucher->discount_type === 'percentage')
                                                            <span class="badge bg-success">{{ $voucher->discount_value }}% OFF</span>
                                                        @else
                                                            <span class="badge bg-success">Rp {{ number_format($voucher->discount_value, 0, ',', '.') }} OFF</span>
                                                        @endif
                                                    </p>
                                                    <small class="text-muted">
                                                        Berlaku: {{ $voucher->valid_from->format('d/m/Y') }} - {{ $voucher->valid_until->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Kolom Kanan: Info Detail & Summary --}}
                <div class="col-lg-4">
                    {{-- Info Dasar --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="card-title mb-3">📦 Informasi Produk</h4>
                            
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">SKU:</td>
                                        <td><code class="fs-6">{{ $product->sku }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Kategori:</td>
                                        <td>
                                            @forelse($product->categories as $category)
                                                <span class="badge" style="background-color: #f3e8ff; color: #7c3aed;">
                                                    {{ $category->name }}
                                                </span>
                                            @empty
                                                <span class="text-muted small">-</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tags:</td>
                                        <td>
                                            @if($product->tags && count($product->tags) > 0)
                                                @foreach($product->tags as $tag)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $tag }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Status:</td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Nonaktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Dibuat:</td>
                                        <td class="small">{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Update Terakhir:</td>
                                        <td class="small">{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @if($product->deleted_at)
                                        <tr>
                                            <td class="text-muted">Dihapus:</td>
                                            <td class="small text-danger">{{ $product->deleted_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Harga --}}
                    <div class="card shadow-sm mb-4 border-primary">
                        <div class="card-body">
                            <h4 class="card-title mb-3">💰 Harga</h4>
                            
                            @if($product->discount_price)
                                <div class="mb-2">
                                    <small class="text-muted">Harga Normal:</small>
                                    <div class="text-decoration-line-through text-muted fs-5">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Harga Diskon:</small>
                                    <div class="display-6 text-danger fw-bold">
                                        Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="alert alert-danger mb-0 py-2">
                                    <strong>Hemat:</strong> 
                                    Rp {{ number_format($product->price - $product->discount_price, 0, ',', '.') }}
                                    ({{ number_format((($product->price - $product->discount_price) / $product->price) * 100, 1) }}%)
                                </div>
                            @else
                                <div class="display-5 fw-bold text-primary">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            @endif

                            <div class="mt-3 pt-3 border-top">
                                <small class="text-muted">Harga Final:</small>
                                <div class="fs-4 fw-bold text-success">
                                    Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Stok Summary --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="card-title mb-3">📊 Ringkasan Stok</h4>
                            
                            <div class="mb-3 p-3 bg-primary bg-opacity-10 rounded">
                                <small class="text-muted d-block">Stok Awal</small>
                                <div class="display-6 fw-bold text-primary">{{ $product->initial_stock }}</div>
                            </div>

                            <div class="mb-3 p-3 bg-warning bg-opacity-10 rounded">
                                <small class="text-muted d-block">Telah Dialokasikan</small>
                                <div class="display-6 fw-bold text-warning">{{ $product->stocks->sum('stock') }}</div>
                            </div>

                            <div class="mb-3 p-3 bg-success bg-opacity-10 rounded">
                                <small class="text-muted d-block">Sisa Stok Awal</small>
                                <div class="display-6 fw-bold text-success">{{ $product->remaining_initial_stock }}</div>
                            </div>

                            {{-- Progress Alokasi --}}
                            @php
                                $allocationPercentage = $product->initial_stock > 0 
                                    ? ($product->stocks->sum('stock') / $product->initial_stock * 100) 
                                    : 0;
                            @endphp
                            <div class="mt-3">
                                <small class="text-muted d-block mb-2">Persentase Alokasi</small>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-info" 
                                        style="width: {{ min($allocationPercentage, 100) }}%">
                                        {{ number_format($allocationPercentage, 1) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Update Initial Stock --}}
                    <div class="card shadow-sm mb-4 border-warning">
                        <div class="card-body">
                            <h4 class="card-title mb-3">🔧 Update Stok Awal</h4>
                            <form action="{{ route('superadmin.stocks.updateInitial', $product) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <input type="number" name="initial_stock" 
                                        value="{{ $product->initial_stock }}" 
                                        min="0" required
                                        class="form-control form-control-lg">
                                    <button type="submit" class="btn btn-warning">
                                        💾 Update
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Perubahan akan mempengaruhi perhitungan sisa stok
                                </small>
                            </form>
                        </div>
                    </div>

                    {{-- Rating & Review Stats --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="card-title mb-3">⭐ Statistik Rating</h4>
                            
                            <div class="text-center mb-3">
                                <div class="display-4 fw-bold text-warning">{{ number_format($product->rating, 1) }}</div>
                                <div class="text-muted">dari 5.0</div>
                                <div class="small text-muted mt-1">{{ $product->reviews->count() }} ulasan</div>
                            </div>

                            @if($product->reviews->count() > 0)
                                @php
                                    $ratingCounts = $product->reviews->groupBy('rating')->map->count();
                                @endphp
                                <div class="mt-3">
                                    @for($i = 5; $i >= 1; $i--)
                                        @php
                                            $count = $ratingCounts->get($i, 0);
                                            $percentage = $product->reviews->count() > 0 
                                                ? ($count / $product->reviews->count() * 100) 
                                                : 0;
                                        @endphp
                                        <div class="d-flex align-items-center mb-2">
                                            <small class="me-2">{{ $i }}⭐</small>
                                            <div class="progress flex-grow-1" style="height: 10px;">
                                                <div class="progress-bar bg-warning" 
                                                    style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <small class="ms-2 text-muted">{{ $count }}</small>
                                        </div>
                                    @endfor
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card shadow-sm border-danger">
                        <div class="card-body">
                            <h4 class="card-title mb-3 text-danger">⚠️ Aksi Berbahaya</h4>
                            
                            <form action="{{ route('superadmin.products.destroy', $product) }}" 
                                method="POST" 
                                onsubmit="return confirm('⚠️ PERINGATAN!\n\nMenghapus produk ini akan:\n- Menghapus semua foto produk\n- Menghapus semua data stok\n- Menghapus semua review\n\nApakah Anda yakin?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    🗑️ Hapus Produk
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2 text-center">
                                Tindakan ini tidak dapat dibatalkan
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>