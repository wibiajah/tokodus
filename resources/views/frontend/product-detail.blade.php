<x-frontend-layout>
    <x-slot:title>Tokodus | {{ $product->title }}</x-slot:title>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/modalstore/store-selector-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/modalstore/cart_modal.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/index/rekomendasi-card.css') }}">

    <!-- Breadcrumb -->
    <div class="breadcrumb-container-789">
        <div class="breadcrumb-789">
            <a href="{{ route('home') }}">Home</a>
            <span class="separator-789">/</span>
            <a href="{{ route('catalog') }}">Products</a>
            @if ($product->categories->count() > 0)
                <span class="separator-789">/</span>
                <span class="current-789">{{ $product->title }}</span>
            @endif
        </div>
    </div>

    <div class="product-detail-container-789">
        <!-- Left Side - Product Image & Details -->
        <div class="product-detail-left-789">
            <div class="product-main-image-789">
                @if ($product->has_discount)
                    <div class="discount-badge-789">-{{ $product->discount_percentage }}%</div>
                @endif

                <button class="wishlist-badge-789 {{ $inWishlist ? 'active-789' : '' }}"
                    data-product-id="{{ $product->id }}" onclick="toggleWishlist({{ $product->id }}, this)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                        </path>
                    </svg>
                </button>

                <div class="brand-badge-789">
                    <img src="{{ asset('frontend/assets/img/logo-icon.png') }}" alt="Tokodus"
                        style="width: 26px; height: 26px; object-fit: contain;">
                    <span>Tokodus</span>
                </div>

                <img id="mainImage-789"
                    src="{{ $product->photo_urls[0] ?? asset('frontend/assets/img/placeholder-product.png') }}"
                    alt="{{ $product->title }}">
            </div>

            @if (count($product->photo_urls) > 1 || $product->video)
    <div class="product-thumbnails-789">
        {{-- Photo thumbnails --}}
        @foreach ($product->photo_urls as $index => $photoUrl)
            <div class="thumbnail-789 {{ $index === 0 ? 'active-789' : '' }}"
                data-image="{{ $photoUrl }}"
                data-type="image">
                <img src="{{ $photoUrl }}" alt="{{ $product->title }} - {{ $index + 1 }}">
            </div>
        @endforeach
        
        {{-- Video thumbnail --}}
        @if ($product->video)
            <div class="thumbnail-789 video-thumbnail-789" 
                data-video="{{ asset('storage/' . $product->video) }}"
                data-type="video"
                style="position: relative;">
                <video style="width: 100%; height: 100%; object-fit: cover; display: block;" muted>
                    <source src="{{ asset('storage/' . $product->video) }}" type="video/mp4">
                </video>
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                    width: 50px; height: 50px; pointer-events: none;">
                    <svg viewBox="0 0 24 24" fill="white" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4));">
                        <circle cx="12" cy="12" r="10" opacity="0.8"/>
                        <path d="M10 8l6 4-6 4V8z" fill="#000" opacity="0.7"/>
                    </svg>
                </div>
            </div>
        @endif
    </div>
