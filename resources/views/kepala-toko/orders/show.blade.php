<x-admin-layout title="Detail Order">
    <div class="container-fluid">
        
        <!-- Page Heading -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-shopping-cart"></i> Detail Order
            </h1>
            <a href="{{ route('kepala-toko.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif

        <div class="row">
            
            <!-- Left Column: Order Info -->
            <div class="col-lg-8">
                
                <!-- Order Info Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-info-circle"></i> Informasi Order
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Order Number:</strong></p>
                                <p class="text-primary">{{ $order->order_number }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Tanggal Order:</strong></p>
                                <p>{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Status:</strong></p>
                                <p>{!! $order->status_badge !!}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Status Pembayaran:</strong></p>
                                <p>{!! $order->payment_status_badge !!}</p>
                            </div>
                        </div>
                        @if($order->payment_reference)
                        <div class="row">
                            <div class="col-12">
                                <p class="mb-2"><strong>Payment Reference:</strong></p>
                                <p class="text-muted">{{ $order->payment_reference }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Customer Info Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user"></i> Informasi Customer
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Nama:</strong></p>
                                <p>{{ $order->customer_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Telepon:</strong></p>
                                <p>{{ $order->customer_phone }}</p>
                            </div>
                        </div>
                        @if($order->customer_email)
                        <div class="row">
                            <div class="col-12">
                                <p class="mb-2"><strong>Email:</strong></p>
                                <p>{{ $order->customer_email }}</p>
                            </div>
                        </div>
                        @endif
                        @if($order->shipping_address)
                        <div class="row">
                            <div class="col-12">
                                <p class="mb-2"><strong>Alamat Pengiriman:</strong></p>
                                <p>{{ $order->shipping_address }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Items Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-box"></i> Daftar Produk
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                                        <td><strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    @if($order->discount_amount > 0)
                                    <tr>
                                        <td colspan="3" class="text-right">Diskon:</td>
                                        <td class="text-success">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    @if($order->shipping_cost > 0)
                                    <tr>
                                        <td colspan="3" class="text-right">Ongkir ({{ $order->shipping_type_text }}):</td>
                                        <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr class="bg-light">
                                        <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                        <td><strong class="text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Shipping Info Card -->
                @if($order->isDelivery())
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-shipping-fast"></i> Informasi Pengiriman
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Metode:</strong></p>
                                <p>{{ $order->delivery_method_text }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Tipe Pengiriman:</strong></p>
                                <p>{{ $order->shipping_type_text }}</p>
                            </div>
                        </div>
                        @if($order->shipping_distance)
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Jarak:</strong></p>
                                <p>{{ number_format($order->shipping_distance, 2) }} km</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Ongkir:</strong></p>
                                <p>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($order->resi_number)
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Nomor Resi:</strong></p>
                                <p class="text-primary"><strong>{{ $order->resi_number }}</strong></p>
                            </div>
                            @if($order->shipped_at)
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Tanggal Kirim:</strong></p>
                                <p>{{ $order->shipped_at->format('d M Y, H:i') }}</p>
                            </div>
                            @endif
                        </div>
                        @if($order->courier_name)
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Nama Kurir:</strong></p>
                                <p>{{ $order->courier_name }}</p>
                            </div>
                            @if($order->courier_phone)
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Telepon Kurir:</strong></p>
                                <p>{{ $order->courier_phone }}</p>
                            </div>
                            @endif
                        </div>
                        @endif
                        @if($order->shipping_notes)
                        <div class="row">
                            <div class="col-12">
                                <p class="mb-2"><strong>Catatan Pengiriman:</strong></p>
                                <p class="text-muted">{{ $order->shipping_notes }}</p>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
                @endif

            </div>

            <!-- Right Column: Actions & Timeline -->
            <div class="col-lg-4">
                
                <!-- Payment Confirmation -->
                @if($order->payment_status === 'unpaid')
                <div class="card shadow mb-4 border-left-warning">
                    <div class="card-header py-3 bg-warning text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-exclamation-triangle"></i> Konfirmasi Pembayaran
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Order ini belum dibayar. Konfirmasi pembayaran?</p>
                        <form method="POST" action="{{ route('kepala-toko.orders.confirm-payment', $order) }}" 
                              onsubmit="return confirm('Yakin konfirmasi pembayaran?')">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-check"></i> Konfirmasi Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-success text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-tasks"></i> Update Status
                        </h6>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#updateStatusModal">
                            <i class="fas fa-edit"></i> Update Status Order
                        </button>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-history"></i> Riwayat Status
                        </h6>
                    </div>
                    <div class="card-body">
                        @forelse($order->statusLogs as $log)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <strong class="text-primary">{{ $log->status_to_text }}</strong>
                                <small class="text-muted">{{ $log->formatted_created_at }}</small>
                            </div>
                            @if($log->status_from)
                            <small class="text-muted">dari: {{ $log->status_from_text }}</small>
                            @endif
                            <br>
                            <small>oleh: {{ $log->changed_by_name }}</small>
                            @if($log->notes)
                            <p class="mb-0 mt-2 text-muted"><em>"{{ $log->notes }}"</em></p>
                            @endif
                        </div>
                        @empty
                        <p class="text-muted text-center">Belum ada riwayat</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('kepala-toko.orders.update-status', $order) }}">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Update Status Order</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="form-group">
                            <label>Status Baru <span class="text-danger">*</span></label>
                            <select name="status" id="statusSelect" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped (Dikirim)</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div id="shippingFields" style="display: none;">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Wajib isi data pengiriman untuk status "Shipped"
                            </div>
                            
                            <div class="form-group">
                                <label>Nomor Resi <span class="text-danger">*</span></label>
                                <input type="text" name="resi_number" class="form-control" value="{{ $order->resi_number }}">
                            </div>

                            <div class="form-group">
                                <label>Nama Kurir</label>
                                <input type="text" name="courier_name" class="form-control" value="{{ $order->courier_name }}">
                            </div>

                            <div class="form-group">
                                <label>Telepon Kurir</label>
                                <input type="text" name="courier_phone" class="form-control" value="{{ $order->courier_phone }}">
                            </div>

                            <div class="form-group">
                                <label>Catatan Pengiriman</label>
                                <textarea name="shipping_notes" class="form-control" rows="2">{{ $order->shipping_notes }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Catatan (opsional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Tambahkan catatan untuk perubahan status ini..."></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Show/hide shipping fields based on status
        document.getElementById('statusSelect').addEventListener('change', function() {
            const shippingFields = document.getElementById('shippingFields');
            if (this.value === 'shipped') {
                shippingFields.style.display = 'block';
            } else {
                shippingFields.style.display = 'none';
            }
        });

        // Trigger on page load
        document.getElementById('statusSelect').dispatchEvent(new Event('change'));
    </script>

</x-admin-layout>