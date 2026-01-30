<x-frontend-layout>
    <!-- Breadcrumb -->
    <div class="breadcrumb-container-774">
        <div class="breadcrumb-774">
            <a href="{{ route('home') }}">Home</a>
            <span class="separator-774">/</span>
            <a href="{{ route('customer.orders.index') }}">Pesanan Saya</a>
            <span class="separator-774">/</span>
            <a href="{{ route('customer.orders.show', $order->order_number) }}">{{ $order->order_number }}</a>
            <span class="separator-774">/</span>
            <span class="current-774">Beri Ulasan</span>
        </div>
    </div>

    <!-- Main Container -->
    <div class="review-774-container">
        <div class="review-774-content">
            <!-- Page Header -->
            <div class="review-774-header-card">
                <div class="review-774-header-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h4 class="review-774-header-title">Beri Ulasan Produk</h4>
                <p class="review-774-header-subtitle">Bagikan pengalaman Anda dengan produk ini</p>
            </div>

            <!-- Product Info -->
            <div class="review-774-product-card">
                <div class="review-774-product-content">
                    <div class="review-774-product-image">
                        <img src="{{ $orderItem->variant_photo }}" 
                             alt="{{ $product->title }}">
                    </div>
                    <div class="review-774-product-info">
                        <h5 class="review-774-product-title">{{ $product->title }}</h5>
                        <div class="review-774-product-variant">
                            <i class="fas fa-tag"></i>
                            <span>{{ $orderItem->variant_display }}</span>
                        </div>
                        <div class="review-774-product-qty">
                            <span class="review-774-badge">Qty: {{ $orderItem->quantity }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <div class="review-774-form-card">
                <form action="{{ route('customer.reviews.store', [$order, $product]) }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      id="reviewForm">
                    @csrf

                    <!-- Rating -->
                    <div class="review-774-form-group">
                        <label class="review-774-label">
                            Rating <span class="review-774-required">*</span>
                        </label>
                        <div class="review-774-rating-container">
                            <input type="hidden" name="rating" id="rating" value="{{ old('rating', 0) }}" required>
                            <div class="review-774-stars" id="stars">
                                <i class="far fa-star" data-rating="1"></i>
                                <i class="far fa-star" data-rating="2"></i>
                                <i class="far fa-star" data-rating="3"></i>
                                <i class="far fa-star" data-rating="4"></i>
                                <i class="far fa-star" data-rating="5"></i>
                            </div>
                            <small class="review-774-hint">Klik bintang untuk memberikan rating</small>
                        </div>
                        @error('rating')
                            <div class="review-774-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Review Text -->
                    <div class="review-774-form-group">
                        <label for="review" class="review-774-label">
                            Ulasan (Opsional)
                            <span class="review-774-char-counter">
                                <span id="charCount">0</span>/1000
                            </span>
                        </label>
                        <textarea name="review" 
                                  id="review" 
                                  rows="6" 
                                  class="review-774-textarea @error('review') is-invalid @enderror"
                                  placeholder="Ceritakan pengalaman Anda dengan produk ini... Bagaimana kualitasnya? Apakah sesuai ekspektasi?"
                                  maxlength="1000">{{ old('review') }}</textarea>
                        @error('review')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Photos -->
                    <div class="review-774-form-group">
                        <label class="review-774-label">Foto Produk (Opsional)</label>
                        <div class="review-774-file-input-wrapper">
                            <input type="file" 
                                   class="review-774-file-input @error('photos.*') is-invalid @enderror" 
                                   id="photos" 
                                   name="photos[]" 
                                   multiple 
                                   accept="image/jpeg,image/jpg,image/png">
                            <label class="review-774-file-label" for="photos">
                                <i class="fas fa-cloud-upload-alt mr-2"></i>
                                <span id="fileLabel">Pilih foto...</span>
                            </label>
                        </div>
                        <small class="review-774-hint">
                            <i class="fas fa-info-circle mr-1"></i>
                            Maksimal 5 foto (JPG, PNG, max 2MB per foto)
                        </small>
                        @error('photos')
                            <div class="review-774-error">{{ $message }}</div>
                        @enderror
                        @error('photos.*')
                            <div class="review-774-error">{{ $message }}</div>
                        @enderror
                        
                        <!-- Preview Photos -->
                        <div id="photoPreview" class="review-774-preview-container d-none">
                            <div class="row" id="previewContainer"></div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="review-774-form-actions">
                        <a href="{{ route('customer.orders.show', $order->order_number) }}" 
                           class="review-774-btn review-774-btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="review-774-btn review-774-btn-primary" id="submitBtn">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Ulasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Breadcrumb 774 - Following 789 style */
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

        /* Main Container - Following 789 style */
        .review-774-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .review-774-content {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Header Card */
        .review-774-header-card {
            background: var(--secondary);
            padding: 2.5rem 2rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .review-774-header-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            backdrop-filter: blur(10px);
        }

        .review-774-header-icon i {
            font-size: 28px;
            color: #fff;
        }

        .review-774-header-title {
            color: #fff;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .review-774-header-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            margin: 0;
        }

        /* Product Card */
        .review-774-product-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e5e5;
        }

        .review-774-product-content {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .review-774-product-image {
            flex-shrink: 0;
        }

        .review-774-product-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #f0f0f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .review-774-product-info {
            flex: 1;
            min-width: 0;
        }

        .review-774-product-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .review-774-product-variant {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .review-774-product-variant i {
            color: #667eea;
        }

        .review-774-badge {
            display: inline-block;
            background: var(--secondary);
            color: #fff;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Form Card */
        .review-774-form-card {
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e5e5;
        }

        .review-774-form-group {
            margin-bottom: 1.5rem;
        }

        .review-774-label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
        }

        .review-774-required {
            color: #e53e3e;
        }

        /* Rating Section */
        .review-774-rating-container {
            background: #FFF;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .review-774-stars {
            font-size: 3rem;
            cursor: pointer;
            display: inline-block;
            letter-spacing: 12px;
            margin-bottom: 1rem;
        }

        .review-774-stars i {
            color: #cbd5e0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .review-774-stars i:hover {
            color: #fbbf24;
            transform: scale(1.15);
        }

        .review-774-stars i.active {
            color: #fbbf24;
            transform: scale(1.1);
        }

        .review-774-hint {
            display: block;
            color: #718096;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* Textarea */
        .review-774-textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #2d3748;
            transition: all 0.3s ease;
            resize: vertical;
            font-family: inherit;
        }

        .review-774-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .review-774-textarea::placeholder {
            color: #a0aec0;
        }

        .review-774-char-counter {
            float: right;
            color: #718096;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* File Input */
        .review-774-file-input-wrapper {
            position: relative;
            margin-bottom: 0.5rem;
        }

        .review-774-file-input {
            position: absolute;
            opacity: 0;
            width: 0.1px;
            height: 0.1px;
        }

        .review-774-file-label {
            display: block;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #4a5568;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .review-774-file-label:hover {
            background: linear-gradient(135deg, #edf2f7 0%, #e2e8f0 100%);
            border-color: #667eea;
            color: #667eea;
        }

        .review-774-file-label i {
            font-size: 1.1rem;
        }

        /* Preview Container */
        .review-774-preview-container {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px dashed #e2e8f0;
        }

        .review-774-preview-image {
            position: relative;
            margin-bottom: 1rem;
        }

        .review-774-preview-image img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .review-774-preview-image:hover img {
            border-color: #667eea;
            transform: scale(1.02);
        }

        .review-774-remove-photo {
            position: absolute;
            top: -10px;
            right: -10px;
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
            border: 3px solid white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(229, 62, 62, 0.4);
            transition: all 0.3s ease;
        }

        .review-774-remove-photo:hover {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            transform: scale(1.1) rotate(90deg);
        }

        /* Error Messages */
        .review-774-error {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .review-774-error::before {
            content: "⚠";
            font-size: 1rem;
        }

        /* Form Actions */
        .review-774-form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding-top: 1.5rem;
            margin-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .review-774-btn {
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .review-774-btn-secondary {
            background: #fff;
            color: #4a5568;
            border: 2px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .review-774-btn-secondary:hover {
            background: #f7fafc;
            color: #2d3748;
            border-color: #cbd5e0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-decoration: none;
        }

        .review-774-btn-primary {
            background: var(--secondary);
            color: #fff;
            border: none;
        }

        .review-774-btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .review-774-btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            box-shadow: none;
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

            .review-774-container {
                padding: 0 1rem;
                margin: 1rem auto;
            }

            .review-774-header-card {
                padding: 2rem 1.5rem;
            }

            .review-774-header-title {
                font-size: 1.5rem;
            }

            .review-774-form-card {
                padding: 1.5rem;
            }

            .review-774-product-content {
                flex-direction: column;
                text-align: center;
            }

            .review-774-product-image img {
                width: 120px;
                height: 120px;
            }

            .review-774-stars {
                font-size: 2.5rem;
                letter-spacing: 8px;
            }

            .review-774-form-actions {
                flex-direction: column-reverse;
                gap: 0.8rem;
            }

            .review-774-btn {
                width: 100%;
                justify-content: center;
                padding: 1.2rem 2rem;
                font-size: 1.05rem;
                font-weight: 700;
            }

            .review-774-char-counter {
                float: none;
                display: block;
                margin-top: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .review-774-container {
                padding: 0 0.5rem;
            }

            .review-774-header-card {
                padding: 1.5rem 1rem;
            }

            .review-774-header-title {
                font-size: 1.3rem;
            }

            .review-774-form-card {
                padding: 1rem;
            }

            .review-774-stars {
                font-size: 2rem;
                letter-spacing: 6px;
            }

            .review-774-btn {
                padding: 1.4rem 2rem;
                font-size: 1.1rem;
                font-weight: 700;
                border-radius: 10px;
            }

            .review-774-btn i {
                font-size: 1.2rem;
            }
        }
    </style>

    <script>
        // Star Rating
        const stars = document.querySelectorAll('#stars i');
        const ratingInput = document.getElementById('rating');
        const submitBtn = document.getElementById('submitBtn');
        let currentRating = {{ old('rating', 0) }};

        // Set initial rating if exists
        if (currentRating > 0) {
            setRating(currentRating);
            enableSubmitButton();
        }

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                setRating(rating);
                ratingInput.value = rating;
                
                // Enable submit button
                enableSubmitButton();
            });

            star.addEventListener('mouseenter', function() {
                const rating = this.getAttribute('data-rating');
                highlightStars(rating);
            });
        });

        document.getElementById('stars').addEventListener('mouseleave', function() {
            highlightStars(currentRating);
        });

        function setRating(rating) {
            currentRating = rating;
            highlightStars(rating);
        }

        function highlightStars(rating) {
            stars.forEach(star => {
                const starRating = star.getAttribute('data-rating');
                if (starRating <= rating) {
                    star.classList.remove('far');
                    star.classList.add('fas', 'active');
                } else {
                    star.classList.remove('fas', 'active');
                    star.classList.add('far');
                }
            });
        }

        function enableSubmitButton() {
            submitBtn.disabled = false;
            submitBtn.classList.remove('disabled');
            submitBtn.style.cursor = 'pointer';
            submitBtn.style.opacity = '1';
        }

        // Character Counter
        const reviewText = document.getElementById('review');
        const charCount = document.getElementById('charCount');

        reviewText.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // Update initial count
        charCount.textContent = reviewText.value.length;

        // Photo Preview
        const photoInput = document.getElementById('photos');
        const photoPreview = document.getElementById('photoPreview');
        const previewContainer = document.getElementById('previewContainer');
        const fileLabel = document.getElementById('fileLabel');
        let selectedFiles = [];

        photoInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            if (files.length > 5) {
                alert('Maksimal 5 foto');
                this.value = '';
                return;
            }

            selectedFiles = files;
            updatePreview();
            updateFileLabel();
        });

        function updatePreview() {
            previewContainer.innerHTML = '';
            
            if (selectedFiles.length === 0) {
                photoPreview.classList.add('d-none');
                return;
            }

            photoPreview.classList.remove('d-none');

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-6 col-md-3';
                    col.innerHTML = `
                        <div class="review-774-preview-image">
                            <img src="${e.target.result}" alt="Preview ${index + 1}">
                            <button type="button" class="review-774-remove-photo" onclick="removePhoto(${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    previewContainer.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        }

        function updateFileLabel() {
            if (selectedFiles.length === 0) {
                fileLabel.innerHTML = 'Pilih foto...';
            } else if (selectedFiles.length === 1) {
                fileLabel.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${selectedFiles[0].name}`;
            } else {
                fileLabel.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${selectedFiles.length} foto dipilih`;
            }
        }

        window.removePhoto = function(index) {
            selectedFiles.splice(index, 1);
            
            // Update file input
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            photoInput.files = dt.files;
            
            updatePreview();
            updateFileLabel();
        }

        // Form Validation
        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            const submitButton = e.target.querySelector('button[type="submit"]');
            
            if (!ratingInput.value || ratingInput.value == 0 || ratingInput.value === '') {
                e.preventDefault();
                alert('Silakan berikan rating terlebih dahulu dengan klik bintang di atas');
                return false;
            }
            
            // Disable submit button to prevent double submission
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
        });
    </script>
    
</x-frontend-layout>