<x-frontend-layout>
    <x-slot:title>Status Pembayaran</x-slot:title>

    <style>
        .payment-finish-container {
            max-width: 700px;
            margin: 120px auto 2rem;
            padding: 0 1rem;
        }

        .status-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .status-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }

        .status-icon.pending { color: #f59e0b; }
        .status-icon.success { color: #10b981; }
        .status-icon.failed { color: #ef4444; }

        .status-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #111;
        }

        .status-message {
            color: #6b7280;
            font-size: 1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .orders-summary {
            background: #f9fafb;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .summary-title {
            font-weight: 600;
            color: #111;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.875rem;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-number {
            font-weight: 600;
            color: #1f4390;
        }

        .total-row {
            padding-top: 0.75rem;
            margin-top: 0.75rem;
            border-top: 2px solid #e5e7eb;
        }

        .total-amount {
            font-size: 1.25rem;
            color: #e9078f;
            font-weight: 700;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.938rem;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #1f4390;
            color: #fff;
        }

        .btn-primary:hover {
            background: #163570;
            color: #fff;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #111;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            color: #111;
        }

        .spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid #1f4390;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .payment-finish-container {
                margin-top: 80px;
            }

            .status-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>

    <div class="payment-finish-container">
        <div class="status-card" id="statusCard">
            <!-- Loading State -->
            <div id="loadingState">
                <div class="spinner"></div>
                <div class="status-title">Memproses Pembayaran...</div>
                <div class="status-message">
                    Mohon tunggu, kami sedang memverifikasi pembayaran Anda.
                </div>
            </div>

            <!-- Order Info (hidden initially) -->
            <div id="orderInfo" style="display: none;">
                <div class="status-icon" id="statusIcon">
                    <i class="fas fa-clock"></i>
                </div>
                
                <div class="status-title" id="statusTitle">Status Pembayaran</div>
                <div class="status-message" id="statusMessage">Loading...</div>

                <div class="orders-summary">
                    <div class="summary-title">
                        <i class="fas fa-shopping-bag"></i> {{ count($orders) }} Pesanan
                    </div>

                    @foreach($orders as $order)
                        <div class="order-item">
                            <span>
                                <span class="order-number">#{{ $order->order_number }}</span>
                                <br>
                                <small style="color: #6b7280;">{{ $order->toko?->nama_toko ?? 'Central Store' }}</small>
                            </span>
                            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach

                    <div class="order-item total-row">
                        <span style="font-weight: 600;">Total Pembayaran</span>
                        <span class="total-amount">Rp {{ number_format($totalPayment, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-primary">
                        <i class="fas fa-list"></i> Lihat Semua Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(checkPaymentStatus, 2000);
        });

        function checkPaymentStatus() {
            const loadingState = document.getElementById('loadingState');
            const orderInfo = document.getElementById('orderInfo');
            const statusIcon = document.getElementById('statusIcon');
            const statusTitle = document.getElementById('statusTitle');
            const statusMessage = document.getElementById('statusMessage');

            loadingState.style.display = 'none';
            orderInfo.style.display = 'block';

            // Check if any order is paid
            const paidCount = {{ $orders->where('payment_status', 'paid')->count() }};
            const totalCount = {{ $orders->count() }};
            const cancelledCount = {{ $orders->where('status', 'cancelled')->count() }};

            if (paidCount === totalCount) {
                // All paid - Success
                statusIcon.className = 'status-icon success';
                statusIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
                statusTitle.textContent = 'Pembayaran Berhasil!';
                statusMessage.textContent = `Terima kasih! Pembayaran untuk ${totalCount} pesanan telah berhasil diverifikasi. Pesanan Anda sedang diproses.`;
            } else if (cancelledCount > 0) {
                // Some cancelled - Failed
                statusIcon.className = 'status-icon failed';
                statusIcon.innerHTML = '<i class="fas fa-times-circle"></i>';
                statusTitle.textContent = 'Pembayaran Gagal';
                statusMessage.textContent = 'Pembayaran Anda gagal atau dibatalkan. Silakan coba lagi atau hubungi customer service.';
            } else {
                // Pending
                statusIcon.className = 'status-icon pending';
                statusIcon.innerHTML = '<i class="fas fa-clock"></i>';
                statusTitle.textContent = 'Menunggu Pembayaran';
                statusMessage.textContent = `Pesanan Anda sedang menunggu pembayaran. Silakan selesaikan pembayaran untuk ${totalCount} pesanan Anda.`;
            }
        }
    </script>
</x-frontend-layout>