@endif

            <!-- Product Description -->
            <div class="product-details-section-789">
                <h2>Product Description</h2>
                @if ($product->description)
                    <p>{!! nl2br(e($product->description)) !!}</p>
                @else
                    <p>Tokodus Solusi Packaging</p>
                @endif
            </div>

            <!-- Product Specifications -->
            <div class="product-details-section-789">
                <h2>Product Specifications</h2>
                <div class="specifications-table-789">
                    <div class="spec-row-789">
                        <span class="spec-label-789">Dimensions:</span>
                        <span class="spec-value-789">{{ $product->ukuran ?? '50 × 40 × 40 cm' }}</span>
                    </div>
                    @if ($product->jenis_bahan)
                        <div class="spec-row-789">
                            <span class="spec-label-789">Material:</span>
                            <span class="spec-value-789">{{ $product->jenis_bahan }}</span>
                        </div>
                    @endif
                    <div class="spec-row-789">
                        <span class="spec-label-789">Weight:</span>
                        <span class="spec-value-789">1200 gram</span>
                    </div>
                    <div class="spec-row-789">
                        <span class="spec-label-789">Brand:</span>
                        <span class="spec-value-789">Tokodus</span>
                    </div>
                </div>
            </div>

            <!-- Key Features -->
            <div class="product-details-section-789">
                <h2>Key Features</h2>
                <ul class="features-list-789">
                    @if ($product->jenis_bahan)
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Material: {{ $product->jenis_bahan }}
                        </li>
                    @endif
                    @if ($product->tipe)
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Tipe: {{ $product->tipe_display }}
                        </li>
                    @endif
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Sangat kuat dan tahan lama
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Cocok untuk barang berat
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Dapat digunakan berulang kali
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Tahan air
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Side - Product Info -->
        <div class="product-detail-right-789">
            <h1 class="product-detail-title-789">{{ $product->title }}</h1>

            <div class="product-badges-789">
                @if ($product->categories->count() > 0)
                    <span class="badge-category-789">{{ $product->categories->first()->name }}</span>
                @endif
            </div>

            <div class="product-detail-pricing-789">
                <div class="price-main-789">{{ $product->formatted_price }}</div>
                @if ($product->has_discount)
                    <div class="price-old-789">{{ $product->formatted_original_price }}</div>
                    <div class="price-save-789">Save Rp
                        {{ number_format($product->original_price - $product->price) }}
                    </div>
                @endif
            </div>

            <div class="product-rating-789">
                <div class="stars-789">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($product->rating))
                            <span class="star-789 filled-789">★</span>
                        @elseif($i - 0.5 <= $product->rating)
                            <span class="star-789 half-789">★</span>
                        @else
                            <span class="star-789">★</span>
                        @endif
                    @endfor
                    <span class="rating-number-789">{{ $product->rating }}</span>
                </div>
                <span class="reviews-count-789">• {{ $product->review_count }} reviews</span>
                <span class="sold-count-789">• {{ number_format($product->total_stock) }}+ terjual</span>
            </div>

            <!-- 🆕 VARIANT STOCK SECTION -->
            @if ($product->variantStocks->count() > 0)
                <div class="stock-status-section-789">
                    <div class="stock-header-789">
                        <span class="stock-label-789">Stock Status</span>
                        <span class="stock-available-789" id="totalStockDisplay-789">
                            {{ number_format($product->variantStocks->sum('stock')) }} available
                        </span>
                    </div>

                    <div class="stock-availability-789">
                        <h3>Stock Availability by Store</h3>
                        <p class="stock-info-789">
                            Barang akan dikirim dari gudang pusat jika stock yang tersedia pada cabang tidak memenuhi,
                            dan akan ada penyesuaian biaya pengiriman. Lakukan konfirmasi terlebih dahulu kepada admin
                            sebelum anda melakukan pemesanan.
                        </p>

                        <button class="btn-select-store-789" onclick="openStoreModal789()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            </svg>
                            <span id="selectedStoreDisplay-789">Choose Store Location</span>
                        </button>
                    </div>
                </div>
            @else
                <div class="stock-status-section-789">
                    <div class="stock-header-789">
                        <span class="stock-label-789">Stock Status</span>
                        <span class="stock-available-789 text-danger">Out of Stock</span>
                    </div>
                    <p class="text-muted">This product is currently unavailable in all stores.</p>
                </div>
            @endif

            <!-- 🆕 VARIANT SELECTION SECTION -->
            <div class="variant-selection-section-789" id="variantSelectionSection-789" style="display: none;">
                <h3>Select Variant</h3>

                <!-- Color Variants -->
                <div class="variant-group-789" id="colorVariantGroup-789">
                    <label>Color:</label>
                    <div class="variant-options-789" id="colorOptions-789">
                        <!-- Will be populated by JS -->
                    </div>
                </div>

                <!-- Size Variants (shown after color selected) -->
                <div class="variant-group-789" id="sizeVariantGroup-789" style="display: none;">
                    <label>Size:</label>
                    <div class="variant-options-789" id="sizeOptions-789">
                        <!-- Will be populated by JS -->
                    </div>
                </div>

                <!-- Selected Variant Info -->
                <div class="selected-variant-info-789" id="selectedVariantInfo-789" style="display: none;">
                    <span class="variant-label-789">Selected:</span>
                    <span class="variant-value-789" id="selectedVariantDisplay-789"></span>
                    <span class="variant-stock-789" id="selectedVariantStock-789"></span>
                </div>
            </div>

            <!-- Quantity -->
            <div class="quantity-section-789">
                <label>Quantity</label>
                <div class="quantity-controls-789">
                    <button class="qty-btn-789" id="decreaseQty-789" disabled>-</button>
                    <input type="number" id="quantity-789" value="1" min="1" max="1" readonly>
                    <button class="qty-btn-789" id="increaseQty-789" disabled>+</button>
                    <span class="qty-max-789" id="qtyMax-789">Select store first</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="product-actions-detail-789">
                @auth('customer')
                    <button class="btn-add-to-cart-789" id="addToCartBtn-789" disabled data-authenticated="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span class="btn-text-789">Add to Cart</span>
                    </button>

                    <button class="btn-buy-now-789" id="buyNowBtn-789" disabled data-authenticated="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="9 11 12 14 22 4"></polyline>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        <span class="btn-text-789">Buy Now</span>
                    </button>
                @endauth

                @auth('web')
                    <button class="btn-add-to-cart-789" onclick="showAdminWarning789()"
                        style="background: #dc2626; cursor: not-allowed;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                            </path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        Admin Access Only
                    </button>

                    <button class="btn-buy-now-789" onclick="showAdminWarning789()"
                        style="background: #dc2626; cursor: not-allowed; border-color: #dc2626;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                            </path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        Admin Access Only
                    </button>
                @endauth

                @guest('customer')
                    @guest('web')
                        <button class="btn-add-to-cart-789" onclick="openLoginModal()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Login to Add to Cart
                        </button>

                        <button class="btn-buy-now-789" onclick="openLoginModal()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            Login to Buy Now
                        </button>
                    @endguest
                @endguest
            </div>

            <!-- Additional Info -->
            <div class="additional-info-789">
                <div class="info-item-789">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6"
                        stroke-width="2">
                        <rect x="1" y="3" width="15" height="13"></rect>
                        <path d="M16 8l4-4"></path>
                        <path d="M20 8l-4-4"></path>
                        <path d="M1 16h15"></path>
                    </svg>
                    <span>Free shipping for orders above Rp 100,000 • Delivery 2-3 days</span>
                </div>
                <div class="info-item-789">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                        stroke-width="2">
                        <path d="M9 12l2 2 4-4"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span>100% Original Products with Warranty</span>
                </div>
                <div class="info-item-789">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316"
                        stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>7 Days Easy Return Policy</span>
                </div>
                <div class="info-item-789">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6"
                        stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>24/7 Customer Support</span>
                </div>
            </div>

            <!-- Share Section -->
            <div class="share-section-789">
                <h3>Share this product</h3>
                <div class="share-buttons-789">
                    <button class="share-btn-789 facebook-789" onclick="shareToFacebook789()"
                        title="Share on Facebook">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </button>
                    <button class="share-btn-789 twitter-789" onclick="shareToTwitter789()" title="Share on Twitter">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </button>
                    <button class="share-btn-789 instagram-789" onclick="shareToInstagram789()"
                        title="Share on Instagram">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                        </svg>
                    </button>
                    <button class="share-btn-789 whatsapp-789" onclick="shareToWhatsapp789()"
                        title="Share on WhatsApp">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Product Tags Section 789 -->
            @if ($product->tags && is_array($product->tags) && count($product->tags) > 0)
                <div class="product-tags-section-789">
                    <h3>Product Tags</h3>
                    <div class="tags-wrapper-789">
                        @foreach ($product->tags as $tag)
                            <a href="{{ route('catalog', ['tag' => $tag]) }}" class="tag-item-789">
                                #{{ $tag }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="product-tabs-container-789">
        <div class="product-tabs-789">
            <button class="tab-btn-789 active-789" data-tab="details">Product Details</button>
            <button class="tab-btn-789" data-tab="specifications">Specifications</button>
