{{-- resources/views/kepala-toko/stock-requests/show.blade.php --}}
<x-admin-layout title="Detail Request Stok">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-alt"></i> Detail Request Stok #{{ $stockRequest->id }}
            </h1>
            <a href="{{ route('kepala-toko.stock-requests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle"></i> {!! session('warning') !!}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        @endif

        {{-- ✅ TAMBAHAN: Alert Warning jika Produk Dihapus --}}
        @if(!$stockRequest->product)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                <div>
                    <strong>Peringatan:</strong> Produk yang direquest sudah tidak tersedia di sistem.
                    <br>
                    <small>Data ini ditampilkan untuk keperluan audit dan riwayat transaksi.</small>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        <div class="row">
            <!-- Request Info -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 bg-{{ $stockRequest->status_badge }} text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-info-circle"></i> Informasi Request
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <span class="badge badge-{{ $stockRequest->status_badge }} p-3" style="font-size: 1rem;">
                                {{ $stockRequest->status_label }}
                            </span>
                        </div>

                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%"><strong>ID Request:</strong></td>
                                <td><code>#{{ $stockRequest->id }}</code></td>
                            </tr>
                            {{-- ✅ PERBAIKAN: Null Check Produk --}}
                            <tr>
                                <td><strong>Produk:</strong></td>
                                <td>
                                    @if($stockRequest->product)
                                        {{ $stockRequest->product->title }}
                                    @else
                                        <span class="text-danger">[Produk Dihapus]</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>SKU:</strong></td>
                                <td>
                                    @if($stockRequest->product)
                                        <code>{{ $stockRequest->product->sku }}</code>
                                    @else
                                        <code class="text-muted">N/A</code>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Toko:</strong></td>
                                <td>
                                    <i class="fas fa-store text-info"></i>
                                    {{ $stockRequest->toko->nama_toko }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Direquest oleh:</strong></td>
                                <td>
                                    {{ $stockRequest->requestedBy->name }}
                                    <br>
                                    <small class="text-muted">{{ ucfirst($stockRequest->requestedBy->role) }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Request:</strong></td>
                                <td>
                                    <small>
                                        <i class="far fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($stockRequest->requested_at)->format('d M Y H:i:s') }}
                                    </small>
                                </td>
                            </tr>
                        </table>

                        @php
                            $isProcessed = !empty($stockRequest->processed_by) && $stockRequest->processedBy;
                        @endphp

                        @if($isProcessed || $stockRequest->notes || ($stockRequest->status === 'approved' && $stockRequest->approval_notes) || ($stockRequest->status === 'rejected' && $stockRequest->rejection_reason))
                        <hr>
                        <div class="x931-info-container border rounded p-3 mb-3" style="background-color: #f8f9fc;">
                            
                            @if($isProcessed)
                            <div class="mb-3 pb-3 border-bottom">
                                <strong class="text-{{ $stockRequest->status_badge }}">
                                    <i class="fas fa-user-check"></i> Diproses oleh:
                                </strong><br>
                                <span class="text-dark">{{ $stockRequest->processedBy->name }}</span><br>
                                <small class="text-muted">{{ ucfirst($stockRequest->processedBy->role) }}</small>
                                @if($stockRequest->processed_at)
                                <br><small class="text-muted">
                                    <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($stockRequest->processed_at)->format('d M Y H:i:s') }}
                                </small>
                                @endif
                            </div>
                            @endif

                            @if($stockRequest->notes)
                            <div class="mb-3 pb-3 border-bottom">
                                <strong><i class="fas fa-sticky-note text-info"></i> Catatan Anda:</strong>
                                <p class="text-muted mb-0 mt-2">{{ $stockRequest->notes }}</p>
                            </div>
                            @endif

                            @if($stockRequest->status === 'approved' && $stockRequest->approval_notes)
                            <div class="mb-3 pb-3 border-bottom">
                                <strong class="text-success">
                                    <i class="fas fa-check-circle"></i> Catatan Approval:
                                </strong>
                                <p class="text-dark mb-0 mt-2">{{ $stockRequest->approval_notes }}</p>
                            </div>
                            @endif

                            @if($stockRequest->status === 'rejected' && $stockRequest->rejection_reason)
                            <div class="mb-0">
                                <strong class="text-danger">
                                    <i class="fas fa-times-circle"></i> Alasan Ditolak:
                                </strong>
                                <p class="text-dark mb-0 mt-2">{{ $stockRequest->rejection_reason }}</p>
                            </div>
                            @endif

                        </div>
                        @endif

                        @if($stockRequest->status === 'pending')
                        <hr>
                        <form action="{{ route('kepala-toko.stock-requests.cancel', $stockRequest) }}" 
                              method="POST"
                              onsubmit="return confirm('Batalkan request ini?')">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-times"></i> Batalkan Request
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items Detail -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-box"></i> Detail Varian yang Direquest
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
    <tr>
        <th width="5%">#</th>
        <th width="10%">Foto</th>
        <th width="30%">Varian</th>
        <th width="15%">Harga</th>
        <th width="15%">Jumlah Request</th>
        <th width="25%">Status Stok</th>
    </tr>
