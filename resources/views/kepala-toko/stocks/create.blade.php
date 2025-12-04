<x-admin-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">Set Stok Produk</h2>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                {{-- Product Info --}}
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title border-bottom pb-2 mb-3">
                                <i class="bi bi-box-seam me-2"></i>Info Produk
                            </h5>

                            @if($product->photos && count($product->photos) > 0)
                                <img src="{{ Storage::url($product->photos[0]) }}" 
                                    alt="{{ $product->title }}"
                                    class="img-fluid rounded mb-3">
                            @endif

                            <div class="mb-2">
                                <h6 class="fw-bold">{{ $product->title }}</h6>
                                <code class="d-block">{{ $product->sku }}</code>
                            </div>

                            <div class="mb-2">
                                <small class="text-muted">Harga:</small>
                                <div class="fs-5 fw-bold text-primary">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </div>

                            @if($product->categories->count() > 0)
                                <div class="mb-2">
                                    <small class="text-muted">Kategori:</small>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @foreach($product->categories as $category)
                                            <span class="badge bg-purple">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Stok Info --}}
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Stok</h6>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Stok Awal Total:</span>
                                <span class="badge bg-secondary fs-6">{{ $product->initial_stock }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Sudah Dialokasikan:</span>
                                <span class="badge bg-info fs-6">{{ $product->stocks->sum('stock') }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <span class="fw-bold">Stok Tersedia:</span>
                                <span class="badge bg-success fs-5">{{ $product->remaining_initial_stock }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Set Stok --}}
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title border-bottom pb-2 mb-4">
                                <i class="bi bi-clipboard-check me-2"></i>Set Stok untuk Toko Anda
                            </h5>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Info Toko --}}
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-shop fs-3 me-3"></i>
                                    <div>
                                        <div class="fw-bold">{{ auth()->user()->toko->nama_toko }}</div>
                                        <small>{{ auth()->user()->toko->alamat }}</small>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('kepala-toko.stocks.store', $product) }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        Jumlah Stok yang Diambil <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="stock" id="stock-input" 
                                        class="form-control form-control-lg" 
                                        value="{{ old('stock') }}" 
                                        min="1" 
                                        max="{{ $product->remaining_initial_stock }}" 
                                        required
                                        placeholder="Masukkan jumlah stok">
                                    <small class="text-muted">
                                        Maksimal: {{ $product->remaining_initial_stock }} unit
                                    </small>
                                </div>

                                {{-- Preview Calculation --}}
                                <div class="card bg-light mb-4">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">📊 Preview Perhitungan:</h6>
                                        
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="border-end">
                                                    <small class="text-muted d-block">Stok Awal Sekarang</small>
                                                    <div class="fs-4 fw-bold text-secondary">
                                                        {{ $product->remaining_initial_stock }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border-end">
                                                    <small class="text-muted d-block">Stok yang Diambil</small>
                                                    <div class="fs-4 fw-bold text-primary" id="taken-stock">
                                                        0
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted d-block">Sisa Stok Awal</small>
                                                <div class="fs-4 fw-bold text-success" id="remaining-stock">
                                                    {{ $product->remaining_initial_stock }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Warning --}}
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <strong>Perhatian!</strong> Stok yang Anda ambil akan langsung dikurangi dari stok awal dan tidak bisa dibatalkan. 
                                    Pastikan jumlah sudah benar sebelum menyimpan.
                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-between pt-3 border-top">
                                    <a href="{{ route('kepala-toko.stocks.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-save"></i> Set Stok Toko
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-purple {
            background-color: #6f42c1 !important;
            color: white;
        }
    </style>


    <script>
        document.getElementById('stock-input').addEventListener('input', function(e) {
            const value = parseInt(e.target.value) || 0;
            const currentStock = {{ $product->remaining_initial_stock }};
            
            document.getElementById('taken-stock').textContent = value;
            document.getElementById('remaining-stock').textContent = Math.max(0, currentStock - value);
        });
    </script>

</x-admin-layout>