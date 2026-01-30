<x-admin-layout title="Detail Order">
    <div class="container-fluid">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-shopping-cart"></i> Detail Order
            </h1>
            <a href="{{ route('superadmin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif

        <div class="row">
            
            <div class="col-lg-8">
                
                <!-- Order Info -->
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
                                <p class="mb-2"><strong>Toko:</strong></p>
                                <p><span class="badge badge-info badge-lg">{{ $order->toko->nama_toko }}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Tanggal Order:</strong></p>
                                <p>{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Status:</strong></p>
                                <p>
                                    @if($order->status === 'pending')
                                        <span class="badge badge-warning badge-lg">Menunggu Pembayaran</span>
                                    @elseif($order->status === 'paid')
                                        <span class="badge badge-info badge-lg">Sudah Dibayar</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="badge badge-primary badge-lg">Dikirim</span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge badge-success badge-lg">Selesai</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge badge-danger badge-lg">Dibatalkan</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Status Pembayaran:</strong></p>
                                <p>
                                    @if($order->payment_status === 'unpaid')
                                        <span class="badge badge-warning badge-lg">Belum Dibayar</span>
                                    @elseif($order->payment_status === 'paid')
                                        <span class="badge badge-success badge-lg">Sudah Dibayar</span>
                                    @elseif($order->payment_status === 'refunded')
                                        <span class="badge badge-danger badge-lg">Refund</span>
                                    @endif
                                </p>
                            </div>
                            @if($order->payment_reference)
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Payment Reference:</strong></p>
                                <p class="text-muted">{{ $order->payment_reference }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
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

                <!-- Items -->
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
                                        <td>
                                            {{ $item->product_title }}
                                            @if($item->variant_name)
                                                <br><small class="text-muted">{{ $item->variant_name }}</small>
                                            @endif
                                        </td>
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

                <!-- Shipping Info -->
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
                                <p class="mb-2"><strong>Tipe:</strong></p>
                                <p>{{ $order->shipping_type_text }}</p>
                            </div>
                        </div>
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
                                <p class="mb-2"><strong>Kurir:</strong></p>
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
                        @endif
                    </div>
                </div>
                @endif

            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                
                <!-- 🔥 IMPROVED: Conditional Actions per Status -->
                <div class="card shadow mb-4 border-left-primary">
                    <div class="card-header py-3 bg-gradient-primary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-tasks"></i> Kelola Order
                        </h6>
                    </div>
                    <div class="card-body">
                        
                        @if($order->status === 'pending')
                            <!-- ✅ PENDING: Konfirmasi Pembayaran -->
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Menunggu Pembayaran</strong>
                                <p class="mb-0 mt-2 small">Customer sudah checkout, menunggu konfirmasi pembayaran manual dari Anda.</p>
                            </div>
                            
                            <form action="{{ route('superadmin.orders.confirm-payment', $order) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran order ini?')">
                                @csrf
                                <div class="form-group">
                                    <label><i class="fas fa-sticky-note"></i> Catatan Konfirmasi (Opsional)</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Pembayaran diterima via BCA a/n John Doe"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-lg btn-block">
                                    <i class="fas fa-check-circle"></i> Konfirmasi Pembayaran
                                </button>
                            </form>
                            
                        @elseif($order->status === 'paid')
                            <!-- 📦 PAID: Kirim Pesanan -->
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle"></i>
                                <strong>Pembayaran Sudah Dikonfirmasi</strong>
                                <p class="mb-0 mt-2 small">Siap untuk dikirim ke customer.</p>
                            </div>
                            
                            <form action="{{ route('superadmin.orders.update-status', $order) }}" method="POST" onsubmit="return confirm('Kirim pesanan ini?')">
                                @csrf
                                <input type="hidden" name="status" value="shipped">
                                
                                <div class="form-group">
                                    <label><i class="fas fa-barcode"></i> No. Resi Pengiriman</label>
                                    <input type="text" name="resi_number" class="form-control" placeholder="Contoh: JNE123456789">
                                    <small class="form-text text-muted">Opsional - bisa diisi nanti</small>
                                </div>
                                
                                <div class="form-group">
                                    <label><i class="fas fa-user"></i> Nama Kurir</label>
                                    <input type="text" name="courier_name" class="form-control" placeholder="Contoh: Budi">
                                </div>
                                
                                <div class="form-group">
                                    <label><i class="fas fa-phone"></i> Telepon Kurir</label>
                                    <input type="text" name="courier_phone" class="form-control" placeholder="Contoh: 08123456789">
                                </div>
                                
                                <div class="form-group">
                                    <label><i class="fas fa-sticky-note"></i> Catatan Pengiriman</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Dikirim via JNE REG"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-shipping-fast"></i> Kirim Pesanan
                                </button>
                            </form>
                            
                        @elseif($order->status === 'shipped')
                            <!-- ⏳ SHIPPED: Menunggu Customer -->
                            <div class="alert alert-primary mb-0">
                                <i class="fas fa-truck"></i>
                                <strong>Pesanan Sudah Dikirim</strong>
                                <p class="mb-0 mt-2 small">Menunggu konfirmasi penerimaan dari customer.</p>
                            </div>
                            
                        @elseif($order->status === 'completed')
                            <!-- ✅ COMPLETED -->
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle"></i>
                                <strong>Pesanan Selesai</strong>
                                <p class="mb-0 mt-2 small">Order telah dikonfirmasi selesai oleh customer.</p>
                            </div>
                            
                        @elseif($order->status === 'cancelled')
                            <!-- ❌ CANCELLED -->
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-ban"></i>
                                <strong>Pesanan Dibatalkan</strong>
                                <p class="mb-0 mt-2 small">Order ini telah dibatalkan.</p>
                            </div>
                        @endif
                        
                        <!-- 🔥 CANCEL BUTTON (hanya jika belum completed) -->
                        @if(in_array($order->status, ['pending', 'paid', 'shipped']))
                            <hr>
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#cancelModal">
                                <i class="fas fa-times-circle"></i> Batalkan Order
                            </button>
                        @endif
                        
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
                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong class="text-primary">{{ $log->status_to_label }}</strong>
                                    @if($log->status_from)
                                        <br><small class="text-muted">dari: {{ $log->status_from_label }}</small>
                                    @endif
                                    <br><small class="text-muted"><i class="fas fa-user"></i> {{ $log->changed_by_name }}</small>
                                </div>
                                <small class="text-muted">{{ $log->formatted_created_at }}</small>
                            </div>
                            @if($log->notes)
                            <p class="mb-0 mt-2 text-muted small"><i class="fas fa-quote-left"></i> <em>{{ $log->notes }}</em></p>
                            @endif
                        </div>
                        @empty
                        <p class="text-muted text-center mb-0">
                            <i class="fas fa-info-circle"></i> Belum ada riwayat perubahan status
                        </p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i> Batalkan Order
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('superadmin.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Yakin membatalkan order ini?')">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i>
                            <strong>Perhatian!</strong>
                            <p class="mb-0">Customer akan melihat status order berubah menjadi "Dibatalkan".</p>
                        </div>
                        
                        <div class="form-group">
                            <label>Alasan Pembatalan <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="4" placeholder="Jelaskan alasan pembatalan order..." required></textarea>
                            <small class="form-text text-muted">Alasan ini akan dicatat di riwayat status.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban"></i> Ya, Batalkan Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>