</thead>
                                <tbody>
                                    @foreach($itemsWithDetails as $index => $item)
                                    <tr>
    <td>{{ $loop->iteration }}</td>
    <td class="text-center">
        @if($item['variant'] && $item['variant']->photo)
        <img src="{{ asset('storage/' . $item['variant']->photo) }}" 
             alt="{{ $item['display_name'] }}" 
             class="img-thumbnail"
             style="width: 60px; height: 60px; object-fit: cover;">
        @else
        <div class="bg-light d-flex align-items-center justify-content-center" 
             style="width: 60px; height: 60px;">
            <i class="fas fa-image text-muted fa-2x"></i>
        </div>
        @endif
    </td>
    <td>
        @if($item['variant'])
            @if($item['variant']->parent_id)
                {{-- Ini ukuran (child), tampilkan warna parent --}}
                <div class="mb-1">
                    <small class="text-muted">
                        <i class="fas fa-palette"></i> Warna:
                    </small>
                    <strong class="text-dark">{{ $item['variant']->parent->name }}</strong>
                </div>
                <div>
                    <small class="text-muted">
                        <i class="fas fa-ruler"></i> Ukuran:
                    </small>
                    <strong>{{ $item['variant']->name }}</strong>
                </div>
            @else
                {{-- Ini warna saja (tanpa ukuran) --}}
                <strong>{{ $item['variant']->name }}</strong>
                <br>
                <small class="text-muted">
                    <i class="fas fa-palette"></i> Warna
                </small>
            @endif
        @else
            <span class="text-danger">[Varian Dihapus]</span>
        @endif
    </td>
    <td>
        @if($item['variant'] && $item['variant']->price)
        <strong class="text-primary">
            Rp {{ number_format($item['variant']->price, 0, ',', '.') }}
        </strong>
        @else
        <span class="text-muted">-</span>
        @endif
    </td>
    <td>
                                            <span class="badge badge-primary badge-pill" style="font-size: 1rem;">
                                                {{ $item['quantity'] }} pcs
                                            </span>
                                        </td>
                                        <td>
                                            {{-- ✅ PERBAIKAN: Null Check untuk Stok --}}
                                            @if($item['variant'])
                                                @if($stockRequest->status === 'pending')
                                                    @if($item['quantity'] > $item['current_stock_pusat'])
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-exclamation-triangle"></i> Stok: {{ $item['current_stock_pusat'] }}
                                                    </span>
                                                    <br><small class="text-danger">Tidak cukup!</small>
                                                    @else
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Stok: {{ $item['current_stock_pusat'] }}
                                                    </span>
                                                    <br><small class="text-success">Tersedia</small>
                                                    @endif
                                                @elseif($stockRequest->status === 'approved')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-double"></i> Sudah Didistribusikan
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $stockRequest->status_label }}</span>
                                                @endif
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-ban"></i> Data tidak tersedia
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                               <tfoot>
    <tr class="table-info">
        <td colspan="4" class="text-right"><strong>Total:</strong></td>
        <td><strong class="text-primary" style="font-size: 1.1rem;">{{ $stockRequest->total_quantity }} pcs</strong></td>
        <td></td>
    </tr>
</tfoot>
                            </table>
                        </div>

                        @if($isProcessed && $stockRequest->processed_at)
                        <hr>
                        <div class="x931-timeline">
                            <h6 class="font-weight-bold mb-3">
                                <i class="fas fa-history"></i> Timeline Request
                            </h6>
                            
                            <div class="d-flex mb-3">
                                <div class="mr-3"><i class="fas fa-circle text-primary" style="font-size: 0.8rem;"></i></div>
                                <div>
                                    <strong>Request Dibuat</strong><br>
                                    <small class="text-muted"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($stockRequest->requested_at)->format('d M Y H:i:s') }}</small><br>
                                    <small>oleh {{ $stockRequest->requestedBy->name }}</small>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="mr-3"><i class="fas fa-circle text-{{ $stockRequest->status_badge }}" style="font-size: 0.8rem;"></i></div>
                                <div>
                                    <strong>
                                        @if($stockRequest->status === 'approved') Request Disetujui
                                        @elseif($stockRequest->status === 'rejected') Request Ditolak
                                        @else Request Dibatalkan
                                        @endif
                                    </strong><br>
                                    <small class="text-muted"><i class="far fa-calendar-check"></i> {{ \Carbon\Carbon::parse($stockRequest->processed_at)->format('d M Y H:i:s') }}</small><br>
                                    <small>oleh {{ $stockRequest->processedBy->name }}</small>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($stockRequest->status === 'pending')
                <div class="card shadow mt-4 border-left-warning">
                    <div class="card-body">
                        <i class="fas fa-info-circle text-warning"></i>
                        <strong>Catatan:</strong> Request Anda sedang menunggu persetujuan. Anda akan menerima notifikasi setelah request diproses.
                    </div>
                </div>
                @elseif($stockRequest->status === 'approved')
                <div class="card shadow mt-4 border-left-success">
                    <div class="card-body">
                        <i class="fas fa-check-circle text-success"></i>
                        <strong>Selamat!</strong> Request Anda telah disetujui dan stok sudah didistribusikan ke toko Anda.
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
    /* X931: Simple container style - BUKAN alert class */
    .x931-info-container {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        background-color: #f8f9fc !important;
    }
    .x931-timeline {
        position: relative;
    }
    </style>
</x-admin-layout>