<x-frontend-layout>
    <!-- Breadcrumb -->
    <div class="breadcrumb-container-774">
        <div class="breadcrumb-774">
            <a href="{{ route('home') }}">Home</a>
            <span class="separator-774">/</span>
            <a href="{{ route('customer.profile') }}">Akun Saya</a>
            <span class="separator-774">/</span>
            <span class="current-774">Ulasan Saya</span>
        </div>
    </div>

    <!-- Main Container -->
    <div class="myreviews-774-container">
        <!-- Page Header -->
        <div class="myreviews-774-header">
            <div class="myreviews-774-header-content">
                <div class="myreviews-774-header-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h1 class="myreviews-774-title">Ulasan Saya</h1>
                    <p class="myreviews-774-subtitle">Lihat semua ulasan yang pernah Anda berikan</p>
                </div>
            </div>
            <div class="myreviews-774-stats">
                <div class="myreviews-774-stat-item">
                    <div class="myreviews-774-stat-value">{{ $reviews->total() }}</div>
                    <div class="myreviews-774-stat-label">Total Ulasan</div>
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        @if($reviews->count() > 0)
            <div class="myreviews-774-list">
                @foreach($reviews as $review)
                    <div class="myreviews-774-card">
                        <!-- Product Info -->
                        <div class="myreviews-774-product-section">
                            <div class="myreviews-774-product-image">
                                @php
                                    $orderItem = $review->order->items->where('product_id', $review->product_id)->first();
                                    $productImage = $orderItem && $orderItem->variant_photo 
                                        ? $orderItem->variant_photo 
                                        : $review->product->main_photo;
                                @endphp
                                <img src="{{ $productImage }}" 
                                     alt="{{ $review->product->title }}"
                                     onerror="this.src='{{ asset('images/no-image.png') }}'">
                            </div>
                            <div class="myreviews-774-product-info">
                                <h3 class="myreviews-774-product-title">{{ $review->product->title }}</h3>
                                <div class="myreviews-774-product-meta">
                                    <span class="myreviews-774-badge myreviews-774-badge-secondary">
                                        <i class="fas fa-receipt"></i>
                                        {{ $review->order->order_number }}
                                    </span>
                                  <span class="myreviews-774-date">
    <i class="far fa-calendar"></i>
    {{ $review->created_at->format('d M Y, H:i') }}
