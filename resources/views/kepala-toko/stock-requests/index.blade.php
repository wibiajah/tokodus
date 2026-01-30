{{-- resources/views/kepala-toko/stock-requests/index.blade.php --}}
<x-admin-layout title="Request Stok Saya">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-inbox"></i> Request Stok Saya
            </h1>
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

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {!! session('warning') !!}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $requests->where('status', 'pending')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Approved
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $requests->where('status', 'approved')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Rejected
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $requests->where('status', 'rejected')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Request
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $requests->total() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" 
                           href="{{ route('kepala-toko.stock-requests.index', ['status' => 'all']) }}">
                            <i class="fas fa-list"></i> Semua
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" 
                           href="{{ route('kepala-toko.stock-requests.index', ['status' => 'pending']) }}">
                            <i class="fas fa-clock"></i> Pending
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" 
                           href="{{ route('kepala-toko.stock-requests.index', ['status' => 'approved']) }}">
                            <i class="fas fa-check-circle"></i> Approved
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}" 
                           href="{{ route('kepala-toko.stock-requests.index', ['status' => 'rejected']) }}">
                            <i class="fas fa-times-circle"></i> Rejected
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                @if($requests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="30%">Produk</th>
                                <th width="15%">Total Item</th>
                                <th width="15%">Status</th>
                                <th width="20%">Tanggal</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                            <tr>
                                <td>{{ $loop->iteration + $requests->firstItem() - 1 }}</td>
                                <td>
                                    @if($request->product)
                                        <strong>{{ $request->product->title }}</strong>
                                        <br>
                                        <small class="text-muted">SKU: {{ $request->product->sku }}</small>
                                    @else
                                        <strong class="text-danger">[Produk Dihapus]</strong>
                                        <br>
                                        <small class="text-muted">Data tidak tersedia</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-primary badge-pill">
                                        {{ $request->total_quantity }} pcs
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $request->status_badge }}">
                                        {{ $request->status_label }}
                                    </span>
                                </td>
                                <td>
                                    {{-- Tanggal Request --}}
                                    <small>
                                        <i class="far fa-calendar"></i>
                                        <strong>Request:</strong>
                                        <br>
                                        {{ \Carbon\Carbon::parse($request->requested_at)->format('d M Y H:i:s') }}
                                    </small>
                                    
                                    {{-- Tanggal Diproses (jika sudah diproses) --}}
                                    @if($request->processed_at && $request->processedBy)
                                    <br>
                                    <small class="text-{{ $request->status_badge }}">
                                        <i class="far fa-clock"></i>
                                        <strong>
                                            @if($request->status === 'approved')
                                                Disetujui:
                                            @elseif($request->status === 'rejected')
                                                Ditolak:
                                            @else
                                                Dibatalkan:
                                            @endif
                                        </strong>
                                        <br>
                                        {{ \Carbon\Carbon::parse($request->processed_at)->format('d M Y H:i:s') }}
                                    </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('kepala-toko.stock-requests.show', $request) }}" 
                                           class="btn btn-info" 
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($request->isPending())
                                        <form action="{{ route('kepala-toko.stock-requests.cancel', $request) }}" 
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Batalkan request ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" title="Batalkan">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan {{ $requests->firstItem() }} - {{ $requests->lastItem() }} dari {{ $requests->total() }} data
                    </div>
                    <div>
                        {{ $requests->links() }}
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada request stok dengan status "{{ $status }}"</p>
                    <a href="{{ route('kepala-toko.dashboard') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus"></i> Buat Request Baru
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>