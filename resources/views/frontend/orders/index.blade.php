<x-frontend-layout>
    <x-slot:title>Pesanan Saya</x-slot:title>

    <style>
        .orders-container {
            max-width: 1200px;
            margin: 100px auto 2rem;
            padding: 0 1rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111;
            margin-bottom: 1rem;
        }

        .filter-bar {
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .filter-btn:hover {
            background: #f9fafb;
        }

        .filter-btn.active {
            background: #1f4390;
            color: #fff;
            border-color: #1f4390;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
        }

        .search-input {
            width: 100%;
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        .order-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .order-header {
            background: #f9fafb;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .order-number {
            font-weight: 700;
            color: #1f4390;
            font-size: 0.938rem;
        }

        .order-date {
            color: #6b7280;
            font-size: 0.813rem;
        }

        .order-body {
            padding: 1.25rem;
        }

        .order-items {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .order-item {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: #111;
            margin-bottom: 0.25rem;
        }

        .item-variant {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .item-qty {
            font-size: 0.813rem;
            color: #6b7280;
        }

        .order-footer {
            border-top: 1px solid #e5e7eb;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .order-total {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .total-amount {
            font-size: 1.125rem;
            font-weight: 700;
            color: #e9078f;
        }

        .order-status {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-primary { background: #dbeafe; color: #1e40af; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }

        .btn-detail {
            padding: 0.5rem 1.25rem;
            background: #1f4390;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-detail:hover {
            background: #163570;
            color: #fff;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .empty-icon {
            font-size: 3rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .empty-text {
            color: #6b7280;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .orders-container {
                margin-top: 70px;
            }

            .order-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .item-image {
                width: 100%;
                height: auto;
                aspect-ratio: 1;
            }
        }
    </style>

    <div class="orders-container">
        <div class="page-header">
            <h2 class="page-title">
                <i class="fas fa-shopping-bag"></i> Pesanan Saya
            </h2>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <form method="GET" action="{{ route('customer.orders.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; width: 100%;">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button type="submit" name="status" value="all" 
                            class="filter-btn {{ request('status', 'all') === 'all' ? 'active' : '' }}">
                        Semua
                    </button>
                    <button type="submit" name="status" value="pending" 
                            class="filter-btn {{ request('status') === 'pending' ? 'active' : '' }}">
                        Pending
                    </button>
                    <button type="submit" name="status" value="processing" 
                            class="filter-btn {{ request('status') === 'processing' ? 'active' : '' }}">
                        Diproses
                    </button>
                    <button type="submit" name="status" value="shipped" 
                            class="filter-btn {{ request('status') === 'shipped' ? 'active' : '' }}">
                        Dikirim
                    </button>
                    <button type="submit" name="status" value="completed" 
                            class="filter-btn {{ request('status') === 'completed' ? 'active' : '' }}">
                        Selesai
                    </button>
                    <button type="submit" name="status" value="cancelled" 
                            class="filter-btn {{ request('status') === 'cancelled' ? 'active' : '' }}">
                        Dibatalkan
                    </button>
                </div>

                <div class="search-box">
                    <input type="text" name="search" class="search-input" 
                           placeholder="Cari nomor pesanan..." 
                           value="{{ request('search') }}">
                </div>
            </form>
        </div>

        <!-- Orders List -->
        @if($orders->count() > 0)
            @foreach($orders as $orderGroup)
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            @if($orderGroup->is_combined)
                                <div class="order-number">{{ $orderGroup->reference }}</div>
                                <div class="order-date">
                                    <i class="far fa-calendar"></i> {{ $orderGroup->formatted_date }}
                                    • <strong>{{ $orderGroup->order_count }} Pesanan</strong> dari {{ $orderGroup->order_count }} Toko
                                </div>
                            @else
                                <div class="order-number">{{ $orderGroup->orders->first()->order_number }}</div>
                                <div class="order-date">
                                    <i class="far fa-calendar"></i> {{ $orderGroup->formatted_date }}
                                </div>
                            @endif
                        </div>
                        <div class="order-status">
                            @if($orderGroup->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($orderGroup->status === 'processing')
                                <span class="badge badge-info">Diproses</span>
                            @elseif($orderGroup->status === 'shipped')
                                <span class="badge badge-primary">Dikirim</span>
                            @elseif($orderGroup->status === 'completed')
                                <span class="badge badge-success">Selesai</span>
                            @elseif($orderGroup->status === 'cancelled')
                                <span class="badge badge-danger">Dibatalkan</span>
                            @endif

                            @if($orderGroup->payment_status === 'unpaid')
                                <span class="badge badge-warning">Belum Dibayar</span>
                            @elseif($orderGroup->payment_status === 'paid')
                                <span class="badge badge-success">Sudah Dibayar</span>
                            @endif
                        </div>
                    </div>

                    <div class="order-body">
                        <div class="order-items">
                            @php
                                $displayedItems = 0;
                                $maxDisplay = 2;
                            @endphp

                            @foreach($orderGroup->orders as $order)
                                @foreach($order->items->take($maxDisplay - $displayedItems) as $item)
                                    <div class="order-item">
                                        <img src="{{ $item->variant_photo }}" 
                                             alt="{{ $item->product_title }}" 
                                             class="item-image">
                                        <div class="item-info">
                                            <div class="item-name">{{ $item->product_title }}</div>
                                            @if($item->variant_name)
                                                <div class="item-variant">{{ $item->variant_name }}</div>
                                            @endif
                                            <div class="item-qty">
                                                {{ $item->quantity }}x {{ $item->formatted_final_price }}
                                                @if($orderGroup->is_combined)
                                                    • <span style="color: #1f4390; font-weight: 600;">{{ $order->toko?->nama_toko ?? 'Central Store' }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @php $displayedItems++; @endphp
                                    @if($displayedItems >= $maxDisplay) @break @endif
                                @endforeach
                                @if($displayedItems >= $maxDisplay) @break @endif
                            @endforeach

                            @if($orderGroup->total_items > $maxDisplay)
                                <div style="font-size: 0.813rem; color: #6b7280;">
                                    +{{ $orderGroup->total_items - $maxDisplay }} produk lainnya
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="order-footer">
                        <div>
                            <div class="order-total">Total Belanja</div>
                            <div class="total-amount">Rp {{ number_format($orderGroup->total_amount, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <a href="{{ route('customer.orders.show', $orderGroup->is_combined ? $orderGroup->reference : $orderGroup->first_order_id) }}" class="btn-detail">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div style="margin-top: 2rem;">
                {{ $orders->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="empty-text">
                    @if(request('search'))
                        Tidak ada pesanan dengan nomor "{{ request('search') }}"
                    @else
                        Anda belum memiliki pesanan
                    @endif
                </div>
            </div>
        @endif
    </div>

</x-frontend-layout>