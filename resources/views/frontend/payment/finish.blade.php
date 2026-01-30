<x-frontend-layout>
    <x-slot:title>Pembayaran</x-slot:title>

    <style>
        .payment-container {
            max-width: 900px;
            margin: 100px auto 2rem;
            padding: 0 1rem;
        }

        .payment-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .orders-summary {
            background: #f9fafb;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #111;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-info {
            flex: 1;
        }

        .order-number {
            font-weight: 600;
            color: #1f4390;
            font-size: 0.938rem;
        }

        .order-toko {
            font-size: 0.813rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .order-amount {
            font-weight: 700;
            color: #111;
            font-size: 1rem;
        }

        .total-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111;
        }

        .total-amount {
            font-size: 1.75rem;
            font-weight: 700;
            color: #e9078f;
        }

        .payment-info {
            background: #dbeafe;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
            font-size: 0.875rem;
            color: #1e40af;
        }

        .payment-info i {
            margin-right: 0.5rem;
        }

        .btn-pay {
            width: 100%;
            background: linear-gradient(135deg, #f9ef21 0%, #fbbf24 100%);
            color: #111;
            border: none;
            padding: 1.25rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.125rem;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-pay:hover {
            background: linear-gradient(135deg, #e9078f 0%, #c7322f 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 7, 143, 0.4);
        }

        .btn-pay:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            text-decoration: none;
        }

        .btn-back:hover {
            color: #1f4390;
        }

        @media (max-width: 768px) {
            .payment-container {
                margin-top: 80px;
            }

            .order-item {
                flex-direction: column;
                gap: 0.5rem;
            }

            .total-section {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }
        }
    </style>

    <div class="payment-container">
        <a href="{{ route('customer.orders.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Pesanan
        </a>

        <div class="payment-card">
            <h2 class="page-title">
                <i class="fas fa-credit-card"></i> Pembayaran
            </h2>

            <div class="payment-info">
                <i class="fas fa-info-circle"></i>
                <strong>Pembayaran untuk {{ count($orders) }} pesanan sekaligus.</strong>
                Setelah pembayaran berhasil, semua pesanan akan diproses.
            </div>

            <div class="orders-summary">
                <div class="summary-title">
                    <i class="fas fa-shopping-bag"></i> Ringkasan Pesanan
                </div>

                @foreach($orders as $order)
                    <div class="order-item">
                        <div class="order-info">
                            <div class="order-number">#{{ $order->order_number }}</div>
                            <div class="order-toko">
                                <i class="fas fa-store"></i> {{ $order->toko?->nama_toko ?? 'Central Store' }}
                                • {{ $order->items->sum('quantity') }} item
                            </div>
                        </div>
                        <div class="order-amount">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach

                <div class="total-section">
                    <div class="total-label">Total Pembayaran</div>
                    <div class="total-amount">Rp {{ number_format($totalPayment, 0, ',', '.') }}</div>
                </div>
            </div>

            <button type="button" class="btn-pay" id="btnPayNow">
                <i class="fas fa-lock"></i> Bayar Sekarang
            </button>

            <p class="text-center mt-3 text-muted" style="font-size: 0.813rem;">
                <i class="fas fa-shield-alt"></i> 
                Transaksi aman dengan enkripsi SSL
            </p>
        </div>
    </div>

    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        const btnPayNow = document.getElementById('btnPayNow');
        const orderIds = @json($orderIds);

        if (btnPayNow) {
            btnPayNow.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

                // Create payment for multiple orders
                fetch('/customer/payment/create-multiple', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        order_ids: orderIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.is_existing) {
                            if (typeof toastr !== 'undefined') {
                                toastr.info('Melanjutkan pembayaran yang sedang berjalan...');
                            }
                        }

                        // Open Midtrans Snap
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.href = `/customer/payment/finish-multiple?orders=${orderIds.join(',')}`;
                            },
                            onPending: function(result) {
                                window.location.href = `/customer/payment/finish-multiple?orders=${orderIds.join(',')}`;
                            },
                            onError: function(result) {
                                alert('Pembayaran gagal! Silakan coba lagi.');
                                btnPayNow.disabled = false;
                                btnPayNow.innerHTML = '<i class="fas fa-lock"></i> Bayar Sekarang';
                            },
                            onClose: function() {
                                btnPayNow.disabled = false;
                                btnPayNow.innerHTML = '<i class="fas fa-lock"></i> Bayar Sekarang';
                            }
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(error.message || 'Gagal membuat pembayaran');
                    } else {
                        alert(error.message || 'Gagal membuat pembayaran');
                    }
                    btnPayNow.disabled = false;
                    btnPayNow.innerHTML = '<i class="fas fa-lock"></i> Bayar Sekarang';
                });
            });
        }
    </script>
</x-frontend-layout>