<button class="tab-btn-789" data-tab="reviews">Reviews ({{ $product->review_count }})</button>
            <button class="tab-btn-789" data-tab="faq">FAQ</button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content-789 active-789" id="tab-details">
            <div class="tab-inner-789">
                <h2>Detailed Product Information</h2>
                @if ($product->description)
                    <p>{!! nl2br(e($product->description)) !!}</p>
                @else
                    <p>BOX KARDUS STORAGE / BIN BOX. Cari Kardus Packing terdekat di Bandung ? Tokodus Bandung aja...
                        Produksi sendiri dengan kualitas terbaik dan terlengkap, mulai dari packing kardus polos hingga
                        dicetak juga bisa dengan custom yang disesuaikan keinginan kamu. Produk ini sangat cocok untuk
                        penyimpanan barang-barang pribadi, dokumen, atau sebagai wadah pengiriman.</p>
                @endif

                <h3 class="mt-4">Key Features</h3>
                <ul class="features-list-789">
                    @if ($product->jenis_bahan)
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Material: {{ $product->jenis_bahan }}
                        </li>
                    @endif
                    @if ($product->tipe)
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Tipe: {{ $product->tipe_display }}
                        </li>
                    @endif
                    @if ($product->ukuran)
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Ukuran: {{ $product->ukuran }}
                        </li>
                    @endif
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Mudah disusun
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Cocok untuk penyimpanan arsip
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Dapat digunakan berulang kali
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content-789" id="tab-specifications">
            <div class="tab-inner-789">
                <h2>Product Specifications</h2>
                <div class="specifications-grid-789">
                    <div class="spec-row-789">
                        <span class="spec-label-789">Dimensions:</span>
                        <span class="spec-value-789">{{ $product->ukuran ?? '-' }}</span>
                    </div>
                    <div class="spec-row-789">
                        <span class="spec-label-789">Material:</span>
                        <span class="spec-value-789">{{ $product->jenis_bahan ?? '-' }}</span>
                    </div>
                    @if ($product->cetak)
                        <div class="spec-row-789">
                            <span class="spec-label-789">Cetak:</span>
                            <span class="spec-value-789">{{ $product->cetak }}</span>
                        </div>
                    @endif
                    @if ($product->finishing)
                        <div class="spec-row-789">
                            <span class="spec-label-789">Finishing:</span>
                            <span class="spec-value-789">{{ $product->finishing }}</span>
                        </div>
                    @endif
                    <div class="spec-row-789">
                        <span class="spec-label-789">Brand:</span>
                        <span class="spec-value-789">Tokodus</span>
                    </div>
                </div>
            </div>
        </div>

