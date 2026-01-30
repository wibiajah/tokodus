<x-admin-layout>
    <x-slot name="header">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-ticket-alt text-primary"></i> Manajemen Voucher
            </h1>
        </div>
    </x-slot>

    <style>
/* Voucher Management Page Scoped Styles */
.voucher-management-page .vouchers-header {
    background: linear-gradient(135deg, #224abe 0%, #224abe 100%);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.voucher-management-page .vouchers-header h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 10px 0;
}

.voucher-management-page .vouchers-header p {
    margin: 0;
    opacity: 0.9;
}

.voucher-management-page .btn-add-voucher {
    background: linear-gradient(135deg, #224abe 0%, #224abe 100%);
    color: white;
    padding: 14px 28px;
    border-radius: 12px;
    border: none;
    font-size: 15px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    text-decoration: none;
}

.voucher-management-page .btn-add-voucher:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    color: white;
    text-decoration: none;
}

/* Table Styles */
.voucher-management-page .table-container {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
}

.voucher-management-page .table {
    margin-bottom: 0;
}

.voucher-management-page .table thead {
    background: linear-gradient(135deg, #f8f9fc 0%, #eef2f7 100%);
}

.voucher-management-page .table thead th {
    font-weight: 700;
    color: #224abe;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    padding: 16px 12px;
    border: none;
    white-space: nowrap;
}

.voucher-management-page .table tbody tr {
    transition: all 0.2s;
}

.voucher-management-page .table tbody tr:hover {
    background: #f8f9fc;
    cursor: pointer;
}

.voucher-management-page .table tbody td {
    padding: 16px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}

.voucher-management-page .voucher-code-badge {
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 8px;
    background: linear-gradient(135deg, #224abe 0%, #1a3a9e 100%);
    color: white;
    letter-spacing: 1px;
}

.voucher-management-page .info-grid {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.voucher-management-page .info-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
}

.voucher-management-page .info-label {
    color: #6c757d;
    font-weight: 600;
    min-width: 80px;
}

.voucher-management-page .info-value {
    color: #2d3748;
    font-weight: 700;
}

.voucher-management-page .discount-box {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    padding: 8px 12px;
    border-radius: 8px;
    text-align: center;
}

.voucher-management-page .discount-value {
    font-size: 1.1rem;
    font-weight: 800;
    color: #2e7d32;
    display: block;
}

.voucher-management-page .discount-max {
    font-size: 0.75rem;
    color: #558b2f;
    margin-top: 2px;
    display: block;
}

.voucher-management-page .period-box {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.voucher-management-page .period-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
}

.voucher-management-page .period-start {
    color: #28a745;
}

.voucher-management-page .period-end {
    color: #dc3545;
}

.voucher-management-page .usage-box {
    text-align: center;
}

.voucher-management-page .usage-progress {
    font-size: 1rem;
    font-weight: 700;
    color: #224abe;
    margin-bottom: 4px;
}

.voucher-management-page .usage-bar {
    width: 100%;
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 4px;
}

.voucher-management-page .usage-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
    transition: width 0.3s;
}

.voucher-management-page .usage-limit {
    font-size: 0.75rem;
    color: #6c757d;
}

.voucher-management-page .action-icon {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.3s;
    text-decoration: none;
    font-size: 1rem;
    border: none;
    cursor: pointer;
}

.voucher-management-page .action-icon:hover {
    transform: scale(1.1);
    text-decoration: none;
}

.voucher-management-page .action-icon.view {
    background: #e3f2fd;
    color: #2196f3;
}

.voucher-management-page .action-icon.edit {
    background: #fff3e0;
    color: #ff9800;
}

.voucher-management-page .action-icon.delete {
    background: #ffebee;
    color: #f44336;
}

/* NEW TOGGLE SWITCH STYLES */
.voucher-management-page .toggle-container {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    padding: 10px 12px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    min-width: 80px;
}

.voucher-management-page .toggle-container.inactive-box {
    background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
}

.voucher-management-page .toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
}

.voucher-management-page .toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.voucher-management-page .toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #e0e0e0 0%, #bdbdbd 100%);
    transition: all 0.3s ease;
    border-radius: 26px;
}

.voucher-management-page .toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: all 0.3s ease;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.voucher-management-page .toggle-switch input:checked + .toggle-slider {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
}

.voucher-management-page .toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(24px);
}

.voucher-management-page .toggle-label {
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: center;
}

.voucher-management-page .toggle-label.active {
    color: #2e7d32;
}

.voucher-management-page .toggle-label.inactive {
    color: #c62828;
}

.voucher-management-page .no-results {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.voucher-management-page .no-results i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.3;
}

