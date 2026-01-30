<x-admin-layout :title="'Detail Produk - ' . $product->title">
    <div class="container-fluid">
        <!-- Header Actions -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('superadmin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('superadmin.products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Edit Produk
            </a>
        </div>

        <!-- HERO SECTION - Product Header -->
        <div class="card shadow-sm mb-4" style="border-radius: 12px; border: none;">
            <div class="card-body p-4" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border-radius: 12px;">
                <div class="row align-items-center">
                    <div class="col-lg-8 text-white">
                        <div class="mb-2">
                            @forelse($product->categories as $category)
                                <span class="badge badge-light mr-1">{{ $category->name }}</span>
                            @empty
                                <span class="badge badge-light">Tanpa Kategori</span>
                            @endforelse
                            
                            @if($product->is_active)
                                <span class="badge badge-success ml-2">
                                    <i class="fas fa-check-circle"></i> Aktif
                                </span>
                            @else
                                <span class="badge badge-danger ml-2">
                                    <i class="fas fa-times-circle"></i> Tidak Aktif
                                </span>
                            @endif
                        </div>
                        
                        <div>
                            <h2 class="font-weight-bold mb-3">{{ $product->title }}</h2>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge badge-warning mr-3" style="font-size: 0.95rem; padding: 0.5rem 1rem;">
                                SKU: {{ $product->sku }}
                            </span>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-star text-warning mr-2"></i>
                                <span class="h5 mb-0 mr-2">{{ number_format($product->rating, 1) }}</span>
                                <small>({{ $product->review_count }} review)</small>
                            </div>
                        </div>

                        @php
                            // Hitung Stok Pusat (dari variants)
                            $stokPusat = 0;
                            $allColorVariants = $product->variants()->whereNull('parent_id')->with('children')->get();
                            foreach($allColorVariants as $cv) {
                                if($cv->children && $cv->children->count() > 0) {
                                    $stokPusat += $cv->children->sum('stock_pusat');
                                } else {
                                    $stokPusat += $cv->stock_pusat;
                                }
                            }
                            
                            // Hitung Total Stok di Toko (dari product_variant_stocks)
                            $stokDiToko = $product->variantStocks()->sum('stock');
                            
                            // Total Stok Keseluruhan
                            $totalStokKeseluruhan = $stokPusat + $stokDiToko;
                        @endphp

                        <div class="row mt-4">
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-cubes text-info" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div>
                                        <small class="d-block" style="opacity: 0.9;">Total Stok</small>
                                        <h4 class="mb-0 font-weight-bold">{{ $totalStokKeseluruhan }} unit</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-warehouse text-primary" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div>
                                        <small class="d-block" style="opacity: 0.9;">Stok Pusat</small>
                                        <h4 class="mb-0 font-weight-bold">{{ $stokPusat }} unit</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-store text-success" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div>
                                        <small class="d-block" style="opacity: 0.9;">Stok di Toko</small>
                                        <h4 class="mb-0 font-weight-bold">{{ $stokDiToko }} unit</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 text-center">
                        @if($product->photos && count($product->photos) > 0)
                            <img src="{{ asset('storage/' . $product->photos[0]) }}" 
                                 alt="{{ $product->title }}"
                                 class="img-fluid rounded shadow"
                                 style="max-height: 250px; object-fit: cover; border: 4px solid rgba(255,255,255,0.3);">
                        @else
                            <div class="rounded bg-white d-flex align-items-center justify-content-center" style="height: 250px;">
                                <div class="text-center">
                                    <i class="fas fa-image text-gray-300" style="font-size: 4rem;"></i>
                                    <p class="text-muted mt-2 mb-0">Tidak ada foto</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- QUICK INFO CARDS -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary-soft d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; background: rgba(78, 115, 223, 0.1);">
                                <i class="fas fa-cubes text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Total Stok</small>
                                @php
                                    $quickStokPusat = 0;
                                    $quickColorVariants = $product->variants()->whereNull('parent_id')->with('children')->get();
                                    foreach($quickColorVariants as $qcv) {
                                        if($qcv->children && $qcv->children->count() > 0) {
                                            $quickStokPusat += $qcv->children->sum('stock_pusat');
                                        } else {
                                            $quickStokPusat += $qcv->stock_pusat;
                                        }
                                    }
                                    $quickStokToko = $product->variantStocks()->sum('stock');
                                    $quickTotalStok = $quickStokPusat + $quickStokToko;
                                @endphp
                                <h5 class="mb-0 font-weight-bold">{{ $quickTotalStok }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary-soft d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; background: rgba(78, 115, 223, 0.1);">
                                <i class="fas fa-palette text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Total Varian</small>
                                <h5 class="mb-0 font-weight-bold">{{ $product->variants->count() }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success-soft d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; background: rgba(28, 200, 138, 0.1);">
                                <i class="fas fa-tag text-success"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Harga Normal</small>
                                <h5 class="mb-0 font-weight-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($product->discount_price)
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-warning-soft d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; background: rgba(246, 194, 62, 0.1);">
                                <i class="fas fa-percent text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Harga Diskon</small>
                                <h5 class="mb-0 font-weight-bold text-success">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-info-soft d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; background: rgba(54, 185, 204, 0.1);">
                                <i class="fas fa-cube text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Tipe Produk</small>
                                <h5 class="mb-0 font-weight-bold">{{ ucfirst($product->tipe) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="row">
            <!-- LEFT COLUMN -->
            <div class="col-lg-8">
                <!-- Deskripsi & Spesifikasi -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-3">
                            <i class="fas fa-align-left text-primary mr-2"></i>Deskripsi Produk
                        </h5>
                        @if($product->description)
                            <p class="text-gray-700 mb-0" style="line-height: 1.7;">{{ $product->description }}</p>
                        @else
                            <p class="text-muted mb-0">Tidak ada deskripsi produk.</p>
                        @endif

                        <hr class="my-4">

                        <h5 class="font-weight-bold mb-3">
                            <i class="fas fa-cogs text-info mr-2"></i>Spesifikasi Lengkap
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-ruler-combined text-primary"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Ukuran</small>
                                        <strong>{{ $product->ukuran }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-cube text-warning"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Tipe</small>
                                        <strong>{{ ucfirst($product->tipe) }}</strong>
                                    </div>
                                </div>
                            </div>

                            @if($product->jenis_bahan)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-layer-group text-success"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Jenis Bahan</small>
                                        <strong>{{ $product->jenis_bahan }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($product->cetak)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-print text-info"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Cetak</small>
                                        <strong>{{ $product->cetak }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($product->finishing)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-magic text-danger"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Finishing</small>
                                        <strong>{{ $product->finishing }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-dollar-sign text-success"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Harga Normal</small>
                                        <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>

                            @if($product->discount_price)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-percent text-warning"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Harga Diskon</small>
                                        <strong class="text-success">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</strong>
                                        <span class="badge badge-danger ml-2">
                                            HEMAT {{ number_format((($product->price - $product->discount_price) / $product->price) * 100, 0) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-barcode text-secondary"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">SKU / Kode Produk</small>
                                        <strong>{{ $product->sku }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-toggle-on text-{{ $product->is_active ? 'success' : 'danger' }}"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Status Produk</small>
                                        <strong class="text-{{ $product->is_active ? 'success' : 'danger' }}">
                                            {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-cubes text-info"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Total Stok Keseluruhan</small>
                                        @php
                                            $specStokPusat = 0;
                                            $specColorVariants = $product->variants()->whereNull('parent_id')->with('children')->get();
                                            foreach($specColorVariants as $scv) {
                                                if($scv->children && $scv->children->count() > 0) {
                                                    $specStokPusat += $scv->children->sum('stock_pusat');
                                                } else {
                                                    $specStokPusat += $scv->stock_pusat;
                                                }
                                            }
                                            $specStokToko = $product->variantStocks()->sum('stock');
                                            $specTotalStok = $specStokPusat + $specStokToko;
                                        @endphp
                                        <strong>{{ $specTotalStok }} unit</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-warehouse text-warning"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Stok Pusat</small>
                                        <strong>{{ $specStokPusat }} unit</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-store text-success"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Total Stok di Toko</small>
                                        <strong>{{ $specStokToko }} unit</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-star text-warning"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Rating Produk</small>
                                        <strong>{{ number_format($product->rating, 1) }}/5.0</strong>
                                        <small class="text-muted">({{ $product->review_count }} review)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-calendar-plus text-primary"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Dibuat</small>
                                        <strong>{{ $product->created_at->format('d M Y, H:i') }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-calendar-check text-success"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Terakhir Update</small>
                                        <strong>{{ $product->updated_at->format('d M Y, H:i') }}</strong>
                                    </div>
                                </div>
                            </div>

                            @if($product->categories && $product->categories->count() > 0)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-folder text-info"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block">Kategori</small>
                                        @foreach($product->categories as $category)
                                            <span class="badge badge-info mr-1">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($product->tags && count($product->tags) > 0)
                            <div class="col-md-12 mb-3">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="fas fa-tags text-secondary"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <small class="text-muted d-block mb-1">Tags Produk</small>
                                        @foreach($product->tags as $tag)
                                            <span class="badge badge-light border mr-1 mb-1">
                                                <i class="fas fa-tag"></i> {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Varian Produk Grid -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-4">
                            <i class="fas fa-palette text-primary mr-2"></i>Varian Produk (Stok Pusat)
                        </h5>

                        @php
                            $colorVariants = $product->variants()
                                ->with('children')
                                ->whereNull('parent_id')
                                ->get();
                        @endphp

                        @forelse($colorVariants as $color)
                            <div class="border rounded p-3 mb-3" style="background: #f8f9fc;">
                                <div class="row align-items-center mb-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            @if($color->photo)
                                                <img src="{{ asset('storage/' . $color->photo) }}" 
                                                     class="rounded mr-3" 
                                                     alt="{{ $color->name }}"
                                                     style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #4e73df;">
                                            @endif
                                            <div>
                                                <h6 class="font-weight-bold mb-0">{{ $color->name }}</h6>
                                                <small class="text-muted">Varian Warna</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        @php
                                            $totalStokColor = $color->children && $color->children->count() > 0 
                                                ? $color->children->sum('stock_pusat') 
                                                : $color->stock_pusat;
                                        @endphp
                                        <span class="badge badge-primary" style="font-size: 0.9rem; padding: 0.4rem 0.8rem;">
                                            <i class="fas fa-boxes"></i> {{ $totalStokColor }} unit
                                        </span>
                                    </div>
                                </div>

                                @if($color->children && $color->children->count() > 0)
                                    <div class="row">
                                        @foreach($color->children as $size)
                                            <div class="col-lg-4 col-md-6 mb-2">
                                                <div class="border rounded p-2 bg-white d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center" style="flex: 1;">
                                                        @if($size->photo)
                                                            <img src="{{ asset('storage/' . $size->photo) }}" 
                                                                 class="rounded mr-2" 
                                                                 alt="{{ $size->name }}"
                                                                 style="width: 35px; height: 35px; object-fit: cover;">
                                                        @endif
                                                        <div>
                                                            <strong class="d-block" style="font-size: 0.9rem;">{{ $size->name }}</strong>
                                                            @if($size->price)
                                                                <small class="text-success">Rp {{ number_format($size->price, 0, ',', '.') }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <span class="badge badge-{{ $size->stock_pusat > 0 ? 'success' : 'secondary' }}">
                                                        {{ $size->stock_pusat }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0 text-center"><small>Tidak ada sub-varian</small></p>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-gray-300 mb-3"></i>
                                <p class="text-muted">Belum ada varian produk</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Galeri Foto -->
                @if($product->photos && count($product->photos) > 0)
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-3">
                            <i class="fas fa-images text-warning mr-2"></i>Galeri Foto
                        </h5>
                        <div class="row">
                            @foreach($product->photos as $index => $photo)
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <img src="{{ asset('storage/' . $photo) }}" 
                                         class="img-fluid rounded shadow-sm" 
                                         alt="Foto {{ $index + 1 }}"
                                         style="height: 200px; width: 100%; object-fit: cover; cursor: pointer;"
                                         onclick="$('#productPhotosCarousel').carousel({{ $index }})"
                                         data-toggle="modal"
                                         data-target="#photoModal">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Video Produk -->
                @if($product->video)
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-3">
                            <i class="fas fa-video text-danger mr-2"></i>Video Produk
                        </h5>
                        <video controls class="w-100 rounded" style="max-height: 400px;">
                            <source src="{{ asset('storage/' . $product->video) }}" type="video/mp4">
                            Browser Anda tidak mendukung video.
                        </video>
                    </div>
                </div>
                @endif

                <!-- Penyebaran Stok -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-3">
                            <i class="fas fa-warehouse text-success mr-2"></i>Penyebaran Stok ke Toko
                        </h5>

                        @php
                            $variantStocks = $product->variantStocks()
                                ->with(['variant.parent', 'toko'])
                                ->get()
                                ->groupBy('toko_id');
                        @endphp

                        @if($variantStocks->count() > 0)
                            @foreach($variantStocks as $tokoId => $stocks)
                                @php
                                    $toko = $stocks->first()->toko;
                                    $totalStokToko = $stocks->sum('stock');
                                @endphp
                                
                                <div class="border rounded p-3 mb-3" style="background: #f8f9fc;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="font-weight-bold mb-1">
                                                <i class="fas fa-store text-success mr-1"></i>{{ $toko->nama_toko }}
                                            </h6>
                                            @if($toko->alamat)
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt"></i> {{ $toko->alamat }}
                                                </small>
                                            @endif
                                        </div>
                                        <span class="badge badge-success" style="font-size: 0.95rem; padding: 0.5rem 1rem;">
                                            <i class="fas fa-boxes"></i> {{ $totalStokToko }} unit
                                        </span>
                                    </div>

                                    <!-- Detail Varian per Toko -->
                                    <div class="row">
                                        @foreach($stocks as $stock)
                                            <div class="col-lg-6 mb-2">
                                                <div class="d-flex justify-content-between align-items-center bg-white rounded p-2">
                                                    <div class="d-flex align-items-center" style="flex: 1;">
                                                        @if($stock->variant->photo)
                                                            <img src="{{ asset('storage/' . $stock->variant->photo) }}" 
                                                                 alt="{{ $stock->variant->name }}"
                                                                 class="rounded mr-2"
                                                                 style="width: 35px; height: 35px; object-fit: cover;">
                                                        @endif
                                                        <div>
                                                            <strong class="d-block" style="font-size: 0.9rem;">{{ $stock->variant->name }}</strong>
                                                            @if($stock->variant->parent)
                                                                <small class="text-muted">{{ $stock->variant->parent->name }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <span class="badge badge-info">{{ $stock->stock }} unit</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <!-- Summary Total Distribusi -->
                            <div class="border-top pt-3 mt-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center p-2 bg-light rounded">
                                            <small class="text-muted d-block">Total Toko</small>
                                            <h5 class="font-weight-bold text-info mb-0">{{ $variantStocks->count() }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-2 bg-light rounded">
                                            <small class="text-muted d-block">Total Stok Terdistribusi</small>
                                            <h5 class="font-weight-bold text-success mb-0">{{ $product->variantStocks()->sum('stock') }} unit</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-2 bg-light rounded">
                                            <small class="text-muted d-block">Stok Tersisa di Pusat</small>
                                            @php
                                                $sisaStokPusat = 0;
                                                $sisaColorVariants = $product->variants()->whereNull('parent_id')->with('children')->get();
                                                foreach($sisaColorVariants as $scv) {
                                                    if($scv->children && $scv->children->count() > 0) {
                                                        $sisaStokPusat += $scv->children->sum('stock_pusat');
                                                    } else {
                                                        $sisaStokPusat += $scv->stock_pusat;
                                                    }
                                                }
                                            @endphp
                                            <h5 class="font-weight-bold text-warning mb-0">{{ $sisaStokPusat }} unit</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                <p class="text-muted">Belum ada distribusi stok ke toko</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- History Distribusi -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-3">
                            <i class="fas fa-history text-secondary mr-2"></i>History Distribusi (10 Terakhir)
                        </h5>

                        @php
                            $logs = $product->distributionLogs()
                                ->with(['variant.parent', 'toko', 'performedBy'])
                                ->latest()
                                ->take(10)
                                ->get();
                        @endphp

                        @if($logs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Varian</th>
                                            <th>Toko</th>
                                            <th>Jenis</th>
                                            <th class="text-center">Qty</th>
                                            <th>Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logs as $log)
                                            <tr>
                                                <td><small>{{ $log->created_at->format('d/m/Y H:i') }}</small></td>
                                                <td>
                                                    <strong>{{ $log->variant->name }}</strong>
                                                    @if($log->variant->parent)
                                                        <br><small class="text-muted">{{ $log->variant->parent->name }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $log->toko->nama_toko }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $log->type === 'in' ? 'success' : 'danger' }}">
                                                        {{ $log->type === 'in' ? 'Masuk' : 'Keluar' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <strong class="text-{{ $log->type === 'in' ? 'success' : 'danger' }}">
                                                        {{ $log->type === 'in' ? '+' : '-' }}{{ $log->quantity }}
                                                    </strong>
                                                </td>
                                                <td><small>{{ $log->performedBy->name }}</small></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-2x text-gray-300 mb-3"></i>
                                <p class="text-muted mb-0">Belum ada history distribusi</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-lg-4">
                <!-- Request Stok Terbaru -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold mb-3">
                            <i class="fas fa-clipboard-list text-warning mr-2"></i>Request Stok Terbaru
                        </h6>

                        @php
                            $requests = $product->stockRequests()
                                ->with(['toko', 'requestedBy'])
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp

                        @if($requests->count() > 0)
                            @foreach($requests as $request)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong class="d-block">{{ $request->toko->nama_toko }}</strong>
                                            <small class="text-muted">
                                                <i class="fas fa-user"></i> {{ $request->requestedBy->name }}
                                            </small>
                                        </div>
                                        <span class="badge badge-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'approved' ? 'success' : 'danger') }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">{{ $request->created_at->format('d/m/Y H:i') }}</small>
                                        <small class="font-weight-bold">{{ $request->total_quantity }} unit</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-gray-300 mb-2"></i>
                                <p class="text-muted mb-0"><small>Belum ada request</small></p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Reviews Terbaru -->
                @if($product->reviews()->count() > 0)
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold mb-3">
                            <i class="fas fa-star text-warning mr-2"></i>Review Terbaru
                        </h6>

                        @foreach($product->reviews()->latest()->take(3)->get() as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>{{ $review->user->name }}</strong>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-{{ $i <= $review->rating ? 'warning' : 'gray-300' }}" style="font-size: 0.8rem;"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-700 mb-1" style="font-size: 0.9rem;">{{ $review->review }}</p>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Informasi Tambahan -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold mb-3">
                            <i class="fas fa-info-circle text-info mr-2"></i>Informasi Tambahan
                        </h6>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Dibuat</small>
                            <strong>{{ $product->created_at->format('d M Y, H:i') }}</strong>
                        </div>

                        <div>
                            <small class="text-muted d-block mb-1">Terakhir Update</small>
                            <strong>{{ $product->updated_at->format('d M Y, H:i') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk foto (optional) -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div id="productPhotosCarousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @if($product->photos && count($product->photos) > 0)
                                @foreach($product->photos as $index => $photo)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $photo) }}" 
                                             class="d-block w-100" 
                                             alt="Foto Produk {{ $index + 1 }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <a class="carousel-control-prev" href="#productPhotosCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </a>
                        <a class="carousel-control-next" href="#productPhotosCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>