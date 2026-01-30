<x-admin-layout>
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-star text-warning"></i> Detail Review
            </h1>
            <a href="{{ route('superadmin.reviews.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="row">
            <!-- Review Info -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Review</h6>
                    </div>
                    <div class="card-body">
                        <!-- Rating -->
                        <div class="mb-4">
                            <h5 class="mb-2">Rating</h5>
                            <div class="text-warning" style="font-size: 1.5rem;">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span class="text-dark ml-2">{{ $review->rating }}/5</span>
                            </div>
                        </div>

                        <!-- Review Text -->
                        <div class="mb-4">
                            <h5 class="mb-2">Ulasan</h5>
                            @if ($review->review)
                                <div class="border rounded p-3 bg-light">
                                    {{ $review->review }}
                                </div>
                            @else
                                <p class="text-muted">Customer tidak memberikan ulasan teks</p>
                            @endif
                        </div>

                        <!-- Photos -->
                        @if ($review->photos && count($review->photos) > 0)
                            <div class="mb-4">
                                <h5 class="mb-3">Foto Review ({{ count($review->photos) }})</h5>
                                <div class="row">
                                    @foreach ($review->photo_urls as $photo)
                                        <div class="col-md-4 mb-3">
                                            <a href="{{ $photo }}" target="_blank">
                                                <img src="{{ $photo }}" class="img-thumbnail"
                                                    style="width: 100%; height: 200px; object-fit: cover;">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Metadata -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <strong>Tanggal Review:</strong><br>
                                    {{ $review->created_at_formatted }}<br>
                                    <small class="text-muted">{{ $review->created_at_human }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <strong>Status:</strong><br>
                                    <span class="badge badge-success">Auto Approved</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Customer Info -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Customer</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="{{ $review->customer && $review->customer->foto_profil
                                ? asset('storage/' . $review->customer->foto_profil)
                                : asset('frontend/assets/img/default-avatar.png') }}"
                                class="rounded-circle" width="80" height="80" style="object-fit: cover;"
                                onerror="this.src='{{ asset('frontend/assets/img/default-avatar.png') }}'">
                        </div>

                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%"><strong>Nama:</strong></td>
                                <td>{{ $review->customer_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $review->customer->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Review:</strong></td>
                                <td>{{ $review->customer->total_reviews }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Produk</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="{{ $review->product->thumbnail }}" class="img-thumbnail"
                                style="max-height: 150px;">
                        </div>
                        <h6>{{ $review->product->title }}</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="50%"><strong>SKU:</strong></td>
                                <td>{{ $review->product->sku }}</td>
                            </tr>
                            <tr>
                                <td><strong>Rating Produk:</strong></td>
                                <td>
                                    <span class="text-warning">
                                        {{ $review->product->rating }} <i class="fas fa-star"></i>
                                    </span>
                                    ({{ $review->product->review_count }} review)
                                </td>
                            </tr>
                        </table>
                        <a href="{{ route('product.detail', $review->product_id) }}"
                            class="btn btn-sm btn-info btn-block" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Lihat Produk
                        </a>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Order</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="50%"><strong>Order Number:</strong></td>
                                <td>{{ $review->order->order_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>{!! $review->order->status_badge !!}</td>
                            </tr>
                            <tr>
                                <td><strong>Total:</strong></td>
                                <td>Rp {{ number_format($review->order->total, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                        <a href="{{ route('superadmin.orders.show', $review->order) }}"
                            class="btn btn-sm btn-info btn-block">
                            <i class="fas fa-receipt"></i> Lihat Order
                        </a>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card shadow mb-4 border-danger">
                    <div class="card-header bg-danger py-3">
                        <h6 class="m-0 font-weight-bold text-white">Danger Zone</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Hapus review ini jika mengandung spam atau konten tidak pantas.
                        </p>
                        <form action="{{ route('superadmin.reviews.destroy', $review) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus review ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> Hapus Review
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