<div class="tab-content-789" id="tab-reviews">
    <div class="tab-inner-789">
        <h2>Penilaian Produk</h2>
        
        @if($product->review_count > 0)
            {{-- Rating Summary --}}
            <div class="review-summary-789">
                <div class="summary-left-789">
                    <div class="rating-big-789">{{ number_format($product->rating, 1) }}</div>
                    <div class="stars-big-789">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($product->rating))
                                <span class="star-789 filled-789">★</span>
                            @elseif($i - 0.5 <= $product->rating)
                                <span class="star-789 half-789">★</span>
                            @else
                                <span class="star-789">★</span>
                            @endif
                        @endfor
                    </div>
                    <div class="total-reviews-789">dari {{ $product->review_count }} ulasan</div>
                </div>
                
                <div class="summary-right-789">
                    @php
                        $ratingDistribution = $product->reviews_by_rating;
                        $totalReviews = $product->review_count;
                    @endphp
                    @for($i = 5; $i >= 1; $i--)
                        @php
                            $count = $ratingDistribution[$i] ?? 0;
                            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                        @endphp
                        <div class="rating-bar-789">
                            <span class="bar-label-789">{{ $i }} ⭐</span>
                            <div class="bar-container-789">
                                <div class="bar-fill-789" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="bar-count-789">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Filter Tabs (Shopee Style) --}}
            <div class="review-filters-789">
                <button class="filter-tab-789 active" data-filter="all" onclick="filterReviews789('all')">
                    Semua
                </button>
                <button class="filter-tab-789" data-filter="media" onclick="filterReviews789('media')">
                    Dengan Media ({{ $product->reviews()->whereNotNull('photos')->where('photos', '!=', '[]')->count() }})
                </button>
                <button class="filter-tab-789" data-filter="comment" onclick="filterReviews789('comment')">
                    Dengan Komentar ({{ $product->reviews()->whereNotNull('review')->where('review', '!=', '')->count() }})
                </button>
                <button class="filter-tab-789" data-filter="5" onclick="filterReviews789('5')">
                    5 Bintang ({{ $ratingDistribution[5] ?? 0 }})
                </button>
                <button class="filter-tab-789" data-filter="4" onclick="filterReviews789('4')">
                    4 Bintang ({{ $ratingDistribution[4] ?? 0 }})
                </button>
                <button class="filter-tab-789" data-filter="3" onclick="filterReviews789('3')">
                    3 Bintang ({{ $ratingDistribution[3] ?? 0 }})
                </button>
                <button class="filter-tab-789" data-filter="2" onclick="filterReviews789('2')">
                    2 Bintang ({{ $ratingDistribution[2] ?? 0 }})
                </button>
                <button class="filter-tab-789" data-filter="1" onclick="filterReviews789('1')">
                    1 Bintang ({{ $ratingDistribution[1] ?? 0 }})
                </button>
            </div>

            {{-- Reviews List --}}
            <div class="reviews-list-789" id="reviewsList789">
                @foreach($product->reviews()->latest()->get() as $review)
                <div class="review-card-789" 
                     data-rating="{{ $review->rating }}"
                     data-has-media="{{ $review->photos && count($review->photos) > 0 ? 'true' : 'false' }}"
                     data-has-comment="{{ $review->review ? 'true' : 'false' }}">
                    <div class="review-header-789">
                        <div class="reviewer-info-789">
                            @if($review->customer && $review->customer->foto_profil)
                                <img src="{{ asset('storage/' . $review->customer->foto_profil) }}" 
                                     alt="{{ $review->customer_name }}" 
                                     class="reviewer-avatar-789"
                                     onerror="this.src='{{ asset('frontend/assets/img/default-avatar.png') }}'">
                            @else
                                <img src="{{ asset('frontend/assets/img/default-avatar.png') }}" 
                                     alt="{{ $review->customer_name }}" 
                                     class="reviewer-avatar-789">
                            @endif
                            <div>
                                <div class="reviewer-name-789">{{ $review->customer_name }}</div>
                                <div class="review-date-789">{{ $review->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        <div class="review-rating-789">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <span class="star-789 filled-789">★</span>
                                @else
                                    <span class="star-789 empty-789">☆</span>
                                @endif
                            @endfor
                        </div>
                    </div>
                    
                    {{-- Variant Info (Shopee Style) --}}
                    @if($review->order && $review->order->items->where('product_id', $product->id)->first())
                        @php
                            $orderItem = $review->order->items->where('product_id', $product->id)->first();
                        @endphp
                        <div class="review-variant-789">
                            Variasi: <span>{{ $orderItem->variant_name ?? 'Standard' }}</span>
                        </div>
                    @endif
                    
                    @if($review->review)
                    <div class="review-text-789">
                        {{ $review->review }}
                    </div>
                    @endif
                    
                    @if($review->photos && count($review->photos) > 0)
                    <div class="review-photos-789">
                        @foreach($review->photo_urls as $photo)
                        <img src="{{ $photo }}" alt="Review photo" class="review-photo-789" onclick="openPhotoModal789('{{ $photo }}')">
                        @endforeach
                    </div>
                    @endif
                    
                    {{-- Review Footer (Shopee Style) --}}
                    <div class="review-footer-789">
                        <span class="review-time-789">
                            <i class="far fa-clock"></i> {{ $review->created_at_human }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- No Results Message --}}
            <div class="no-filter-results-789" id="noFilterResults789" style="display: none;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#cbd5e0" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <h3>Tidak ada ulasan yang sesuai</h3>
                <p>Coba filter lain untuk melihat ulasan</p>
            </div>
        @else
            <div class="no-reviews-789">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#cbd5e0" stroke-width="1.5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <h3>Belum Ada Ulasan</h3>
                <p>Jadilah yang pertama memberikan ulasan untuk produk ini</p>
            </div>
        @endif
    </div>
</div>

{{-- Photo Modal --}}
<div class="photo-modal-789" id="photoModal789" onclick="closePhotoModal789()">
    <span class="photo-modal-close-789">&times;</span>
    <img class="photo-modal-content-789" id="photoModalImg789">
</div>

        <div class="tab-content-789" id="tab-faq">
            <div class="tab-inner-789">
                <h2>Frequently Asked Questions</h2>
                <p class="text-muted">FAQ section coming soon...</p>
            </div>
        </div>

        <!-- Related Products Section 789 -->
        @if ($relatedProducts && $relatedProducts->count() > 0)
    <div class="related-products-section-789">
        <div class="related-products-container-789">
            <div class="related-products-header-789">
                <h2>Produk Terkait</h2>
            </div>

            <div class="related-products-grid-789">
                @php
                    $defaultPlaceholder =
                        'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="500"%3E%3Crect width="100%25" height="100%25" fill="%23e0e0e0"/%3E%3Ctext x="50%25" y="50%25" font-family="Arial" font-size="20" fill="%23666" text-anchor="middle" dominant-baseline="middle"%3ENo Image%3C/text%3E%3C/svg%3E';
                @endphp

                @foreach ($relatedProducts as $product)
                    <a href="{{ route('product.detail', $product->id) }}" class="product-card-441">
                        <!-- Product Image -->
                        <div class="product-image-441">
                            @if ($product->photo_urls && count($product->photo_urls) > 0)
                                <img alt="{{ $product->sku }}" class="default-image-441"
                                    src="{{ $product->photo_urls[0] }}"
                                    onerror="this.src='{{ $defaultPlaceholder }}'" />
                                @if (count($product->photo_urls) > 1)
                                    <img alt="{{ $product->sku }}" class="hover-image-441"
                                        src="{{ $product->photo_urls[1] }}"
                                        onerror="this.style.display='none'" />
                                @endif
                            @else
                                <img alt="{{ $product->sku }}" class="default-image-441"
                                    src="{{ $product->thumbnail ?? $defaultPlaceholder }}"
                                    onerror="this.src='{{ $defaultPlaceholder }}'" />
                            @endif

                            @if ($product->has_discount)
                                <span class="badge-discount-441">-{{ $product->discount_percentage }}%</span>
                            @endif
                            @if (!$product->is_available)
                                <span class="badge-stock-441">Stok Habis</span>
                            @endif
                        </div>

                        <!-- Product Label (SKU) -->
                        <div class="product-label-441">
                            <span>{{ $product->sku }}</span>
                        </div>

                        <!-- Product Details -->
                        <div class="product-detail-441">
                            <div class="product-title-441">
                                <p>{{ $product->tipe_display }}</p>
                            </div>
                            <div class="ratings-441" title="{{ $product->rating }} dari 5">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($product->rating))
                                        <span class="star-441 filled-441">★</span>
                                    @elseif($i - 0.5 <= $product->rating)
                                        <span class="star-441 half-441">★</span>
                                    @else
                                        <span class="star-441 empty-441">☆</span>
                                    @endif
                                @endfor
                            </div>
                            <div class="product-deskripsi-441">
                                <p>{{ $product->title }}</p>
                            </div>
                            <div class="product-price-441">
                                @if ($product->has_discount)
                                    <span class="original-price-441">{{ $product->formatted_original_price }}</span>
                                @endif
                                <span class="final-price-441">{{ $product->formatted_price }}</span>
                            </div>

                            <!-- Icon Actions -->
                            <span
                                class="wishlist-icon-441 {{ in_array($product->id, $wishlistIds ?? []) ? 'active' : '' }}"
                                data-product-id="{{ $product->id }}"
                                onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }}, this);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                            </span>
                            <span class="share-icon-441"
                                onclick="event.preventDefault(); event.stopPropagation(); shareProduct({{ $product->id }});">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
    </div>

    <!-- 🆕 STORE SELECTOR MODAL -->
    <div class="store-modal-overlay-789" id="storeModalOverlay-789">
        <div class="store-modal-789" id="storeModal-789">
            <!-- Header -->
            <div class="store-modal-header-789">
                <h2>Choose Store Location</h2>
                <button class="store-modal-close-789" id="closeStoreModal-789">×</button>
            </div>

            <!-- Search -->
            <div class="store-modal-search-789">
                <input type="text" class="store-search-input-789" id="storeSearchInput-789"
                    placeholder="Search store by name...">
            </div>

            <!-- Body with Filters -->
            <!-- Body with Filters -->
            <div class="store-modal-body-789">
                <!-- FILTER SECTION (REVISED - Remove Stock Filter) -->
                <div class="store-filter-section-789">
                    <label class="store-filter-label-789">Filter by:</label>
                    <div class="store-filter-buttons-789">
                        <!-- Keep: Nearest filter -->
                        <button class="filter-btn-789" id="filterNearest-789" onclick="filterByNearest789()"
                            data-customer-postal="{{ $customerPostalCode ?? '' }}"
                            {{ !$customerPostalCode ? 'disabled' : '' }}>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            {{ $customerPostalCode ? 'Nearest' : 'Nearest (Login)' }}
                        </button>

                        <!-- ❌ REMOVED: Highest Stock button (delete this entire button) -->

                        <!-- Keep: Reset filter -->
                        <button class="filter-btn-789" onclick="resetFilter789()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="1 4 1 10 7 10"></polyline>
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Store List -->
                <div id="storeListContainer-789"></div>
            </div>

            <!-- Footer -->
            <div class="store-modal-footer-789">
                <button class="store-modal-btn-789 store-modal-btn-cancel-789" id="cancelStoreBtn-789">
                    Cancel
                </button>
                <button class="store-modal-btn-789 store-modal-btn-confirm-789" id="confirmStoreBtn-789" disabled>
                    Confirm Selection
                </button>
            </div>
        </div>
    </div>

    <style>/* ============================================================================
   MOBILE FIX: Related Products Grid (789) - Override CSS 441 untuk Mobile
   Hanya untuk Android/iOS - Desktop tetap horizontal scroll
   ============================================================================ */