</span>
                                </div>
                            </div>
                        </div>

                        <!-- Review Content -->
                        <div class="myreviews-774-content-section">
                            <!-- Rating -->
                            <div class="myreviews-774-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                @endfor
                                <span class="myreviews-774-rating-text">{{ $review->rating }}/5</span>
                            </div>

                            <!-- Review Text -->
                            @if($review->review)
                                <div class="myreviews-774-text">
                                    <p>{{ $review->review }}</p>
                                </div>
                            @else
                                <div class="myreviews-774-no-text">
                                    <i class="fas fa-info-circle"></i>
                                    <em>Tidak ada ulasan tertulis</em>
                                </div>
                            @endif

                            <!-- Photos -->
                            @if($review->photos && count($review->photos) > 0)
                                <div class="myreviews-774-photos">
                                    <div class="myreviews-774-photos-grid">
                                        @foreach($review->photo_urls as $photoUrl)
                                            <a href="{{ $photoUrl }}" 
                                               data-lightbox="review-{{ $review->id }}" 
                                               class="myreviews-774-photo-item">
                                                <img src="{{ $photoUrl }}" alt="Review Photo">
                                                <div class="myreviews-774-photo-overlay">
                                                    <i class="fas fa-search-plus"></i>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                    <div class="myreviews-774-photo-count">
                                        <i class="fas fa-images"></i>
                                        {{ count($review->photos) }} foto
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="myreviews-774-actions">
                            <a href="{{ route('customer.reviews.show', $review->id) }}" 
                               class="myreviews-774-btn myreviews-774-btn-detail">
                                <i class="fas fa-eye"></i>
                                Lihat Detail
                            </a>
                            <a href="{{ route('customer.orders.show', $review->order->order_number) }}" 
                               class="myreviews-774-btn myreviews-774-btn-order">
                                <i class="fas fa-box"></i>
                                Lihat Pesanan
                            </a>
                        </div>

                        
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($reviews->hasPages())
                <div class="myreviews-774-pagination">
                    {{ $reviews->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="myreviews-774-empty">
                <div class="myreviews-774-empty-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="myreviews-774-empty-title">Belum Ada Ulasan</h3>
                <p class="myreviews-774-empty-text">
                    Anda belum memberikan ulasan untuk produk apapun.<br>
                    Mulai berikan ulasan setelah pesanan Anda selesai!
                </p>
                <a href="{{ route('customer.orders.index') }}" class="myreviews-774-btn myreviews-774-btn-primary">
                    <i class="fas fa-shopping-bag"></i>
                    Lihat Pesanan Saya
                </a>
            </div>
        @endif
    </div>

    <style>
        /* Breadcrumb */
        .breadcrumb-container-774 {
            background: #f8f9fa;
            padding: 1rem 0;
            border-bottom: 1px solid #e5e5e5;
            margin-top: 80px;
        }

        .breadcrumb-774 {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .breadcrumb-774 a {
            color: #2b4c9f;
            text-decoration: none;
            transition: all 0.3s;
        }

        .breadcrumb-774 a:hover {
            text-decoration: underline;
        }

        .breadcrumb-774 .separator-774 {
            color: #999;
        }

        .breadcrumb-774 .current-774 {
            color: #666;
        }

        /* Main Container */
        .myreviews-774-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Page Header */
        .myreviews-774-header {
            background: var(--secondary);
            padding: 2.5rem 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .myreviews-774-header-content {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .myreviews-774-header-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            flex-shrink: 0;
        }

        .myreviews-774-header-icon i {
            font-size: 32px;
            color: #fff;
        }

        .myreviews-774-title {
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.5px;
        }

        .myreviews-774-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            margin: 0;
        }

        .myreviews-774-stats {
            display: flex;
            gap: 2rem;
        }

        .myreviews-774-stat-item {
            text-align: center;
            background: rgba(255, 255, 255, 0.15);
            padding: 1.5rem 2rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            min-width: 120px;
        }

        .myreviews-774-stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .myreviews-774-stat-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Review Card */
        .myreviews-774-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .myreviews-774-card {
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e5e5;
            transition: all 0.3s ease;
            position: relative;
        }

        .myreviews-774-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        /* Product Section */
        .myreviews-774-product-section {
            display: flex;
            gap: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 1.5rem;
        }

        .myreviews-774-product-image {
            flex-shrink: 0;
        }

        .myreviews-774-product-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #f0f0f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .myreviews-774-product-info {
            flex: 1;
            min-width: 0;
        }

        .myreviews-774-product-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0 0 1rem 0;
            line-height: 1.4;
        }

        .myreviews-774-product-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .myreviews-774-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .myreviews-774-badge-secondary {
            background: #edf2f7;
            color: #4a5568;
        }

        .myreviews-774-badge-success {
            background: #c6f6d5;
            color: #22543d;
        }

        .myreviews-774-badge-warning {
            background: #feebc8;
            color: #744210;
        }

        .myreviews-774-date {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: #718096;
            font-size: 0.9rem;
        }

        /* Content Section */
        .myreviews-774-content-section {
            margin-bottom: 1.5rem;
        }

        .myreviews-774-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .myreviews-774-rating i {
            color: #fbbf24;
            font-size: 1.3rem;
        }

        .myreviews-774-rating-text {
            font-weight: 600;
            color: #2d3748;
            font-size: 1.1rem;
            margin-left: 0.5rem;
        }

        .myreviews-774-text {
            background: #f7fafc;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid var(--secondary);
            margin-bottom: 1rem;
        }

        .myreviews-774-text p {
            color: #2d3748;
            line-height: 1.7;
            margin: 0;
            font-size: 0.95rem;
        }

        .myreviews-774-no-text {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #a0aec0;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        /* Photos */
        .myreviews-774-photos {
            margin-top: 1rem;
        }

        .myreviews-774-photos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .myreviews-774-photo-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            aspect-ratio: 1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .myreviews-774-photo-item:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .myreviews-774-photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .myreviews-774-photo-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .myreviews-774-photo-item:hover .myreviews-774-photo-overlay {
            opacity: 1;
        }

        .myreviews-774-photo-overlay i {
            color: #fff;
            font-size: 1.5rem;
        }

        .myreviews-774-photo-count {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: #718096;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Actions */
        .myreviews-774-actions {
            display: flex;
            gap: 1rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e5e5;
        }

        .myreviews-774-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .myreviews-774-btn-detail {
            background: var(--secondary);
            color: #fff;
        }

        .myreviews-774-btn-detail:hover {
            background: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            text-decoration: none;
            color: #fff;
        }

        .myreviews-774-btn-order {
            background: #fff;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }

        .myreviews-774-btn-order:hover {
            background: #f7fafc;
            color: #2d3748;
            border-color: #cbd5e0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-decoration: none;
        }

        .myreviews-774-btn-primary {
            background: var(--secondary);
            color: #fff;
        }

        .myreviews-774-btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            text-decoration: none;
            color: #fff;
        }

        /* Empty State */
        .myreviews-774-empty {
            background: #fff;
            border-radius: 12px;
            padding: 4rem 2rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e5e5;
        }

        .myreviews-774-empty-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .myreviews-774-empty-icon i {
            font-size: 48px;
            color: #cbd5e0;
        }

        .myreviews-774-empty-title {
            color: #2d3748;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .myreviews-774-empty-text {
            color: #718096;
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        /* Pagination */
        .myreviews-774-pagination {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        /* Responsive */
@media (max-width: 768px) {
    .breadcrumb-container-774 {
        margin-top: 60px;
    }

    .breadcrumb-774 {
        padding: 0 1rem;
        font-size: 0.8rem;
    }

    .myreviews-774-container {
        padding: 0 1rem;
        margin: 1rem auto;
    }

    .myreviews-774-header {
        flex-direction: row;
        text-align: left;
        padding: 1.2rem 1.5rem;
    }

    .myreviews-774-header-content {
        flex-direction: row;
    }

    .myreviews-774-header-icon {
        width: 50px;
        height: 50px;
    }

    .myreviews-774-header-icon i {
        font-size: 24px;
    }

    .myreviews-774-title {
        font-size: 1.3rem;
        line-height: 1.3;
        margin-bottom: 0.25rem;
    }

    .myreviews-774-subtitle {
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .myreviews-774-stats {
        display: none;
    }

    .myreviews-774-card {
        padding: 1.5rem;
    }

    .myreviews-774-product-section {
        flex-direction: row;
        align-items: flex-start;
        gap: 1rem;
    }

    .myreviews-774-product-image img {
        width: 80px;
        height: 80px;
    }

    .myreviews-774-product-title {
        font-size: 1rem;
        line-height: 1.4;
        word-break: break-word;
        white-space: normal;
    }

    .myreviews-774-product-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .myreviews-774-badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.8rem;
        white-space: nowrap;
    }

    .myreviews-774-date {
        font-size: 0.85rem;
    }

    .myreviews-774-rating {
        flex-wrap: wrap;
        gap: 0.3rem;
    }

    .myreviews-774-rating i {
        font-size: 1.1rem;
    }

    .myreviews-774-rating-text {
        font-size: 1rem;
    }

    .myreviews-774-text {
        padding: 1rem;
    }

    .myreviews-774-text p {
        font-size: 0.9rem;
        line-height: 1.6;
        word-break: break-word;
        white-space: normal;
    }

    .myreviews-774-actions {
        flex-direction: column;
        gap: 0.8rem;
    }

    .myreviews-774-btn {
        width: 100%;
        justify-content: center;
        font-size: 1.05rem;
        padding: 1.2rem 2rem;
        font-weight: 700;
    }


    .myreviews-774-photos-grid {
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 0.5rem;
    }

    .myreviews-774-photo-item {
        width: 80px;
        height: 80px;
    }

    .myreviews-774-empty {
        padding: 3rem 1.5rem;
    }

    .myreviews-774-empty-icon {
        width: 90px;
        height: 90px;
        margin-bottom: 1.5rem;
    }

    .myreviews-774-empty-icon i {
        font-size: 44px;
    }

    .myreviews-774-empty-title {
        font-size: 1.6rem;
    }

    .myreviews-774-empty-text {
        font-size: 1rem;
        line-height: 1.6;
    }
}

@media (max-width: 480px) {
    .myreviews-774-container {
        padding: 0 0.75rem;
    }

    .myreviews-774-header {
        padding: 1rem;
    }

    .myreviews-774-header-icon {
        width: 45px;
        height: 45px;
    }

    .myreviews-774-header-icon i {
        font-size: 22px;
    }

    .myreviews-774-title {
        font-size: 1.2rem;
        line-height: 1.3;
        margin-bottom: 0.2rem;
    }

    .myreviews-774-subtitle {
        font-size: 0.8rem;
        line-height: 1.4;
    }

    .myreviews-774-card {
        padding: 1rem;
    }

    .myreviews-774-product-section {
        gap: 0.75rem;
    }

    .myreviews-774-product-image img {
        width: 70px;
        height: 70px;
    }

    .myreviews-774-product-title {
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
    }

    .myreviews-774-badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.7rem;
    }

    .myreviews-774-date {
        font-size: 0.8rem;
    }

    .myreviews-774-rating i {
        font-size: 1rem;
    }

    .myreviews-774-rating-text {
        font-size: 0.95rem;
    }

    .myreviews-774-text {
        padding: 0.875rem;
    }

    .myreviews-774-text p {
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .myreviews-774-no-text {
        font-size: 0.85rem;
    }

    .myreviews-774-actions {
        gap: 0.8rem;
    }

    .myreviews-774-btn {
        font-size: 1.1rem;
        padding: 1.4rem 2rem;
        font-weight: 700;
        border-radius: 10px;
    }

    .myreviews-774-btn i {
        font-size: 1.2rem;
    }

    .myreviews-774-photos-grid {
        grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
        gap: 0.5rem;
    }

    .myreviews-774-photo-item {
        width: 70px;
        height: 70px;
    }

    .myreviews-774-photo-count {
        font-size: 0.8rem;
    }

    .myreviews-774-empty {
        padding: 2.5rem 1rem;
    }

    .myreviews-774-empty-icon {
        width: 80px;
        height: 80px;
        margin-bottom: 1.5rem;
    }

    .myreviews-774-empty-icon i {
        font-size: 40px;
    }

    .myreviews-774-empty-title {
        font-size: 1.5rem;
    }

    .myreviews-774-empty-text {
        font-size: 0.95rem;
        line-height: 1.6;
    }
}
    </style>
</x-frontend-layout>