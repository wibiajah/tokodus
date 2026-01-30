<x-admin-layout title="History Stok - {{ $product->title }}">
    <div class="container-fluid">

        @include('layouts.management-header', [
            'icon' => 'fas fa-history',
            'title' => 'History Distribusi Stok',
            'description' => 'Riwayat distribusi warehouse dan edit manual kepala toko',
            'buttonText' => 'Kembali ke Detail',
            'buttonRoute' => route('superadmin.stocks.detail', $product),
            'buttonIcon' => 'fas fa-arrow-left',
        ])

        <!-- Product Info Card -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        @if ($product->photos && count($product->photos) > 0)
                            <img src="{{ asset('storage/' . $product->photos[0]) }}" alt="{{ $product->title }}"
                                class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                style="width: 100px; height: 100px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-7">
                        <h4 class="mb-2">{{ $product->title }}</h4>
                        <p class="mb-0"><strong>SKU:</strong> <span
                                class="badge badge-secondary">{{ $product->sku }}</span></p>
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="{{ route('superadmin.stocks.detail', $product) }}" class="btn btn-primary">
                            <i class="fas fa-truck mr-1"></i> Distribusi Stok
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Tabs: Distribusi Warehouse vs Edit Manual -->
        <ul class="nav nav-tabs" id="historyTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="superadmin-tab" data-toggle="tab" href="#superadmin" role="tab">
                    <i class="fas fa-user-shield mr-1"></i> Aktivitas Super Admin
                    @if ($superAdminLogs->total() > 0)
                        <span class="badge badge-primary ml-2">{{ $superAdminLogs->total() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="manual-edit-tab" data-toggle="tab" href="#manual-edit" role="tab">
                    <i class="fas fa-edit mr-1"></i> Edit Manual Kepala Toko
                    @if ($manualEditLogs->total() > 0)
                        <span class="badge badge-warning ml-2">{{ $manualEditLogs->total() }}</span>
                    @endif
                </a>
            </li>
        </ul>

        <div class="tab-content" id="historyTabsContent">

            <!-- TAB 1: Distribusi Warehouse -->
            <!-- TAB 1: Aktivitas Super Admin (Distribusi + Edit Stok Pusat) -->
            <div class="tab-pane fade show active" id="superadmin" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="150">Tanggal</th>
                                        <th width="120">Jenis Aktivitas</th>
                                        <th>Toko/Target</th>
                                        <th>Item Varian</th>
                                        <th class="text-center" width="120">Total Qty</th>
                                        <th>Dilakukan Oleh</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $processedBatches = [];
                                    @endphp
                                    @forelse($superAdminLogs as $log)
                                        @php
                                            $batchKey =
                                                $log->created_at->format('Y-m-d H:i:s') .
                                                '-' .
                                                $log->toko_id .
                                                '-' .
                                                $log->source_type;

                                            if (in_array($batchKey, $processedBatches)) {
                                                continue;
                                            }
                                            $processedBatches[] = $batchKey;

                                            $batchLogs = $superAdminLogs->filter(function ($item) use ($log) {
                                                return $item->created_at->format('Y-m-d H:i:s') ===
                                                    $log->created_at->format('Y-m-d H:i:s') &&
                                                    $item->toko_id === $log->toko_id &&
                                                    $item->source_type === $log->source_type;
                                            });

                                            $isDistribution = in_array($log->source_type, ['direct', 'request']);
                                            $isStockAdjustment = $log->source_type === 'stock_adjustment';

                                            if ($isDistribution) {
                                                $totalQty = $batchLogs->sum('quantity');
                                                $badgeClass = 'primary';
                                                $badgeIcon = 'truck';
                                                $badgeText = 'Distribusi';
                                                $modalId = 'distModal';
                                                $modalHeaderClass = 'bg-primary text-white';
                                                $modalTitle = 'Detail Distribusi Warehouse';
                                            } else {
                                                $totalIncrease = $batchLogs->where('type', 'in')->sum('quantity');
                                                $totalDecrease = $batchLogs->where('type', 'out')->sum('quantity');
                                                $totalQty = $totalIncrease - $totalDecrease;
                                                $badgeClass = 'warning text-dark';
                                                $badgeIcon = 'edit';
                                                $badgeText = 'Edit Stok Pusat';
                                                $modalId = 'editModal';
                                                $modalHeaderClass = 'bg-warning text-dark';
                                                $modalTitle = 'Detail Edit Stok Pusat';
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $log->created_at->format('d/m/Y') }}</strong><br>
                                                <small class="text-muted">{{ $log->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $badgeClass }}">
                                                    <i class="fas fa-{{ $badgeIcon }} mr-1"></i>{{ $badgeText }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($isDistribution)
                                                    <strong>{{ $log->toko->nama_toko }}</strong>
                                                @else
                                                    <strong class="text-primary">Warehouse Pusat</strong>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-{{ $isDistribution ? 'primary' : 'warning' }}"
                                                    data-toggle="modal"
                                                    data-target="#{{ $modalId }}{{ $log->id }}">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Lihat Detail ({{ $batchLogs->count() }} item)
                                                </button>

                                                <!-- Modal Detail -->
                                                <div class="modal fade" id="{{ $modalId }}{{ $log->id }}"
                                                    tabindex="-1" role="dialog">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header {{ $modalHeaderClass }}">
                                                                <h5 class="modal-title">
                                                                    <i
                                                                        class="fas fa-{{ $badgeIcon }} mr-2"></i>{{ $modalTitle }}
                                                                </h5>
                                                                <button type="button"
                                                                    class="close {{ $isDistribution ? 'text-white' : '' }}"
                                                                    data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="alert alert-light border">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <strong><i
                                                                                    class="fas fa-calendar mr-2"></i>Tanggal:</strong>
                                                                            {{ $log->created_at->format('d F Y, H:i') }}
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong><i
                                                                                    class="fas fa-{{ $isDistribution ? 'store' : 'warehouse' }} mr-2"></i>{{ $isDistribution ? 'Toko' : 'Lokasi' }}:</strong>
                                                                            {{ $isDistribution ? $log->toko->nama_toko : 'Warehouse Pusat' }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-12">
                                                                            <strong><i
                                                                                    class="fas fa-user mr-2"></i>{{ $isDistribution ? 'Didistribusikan' : 'Diedit' }}
                                                                                oleh:</strong>
                                                                            {{ $log->performedBy->name }}
                                                                            <span
                                                                                class="badge badge-{{ $isDistribution ? 'primary' : 'warning text-dark' }} ml-2">
                                                                                {{ ucfirst(str_replace('_', ' ', $log->performedBy->role)) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <table class="table table-sm table-bordered">
                                                                    <thead class="thead-light">
                                                                        <tr>
                                                                            <th>Varian</th>
                                                                            <th class="text-center" width="120">
                                                                                {{ $isDistribution ? 'Quantity' : 'Perubahan' }}
                                                                            </th>
                                                                            <th class="text-center" width="120">Stok
                                                                                Sebelum</th>
                                                                            <th class="text-center" width="120">
                                                                                Stok Sesudah</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($batchLogs as $batchLog)
                                                                            <tr>
                                                                                <td>{{ $batchLog->variant->display_name }}
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    @if ($isDistribution)
                                                                                        <span
                                                                                            class="badge badge-primary">+{{ number_format($batchLog->quantity) }}</span>
                                                                                    @else
                                                                                        @php
                                                                                            $change =
                                                                                                $batchLog->type === 'in'
                                                                                                    ? $batchLog->quantity
                                                                                                    : -$batchLog->quantity;
                                                                                        @endphp
                                                                                        @if ($change > 0)
                                                                                            <span
                                                                                                class="badge badge-success">+{{ number_format($change) }}</span>
                                                                                        @elseif($change < 0)
                                                                                            <span
                                                                                                class="badge badge-danger">{{ number_format($change) }}</span>
                                                                                        @else
                                                                                            <span
                                                                                                class="badge badge-secondary">0</span>
                                                                                        @endif
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{ number_format($batchLog->stock_before) }}
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <strong
                                                                                        class="{{ $batchLog->stock_after > $batchLog->stock_before ? 'text-success' : ($batchLog->stock_after < $batchLog->stock_before ? 'text-danger' : '') }}">
                                                                                        {{ number_format($batchLog->stock_after) }}
                                                                                    </strong>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                    <tfoot class="thead-light">
                                                                        <tr>
                                                                            <td><strong>{{ $isDistribution ? 'TOTAL' : 'NET CHANGE' }}</strong>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <strong
                                                                                    class="{{ $totalQty > 0 ? 'text-success' : ($totalQty < 0 ? 'text-danger' : 'text-primary') }}">
                                                                                    {{ $totalQty > 0 && !$isDistribution ? '+' : '' }}{{ number_format($totalQty) }}
                                                                                </strong>
                                                                            </td>
                                                                            <td colspan="2"></td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>

                                                                @if ($log->notes)
                                                                    <div
                                                                        class="alert alert-{{ $isDistribution ? 'info' : 'warning' }} mb-0">
                                                                        <strong><i
                                                                                class="fas fa-sticky-note mr-2"></i>Catatan:</strong><br>
                                                                        {{ $log->notes }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if ($isDistribution)
                                                    <strong class="text-success">+{{ number_format($totalQty) }}
                                                        pcs</strong>
                                                @else
                                                    @if ($totalQty > 0)
                                                        <strong class="text-success">+{{ number_format($totalQty) }}
                                                            pcs</strong>
                                                    @elseif($totalQty < 0)
                                                        <strong class="text-danger">{{ number_format($totalQty) }}
                                                            pcs</strong>
                                                    @else
                                                        <span class="text-muted">0 pcs</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                {{ $log->performedBy->name }}<br>
                                                <small
                                                    class="text-muted">{{ ucfirst(str_replace('_', ' ', $log->performedBy->role)) }}</small>
                                            </td>
                                            <td>
                                                @if ($log->notes)
                                                    <small
                                                        class="text-muted">{{ Str::limit($log->notes, 50) }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                                <p class="text-muted mb-0">Belum ada history aktivitas super admin</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($superAdminLogs->hasPages())
                            <div class="card-footer">
                                {{ $superAdminLogs->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Summary -->
                @if ($superAdminLogs->count() > 0)
                    <div class="card shadow">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3">Ringkasan Aktivitas Super Admin:</h6>
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="border-left border-primary pl-3">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total
                                            Aktivitas</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ count($processedBatches) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border-left border-success pl-3">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Distribusi Warehouse</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $superAdminLogs->whereIn('source_type', ['direct', 'request'])->count() }}
                                            transaksi
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border-left border-warning pl-3">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Edit
                                            Stok Pusat</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $superAdminLogs->where('source_type', 'stock_adjustment')->where('toko_id', 999)->count() }}
                                            transaksi
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- TAB 2: Edit Manual Kepala Toko -->
            <div class="tab-pane fade" id="manual-edit" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="150">Tanggal</th>
                                        <th>Toko</th>
                                        <th>Item Varian</th>
                                        <th class="text-center" width="120">Total Perubahan</th>
                                        <th width="100">Jenis Aksi</th>
                                        <th>Dilakukan Oleh</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $processedManualBatches = [];
                                    @endphp
                                    @forelse($manualEditLogs as $log)
                                        @php
                                            $batchKey =
                                                $log->created_at->format('Y-m-d H:i:s') .
                                                '-' .
                                                $log->toko_id .
                                                '-' .
                                                $log->performed_by;

                                            if (in_array($batchKey, $processedManualBatches)) {
                                                continue;
                                            }
                                            $processedManualBatches[] = $batchKey;

                                            $batchLogs = $manualEditLogs->filter(function ($item) use ($log) {
                                                return $item->created_at->format('Y-m-d H:i:s') ===
                                                    $log->created_at->format('Y-m-d H:i:s') &&
                                                    $item->toko_id === $log->toko_id &&
                                                    $item->performed_by === $log->performed_by;
                                            });

                                            $totalChange = $batchLogs->sum('quantity');
                                            $hasIncrease = $batchLogs->where('quantity', '>', 0)->count() > 0;
                                            $hasDecrease = $batchLogs->where('quantity', '<', 0)->count() > 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $log->created_at->format('d/m/Y') }}</strong><br>
                                                <small
                                                    class="text-muted">{{ $log->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $log->toko->nama_toko }}</strong>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                    data-toggle="modal" data-target="#editModal{{ $log->id }}">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Lihat Detail ({{ $batchLogs->count() }} item)
                                                </button>

                                                <!-- Modal Detail -->
                                                <div class="modal fade" id="editModal{{ $log->id }}"
                                                    tabindex="-1" role="dialog">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-warning text-dark">
                                                                <h5 class="modal-title">
                                                                    <i class="fas fa-edit mr-2"></i>Detail Edit Manual
                                                                    Stok
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="alert alert-light border">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <strong><i
                                                                                    class="fas fa-calendar mr-2"></i>Tanggal:</strong>
                                                                            {{ $log->created_at->format('d F Y, H:i') }}
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong><i
                                                                                    class="fas fa-store mr-2"></i>Toko:</strong>
                                                                            {{ $log->toko->nama_toko }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-12">
                                                                            <strong><i
                                                                                    class="fas fa-user mr-2"></i>Diedit
                                                                                oleh:</strong>
                                                                            {{ $log->performedBy->name }}
                                                                            <span
                                                                                class="badge badge-warning text-dark ml-2">{{ ucfirst(str_replace('_', ' ', $log->performedBy->role)) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <table class="table table-sm table-bordered">
                                                                    <thead class="thead-light">
                                                                        <tr>
                                                                            <th>Varian</th>
                                                                            <th class="text-center" width="120">
                                                                                Perubahan</th>
                                                                            <th class="text-center" width="120">
                                                                                Stok Sebelum</th>
                                                                            <th class="text-center" width="120">
                                                                                Stok Sesudah</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($batchLogs as $batchLog)
                                                                            <tr>
                                                                                <td>{{ $batchLog->variant->display_name }}
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    @if ($batchLog->quantity > 0)
                                                                                        <span
                                                                                            class="badge badge-success">+{{ number_format($batchLog->quantity) }}</span>
                                                                                    @elseif($batchLog->quantity < 0)
                                                                                        <span
                                                                                            class="badge badge-danger">{{ number_format($batchLog->quantity) }}</span>
                                                                                    @else
                                                                                        <span
                                                                                            class="badge badge-secondary">0</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{ number_format($batchLog->stock_before) }}
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <strong
                                                                                        class="{{ $batchLog->quantity > 0 ? 'text-success' : ($batchLog->quantity < 0 ? 'text-danger' : '') }}">
                                                                                        {{ number_format($batchLog->stock_after) }}
                                                                                    </strong>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                    <tfoot class="thead-light">
                                                                        <tr>
                                                                            <td><strong>NET CHANGE</strong></td>
                                                                            <td class="text-center">
                                                                                <strong
                                                                                    class="{{ $totalChange > 0 ? 'text-success' : ($totalChange < 0 ? 'text-danger' : '') }}">
                                                                                    {{ $totalChange > 0 ? '+' : '' }}{{ number_format($totalChange) }}
                                                                                </strong>
                                                                            </td>
                                                                            <td colspan="2"></td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>

                                                                @if ($log->notes)
                                                                    <div class="alert alert-warning mb-0">
                                                                        <strong><i
                                                                                class="fas fa-sticky-note mr-2"></i>Catatan:</strong><br>
                                                                        {{ $log->notes }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if ($totalChange > 0)
                                                    <strong class="text-success">+{{ number_format($totalChange) }}
                                                        pcs</strong>
                                                @elseif($totalChange < 0)
                                                    <strong class="text-danger">{{ number_format($totalChange) }}
                                                        pcs</strong>
                                                @else
                                                    <span class="text-muted">0 pcs</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($hasIncrease && $hasDecrease)
                                                    <span class="badge badge-secondary">Mixed</span>
                                                @elseif($hasIncrease)
                                                    <span class="badge badge-success">Tambah</span>
                                                @elseif($hasDecrease)
                                                    <span class="badge badge-danger">Kurangi</span>
                                                @else
                                                    <span class="badge badge-secondary">Set</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $log->performedBy->name }}<br>
                                                <small
                                                    class="text-muted">{{ ucfirst(str_replace('_', ' ', $log->performedBy->role)) }}</small>
                                            </td>
                                            <td>
                                                @if ($log->notes)
                                                    <small
                                                        class="text-muted">{{ Str::limit($log->notes, 50) }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="fas fa-edit fa-3x text-muted mb-3"></i>
                                                <p class="text-muted mb-0">Belum ada history edit manual stok</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($manualEditLogs->hasPages())
                            <div class="card-footer">
                                {{ $manualEditLogs->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Summary Edit Manual -->
                @if ($manualEditLogs->count() > 0)
                    <div class="card shadow">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3">Ringkasan Edit Manual:</h6>
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="border-left border-warning pl-3">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total
                                            Transaksi</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ count($processedManualBatches) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-left border-success pl-3">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total
                                            Penambahan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            +{{ number_format($manualEditLogs->where('quantity', '>', 0)->sum('quantity')) }}
                                            pcs
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-left border-danger pl-3">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total
                                            Pengurangan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ number_format($manualEditLogs->where('quantity', '<', 0)->sum('quantity')) }}
                                            pcs
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-left border-info pl-3">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Net Change
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            @php
                                                $netChange = $manualEditLogs->sum('quantity');
                                            @endphp
                                            <span
                                                class="{{ $netChange > 0 ? 'text-success' : ($netChange < 0 ? 'text-danger' : '') }}">
                                                {{ $netChange > 0 ? '+' : '' }}{{ number_format($netChange) }} pcs
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-admin-layout>