/* Tablet - 768px ke bawah */
@media (max-width: 768px) {
    /* Override grid dari horizontal scroll jadi 2 kolom grid */
    .related-products-grid-789 {
        display: flex !important;
        flex-wrap: wrap !important;
        justify-content: space-between !important;
        overflow-x: visible !important;
        scroll-snap-type: none !important;
        padding: 0 1rem !important;
        gap: 15px !important;
    }

    /* Override card dari horizontal scroll jadi grid item */
    .related-products-grid-789 .product-card-441 {
        flex: 1 1 calc(50% - 7.5px) !important;
        min-width: 150px !important;
        max-width: calc(50% - 7.5px) !important;
        width: calc(50% - 7.5px) !important;
        height: 250px !important;
        flex-shrink: 1 !important;
        scroll-snap-align: none !important;
    }

    /* Atur ulang container padding */
    .related-products-container-789 {
        padding: 1.5rem 0 !important;
        margin: 0 1rem !important;
    }

    .related-products-header-789 {
        padding: 0 1rem 1rem 1rem !important;
    }
}

/* Mobile Large - 600px to 767px */
@media (min-width: 600px) and (max-width: 767px) {
    .related-products-grid-789 {
        padding: 0 1rem !important;
        gap: 15px !important;
    }

    .related-products-grid-789 .product-card-441 {
        flex: 1 1 calc(50% - 7.5px) !important;
        min-width: 160px !important;
        max-width: calc(50% - 7.5px) !important;
        width: calc(50% - 7.5px) !important;
        height: 240px !important;
    }
}

