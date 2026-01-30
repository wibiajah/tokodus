{{-- resources/views/kepala-toko/products/show.blade.php --}}
<x-admin-layout title="Detail Produk">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-box"></i> Detail Produk
            </h1>
            <div>
                <a href="{{ route('kepala-toko.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                @if($product->total_stock > 0)
                <a href="{{ route('kepala-toko.stock-requests.create', $product) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Request Stok
                </a>
                @endif
            </div>
        </div>

        <div class="row">
            <!-- Product Info -->
            <div class="col-lg-5 mb-4">
                <!-- Image Gallery -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @if($product->photos && count($product->photos) > 0)
                        <div id="productCarousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($product->photos as $index => $photo)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $photo) }}" 
                                         class="d-block w-100 rounded" 
                                         alt="{{ $product->title }}"
                                         style="height: 400px; object-fit: cover;">
                                </div>
                                @endforeach
                            </div>
                            @if(count($product->photos) > 1)
                            <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </a>
                            <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </a>
                            @endif
                        </div>
                        @else
                        <img src="{{ $product->thumbnail }}" 
                             class="img-fluid rounded" 
                             alt="{{ $product->title }}">
                        @endif
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Dasar</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%"><strong>SKU:</strong></td>
                                <td><code>{{ $product->sku }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Kategori:</strong></td>
                                <td>
                                    @foreach($product->categories as $category)
                                    <span class="badge badge-info">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Harga:</strong></td>
                                <td>{{ $product->formatted_price }}</td>
                            </tr>
                            @if($product->ukuran)
                            <tr>
                                <td><strong>Ukuran:</strong></td>
                                <td>{{ $product->ukuran }}</td>
                            </tr>
                            @endif
                            @if($product->jenis_bahan)
                            <tr>
                                <td><strong>Bahan:</strong></td>
                                <td>{{ $product->jenis_bahan }}</td>
                            </tr>
                            @endif
                            @if($product->tipe)
                            <tr>
                                <td><strong>Tipe:</strong></td>
                                <td>{{ $product->tipe_display }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-7 mb-4">
                <!-- Title & Description -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h3 class="font-weight-bold mb-3">{{ $product->title }}</h3>
                        
                        @if($product->description)
                        <div class="mb-4">
                            <h6 class="font-weight-bold">Deskripsi:</h6>
                            <p class="text-muted">{{ $product->description }}</p>
                        </div>
                        @endif

                        <!-- Stock Summary -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-left-success">
                                    <div class="card-body py-3">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Stok Warehouse
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $product->total_stock }} pcs
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-left-primary">
                                    <div class="card-body py-3">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Stok Toko Saya
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ array_sum($variantStocks) }} pcs
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Variants & Stock -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-palette"></i> Varian & Stok
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($product->variants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="40%">Varian</th>
                                        <th width="30%">Stok Warehouse</th>
                                        <th width="30%">Stok Toko Saya</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->variants as $color)
                                    @if($color->hasChildren())
                                        <!-- Color with sizes -->
                                        <tr class="table-active">
                                            <td colspan="3">
                                                <strong>
                                                    @if($color->photo)
                                                    <img src="{{ $color->photo_url }}" 
                                                         alt="{{ $color->name }}"
                                                         width="30" 
                                                         height="30"
                                                         class="rounded mr-2">
                                                    @endif
                                                    {{ $color->name }}
                                                </strong>
                                            </td>
                                        </tr>
                                        @foreach($color->children as $size)
                                        <tr>
                                            <td class="pl-5">
                                                <i class="fas fa-arrow-right text-muted mr-2"></i>
                                                {{ $size->name }}
                                                @if($size->price)
                                                <br>
                                                <small class="text-muted">{{ $size->formatted_price }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $size->stock_pusat > 0 ? 'success' : 'danger' }}">
                                                    {{ $size->stock_pusat }} pcs
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $variantStocks[$size->id] > 0 ? 'primary' : 'secondary' }}">
                                                    {{ $variantStocks[$size->id] ?? 0 }} pcs
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <!-- Color without sizes -->
                                        <tr>
                                            <td>
                                                @if($color->photo)
                                                <img src="{{ $color->photo_url }}" 
                                                     alt="{{ $color->name }}"
                                                     width="30" 
                                                     height="30"
                                                     class="rounded mr-2">
                                                @endif
                                                <strong>{{ $color->name }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $color->stock_pusat > 0 ? 'success' : 'danger' }}">
                                                    {{ $color->stock_pusat }} pcs
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $variantStocks[$color->id] > 0 ? 'primary' : 'secondary' }}">
                                                    {{ $variantStocks[$color->id] ?? 0 }} pcs
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted text-center mb-0">Produk ini belum memiliki varian</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>