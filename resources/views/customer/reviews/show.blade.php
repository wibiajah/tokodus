<x-frontend-layout>
    <!-- Breadcrumb -->
    <div class="breadcrumb-container-312">
        <div class="breadcrumb-312">
            <a href="{{ route('home') }}">Home</a>
            <span class="separator-312">/</span>
            <a href="{{ route('customer.profile') }}">Akun Saya</a>
            <span class="separator-312">/</span>
            <a href="{{ route('customer.my-reviews') }}">Ulasan Saya</a>
            <span class="separator-312">/</span>
            <span class="current-312">Detail Ulasan</span>
        </div>
    </div>

    <!-- Main Container -->
    <div class="review-show-312-container">
        <!-- Back Button -->
        <div class="review-show-312-back">
            <a href="{{ route('customer.my-reviews') }}" class="review-show-312-back-btn">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Ulasan Saya
            </a>
        </div>

        <!-- Main Card -->
        <div class="review-show-312-card">
            <!-- Header Section -->
            <div class="review-show-312-header">
                <div class="review-show-312-header-content">
                    <div class="review-show-312-header-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <h1 class="review-show-312-title">Detail Ulasan</h1>
                        <p class="review-show-312-subtitle">
                            Dikirim pada {{ $review->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
               
            </div>

            <!-- Product Info Section -->
            <div class="review-show-312-product-section">
                <h2 class="review-show-312-section-title">
                    <i class="fas fa-box"></i>
                    Informasi Produk
                </h2>
                <div class="review-show-312-product-card">
                    <div class="review-show-312-product-image">
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
                    <div class="review-show-312-product-info">
                        <h3 class="review-show-312-product-title">{{ $review->product->title }}</h3>
                        <div class="review-show-312-product-meta">
                            <div class="review-show-312-meta-item">
                                <i class="fas fa-receipt"></i>
                                <span>Order: <strong>{{ $review->order->order_number }}</strong></span>
                            </div>
                            <div class="review-show-312-meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>Tanggal Order: <strong>{{ $review->order->created_at->format('d M Y') }}</strong></span>
                            </div>
                            @if($orderItem && $orderItem->variant_name)
                                <div class="review-show-312-meta-item">
                                    <i class="fas fa-tag"></i>
                                    <span>Varian: <strong>{{ $orderItem->variant_name }}</strong></span>
                                </div>
                            @endif
                        </div>
                        <div class="review-show-312-product-actions">
                            <a href="{{ route('product.detail', $review->product->id) }}" 
                               class="review-show-312-btn review-show-312-btn-outline"
                               target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                                Lihat Produk
                            </a>
                            <a href="{{ route('customer.orders.show', $review->order->order_number) }}" 
                               class="review-show-312-btn review-show-312-btn-outline">
                                <i class="fas fa-shopping-bag"></i>
                                Lihat Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Content Section -->
            <div class="review-show-312-content-section">
                <h2 class="review-show-312-section-title">
                    <i class="fas fa-comment-dots"></i>
                    Ulasan Anda
                </h2>
                
                <!-- Rating -->
                <div class="review-show-312-rating-box">
                    <div class="review-show-312-rating-label">Rating:</div>
                    <div class="review-show-312-rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                        @endfor
                    </div>
                    <div class="review-show-312-rating-value">{{ $review->rating }}.0 / 5.0</div>
                </div>

                <!-- Review Text -->
                @if($review->review)
                    <div class="review-show-312-text-box">
                        <div class="review-show-312-text-label">
                            <i class="fas fa-quote-left"></i>
                            Ulasan Tertulis
                        </div>
                        <div class="review-show-312-text-content">
                            <p>{{ $review->review }}</p>
                        </div>
                    </div>
                @else
                    <div class="review-show-312-no-text">
                        <i class="fas fa-info-circle"></i>
                        <em>Tidak ada ulasan tertulis</em>
                    </div>
                @endif

                <!-- Photos -->
                @if($review->photos && count($review->photos) > 0)
                    <div class="review-show-312-photos-section">
                        <div class="review-show-312-photos-header">
                            <i class="fas fa-images"></i>
                            Foto Ulasan ({{ count($review->photos) }} foto)
                        </div>
                        <div class="review-show-312-photos-grid">
                            @foreach($review->photo_urls as $index => $photoUrl)
                                <a href="{{ $photoUrl }}" 
                                   data-lightbox="review-photos" 
                                   data-title="Foto Ulasan {{ $index + 1 }}"
                                   class="review-show-312-photo-item">
                                    <img src="{{ $photoUrl }}" alt="Review Photo {{ $index + 1 }}">
                                    <div class="review-show-312-photo-overlay">
                                        <i class="fas fa-search-plus"></i>
                                        <span>Lihat Foto</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Timeline Section -->
            <div class="review-show-312-timeline-section">
                <h2 class="review-show-312-section-title">
                    <i class="fas fa-history"></i>
                    Timeline
                </h2>
                <div class="review-show-312-timeline">
                    @php
                        $events = [
                            [
                                'title' => 'Ulasan Dikirim',
                                'date' => $review->created_at->format('d M Y, H:i'),
                                'desc' => 'Anda berhasil mengirimkan ulasan untuk produk ini',
                                'completed' => true
                            ]
                        ];

                        if($review->is_approved) {
                            $events[] = [
                                'title' => 'Ulasan Disetujui',
                                'date' => $review->updated_at->format('d M Y, H:i'),
                                'desc' => 'Ulasan Anda telah disetujui dan dipublikasikan',
                                'completed' => true
                            ];
                        }

                        $events[] = [
                            'title' => 'Pesanan Selesai',
                            'date' => $review->order->updated_at->format('d M Y, H:i'),
                            'desc' => 'Pesanan ' . $review->order->order_number . ' telah selesai',
                            'completed' => true
                        ];
                    @endphp

                    @foreach($events as $index => $event)
                        @php
                            $isCompleted = !$loop->last; // Semua kecuali terakhir = hijau
                        @endphp
                        <div class="review-show-312-timeline-item {{ $isCompleted ? 'completed' : '' }}">
                            <div class="icon-box-312">✓</div>
                            <div class="review-show-312-timeline-content">
                                <div class="review-show-312-timeline-status">
                                    {{ $event['title'] }}
                                </div>
                                <div class="review-show-312-timeline-date">
                                    {{ $event['date'] }}
                                </div>
                                <div class="review-show-312-timeline-notes">
                                    "{{ $event['desc'] }}"
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="review-show-312-actions">
                <a href="{{ route('customer.my-reviews') }}" 
                   class="review-show-312-btn review-show-312-btn-secondary">
                    <i class="fas fa-list"></i>
                    Lihat Semua Ulasan
                </a>
                <button type="button" 
                        class="review-show-312-btn review-show-312-btn-warning"
                        onclick="openEditModal312()">
                    <i class="fas fa-edit"></i>
                    Edit Ulasan
                </button>
                <a href="{{ route('customer.orders.show', $review->order->order_number) }}" 
                   class="review-show-312-btn review-show-312-btn-primary">
                    <i class="fas fa-box"></i>
                    Lihat Detail Pesanan
                </a>
            </div>
        </div>
    </div>

    <!-- ===== MODAL EDIT REVIEW ===== -->
    <div class="modal-312 fade" id="editReviewModal312" tabindex="-1" role="dialog">
        <div class="modal-dialog-312 modal-dialog-centered-312" role="document">
            <div class="modal-content-312">
                <div class="modal-header-312">
                    <h5 class="modal-title-312">
                        <i class="fas fa-edit"></i>
                        Edit Ulasan
                    </h5>
                    <button type="button" class="modal-close-312" onclick="closeEditModal312()">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('customer.reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data" id="editReviewForm312">
                    @csrf
                    @method('PUT')
                    <div class="modal-body-312">
                        <!-- Product Info Mini -->
                        <div class="modal-product-info-312">
                            <img src="{{ $productImage }}" alt="{{ $review->product->title }}">
                            <div>
                                <div class="modal-product-name-312">{{ $review->product->title }}</div>
                                <div class="modal-product-meta-312">Order #{{ $review->order->order_number }}</div>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="modal-form-group-312">
                            <label class="modal-label-312">Rating <span style="color: red;">*</span></label>
                            <div class="modal-rating-input-312">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" 
                                           name="rating" 
                                           id="rating-{{ $i }}-312" 
                                           value="{{ $i }}"
                                           {{ $review->rating == $i ? 'checked' : '' }}
                                           required>
                                    <label for="rating-{{ $i }}-312" class="modal-star-label-312">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            <small class="modal-help-text-312">Klik bintang untuk memberikan rating</small>
                        </div>

                        <!-- Review Text -->
                        <div class="modal-form-group-312">
                            <label class="modal-label-312">Ulasan Anda</label>
                            <textarea name="review" 
                                      class="modal-textarea-312" 
                                      rows="5"
                                      placeholder="Ceritakan pengalaman Anda dengan produk ini...">{{ $review->review }}</textarea>
                            <small class="modal-help-text-312">Opsional - Anda bisa mengosongkan jika hanya ingin memberikan rating</small>
                        </div>

                        <!-- Current Photos -->
                        @if($review->photos && count($review->photos) > 0)
                            <div class="modal-form-group-312">
                                <label class="modal-label-312">Foto Saat Ini</label>
                                <div class="modal-current-photos-312">
                                    @foreach($review->photos as $index => $photo)
                                        <div class="modal-photo-item-312">
                                            <img src="{{ asset('storage/' . $photo) }}" alt="Photo {{ $index + 1 }}">
                                            <button type="button" 
                                                    class="modal-photo-remove-312" 
                                                    onclick="removePhoto312({{ $index }})"
                                                    title="Hapus foto">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <input type="hidden" 
                                                   name="existing_photos[]" 
                                                   value="{{ $photo }}" 
                                                   id="existing-photo-{{ $index }}-312">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- New Photos -->
                        <div class="modal-form-group-312">
                            <label class="modal-label-312">Tambah Foto Baru (Opsional)</label>
                            <input type="file" 
                                   name="photos[]" 
                                   class="modal-file-input-312" 
                                   id="photoInput312"
                                   accept="image/*" 
                                   multiple
                                   onchange="previewPhotos312(event)">
                            <small class="modal-help-text-312">Maksimal 5 foto, format JPG/PNG, maks 2MB per foto</small>
                            <div id="photoPreview312" class="modal-photo-preview-312"></div>
                        </div>

                        <!-- Alert Info -->
                        <div class="modal-alert-312 modal-alert-info-312">
                            <i class="fas fa-info-circle"></i>
                            <strong>Info:</strong> Perubahan akan langsung dipublikasikan tanpa perlu persetujuan admin.
                        </div>
                    </div>
                    <div class="modal-footer-312">
                        <button type="button" class="modal-btn-312 modal-btn-secondary-312" onclick="closeEditModal312()">
                            Batal
                        </button>
                        <button type="submit" class="modal-btn-312 modal-btn-primary-312">
                            <i class="fas fa-save"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Breadcrumb */
        .breadcrumb-container-312 {
            background: #f8f9fa;
            padding: 1rem 0;
            border-bottom: 1px solid #e5e5e5;
            margin-top: 80px;
        }

        .breadcrumb-312 {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .breadcrumb-312 a {
            color: #2b4c9f;
            text-decoration: none;
            transition: all 0.3s;
        }

        .breadcrumb-312 a:hover {
            text-decoration: underline;
        }

        .breadcrumb-312 .separator-312 {
            color: #999;
        }

        .breadcrumb-312 .current-312 {
            color: #666;
        }

        /* Main Container */
        .review-show-312-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Back Button */
        .review-show-312-back {
            margin-bottom: 1.5rem;
        }

        .review-show-312-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #fff;
            color: #4a5568;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .review-show-312-back-btn:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
            transform: translateX(-4px);
            text-decoration: none;
            color: #2d3748;
        }

        /* Main Card */
        .review-show-312-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e5e5;
            overflow: hidden;
        }

        /* Header */
        .review-show-312-header {
            background: var(--secondary);
            padding: 2.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .review-show-312-header-content {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .review-show-312-header-icon {
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

        .review-show-312-header-icon i {
            font-size: 32px;
            color: #fff;
        }

        .review-show-312-title {
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.5px;
        }

        .review-show-312-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            margin: 0;
        }

        .review-show-312-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-size: 0.95rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .review-show-312-badge-success {
            background: #48bb78;
            color: #fff;
        }

        .review-show-312-badge-warning {
            background: #ed8936;
            color: #fff;
        }

        /* Section Title */
        .review-show-312-section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #e2e8f0;
        }

        .review-show-312-section-title i {
            color: var(--secondary);
        }

        /* Product Section */
        .review-show-312-product-section {
            padding: 2rem;
            border-bottom: 1px solid #e5e5e5;
        }

        .review-show-312-product-card {
            display: flex;
            gap: 2rem;
            background: #f7fafc;
            padding: 1.5rem;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
        }

        .review-show-312-product-image {
            flex-shrink: 0;
        }

        .review-show-312-product-image img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .review-show-312-product-info {
            flex: 1;
            min-width: 0;
        }

        .review-show-312-product-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0 0 1.5rem 0;
            line-height: 1.4;
        }

        .review-show-312-product-meta {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .review-show-312-meta-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #4a5568;
            font-size: 0.95rem;
        }

        .review-show-312-meta-item i {
            color: var(--secondary);
            width: 20px;
            text-align: center;
        }

        .review-show-312-product-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Content Section */
        .review-show-312-content-section {
            padding: 2rem;
            border-bottom: 1px solid #e5e5e5;
        }

        .review-show-312-rating-box {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            background: linear-gradient(135deg, #fef5e7 0%, #fff9e6 100%);
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 5px solid #fbbf24;
            margin-bottom: 2rem;
        }

        .review-show-312-rating-label {
            font-weight: 700;
            color: #744210;
            font-size: 1.1rem;
        }

        .review-show-312-rating-stars {
            display: flex;
            gap: 0.3rem;
        }

        .review-show-312-rating-stars i {
            color: #fbbf24;
            font-size: 1.8rem;
        }

        .review-show-312-rating-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #744210;
            margin-left: auto;
        }

        .review-show-312-text-box {
            background: #fff;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .review-show-312-text-label {
            background: var(--secondary);
            color: #fff;
            padding: 1rem 1.5rem;
            font-weight: 700;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .review-show-312-text-content {
            padding: 2rem;
            background: #f7fafc;
        }

        .review-show-312-text-content p {
            color: #2d3748;
            line-height: 1.8;
            font-size: 1.05rem;
            margin: 0;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .review-show-312-no-text {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 2rem;
            background: #f7fafc;
            border-radius: 12px;
            color: #a0aec0;
            font-size: 1rem;
            border: 2px dashed #cbd5e0;
        }

        /* Photos Section */
        .review-show-312-photos-section {
            margin-top: 2rem;
        }

        .review-show-312-photos-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            color: #2d3748;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .review-show-312-photos-header i {
            color: var(--secondary);
        }

        .review-show-312-photos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        

        .review-show-312-photo-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            aspect-ratio: 1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 3px solid #e2e8f0;
        }

        .review-show-312-photo-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            border-color: var(--secondary);
        }

        .review-show-312-photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .review-show-312-photo-overlay {
            position: absolute;
            inset: 0;
            background: rgba(102, 126, 234, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .review-show-312-photo-item:hover .review-show-312-photo-overlay {
            opacity: 1;
        }

        .review-show-312-photo-overlay i {
            color: #fff;
            font-size: 2rem;
        }

        .review-show-312-photo-overlay span {
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* Timeline Section */
        .review-show-312-timeline-section {
            padding: 2rem;
            border-bottom: 1px solid #e5e5e5;
        }

        /* ===== TIMELINE RIWAYAT STATUS - PROGRESS TRACKING UI (NO DOT) ===== */

        /* Timeline Container */
        .review-show-312-timeline {
            position: relative;
            padding: 0;
            margin-top: 1rem;
        }

        /* Timeline Item (Step) */
        .review-show-312-timeline-item {
            display: flex;
            position: relative;
            margin-bottom: 30px;
            min-height: 40px;
        }

        .review-show-312-timeline-item:last-child {
            margin-bottom: 0;
        }

        /* Garis Penghubung Vertikal - FIXED POSITION */
        .review-show-312-timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 14.5px; /* Tepat di tengah icon box 30px */
            top: 30px; /* Mulai dari bawah icon box */
            width: 3px;
            height: calc(100% + 0px);
            background-color: #e0e0e0;
            z-index: 1;
        }

        /* Garis hijau jika completed */
        .review-show-312-timeline-item.completed:not(:last-child)::before {
            background-color: #28a745;
        }

        /* Icon Box - FIXED */
        .review-show-312-timeline-item .icon-box-312 {
            width: 30px;
            height: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e0e0e0;
            font-size: 16px;
            font-weight: bold;
            background: white;
            z-index: 2;
            position: relative;
            flex-shrink: 0;
        }

        /* Semua checkbox HIJAU */
        .review-show-312-timeline-item .icon-box-312 {
            color: #28a745 !important;
            border-color: #28a745 !important;
            background: white !important;
        }

        /* Remove default ::after for icon */
        .review-show-312-timeline-item::after {
            display: none;
        }

        /* Timeline Content - FIXED */
        .review-show-312-timeline-content {
            position: relative;
            margin-left: 15px; /* Spacing dari icon box */
            padding: 0;
            background: transparent;
            border: none;
            box-shadow: none;
            flex: 1;
        }

        /* Remove default ::before for dot */
        .review-show-312-timeline-content::before {
            display: none;
        }

        .review-show-312-timeline-content:hover {
            transform: none;
            box-shadow: none;
        }

        /* Status Text */
        .review-show-312-timeline-status {
            margin: 0;
            padding: 0;
            font-size: 16px;
            color: #333;
            font-weight: 600;
            line-height: 1.4;
        }

        .review-show-312-timeline-status i,
        .review-show-312-timeline-status span {
            display: none;
        }

        /* Date */
        .review-show-312-timeline-date {
            margin: 4px 0 0;
            padding: 0;
            font-size: 13px;
            color: #777;
            line-height: 1.3;
        }

        .review-show-312-timeline-date i {
            display: none;
        }

        .review-show-312-timeline-date::before {
            display: none;
        }

        /* Notes */
        .review-show-312-timeline-notes {
            font-size: 13px;
            color: #777;
            line-height: 1.4;
            padding: 0;
            background: transparent;
            border: none;
            margin: 4px 0 0;
            font-style: italic;
        }

        /* User Info */
        .review-show-312-timeline-user {
            font-size: 12px;
            color: #999;
            font-style: italic;
            margin-top: 4px;
            padding: 0;
        }

        /* Semua teks tetap warna normal (tidak pudar) */
        .review-show-312-timeline-item .review-show-312-timeline-status {
            color: #333 !important;
        }

        .review-show-312-timeline-item .review-show-312-timeline-date,
        .review-show-312-timeline-item .review-show-312-timeline-notes {
            color: #777 !important;
        }

        .review-show-312-timeline-item .review-show-312-timeline-user {
            color: #999 !important;
        }

        /* Buttons */
        .review-show-312-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .review-show-312-btn-outline {
            background: #fff;
            color: #4a5568;
            border-color: #e2e8f0;
        }

        .review-show-312-btn-outline:hover {
            background: #f7fafc;
            border-color: var(--secondary);
            color: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
            text-decoration: none;
        }

        .review-show-312-btn-primary {
            background: var(--secondary);
            color: #fff;
            border-color: var(--secondary);
        }

        .review-show-312-btn-primary:hover {
            background: #5a67d8;
            border-color: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            text-decoration: none;
            color: #fff;
        }

        .review-show-312-btn-secondary {
            background: #fff;
            color: #4a5568;
            border-color: #cbd5e0;
        }

        .review-show-312-btn-secondary:hover {
            background: #f7fafc;
            border-color: #a0aec0;
            color: #2d3748;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-decoration: none;
        }

        .review-show-312-btn-warning {
            background: #f59e0b;
            color: #fff;
            border-color: #f59e0b;
        }

        .review-show-312-btn-warning:hover {
            background: #d97706;
            border-color: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            text-decoration: none;
            color: #fff;
        }

        /* Actions */
        .review-show-312-actions {
            padding: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
            background: #f7fafc;
        }

        /* ===== MODAL STYLES ===== */
        .modal-312 {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
        }

        .modal-312.show {
            display: block;
        }

        .modal-dialog-312 {
            position: relative;
            width: auto;
            max-width: 600px;
            margin: 1.75rem auto;
        }

        .modal-dialog-centered-312 {
            display: flex;
            align-items: center;
            min-height: calc(100% - 3.5rem);
        }

        .modal-content-312 {
            position: relative;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .modal-header-312 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem;
            background: var(--secondary);
            color: #fff;
        }

        .modal-title-312 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-close-312 {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .modal-close-312:hover {
            opacity: 1;
        }

        .modal-body-312 {
            padding: 1.5rem;
            max-height: 70vh;
            overflow-y: auto;
        }

        .modal-product-info-312 {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background: #f7fafc;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .modal-product-info-312 img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            flex-shrink: 0;
        }

        .modal-product-name-312 {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.25rem;
        }

        .modal-product-meta-312 {
            font-size: 0.875rem;
            color: #718096;
        }

        .modal-form-group-312 {
            margin-bottom: 1.5rem;
        }

        .modal-label-312 {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2d3748;
            font-size: 0.95rem;
        }

        .modal-rating-input-312 {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .modal-rating-input-312 input[type="radio"] {
            display: none;
        }

        .modal-star-label-312 {
            font-size: 2rem;
            color: #cbd5e0;
            cursor: pointer;
            transition: all 0.2s;
        }

        .modal-rating-input-312 input[type="radio"]:checked ~ .modal-star-label-312,
        .modal-rating-input-312 input[type="radio"]:checked + .modal-star-label-312 {
            color: #fbbf24;
        }

        .modal-star-label-312:hover,
        .modal-rating-input-312:hover .modal-star-label-312 {
            color: #fbbf24;
            transform: scale(1.1);
        }

        .modal-textarea-312 {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            resize: vertical;
            font-family: inherit;
            transition: border-color 0.2s;
        }

        .modal-textarea-312:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modal-file-input-312 {
            width: 100%;
            padding: 0.75rem;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .modal-file-input-312:hover {
            border-color: var(--secondary);
        }

        .modal-help-text-312 {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: #6b7280;
        }

        .modal-current-photos-312 {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 1rem;
        }

        .modal-photo-item-312 {
            position: relative;
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
        }

        .modal-photo-item-312 img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-photo-remove-312 {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            width: 24px;
            height: 24px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            transition: all 0.2s;
        }

        .modal-photo-remove-312:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        .modal-photo-preview-312 {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .modal-preview-item-312 {
            position: relative;
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
        }

        .modal-preview-item-312 img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-alert-312 {
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .modal-alert-info-312 {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            color: #1e40af;
        }

        .modal-alert-312 i {
            flex-shrink: 0;
            margin-top: 0.125rem;
        }

        .modal-footer-312 {
            display: flex;
            gap: 0.75rem;
            padding: 1.25rem 1.5rem;
            background: #f7fafc;
            border-top: 1px solid #e5e7eb;
            justify-content: flex-end;
        }

        .modal-btn-312 {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-btn-secondary-312 {
            background: #fff;
            color: #4a5568;
            border-color: #d1d5db;
        }

        .modal-btn-secondary-312:hover {
            background: #f7fafc;
            border-color: #9ca3af;
        }

        .modal-btn-primary-312 {
            background: var(--secondary);
            color: #fff;
            border-color: var(--secondary);
        }

        .modal-btn-primary-312:hover {
            background: #5a67d8;
            border-color: #5a67d8;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .breadcrumb-container-312 {
                margin-top: 60px;
            }

            .breadcrumb-312 {
                padding: 0 1rem;
                font-size: 0.8rem;
            }

            .review-show-312-container {
                padding: 0 1rem;
                margin: 1rem auto;
            }

            .review-show-312-back {
                margin-bottom: 1rem;
            }

            .review-show-312-back-btn {
                width: 100%;
                justify-content: center;
                font-size: 0.95rem;
            }

            .review-show-312-header {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
                gap: 1rem;
            }

            .review-show-312-header-content {
                flex-direction: column;
                text-align: center;
            }

            .review-show-312-header-icon {
                width: 60px;
                height: 60px;
            }

            .review-show-312-header-icon i {
                font-size: 28px;
            }

            .review-show-312-title {
                font-size: 1.5rem;
            }

            .review-show-312-subtitle {
                font-size: 0.9rem;
            }

            .review-show-312-product-section,
            .review-show-312-content-section,
            .review-show-312-timeline-section {
                padding: 1.5rem;
            }

            .review-show-312-section-title {
                font-size: 1.1rem;
            }

            .review-show-312-product-card {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .review-show-312-product-image img {
                width: 120px;
                height: 120px;
                margin: 0 auto;
            }

            .review-show-312-product-title {
                font-size: 1.1rem;
            }

            .review-show-312-product-meta {
                align-items: center;
            }

            .review-show-312-product-actions {
                flex-direction: column;
            }

            .review-show-312-product-actions .review-show-312-btn {
                width: 100%;
                justify-content: center;
            }

            .review-show-312-rating-box {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 1rem;
            }

            .review-show-312-rating-value {
                margin-left: 0;
            }

.review-show-312-photos-grid {
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));  /* UBAH dari repeat(2, 1fr) */
    gap: 0.5rem;  /* UBAH dari 1rem */
}

.review-show-312-photo-item {
    width: 80px;
    height: 80px;
    margin: 0 auto;
}
            

            .review-show-312-timeline-item {
                margin-bottom: 25px;
                min-height: 35px;
            }

            .review-show-312-timeline-item:not(:last-child)::before {
                left: 13.5px;
                top: 28px;
            }

            .review-show-312-timeline-item .icon-box-312 {
                width: 28px;
                height: 28px;
                font-size: 14px;
            }

            .review-show-312-timeline-content {
                margin-left: 12px;
            }

            .review-show-312-timeline-status {
                font-size: 15px;
            }

            .review-show-312-timeline-date,
            .review-show-312-timeline-notes {
                font-size: 12px;
            }

            .review-show-312-timeline-user {
                font-size: 11px;
            }

            .review-show-312-actions {
                flex-direction: column;
                padding: 1.5rem;
            }

            .review-show-312-actions .review-show-312-btn {
                width: 100%;
                justify-content: center;
                font-size: 1.05rem;
                padding: 1.2rem;
            }

            .modal-dialog-312 {
                max-width: 90%;
                margin: 1rem auto;
            }

            .modal-body-312 {
                padding: 1.25rem;
            }

            .modal-current-photos-312,
            .modal-photo-preview-312 {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.75rem;
            }

            .modal-footer-312 {
                flex-direction: column;
                gap: 0.75rem;
            }

            .modal-footer-312 .modal-btn-312 {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .review-show-312-container {
                padding: 0 0.75rem;
            }

            .review-show-312-header {
                padding: 1.2rem;
            }

            .review-show-312-header-icon {
                width: 50px;
                height: 50px;
            }

            .review-show-312-header-icon i {
                font-size: 24px;
            }

            .review-show-312-title {
                font-size: 1.3rem;
            }

            .review-show-312-subtitle {
                font-size: 0.85rem;
            }

            .review-show-312-product-section,
            .review-show-312-content-section,
            .review-show-312-timeline-section {
                padding: 1.2rem;
            }

            .review-show-312-section-title {
                font-size: 1rem;
            }

            .review-show-312-product-image img {
                width: 100px;
                height: 100px;
            }

            .review-show-312-product-title {
                font-size: 1rem;
            }

            .review-show-312-meta-item {
                font-size: 0.85rem;
            }

            .review-show-312-rating-stars i {
                font-size: 1.5rem;
            }

            .review-show-312-rating-value {
                font-size: 1.3rem;
            }

            .review-show-312-text-content {
                padding: 1.2rem;
            }

            .review-show-312-text-content p {
                font-size: 0.95rem;
            }

            .review-show-312-photos-grid {
    grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));  /* UBAH dari repeat(2, 1fr) */
    gap: 0.5rem;  /* UBAH dari 0.75rem */
}

/* HAPUS bagian ini (sudah ada di dalam @media): */
.review-show-312-photo-item {
    width: 100px;
    height: 100px;
    margin: 0 auto;
}

/* GANTI DENGAN: */
.review-show-312-photo-item {
    width: 70px;
    height: 70px;
    margin: 0 auto;
}

            .review-show-312-timeline-item {
                margin-bottom: 22px;
                min-height: 32px;
            }

            .review-show-312-timeline-item:not(:last-child)::before {
                left: 12.5px;
                top: 26px;
            }

            .review-show-312-timeline-item .icon-box-312 {
                width: 26px;
                height: 26px;
                font-size: 13px;
            }

            .review-show-312-timeline-content {
                margin-left: 10px;
            }

            .review-show-312-timeline-status {
                font-size: 14px;
            }

            .review-show-312-timeline-date,
            .review-show-312-timeline-notes {
                font-size: 11px;
            }

            .review-show-312-timeline-user {
                font-size: 10px;
            }

            .review-show-312-actions {
                padding: 1.2rem;
            }

            .review-show-312-actions .review-show-312-btn {
                font-size: 1.1rem;
                padding: 1.4rem;
            }

            .modal-dialog-312 {
                max-width: 95%;
                margin: 0.5rem auto;
            }

            .modal-body-312 {
                padding: 1rem;
                max-height: 60vh;
            }

            .modal-product-info-312 {
                padding: 0.875rem;
            }

            .modal-product-info-312 img {
                width: 50px;
                height: 50px;
            }

            .modal-rating-input-312 {
                gap: 0.25rem;
            }

            .modal-star-label-312 {
                font-size: 1.75rem;
            }

            .modal-current-photos-312,
            .modal-photo-preview-312 {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }
        }
    </style>

    <!-- Modal & Photo Preview Scripts -->
    <script>
        // Open Edit Modal
        function openEditModal312() {
            document.getElementById('editReviewModal312').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Close Edit Modal
        function closeEditModal312() {
            document.getElementById('editReviewModal312').classList.remove('show');
            document.body.style.overflow = '';
        }

        // Close modal when clicking outside
        document.getElementById('editReviewModal312')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal312();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal312();
            }
        });

        // Remove existing photo
        function removePhoto312(index) {
            if (confirm('Hapus foto ini?')) {
                const photoInput = document.getElementById(`existing-photo-${index}-312`);
                if (photoInput) {
                    photoInput.remove();
                }
                const photoItem = event.target.closest('.modal-photo-item-312');
                if (photoItem) {
                    photoItem.remove();
                }
            }
        }

        // Preview new photos
        function previewPhotos312(event) {
            const previewContainer = document.getElementById('photoPreview312');
            previewContainer.innerHTML = '';
            
            const files = event.target.files;
            
            if (files.length > 5) {
                alert('Maksimal 5 foto!');
                event.target.value = '';
                return;
            }
            
            Array.from(files).forEach((file, index) => {
                // Check file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert(`File ${file.name} terlalu besar! Maksimal 2MB per foto.`);
                    return;
                }
                
                // Check file type
                if (!file.type.startsWith('image/')) {
                    alert(`File ${file.name} bukan gambar!`);
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'modal-preview-item-312';
                    div.innerHTML = `<img src="${e.target.result}" alt="Preview ${index + 1}">`;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // Star rating hover effect
        document.addEventListener('DOMContentLoaded', function() {
            const ratingInputs = document.querySelectorAll('.modal-rating-input-312 input[type="radio"]');
            const starLabels = document.querySelectorAll('.modal-star-label-312');
            
            starLabels.forEach((label, index) => {
                label.addEventListener('mouseenter', function() {
                    starLabels.forEach((l, i) => {
                        if (i <= index) {
                            l.style.color = '#fbbf24';
                        } else {
                            l.style.color = '#cbd5e0';
                        }
                    });
                });
            });
            
            document.querySelector('.modal-rating-input-312')?.addEventListener('mouseleave', function() {
                const checkedInput = document.querySelector('.modal-rating-input-312 input[type="radio"]:checked');
                if (checkedInput) {
                    const checkedIndex = Array.from(ratingInputs).indexOf(checkedInput);
                    starLabels.forEach((l, i) => {
                        if (i <= checkedIndex) {
                            l.style.color = '#fbbf24';
                        } else {
                            l.style.color = '#cbd5e0';
                        }
                    });
                } else {
                    starLabels.forEach(l => l.style.color = '#cbd5e0');
                }
            });
            
            // Set initial star colors based on checked radio
            const checkedInput = document.querySelector('.modal-rating-input-312 input[type="radio"]:checked');
            if (checkedInput) {
                const checkedIndex = Array.from(ratingInputs).indexOf(checkedInput);
                starLabels.forEach((l, i) => {
                    if (i <= checkedIndex) {
                        l.style.color = '#fbbf24';
                    }
                });
            }
        });
    </script>

    <!-- Lightbox Script (jika belum ada di layout) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
</x-frontend-layout>