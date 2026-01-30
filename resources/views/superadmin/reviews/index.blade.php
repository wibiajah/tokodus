<x-admin-layout>
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-star text-warning"></i> Manajemen Review Produk
            </h1>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Review
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Rating Rata-rata
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['average_rating'] }} <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-star fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Review dengan Foto
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_with_photos'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-images fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Review Hari Ini
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['reviews_today'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter & Pencarian</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('superadmin.reviews.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Produk</label>
                                <select name="product_id" class="form-control">
                                    <option value="">Semua Produk</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Customer</label>
                                <select name="customer_id" class="form-control">
                                    <option value="">Semua Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" 
                                                {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->firstname }} {{ $customer->lastname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Rating</label>
                                <select name="rating" class="form-control">
                                    <option value="">Semua Rating</option>
                                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 ⭐</option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 ⭐</option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 ⭐</option>
                                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 ⭐</option>
                                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 ⭐</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Urutkan</label>
                                <select name="sort_by" class="form-control">
                                    <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                                    <option value="highest_rating" {{ request('sort_by') == 'highest_rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                                    <option value="lowest_rating" {{ request('sort_by') == 'lowest_rating' ? 'selected' : '' }}>Rating Terendah</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group mb-0">
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Cari customer, produk, atau ulasan..."
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('superadmin.reviews.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reviews Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Review</h6>
            </div>
            <div class="card-body">
                @if($reviews->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th width="15%">Customer</th>
                                    <th width="25%">Produk</th>
                                    <th width="10%">Rating</th>
                                    <th width="30%">Ulasan</th>
                                    <th width="10%">Tanggal</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviews as $review)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="review-checkbox" value="{{ $review->id }}">
                                    </td>
                                    <td>
                                        <strong>{{ $review->customer_name }}</strong><br>
                                        <small class="text-muted">{{ $review->customer->email }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('product.detail', $review->product_id) }}" target="_blank">
                                            {{ $review->product->title }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">
                                            {{ $review->rating }} <i class="fas fa-star"></i>
                                        </span>
                                    </td>
                                    <td>
                                        @if($review->review)
                                            <div style="max-height: 60px; overflow-y: auto;">
                                                {{ Str::limit($review->review, 100) }}
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                        @if($review->photos && count($review->photos) > 0)
                                            <div class="mt-1">
                                                <i class="fas fa-image text-info"></i> {{ count($review->photos) }} foto
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $review->created_at_formatted }}</small><br>
                                        <small class="text-muted">{{ $review->created_at_human }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('superadmin.reviews.show', $review) }}" 
                                               class="btn btn-info" 
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger"
                                                    onclick="deleteReview({{ $review->id }})"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn" disabled>
                                <i class="fas fa-trash"></i> Hapus Terpilih
                            </button>
                        </div>
                        <div>
                            {{ $reviews->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada review ditemukan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    
    <script>
        // Select All Checkbox
        document.getElementById('selectAll')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.review-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkDeleteBtn();
        });

        // Individual Checkbox
        document.querySelectorAll('.review-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkDeleteBtn);
        });

        function updateBulkDeleteBtn() {
            const checked = document.querySelectorAll('.review-checkbox:checked');
            const bulkBtn = document.getElementById('bulkDeleteBtn');
            if (bulkBtn) {
                bulkBtn.disabled = checked.length === 0;
            }
        }

        // Delete Single Review
        function deleteReview(id) {
            if (confirm('Yakin ingin menghapus review ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/superadmin/reviews/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Bulk Delete
        document.getElementById('bulkDeleteBtn')?.addEventListener('click', function() {
            const checked = Array.from(document.querySelectorAll('.review-checkbox:checked'))
                .map(cb => cb.value);
            
            if (checked.length === 0) return;
            
            if (confirm(`Yakin ingin menghapus ${checked.length} review?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("superadmin.reviews.bulk-delete") }}';
                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="review_ids" value='${JSON.stringify(checked)}'>
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    </script>
    
</x-admin-layout>