/* Mobile - 440px to 599px */
@media (min-width: 440px) and (max-width: 599px) {
    .related-products-grid-789 {
        padding: 0 1rem !important;
        gap: 12px !important;
    }

    .related-products-grid-789 .product-card-441 {
        flex: 1 1 calc(50% - 6px) !important;
        min-width: 150px !important;
        max-width: calc(50% - 6px) !important;
        width: calc(50% - 6px) !important;
        height: 230px !important;
    }
}

/* Mobile Small - 380px to 439px */
@media (min-width: 380px) and (max-width: 439px) {
    .related-products-grid-789 {
        padding: 0 0.8rem !important;
        gap: 10px !important;
    }

    .related-products-grid-789 .product-card-441 {
        flex: 1 1 calc(50% - 5px) !important;
        min-width: 145px !important;
        max-width: calc(50% - 5px) !important;
        width: calc(50% - 5px) !important;
        height: 220px !important;
    }

    .related-products-container-789 {
        margin: 0 0.8rem !important;
    }
}

/* Mobile Extra Small - below 380px */
@media (max-width: 379px) {
    .related-products-grid-789 {
        padding: 0 0.5rem !important;
        gap: 10px !important;
    }

    .related-products-grid-789 .product-card-441 {
        flex: 1 1 calc(50% - 5px) !important;
        min-width: 140px !important;
        max-width: calc(50% - 5px) !important;
        width: calc(50% - 5px) !important;
        height: 210px !important;
    }

    .related-products-container-789 {
        margin: 0 0.5rem !important;
        padding: 1rem 0 !important;
    }

    .related-products-header-789 {
        padding: 0 0.5rem 1rem 0.5rem !important;
    }
}

