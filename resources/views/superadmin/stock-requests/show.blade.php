{{-- resources/views/superadmin/stock-requests/show.blade.php --}}
<x-admin-layout title="Detail Request Stok">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-alt"></i> Detail Request Stok #{{ $stockRequest->id }}
            </h1>
            <a href="{{ route('superadmin.stock-requests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{!! $error !!}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        {{-- Alert Warning jika Produk Dihapus --}}
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
                                <td><strong>Diminta oleh:</strong></td>
                                <td>
                                    {{ $stockRequest->requestedBy->name }}
                                    <br>
                                    <small class="text-muted">{{ ucfirst($stockRequest->requestedBy->role) }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Request:</strong></td>
                                <td>
                                    @if($stockRequest->requested_at)
                                    <small>
                                        <i class="far fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($stockRequest->requested_at)->format('d M Y H:i:s') }}
                                    </small>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @if($stockRequest->processedBy)
                            <tr>
                                <td><strong>Diproses oleh:</strong></td>
                                <td>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar-circle bg-{{ $stockRequest->status_badge }} mr-2">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $stockRequest->processedBy->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ ucfirst($stockRequest->processedBy->role) }}</small>
                                        </div>
                                    </div>
                                    @if($stockRequest->processed_at)
                                    <small class="text-{{ $stockRequest->status_badge }}">
                                        <i class="far fa-calendar-check"></i>
                                        {{ \Carbon\Carbon::parse($stockRequest->processed_at)->format('d M Y H:i:s') }}
                                    </small>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </table>

                        @if($stockRequest->notes)
                        <hr>
                        <div class="border rounded p-3" style="background-color: #f8f9fc;">
                            <strong><i class="fas fa-sticky-note text-info"></i> Catatan Request:</strong>
                            <p class="text-muted mb-0 mt-2">{{ $stockRequest->notes }}</p>
                        </div>
                        @endif

                        @if($stockRequest->approval_notes)
                        <hr>
                        <div class="alert alert-success mb-0">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-circle text-success mr-2 mt-1"></i>
                                <div>
                                    <strong>Catatan Approval:</strong>
                                    <p class="mb-0 mt-1">{{ $stockRequest->approval_notes }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($stockRequest->rejection_reason)
                        <hr>
                        <div class="alert alert-danger mb-0">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-times-circle text-danger mr-2 mt-1"></i>
                                <div>
                                    <strong>Alasan Ditolak:</strong>
                                    <p class="mb-0 mt-1">{{ $stockRequest->rejection_reason }}</p>
                                </div>
                            </div>
                        </div>
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
                                        <th width="25%">Stok Warehouse</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($itemsWithDetails as $index => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        
                                        {{-- Foto Varian --}}
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
                                        
                                        {{-- Nama Varian --}}
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
                                        
                                        {{-- Harga --}}
                                        <td>
                                            @if($item['variant'] && $item['variant']->price)
                                            <strong class="text-primary">
                                                Rp {{ number_format($item['variant']->price, 0, ',', '.') }}
                                            </strong>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        
                                        {{-- Jumlah Request --}}
                                        <td>
                                            <span class="badge badge-primary badge-pill" style="font-size: 1rem;">
                                                {{ $item['quantity'] }} pcs
                                            </span>
                                        </td>
                                        
                                        {{-- Stok Warehouse --}}
                                        <td>
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
                                        <td>
                                            <strong class="text-primary" style="font-size: 1.1rem;">{{ $stockRequest->total_quantity }} pcs</strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons (hanya untuk pending) -->
                @if($stockRequest->isPending())
                <div class="card shadow mt-4">
                    <div class="card-header py-3 bg-warning text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-tasks"></i> Aksi Request
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Approve Form -->
                            <div class="col-md-6">
                                <form action="{{ route('superadmin.stock-requests.approve', $stockRequest) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Approve request ini? Stok akan didistribusikan ke toko.')">
                                    @csrf
                                    <div class="form-group">
                                        <label>Catatan Approval (Opsional)</label>
                                        <textarea name="approval_notes" 
                                                  class="form-control" 
                                                  rows="3"
                                                  placeholder="Tambahkan catatan..."
                                                  maxlength="500"></textarea>
                                        <small class="text-muted">Maksimal 500 karakter</small>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block btn-lg">
                                        <i class="fas fa-check"></i> Approve Request
                                    </button>
                                </form>
                            </div>

                            <!-- Reject Form -->
                            <div class="col-md-6">
                                <form action="{{ route('superadmin.stock-requests.reject', $stockRequest) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Tolak request ini?')">
                                    @csrf
                                    <div class="form-group">
                                        <label>Alasan Penolakan <span class="text-danger">*</span></label>
                                        <textarea name="rejection_reason" 
                                                  class="form-control" 
                                                  rows="3"
                                                  placeholder="Jelaskan alasan penolakan..."
                                                  required
                                                  minlength="10"
                                                  maxlength="500"></textarea>
                                        <small class="text-muted">Minimal 10 karakter, maksimal 500 karakter</small>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-block btn-lg">
                                        <i class="fas fa-times"></i> Reject Request
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Activity Timeline -->
                @if($stockRequest->processedBy || !$stockRequest->isPending())
                <div class="card shadow mt-4">
                    <div class="card-header py-3 bg-light">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-history"></i> Riwayat Aktivitas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <!-- Request Created -->
                            @if($stockRequest->requested_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary">
                                    <i class="fas fa-paper-plane text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <strong>Request Dibuat</strong>
                                        <span class="badge badge-primary ml-2">Created</span>
                                    </div>
                                    <div class="timeline-body">
                                        <p class="mb-1">
                                            <i class="fas fa-user"></i> 
                                            <strong>{{ $stockRequest->requestedBy->name }}</strong>
                                            ({{ ucfirst($stockRequest->requestedBy->role) }})
                                        </p>
                                        <p class="mb-1">
                                            <i class="fas fa-store"></i>
                                            {{ $stockRequest->toko->nama_toko }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="fas fa-box"></i>
                                            Total: <strong>{{ $stockRequest->total_quantity }} pcs</strong>
                                        </p>
                                        <small class="text-muted">
                                            <i class="far fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($stockRequest->requested_at)->format('d F Y, H:i') }} WIB
                                            <span class="ml-2">({{ \Carbon\Carbon::parse($stockRequest->requested_at)->diffForHumans() }})</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Processed (Approved/Rejected/Cancelled) -->
                            @if($stockRequest->processedBy && $stockRequest->processed_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $stockRequest->status_badge }}">
                                    @if($stockRequest->isApproved())
                                    <i class="fas fa-check text-white"></i>
                                    @elseif($stockRequest->isRejected())
                                    <i class="fas fa-times text-white"></i>
                                    @else
                                    <i class="fas fa-ban text-white"></i>
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <strong>
                                            @if($stockRequest->isApproved())
                                            Request Disetujui
                                            @elseif($stockRequest->isRejected())
                                            Request Ditolak
                                            @else
                                            Request Dibatalkan
                                            @endif
                                        </strong>
                                        <span class="badge badge-{{ $stockRequest->status_badge }} ml-2">
                                            {{ $stockRequest->status_label }}
                                        </span>
                                    </div>
                                    <div class="timeline-body">
                                        <p class="mb-1">
                                            <i class="fas fa-user-shield"></i> 
                                            <strong>{{ $stockRequest->processedBy->name }}</strong>
                                            ({{ ucfirst($stockRequest->processedBy->role) }})
                                        </p>
                                        <small class="text-muted">
                                            <i class="far fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($stockRequest->processed_at)->format('d F Y, H:i') }} WIB
                                            <span class="ml-2">({{ \Carbon\Carbon::parse($stockRequest->processed_at)->diffForHumans() }})</span>
                                        </small>

                                        @if($stockRequest->approval_notes)
                                        <div class="alert alert-success mt-2 mb-0">
                                            <small>
                                                <i class="fas fa-comment"></i>
                                                <strong>Catatan:</strong> {{ $stockRequest->approval_notes }}
                                            </small>
                                        </div>
                                        @endif

                                        @if($stockRequest->rejection_reason)
                                        <div class="alert alert-danger mt-2 mb-0">
                                            <small>
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <strong>Alasan:</strong> {{ $stockRequest->rejection_reason }}
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Stock Distributed (if approved) -->
                            @if($stockRequest->isApproved() && $stockRequest->distributionLogs && $stockRequest->distributionLogs->count() > 0)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success">
                                    <i class="fas fa-shipping-fast text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <strong>Stok Didistribusikan</strong>
                                        <span class="badge badge-success ml-2">Distributed</span>
                                    </div>
                                    <div class="timeline-body">
                                        <p class="mb-1">
                                            <i class="fas fa-box"></i>
                                            Total <strong>{{ $stockRequest->total_quantity }} pcs</strong> telah didistribusikan ke toko
                                        </p>
                                        <small class="text-muted">
                                            <i class="far fa-clock"></i>
                                            {{ $stockRequest->distributionLogs->first()->created_at->format('d F Y, H:i') }} WIB
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Timeline Styles */
    .timeline {
        position: relative;
        padding-left: 0;
    }

    .timeline-item {
        display: flex;
        margin-bottom: 2rem;
        position: relative;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 45px;
        bottom: -30px;
        width: 2px;
        background: #e3e6f0;
    }

    .timeline-marker {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        z-index: 1;
        box-shadow: 0 0 0 4px #fff;
    }

    .timeline-content {
        flex: 1;
        margin-left: 1rem;
    }

    .timeline-header {
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .timeline-body {
        background: #f8f9fc;
        padding: 1rem;
        border-radius: 0.5rem;
        border-left: 3px solid #4e73df;
    }

    .timeline-body p:last-child {
        margin-bottom: 0;
    }
    </style>
   
</x-admin-layout>