.voucher-management-page .badge-custom {
    padding: 6px 10px;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.voucher-management-page .badge-distribution-public {
    background: #d4edda;
    color: #155724;
}

.voucher-management-page .badge-distribution-private {
    background: #fff3cd;
    color: #856404;
}

.voucher-management-page .badge-type-fixed {
    background: #cfe2ff;
    color: #084298;
}

.voucher-management-page .badge-type-percentage {
    background: #e7d5f7;
    color: #6f42c1;
}

.voucher-management-page .badge-scope-all {
    background: #cfe2ff;
    color: #0d6efd;
}

.voucher-management-page .badge-scope-specific {
    background: #f8d7da;
    color: #842029;
}

.voucher-management-page .badge-scope-category {
    background: #fff3cd;
    color: #997404;
}

.voucher-management-page .status-badge-active {
    background: #d4edda;
    color: #155724;
}

.voucher-management-page .status-badge-upcoming {
    background: #fff3cd;
    color: #856404;
}

.voucher-management-page .status-badge-inactive {
    background: #e2e3e5;
    color: #383d41;
}

.voucher-management-page .status-badge-expired {
    background: #f8d7da;
    color: #721c24;
}

.voucher-management-page .status-badge-limit {
    background: #f8d7da;
    color: #721c24;
}

.voucher-management-page .customer-count {
    font-size: 0.75rem;
    padding: 3px 8px;
    background: #e7f3ff;
    color: #0066cc;
    border-radius: 4px;
    display: inline-block;
    margin-top: 4px;
}

/* FORCE INFORMASI VOUCHER HITAM SOLID */
.voucher-management-page .fw-bold {
    color: #000000 !important;
    font-weight: 700 !important;
}

.voucher-management-page .text-muted.small.text-truncate {
    color: #000000 !important;
}

/* BOLD untuk semua teks lainnya */
.voucher-management-page .table thead th {
    font-weight: 700 !important;
}

.voucher-management-page .voucher-code-badge {
    font-weight: 700 !important;
}

.voucher-management-page .badge-custom {
    font-weight: 700 !important;
}

.voucher-management-page .discount-value {
    font-weight: 800 !important;
}

.voucher-management-page .discount-max {
    font-weight: 700 !important;
}

.voucher-management-page .period-item span {
    font-weight: 700 !important;
}

.voucher-management-page .usage-progress {
    font-weight: 700 !important;
}

.voucher-management-page .customer-count {
    font-weight: 700 !important;
}
    </style>

    <div class="container-fluid voucher-management-page">
        {{-- Alert Success --}}


        <!-- Header -->
        <div class="vouchers-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1><i class="fas fa-ticket-alt"></i> Manajemen Voucher</h1>
                    <p>Kelola semua voucher dan promo diskon</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('superadmin.vouchers.create') }}" class="btn-add-voucher">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Voucher</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th width="140">Kode Voucher</th>
                            <th width="200">Informasi Voucher</th>
                            <th width="110" class="text-center">Distribusi</th>
                            <th width="110" class="text-center">Tipe Diskon</th>
                            <th width="140" class="text-center">Nilai Diskon</th>
                            <th width="130" class="text-center">Min. Belanja</th>
                            <th width="160">Periode Aktif</th>
                            <th width="130" class="text-center">Cakupan</th>
                            <th width="140" class="text-center">Penggunaan</th>
                            <th width="110" class="text-center">Status</th>
                            <th width="100" class="text-center">Toggle</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $index => $voucher)
                            <tr>
                                <td class="text-center text-muted fw-medium">
                                    {{ $vouchers->firstItem() + $index }}
                                </td>
                                <td>
                                    @if($voucher->code)
                                        <div class="voucher-code-badge">
                                            {{ $voucher->code }}
                                        </div>
                                    @else
                                        <span class="badge-custom badge-distribution-private">
                                            <i class="fas fa-lock"></i> Private
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold mb-1" style="font-size: 0.95rem;">{{ $voucher->name }}</div>
                                    @if($voucher->description)
                                        <div class="text-muted small text-truncate" style="max-width: 200px;" title="{{ $voucher->description }}">
                                            {{ $voucher->description }}
                                        </div>
                                    @endif
                                    @if($voucher->customers->count() > 0)
                                        <div class="customer-count">
                                            <i class="fas fa-users"></i> {{ $voucher->customers->count() }} Customer Assigned
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($voucher->distribution_type === 'public')
                                        <span class="badge-custom badge-distribution-public">
                                            <i class="fas fa-globe"></i> Public
                                        </span>
                                    @else
                                        <span class="badge-custom badge-distribution-private">
                                            <i class="fas fa-user-lock"></i> Private
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($voucher->discount_type === 'fixed')
                                        <span class="badge-custom badge-type-fixed">
                                            <i class="fas fa-money-bill-wave"></i> Rupiah
                                        </span>
                                    @else
                                        <span class="badge-custom badge-type-percentage">
                                            <i class="fas fa-percent"></i> Persen
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="discount-box">
                                        @if($voucher->discount_type === 'fixed')
                                            <span class="discount-value">
                                                Rp {{ number_format($voucher->discount_value, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="discount-value">
                                                {{ $voucher->discount_value }}%
                                            </span>
                                            @if($voucher->max_discount)
                                                <span class="discount-max">
                                                    Max: Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($voucher->min_purchase > 0)
                                        <div class="fw-bold" style="color: #224abe;">
                                            Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="period-box">
                                        <div class="period-item period-start">
                                            <i class="fas fa-play-circle"></i>
                                            <span class="fw-semibold">{{ $voucher->start_date->format('d M Y') }}</span>
                                        </div>
                                        <div class="period-item period-end">
                                            <i class="fas fa-stop-circle"></i>
                                            <span class="fw-semibold">{{ $voucher->end_date->format('d M Y') }}</span>
                                        </div>
                                        @php
                                            $now = now();
                                            $daysLeft = $voucher->end_date->diffInDays($now, false);
                                        @endphp
                                        @if($daysLeft >= 0 && $voucher->is_active)
                                            <div class="text-muted" style="font-size: 0.7rem;">
                                                <i class="fas fa-clock"></i> {{ abs($daysLeft) }} hari tersisa
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($voucher->scope === 'all_products')
                                        <span class="badge-custom badge-scope-all">
                                            <i class="fas fa-boxes"></i> Semua Produk
                                        </span>
                                    @elseif($voucher->scope === 'specific_products')
                                        <span class="badge-custom badge-scope-specific">
                                            <i class="fas fa-box"></i> {{ $voucher->products->count() }} Produk
                                        </span>
                                    @else
                                        <span class="badge-custom badge-scope-category">
                                            <i class="fas fa-tags"></i> {{ $voucher->categories->count() }} Kategori
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="usage-box">
                                        <div class="usage-progress">
                                            {{ $voucher->usage_count }}
                                            @if($voucher->usage_limit_total)
                                                / {{ $voucher->usage_limit_total }}
                                            @else
                                                / <i class="fas fa-infinity" style="font-size: 0.9rem;"></i>
                                            @endif
                                        </div>
                                        @if($voucher->usage_limit_total)
                                            @php
                                                $percentage = ($voucher->usage_count / $voucher->usage_limit_total) * 100;
                                            @endphp
                                            <div class="usage-bar">
                                                <div class="usage-bar-fill" style="width: {{ min($percentage, 100) }}%"></div>
                                            </div>
                                        @endif
                                        @if($voucher->usage_limit_per_customer)
                                            <div class="usage-limit">
                                                Max {{ $voucher->usage_limit_per_customer }}x/customer
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $status = $voucher->status_badge;
                                    @endphp
                                    
                                    @if($status === 'Aktif')
                                        <span class="badge-custom status-badge-active">
                                            <i class="fas fa-check-circle"></i> Aktif
                                        </span>
                                    @elseif($status === 'Belum Mulai')
                                        <span class="badge-custom status-badge-upcoming">
                                            <i class="fas fa-clock"></i> Belum Mulai
                                        </span>
                                    @elseif($status === 'Nonaktif')
                                        <span class="badge-custom status-badge-inactive">
                                            <i class="fas fa-ban"></i> Nonaktif
                                        </span>
                                    @elseif($status === 'Limit Tercapai')
                                        <span class="badge-custom status-badge-limit">
                                            <i class="fas fa-exclamation-circle"></i> Limit Tercapai
                                        </span>
                                    @else
                                        <span class="badge-custom status-badge-expired">
                                            <i class="fas fa-times-circle"></i> Kadaluarsa
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="toggle-container {{ $voucher->is_active ? '' : 'inactive-box' }}">
                                        <span class="toggle-label {{ $voucher->is_active ? 'active' : 'inactive' }}">
                                            {{ $voucher->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                        </span>
                                        <form action="{{ route('superadmin.vouchers.toggle-status', $voucher) }}" 
                                              method="POST"
                                              onchange="this.submit()">
                                            @csrf
                                            <label class="toggle-switch">
                                                <input type="checkbox" 
                                                       {{ $voucher->is_active ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </form>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                                        <a href="{{ route('superadmin.vouchers.show', $voucher) }}" 
                                           class="action-icon view" 
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('superadmin.vouchers.edit', $voucher) }}" 
                                           class="action-icon edit" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('superadmin.vouchers.destroy', $voucher) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus voucher ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="action-icon delete" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center py-5">
                                    <div class="no-results">
                                        <i class="fas fa-ticket-alt"></i>
                                        <h4>Belum Ada Voucher</h4>
                                        <p class="mb-3">Mulai dengan menambahkan voucher pertama Anda</p>
                                        <a href="{{ route('superadmin.vouchers.create') }}" class="btn-add-voucher">
                                            <i class="fas fa-plus-circle"></i> Tambah Voucher Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($vouchers->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
                    <div class="text-muted small">
                        Menampilkan {{ $vouchers->firstItem() }} - {{ $vouchers->lastItem() }} 
                        dari {{ $vouchers->total() }} voucher
                    </div>
                    <div>
                        {{ $vouchers->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>