/* Disable hover effects pada mobile untuk card 441 di section 789 */
@media (max-width: 768px) {
    .related-products-grid-789 .product-card-441:hover {
        transform: none !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important;
    }

    .related-products-grid-789 .product-card-441:hover .product-detail-441,
    .related-products-grid-789 .product-card-441:hover .ratings-441 {
        transform: none !important;
    }

    .related-products-grid-789 .product-card-441:hover .default-image-441 {
        opacity: 1 !important;
    }

    .related-products-grid-789 .product-card-441:hover .hover-image-441 {
        opacity: 0 !important;
    }

    .related-products-grid-789 .product-card-441:hover .original-price-441 {
        opacity: 1 !important;
        visibility: visible !important;
        height: auto !important;
    }

    .related-products-grid-789 .product-card-441:hover .final-price-441 {
        margin-top: 0 !important;
    }

    .related-products-grid-789 .product-label-441 {
        left: -2rem !important;
    }

    .related-products-grid-789 .wishlist-icon-441,
    .related-products-grid-789 .share-icon-441 {
        opacity: 0 !important;
        visibility: hidden !important;
    }
}
</style>

    <!-- 🆕 LINK CSS & JS -->
    <script src="{{ asset('frontend/assets/js/store-selector-modal.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/product-detail.js') }}"></script>
    <script>
        const storesDataFromBackend = @json($storesData);
        const customerPostalCode = @json($customerPostalCode); // ← TAMBAHKAN INI

        initStoreModal789(storesDataFromBackend, customerPostalCode); // ← PASS 2 PARAMETERS
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 🔥 CRITICAL: Convert PHP data to JavaScript dengan LOCATION INFO
            const storesDataFromServer = @json($storesData);

            console.log('🏪 Stores data received:', storesDataFromServer);
            console.log('📍 First store location:', storesDataFromServer[0]?.location);

            // Initialize store modal dengan data lengkap
            if (typeof initStoreModal789 === 'function') {
                initStoreModal789(
                    storesDataFromServer,
                    '{{ $customerPostalCode ?? null }}'
                );
            }
        });
    </script>

  {{-- Load external wishlist handler --}}
    <script src="{{ asset('frontend/assets/js/wishlist/wishlist-handler.js') }}"></script>

    {{-- Set konfigurasi wishlist --}}
    <script>
        window.wishlistConfig = {
            isAuthenticated: {{ auth('customer')->check() ? 'true' : 'false' }},
            loginUrl: "{{ route('home') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    
{{-- Video & Image Gallery Handler --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mainImageElement = document.getElementById('mainImage-789');
    const thumbnails = document.querySelectorAll('.thumbnail-789');
    
    if (!mainImageElement || thumbnails.length === 0) return;
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            
            // Update active state
            thumbnails.forEach(t => t.classList.remove('active-789'));
            this.classList.add('active-789');
            
            if (type === 'video') {
                // Switch to video
                const videoUrl = this.getAttribute('data-video');
                
                if (mainImageElement.tagName === 'IMG') {
                    // Create video element
                    const video = document.createElement('video');
                    video.id = 'mainImage-789';
                    video.controls = true;
                    video.autoplay = true;
                    video.muted = false;
                    video.style.cssText = 'width: 100%; height: 100%; object-fit: contain; border-radius: 12px;';
                    
                    const source = document.createElement('source');
                    source.src = videoUrl;
                    source.type = 'video/mp4';
                    video.appendChild(source);
                    
                    // Replace only the img element
                    mainImageElement.replaceWith(video);
                }
            } else if (type === 'image') {
                // Switch to image
                const imageUrl = this.getAttribute('data-image');
                const currentElement = document.getElementById('mainImage-789');
                
                if (currentElement.tagName === 'VIDEO') {
                    // Pause video first
                    currentElement.pause();
                    
                    // Create img element
                    const img = document.createElement('img');
                    img.id = 'mainImage-789';
                    img.src = imageUrl;
                    img.alt = '{{ $product->title }}';
                    img.style.cssText = 'width: 100%; height: 100%; object-fit: contain; border-radius: 12px;';
                    
                    // Replace video with img
                    currentElement.replaceWith(img);
                } else {
                    // Just change src if already img
                    currentElement.src = imageUrl;
                }
            }
        });
    });
});
</script>
<style>
/* Review Summary */
.review-summary-789 {
    display: flex;
    gap: 3rem;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.summary-left-789 {
    text-align: center;
    min-width: 200px;
}

.rating-big-789 {
    font-size: 4rem;
    font-weight: 700;
    color: #111;
    line-height: 1;
}

.stars-big-789 {
    font-size: 2rem;
    color: #fbbf24;
    margin: 0.5rem 0;
}

.total-reviews-789 {
    color: #6b7280;
    font-size: 0.938rem;
}

.summary-right-789 {
    flex: 1;
}

.rating-bar-789 {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.bar-label-789 {
    font-size: 0.875rem;
    color: #6b7280;
    min-width: 50px;
}

.bar-container-789 {
    flex: 1;
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
}

.bar-fill-789 {
    height: 100%;
    background: #fbbf24;
    transition: width 0.3s;
}

.bar-count-789 {
    font-size: 0.875rem;
    color: #6b7280;
    min-width: 40px;
    text-align: right;
}

/* Reviews List */
.reviews-list-789 {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.review-card-789 {
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: white;
}

.review-header-789 {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.reviewer-info-789 {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.reviewer-avatar-789 {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e7eb;
}

.reviewer-name-789 {
    font-weight: 600;
    color: #111;
    margin-bottom: 0.25rem;
}

.review-date-789 {
    font-size: 0.813rem;
    color: #6b7280;
}

.review-rating-789 .star-789 {
    font-size: 1.25rem;
    color: #fbbf24;
}

.review-text-789 {
    color: #374151;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.review-photos-789 {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.review-photo-789 {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    border: 1px solid #e5e7eb;
    transition: transform 0.2s;
}

.review-photo-789:hover {
    transform: scale(1.05);
}

/* Load More */
.load-more-reviews-789 {
    text-align: center;
    margin-top: 2rem;
}

.btn-load-more-789 {
    padding: 0.75rem 2rem;
    background: white;
    border: 2px solid #1f4390;
    color: #1f4390;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-load-more-789:hover {
    background: #1f4390;
    color: white;
}

/* No Reviews */
.no-reviews-789 {
    text-align: center;
    padding: 4rem 2rem;
}

.no-reviews-789 h3 {
    margin: 1rem 0 0.5rem;
    color: #111;
}

.no-reviews-789 p {
    color: #6b7280;
}

/* Photo Modal */
.photo-modal-789 {
    display: none;
    position: fixed;
    z-index: 9999;
    padding-top: 60px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.9);
}

.photo-modal-content-789 {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    border-radius: 8px;
}

.photo-modal-close-789 {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
    cursor: pointer;
}

.photo-modal-close-789:hover {
    color: #bbb;
}

/* Review Filters (Shopee Style) */
.review-filters-789 {
    display: flex;
    gap: 0.5rem;
    padding: 1.5rem 0;
    border-bottom: 2px solid #f3f4f6;
    margin-bottom: 1.5rem;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.review-filters-789::-webkit-scrollbar {
    height: 4px;
}

.review-filters-789::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.filter-tab-789 {
    padding: 0.5rem 1rem;
    border: 1px solid #e5e7eb;
    background: white;
    border-radius: 20px;
    font-size: 0.875rem;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
    flex-shrink: 0;
}

.filter-tab-789:hover {
    border-color: #1f4390;
    color: #1f4390;
}

.filter-tab-789.active {
    background: #1f4390;
    color: white;
    border-color: #1f4390;
}

/* No Filter Results */
.no-filter-results-789 {
    text-align: center;
    padding: 4rem 2rem;
}

.no-filter-results-789 h3 {
    margin: 1rem 0 0.5rem;
    color: #111;
}

.no-filter-results-789 p {
    color: #6b7280;
}

/* Review Variant Info (Shopee Style) */
.review-variant-789 {
    background: #f8f9fa;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-size: 0.813rem;
    color: #6b7280;
    margin-bottom: 0.75rem;
}

.review-variant-789 span {
    color: #111;
    font-weight: 500;
}

/* Review Footer */
.review-footer-789 {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f3f4f6;
    margin-top: 0.75rem;
}

.review-time-789 {
    font-size: 0.75rem;
    color: #9ca3af;
}

.review-time-789 i {
    margin-right: 0.25rem;
}

.review-rating-789 .star-789.empty-789 {
    color: #d1d5db;
}

/* Responsive */
@media (max-width: 768px) {
    .review-summary-789 {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .summary-left-789 {
        min-width: auto;
    }
    
    .review-header-789 {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .review-variant-789 {
        font-size: 0.75rem;
    }
    
    .review-filters-789 {
        padding: 1rem 0;
    }
    
    .filter-tab-789 {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
}
</style>

{{-- ============================================
     TAMBAHKAN JS INI DI AKHIR FILE (sebelum </x-frontend-layout>)
     ============================================ --}}
<script>
function openPhotoModal789(photoUrl) {
    const modal = document.getElementById('photoModal789');
    const modalImg = document.getElementById('photoModalImg789');
    modal.style.display = 'block';
    modalImg.src = photoUrl;
}

function closePhotoModal789() {
    document.getElementById('photoModal789').style.display = 'none';
}

function loadMoreReviews789() {
    // TODO: Implement load more functionality via AJAX
    alert('Load more reviews - Coming soon!');
}

// ============================================
// FILTER REVIEWS FUNCTION (SHOPEE STYLE)
// ============================================
function filterReviews789(filter) {
    const allCards = document.querySelectorAll('.review-card-789');
    const noResultsDiv = document.getElementById('noFilterResults789');
    const filterTabs = document.querySelectorAll('.filter-tab-789');
    
    // Update active tab
    filterTabs.forEach(tab => {
        if (tab.getAttribute('data-filter') === filter) {
            tab.classList.add('active');
        } else {
            tab.classList.remove('active');
        }
    });
    
    let visibleCount = 0;
    
    allCards.forEach(card => {
        let shouldShow = false;
        
        if (filter === 'all') {
            shouldShow = true;
        } else if (filter === 'media') {
            shouldShow = card.getAttribute('data-has-media') === 'true';
        } else if (filter === 'comment') {
            shouldShow = card.getAttribute('data-has-comment') === 'true';
        } else if (['1', '2', '3', '4', '5'].includes(filter)) {
            shouldShow = card.getAttribute('data-rating') === filter;
        }
        
        if (shouldShow) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    if (visibleCount === 0) {
        noResultsDiv.style.display = 'block';
    } else {
        noResultsDiv.style.display = 'none';
    }
}
</script>

</x-frontend-layout>
