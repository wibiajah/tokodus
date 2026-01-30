<x-frontend-layout title="Tokodus | Solusi Packaging Anda">

    <!-- ========================================
         CRITICAL META TAGS - iOS & Android Optimized
         ======================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#1f4390">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Preconnect untuk performance -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <!-- ========================================
         CRITICAL CSS - Load First (Above Fold)
         ======================================== -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/index/banner.css') }}">

    <!-- ========================================
         NON-CRITICAL CSS - Deferred Loading
         ======================================== -->
    <link rel="preload" as="style" href="{{ asset('frontend/assets/css/index/explore-category.css') }}"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="{{ asset('frontend/assets/css/index/rekomendasi-card.css') }}"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="{{ asset('frontend/assets/css/index/rekomendasi-text.css') }}"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="{{ asset('frontend/assets/css/index/small-banner.css') }}"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="{{ asset('frontend/assets/css/index/our-partner.css') }}"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="{{ asset('frontend/assets/css/index/new-release.css') }}"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="{{ asset('frontend/assets/css/index/service.css') }}"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="{{ asset('frontend/assets/css/index/specialproduct.css') }}"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="{{ asset('frontend/assets/css/index/store.css') }}"
        onload="this.onload=null;this.rel='stylesheet'">

    <!-- Flickity CSS - Deferred -->
    <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.3.0/flickity.min.css"
        onload="this.onload=null;this.rel='stylesheet'">

    <!-- Fallback for browsers that don't support preload -->
    <noscript>
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/index/explore-category.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/index/rekomendasi-card.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/index/rekomendasi-text.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/index/small-banner.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/index/our-partner.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/index/new-release.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/index/service.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/index/specialproduct.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/index/store.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.3.0/flickity.min.css">
    </noscript>

    <!-- ========================================
         INLINE CRITICAL CSS
         ======================================== -->
    <style>
    /* Prevent FOUC - IMPROVED */
    html {
        background-color: #fff; /* Atau warna background utama website Anda */
    }
    
    body {
        opacity: 1; /* Langsung visible, tidak perlu delay */
        min-height: 100vh;
    }

    /* Force Hardware Acceleration */
    img {
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
    }

    /* Lazy Loading - ONLY for non-product images */
    img[loading="lazy"] {
        opacity: 0;
        transition: opacity 0.3s ease-in;
    }

    img[loading="lazy"].loaded {
        opacity: 1;
    }

    /* Placeholder for lazy load images */
    img[loading="lazy"]:not(.loaded) {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        min-height: 100px;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    /* Reduce repaints on mobile */
    * {
        -webkit-tap-highlight-color: transparent;
    }

    html {
        -webkit-text-size-adjust: 100%;
        scroll-behavior: smooth;
    }
</style>

    <!-- ========================================
         YOUR CONTENT BODY HERE
         PRODUCT IMAGES: TETAP GUNAKAN YANG ASLI (NO CHANGES!)
         ======================================== -->

    <div id="content">

        <!--   SECTION 1: BANNER SLIDER -->
        <section class="banner-322-section">
            <div class="banner-322-container">

                <!-- Slide 1 - Blue Left, Gray Right -->
                <div class="banner-322-slide">
                    <div class="banner-322-content banner-322-layout-1">

                        <!-- Desktop Image -->
                        <div class="banner-322-image-wrapper banner-322-desktop-only">
                            <img src="{{ asset('frontend/assets/img/banner/hero-bg1.png') }}" alt="Tokodus Box"
                                class="banner-322-image" loading="lazy" decoding="async">
                        </div>

                        <!-- Mobile Image -->
                        <div class="banner-322-image-wrapper banner-322-mobile-only">
                            <img src="{{ asset('frontend/assets/img/banner/hero-bg1.png') }}" alt="Tokodus Box"
                                class="banner-322-image" loading="lazy" decoding="async">
                        </div>

                        <div class="banner-322-text-wrapper">
                            <h1 class="banner-322-title">Solusi Ideal</h1>
                            <p class="banner-322-subtitle">untuk Mengemas Produk Anda dengan Elegan dan Aman!</p>
                            <a href="#stores" class="banner-322-cta">Beli Sekarang</a>
                        </div>

                    </div>
                </div>

                <!-- Slide 2 - Full Gray Background -->
                <div class="banner-322-slide">
                    <div class="banner-322-content banner-322-layout-2">
                        <div class="banner-322-image-wrapper">
                            <img src="{{ asset('frontend/assets/img/banner/hero-bg2.png') }}"
                                alt="Tokodus Boxes Collection" class="banner-322-image" loading="lazy"
                                decoding="async">
                        </div>
                        <div class="banner-322-text-wrapper">
                            <h1 class="banner-322-title banner-322-black">Belanja Lebih Hemat dengan Diskon 5%</h1>
                            <p class="banner-322-subtitle banner-322-black">untuk Semua Produk Kami!</p>
                            <div class="banner-322-features">
                                <span class="banner-322-feature">High Quality</span>
                                <span class="banner-322-divider">|</span>
                                <span class="banner-322-feature">Reasonable Price</span>
                                <span class="banner-322-divider">|</span>
                                <span class="banner-322-feature">Eco Friendly</span>
                            </div>
                            <a href="#stores" class="banner-322-cta">Beli Sekarang</a>
                            <p class="banner-322-terms">*Syarat & ketentuan berlaku</p>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 - Full Blue Background -->
                <div class="banner-322-slide">
                    <div class="banner-322-content banner-322-layout-3">
                        <div class="banner-322-text-wrapper">
                            <h1 class="banner-322-title banner-322-orange">Solusi Packaging Anda</h1>
                            <p class="banner-322-subtitle banner-322-white">High Quality Reasonable Price</p>
                            <a href="#stores" class="banner-322-cta banner-322-cta-desktop">Beli Sekarang</a>
                        </div>
                        <div class="banner-322-image-wrapper">
                            <img src="{{ asset('frontend/assets/img/banner/hero-bg.png') }}" alt="Tokodus Packaging"
                                class="banner-322-image" loading="lazy" decoding="async">
                        </div>
                        <a href="#stores" class="banner-322-cta banner-322-cta-mobile">Beli Sekarang</a>
                    </div>
                </div>

            </div>

            <!-- Navigation Arrows -->
            <button class="banner-322-nav banner-322-prev" aria-label="Previous slide">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
            <button class="banner-322-nav banner-322-next" aria-label="Next slide">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>

            <!-- Dots Indicator -->
            <div class="banner-322-dots">
                <button class="banner-322-dot banner-322-dot-active" data-slide="0"
                    aria-label="Go to slide 1"></button>
                <button class="banner-322-dot" data-slide="1" aria-label="Go to slide 2"></button>
                <button class="banner-322-dot" data-slide="2" aria-label="Go to slide 3"></button>
            </div>
        </section>
        <!--   END SECTION 1: BANNER SLIDER -->

        <!--   SECTION 2: EXPLORE CATEGORY -->
        <section class="category-slide-434">
            <div class="category-container-434">
                <h1 class="category-title-434">
                    Explore <br />
                    Categories
                </h1>
                <div class="image-container-434">
                    <img src="{{ asset('frontend/assets/img/category/Product Category.png') }}" alt="category-image"
                        class="category-image-434" loading="lazy" decoding="async" />
                </div>
            </div>
            <div class="category-slider-container-434">
                <div class="category-slider-434" id="categoryCardsContainer-434">
                    <!-- Category Cards - Data dari Database -->
                    @forelse($categories as $category)
                        <div class="category-card-434">
                            @if ($category->photo)
                                <img src="{{ asset('storage/' . $category->photo) }}"
                                    alt="icon {{ $category->name }}" class="{{ Str::slug($category->name) }}"
                                    loading="lazy" decoding="async" />
                            @else
                                <img src="{{ asset('frontend/assets/img/category/default.png') }}"
                                    alt="icon {{ $category->name }}" class="{{ Str::slug($category->name) }}"
                                    loading="lazy" decoding="async" />
                            @endif
                            <a href="{{ route('category', $category->slug) }}" class="card-title-434">
                                {{ $category->name }}
                            </a>
                        </div>
                    @empty
                        <div class="category-card-434">
                            <p class="text-muted">Belum ada kategori tersedia</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
        <!--   END SECTION 2: EXPLORE CATEGORY -->

        <!--   SECTION 3: OUR RECOMMENDED PRODUCT -->

        <!-- SECTION 440: OUR RECOMMENDED PRODUCT TITLE & COUNTDOWN -->
        <!-- SECTION 440: OUR RECOMMENDED PRODUCT TITLE & COUNTDOWN -->
        <section class="promo-product-440">
            <div class="promo-product-440-inner">
                <div class="promo-content-440">
                    <div class="promo-title-container-440">
                        <h1 class="promo-title-440">Our <span>Recommended</span> Product</h1>
                        <p class="promo-deskripsi-440">Don't wait. The time will never be just right.</p>
                    </div>
                </div>
                <div class="countdown-440">
                    <span class="days-440" id="days-440">0</span> Days
                    <span class="time-440" id="hours-440">00</span> :
                    <span class="time-440" id="minutes-440">00</span> :
                    <span class="time-440" id="seconds-440">00</span>
                </div>
            </div>
        </section>

        <!-- SECTION 441: PRODUCT CARDS RECOMMENDED -->
        <section class="products-section-441">
            <div class="products-grid-441">
                @forelse($recommendedProducts as $product)
                    <a href="{{ route('product.detail', $product->id) }}" class="product-card-441">
                        <!-- Product Image -->
                        <div class="product-image-441">
                            @if ($product->photo_urls && count($product->photo_urls) > 0)
                                <img alt="{{ $product->sku }}" class="default-image-441"
                                    src="{{ $product->photo_urls[0] }}" />
                                @if (count($product->photo_urls) > 1)
                                    <img alt="{{ $product->sku }}" class="hover-image-441"
                                        src="{{ $product->photo_urls[1] }}" />
                                @endif
                            @else
                                <img alt="{{ $product->sku }}" class="default-image-441"
                                    src="{{ $product->thumbnail }}" />
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

                        <div class="product-detail-441">
                            <!-- Product Title -->
                            <div class="product-title-441">
                                <p>{{ $product->tipe_display }}</p>
                            </div>

                            <!-- Ratings -->
                            <div class="ratings-441">
                                @php
                                    $fullStars = floor($product->rating);
                                    $halfStar = $product->rating - $fullStars >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp

                                @for ($i = 0; $i < $fullStars; $i++)
                                    <span class="star-441 filled-441">★</span>
                                @endfor

                                @if ($halfStar)
                                    <span class="star-441 half-441">★</span>
                                @endif

                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <span class="star-441 empty-441">☆</span>
                                @endfor
                            </div>

                            <!-- Product Description -->
                            <div class="product-deskripsi-441">
                                <p>{{ $product->title }}</p>
                            </div>

                            <!-- Product Price -->
                            <div class="product-price-441">
                                @if ($product->has_discount)
                                    <span class="original-price-441">{{ $product->formatted_original_price }}</span>
                                    <span class="final-price-441">{{ $product->formatted_price }}</span>
                                @else
                                    <span class="final-price-441">{{ $product->formatted_price }}</span>
                                @endif
                            </div>

                            <!-- Wishlist Icon -->
                            <span
                                class="wishlist-icon-441 {{ in_array($product->id, $wishlistIds ?? []) ? 'active' : '' }}"
                                data-product-id="{{ $product->id }}"
                                onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }}, this);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                            </span>

                            <!-- Share Icon -->
                            <span class="share-icon-441"
                                onclick="event.preventDefault(); event.stopPropagation(); alert('Share product');">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                </svg>
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Belum ada produk rekomendasi tersedia</p>
                    </div>
                @endforelse
            </div>
        </section>
        <!--   SECTION 4: SMALL BANNER -->
        <section class="small-benner">
            <div class="small-benner-container">
                <div class="small-benner1">
                    <div class="content-1">
                        <h1 class="sbennertitle">Pengiriman Aman, Ramah Lingkungan</h1>
                        <h2 class="sbennersubtitle">
                            Perlindungan Optimal untuk Setiap Kiriman
                        </h2>
                        <a href="#stores" class="scta-1">Beli Sekarang</a>
                    </div>
                    <img class="small-image-1" src="{{ asset('frontend/assets/img/banner/hero-bg.png') }}"
                        alt="Slide 1 Image" loading="lazy" decoding="async" />
                    <div class="background-benner1-1"></div>
                    <div class="background-benner1-2"></div>
                </div>
                <div class="small-benner2">
                    <div class="content-2">
                        <h1 class="sbennertitle">Solusi Pengemasan Optimal</h1>
                        <h2 class="sbennersubtitle">
                            Aman, kuat, dan efisien untuk kebutuhan kemasan
                        </h2>
                        <a href="#stores" class="scta-2">Beli Sekarang</a>
                    </div>
                    <img class="small-image-2" src="{{ asset('frontend/assets/img/banner/hero-bg3.png') }}"
                        alt="Slide 2 Image" loading="lazy" decoding="async" />
                    <div class="background-benner2-1"></div>
                    <div class="background-benner2-2"></div>
                </div>
            </div>
        </section>
        <!--   END SECTION 4: SMALL BANNER -->

        <!--   SECTION 5: OUR PARTNER LOGO -->
        <section class="our-partner-section-436">
            <div class="partner-logo-slider-container-436">
                <div class="partner-logo-slide-436">
                    {{-- ✅ ORIGINAL SET --}}
                    <img src="{{ asset('frontend/assets/img/our-partner/brodo-logo.png') }}" alt="Brodo"
                        loading="lazy" />
                    <img src="{{ asset('frontend/assets/img/our-partner/prodigo-logo.png') }}" alt="Prodigo"
                        loading="lazy" />
                    <img src="{{ asset('frontend/assets/img/our-partner/portegood-logo.png') }}" alt="Portegood"
                        loading="lazy" />
                    <img src="{{ asset('frontend/assets/img/our-partner/Luxxe-Studio-logo.png') }}"
                        alt="Luxxe Studio" loading="lazy" />
                    <img src="{{ asset('frontend/assets/img/our-partner/D\'lilac-logo.png') }}" alt="D'lilac"
                        loading="lazy" />
                    <img src="{{ asset('frontend/assets/img/our-partner/heykama-logo.png') }}" alt="Heykama"
                        loading="lazy" />
                    <img src="{{ asset('frontend/assets/img/our-partner/Babyloop-logo.png') }}" alt="Babyloop"
                        loading="lazy" />
                    <img src="{{ asset('frontend/assets/img/our-partner/Bienbali-logo.png') }}" alt="Bienbali"
                        loading="lazy" />
                    <img src="{{ asset('frontend/assets/img/our-partner/Enha-logo.png') }}" alt="Enha"
                        loading="lazy" />
                    <img src="{{ asset('frontend/assets/img/our-partner/ilookshoes-logo.png') }}" alt="Ilook Shoes"
                        loading="lazy" />
                    <img src="{{ asset('frontend/assets/img/our-partner/peony-logo.png') }}" alt="Peony"
                        loading="lazy" />

                    {{-- ✅ DUPLICATE SET (for seamless infinite loop) --}}
                    <img src="{{ asset('frontend/assets/img/our-partner/brodo-logo.png') }}" alt="Brodo"
                        loading="lazy" aria-hidden="true" />
                    <img src="{{ asset('frontend/assets/img/our-partner/prodigo-logo.png') }}" alt="Prodigo"
                        loading="lazy" aria-hidden="true" />
                    <img src="{{ asset('frontend/assets/img/our-partner/portegood-logo.png') }}" alt="Portegood"
                        loading="lazy" aria-hidden="true" />
                    <img src="{{ asset('frontend/assets/img/our-partner/Luxxe-Studio-logo.png') }}"
                        alt="Luxxe Studio" loading="lazy" aria-hidden="true" />
                    <img src="{{ asset('frontend/assets/img/our-partner/D\'lilac-logo.png') }}" alt="D'lilac"
                        loading="lazy" aria-hidden="true" />
                    <img src="{{ asset('frontend/assets/img/our-partner/heykama-logo.png') }}" alt="Heykama"
                        loading="lazy" aria-hidden="true" />
                    <img src="{{ asset('frontend/assets/img/our-partner/Babyloop-logo.png') }}" alt="Babyloop"
                        loading="lazy" aria-hidden="true" />
                    <img src="{{ asset('frontend/assets/img/our-partner/Bienbali-logo.png') }}" alt="Bienbali"
                        loading="lazy" aria-hidden="true" />
                    <img src="{{ asset('frontend/assets/img/our-partner/Enha-logo.png') }}" alt="Enha"
                        loading="lazy" aria-hidden="true" />
                    <img src="{{ asset('frontend/assets/img/our-partner/ilookshoes-logo.png') }}" alt="Ilook Shoes"
                        loading="lazy" aria-hidden="true" />
                    <img src="{{ asset('frontend/assets/img/our-partner/peony-logo.png') }}" alt="Peony"
                        loading="lazy" aria-hidden="true" />
                </div>
            </div>
        </section>
        <!--   END SECTION 5: OUR PARTNER LOGO -->

        <!-- Section berikutnya akan dimulai di sini dengan spacing yang dinamis -->

        <!--SECTION 6 -->
        <section class="new-release-container-437">
            <div class="header-new-release-437">
                <div class="content-header-new-release-437">
                    <img src="{{ asset('frontend/assets/img/logo-icon.png') }}" alt="Tokodus Logo"
                        class="image-header-new-realease-437" loading="lazy" decoding="async" />
                    <h1 class="header-new-release-title-437">Tokodus</h1>
                    <h2 class="header-new-release-subtitile-437">High Quality Reasonable price</h2>
                </div>
                <div class="promonewrelease-header-437">
                    <h1 class="promonewrelease-header-title-437">Special Edition</h1>
                    <p class="deskripsi-promonewrelease-header-437">Eksklusif untuk Anda</p>
                    <a href="#stores" class="pcta-437">Beli Sekarang</a>
                </div>
            </div>
        </section>

        <!--SECTION 7 -->
        <section class="spesialeditionproduct-438">
            <div class="spesial-edition-container-438">
                <div class="promo-title-container-438">
                    <h1 class="promo-title-438">Special <span>Edition</span> Product</h1>
                    <p class="promo-deskripsi-438">Discover our exclusive seasonal collections</p>
                </div>

                <!-- Navbar kategori -->
                <div class="spesialedition-navbar-438">
                    <ul class="spesialedition-navbar-menu-438">
                        <li data-category="newrelease" class="active">New Release</li>
                        <span> / </span>
                        <li data-category="lebaran">Lebaran</li>
                        <span> / </span>
                        <li data-category="christmas">Christmas</li>
                        <span> / </span>
                        <li data-category="imlek">Imlek</li>
                    </ul>
                </div>

                <!-- NEW RELEASE PRODUCTS -->
                <div id="newrelease-438" class="spesial-edition-438 active">
                    @php
                        $newReleaseProducts = $specialProducts->filter(function ($product) {
                            return $product->categories->contains('slug', 'new-release');
                        });
                    @endphp

                    @forelse($newReleaseProducts as $product)
                        <a href="{{ route('product.detail', $product->id) }}" class="product-card-438">
                            <!-- Product Image -->
                            <div class="product-image-438">
                                @if ($product->photo_urls && count($product->photo_urls) > 0)
                                    <img alt="{{ $product->sku }}" class="default-image-438"
                                        src="{{ $product->photo_urls[0] }}" />
                                    @if (count($product->photo_urls) > 1)
                                        <img alt="{{ $product->sku }}" class="hover-image-438"
                                            src="{{ $product->photo_urls[1] }}" />
                                    @endif
                                @else
                                    <img alt="{{ $product->sku }}" class="default-image-438"
                                        src="{{ $product->thumbnail }}" />
                                @endif

                                @if ($product->has_discount)
                                    <span class="badge-discount-438">-{{ $product->discount_percentage }}%</span>
                                @endif
                                @if (!$product->is_available)
                                    <span class="badge-stock-438">Stok Habis</span>
                                @endif
                            </div>

                            <div class="product-label-438">
                                <span>{{ $product->sku }}</span>
                            </div>

                            <div class="product-detail-438">
                                <div class="product-title-438">
                                    <p>{{ $product->tipe_display }}</p>
                                </div>
                                <div class="ratings-438">
                                    @php
                                        $fullStars = floor($product->rating);
                                        $halfStar = $product->rating - $fullStars >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                    @endphp
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <span class="star-438 filled-438">★</span>
                                    @endfor
                                    @if ($halfStar)
                                        <span class="star-438 half-438">★</span>
                                    @endif
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <span class="star-438 empty-438">☆</span>
                                    @endfor
                                </div>
                                <div class="product-deskripsi-438">
                                    <p>{{ Str::limit($product->title, 50) }}</p>
                                </div>
                                <div class="product-price-438">
                                    @if ($product->has_discount)
                                        <span
                                            class="original-price-438">{{ $product->formatted_original_price }}</span>
                                        <span class="final-price-438">{{ $product->formatted_price }}</span>
                                    @else
                                        <span class="final-price-438">{{ $product->formatted_price }}</span>
                                    @endif
                                </div>

                                <!-- Wishlist Icon - FIXED -->
                                <span
                                    class="wishlist-icon-438 {{ in_array($product->id, $wishlistIds ?? []) ? 'active' : '' }}"
                                    data-product-id="{{ $product->id }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }}, this);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </span>

                                <!-- Share Icon -->
                                <span class="share-icon-438"
                                    onclick="event.preventDefault(); event.stopPropagation(); alert('Share product');">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="no-products-438">
                            <p>Belum ada produk New Release</p>
                        </div>
                    @endforelse
                </div>

                <!-- LEBARAN PRODUCTS -->
                <div id="lebaran-438" class="spesial-edition-438">
                    @php
                        $lebaranProducts = $specialProducts->filter(function ($product) {
                            return $product->categories->contains('slug', 'lebaran');
                        });
                    @endphp

                    @forelse($lebaranProducts as $product)
                        <a href="{{ route('product.detail', $product->id) }}" class="product-card-438">
                            <div class="product-image-438">
                                @if ($product->photo_urls && count($product->photo_urls) > 0)
                                    <img alt="{{ $product->sku }}" class="default-image-438"
                                        src="{{ $product->photo_urls[0] }}" />
                                    @if (count($product->photo_urls) > 1)
                                        <img alt="{{ $product->sku }}" class="hover-image-438"
                                            src="{{ $product->photo_urls[1] }}" />
                                    @endif
                                @else
                                    <img alt="{{ $product->sku }}" class="default-image-438"
                                        src="{{ $product->thumbnail }}" />
                                @endif
                                @if ($product->has_discount)
                                    <span class="badge-discount-438">-{{ $product->discount_percentage }}%</span>
                                @endif
                                @if (!$product->is_available)
                                    <span class="badge-stock-438">Stok Habis</span>
                                @endif
                            </div>

                            <div class="product-label-438">
                                <span>{{ $product->sku }}</span>
                            </div>

                            <div class="product-detail-438">
                                <div class="product-title-438">
                                    <p>{{ $product->tipe_display }}</p>
                                </div>
                                <div class="ratings-438">
                                    @for ($i = 0; $i < floor($product->rating); $i++)
                                        <span class="star-438 filled-438">★</span>
                                    @endfor
                                    @if ($product->rating - floor($product->rating) >= 0.5)
                                        <span class="star-438 half-438">★</span>
                                    @endif
                                    @for ($i = 0; $i < 5 - floor($product->rating) - ($product->rating - floor($product->rating) >= 0.5 ? 1 : 0); $i++)
                                        <span class="star-438 empty-438">☆</span>
                                    @endfor
                                </div>
                                <div class="product-deskripsi-438">
                                    <p>{{ Str::limit($product->title, 50) }}</p>
                                </div>
                                <div class="product-price-438">
                                    @if ($product->has_discount)
                                        <span
                                            class="original-price-438">{{ $product->formatted_original_price }}</span>
                                    @endif
                                    <span class="final-price-438">{{ $product->formatted_price }}</span>
                                </div>

                                <!-- Wishlist Icon - FIXED -->
                                <span
                                    class="wishlist-icon-438 {{ in_array($product->id, $wishlistIds ?? []) ? 'active' : '' }}"
                                    data-product-id="{{ $product->id }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }}, this);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </span>

                                <span class="share-icon-438"
                                    onclick="event.preventDefault(); event.stopPropagation(); alert('Share product');">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="no-products-438">
                            <p>Belum ada produk Lebaran</p>
                        </div>
                    @endforelse
                </div>

                <!-- CHRISTMAS PRODUCTS -->
                <div id="christmas-438" class="spesial-edition-438">
                    @php
                        $christmasProducts = $specialProducts->filter(function ($product) {
                            return $product->categories->contains('slug', 'christmas');
                        });
                    @endphp

                    @forelse($christmasProducts as $product)
                        <a href="{{ route('product.detail', $product->id) }}" class="product-card-438">
                            <div class="product-image-438">
                                @if ($product->photo_urls && count($product->photo_urls) > 0)
                                    <img alt="{{ $product->sku }}" class="default-image-438"
                                        src="{{ $product->photo_urls[0] }}" />
                                    @if (count($product->photo_urls) > 1)
                                        <img alt="{{ $product->sku }}" class="hover-image-438"
                                            src="{{ $product->photo_urls[1] }}" />
                                    @endif
                                @else
                                    <img alt="{{ $product->sku }}" class="default-image-438"
                                        src="{{ $product->thumbnail }}" />
                                @endif
                                @if ($product->has_discount)
                                    <span class="badge-discount-438">-{{ $product->discount_percentage }}%</span>
                                @endif
                                @if (!$product->is_available)
                                    <span class="badge-stock-438">Stok Habis</span>
                                @endif
                            </div>

                            <div class="product-label-438">
                                <span>{{ $product->sku }}</span>
                            </div>

                            <div class="product-detail-438">
                                <div class="product-title-438">
                                    <p>{{ $product->tipe_display }}</p>
                                </div>
                                <div class="ratings-438">
                                    @for ($i = 0; $i < floor($product->rating); $i++)
                                        <span class="star-438 filled-438">★</span>
                                    @endfor
                                    @if ($product->rating - floor($product->rating) >= 0.5)
                                        <span class="star-438 half-438">★</span>
                                    @endif
                                    @for ($i = 0; $i < 5 - floor($product->rating) - ($product->rating - floor($product->rating) >= 0.5 ? 1 : 0); $i++)
                                        <span class="star-438 empty-438">☆</span>
                                    @endfor
                                </div>
                                <div class="product-deskripsi-438">
                                    <p>{{ Str::limit($product->title, 50) }}</p>
                                </div>
                                <div class="product-price-438">
                                    @if ($product->has_discount)
                                        <span
                                            class="original-price-438">{{ $product->formatted_original_price }}</span>
                                    @endif
                                    <span class="final-price-438">{{ $product->formatted_price }}</span>
                                </div>

                                <!-- Wishlist Icon - FIXED -->
                                <span
                                    class="wishlist-icon-438 {{ in_array($product->id, $wishlistIds ?? []) ? 'active' : '' }}"
                                    data-product-id="{{ $product->id }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }}, this);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </span>

                                <span class="share-icon-438"
                                    onclick="event.preventDefault(); event.stopPropagation(); alert('Share product');">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="no-products-438">
                            <p>Belum ada produk Christmas</p>
                        </div>
                    @endforelse
                </div>

                <!-- IMLEK PRODUCTS -->
                <div id="imlek-438" class="spesial-edition-438">
                    @php
                        $imlekProducts = $specialProducts->filter(function ($product) {
                            return $product->categories->contains('slug', 'imlek');
                        });
                    @endphp

                    @forelse($imlekProducts as $product)
                        <a href="{{ route('product.detail', $product->id) }}" class="product-card-438">
                            <div class="product-image-438">
                                @if ($product->photo_urls && count($product->photo_urls) > 0)
                                    <img alt="{{ $product->sku }}" class="default-image-438"
                                        src="{{ $product->photo_urls[0] }}" />
                                    @if (count($product->photo_urls) > 1)
                                        <img alt="{{ $product->sku }}" class="hover-image-438"
                                            src="{{ $product->photo_urls[1] }}" />
                                    @endif
                                @else
                                    <img alt="{{ $product->sku }}" class="default-image-438"
                                        src="{{ $product->thumbnail }}" />
                                @endif
                                @if ($product->has_discount)
                                    <span class="badge-discount-438">-{{ $product->discount_percentage }}%</span>
                                @endif
                                @if (!$product->is_available)
                                    <span class="badge-stock-438">Stok Habis</span>
                                @endif
                            </div>

                            <div class="product-label-438">
                                <span>{{ $product->sku }}</span>
                            </div>

                            <div class="product-detail-438">
                                <div class="product-title-438">
                                    <p>{{ $product->tipe_display }}</p>
                                </div>
                                <div class="ratings-438">
                                    @for ($i = 0; $i < floor($product->rating); $i++)
                                        <span class="star-438 filled-438">★</span>
                                    @endfor
                                    @if ($product->rating - floor($product->rating) >= 0.5)
                                        <span class="star-438 half-438">★</span>
                                    @endif
                                    @for ($i = 0; $i < 5 - floor($product->rating) - ($product->rating - floor($product->rating) >= 0.5 ? 1 : 0); $i++)
                                        <span class="star-438 empty-438">☆</span>
                                    @endfor
                                </div>
                                <div class="product-deskripsi-438">
                                    <p>{{ Str::limit($product->title, 50) }}</p>
                                </div>
                                <div class="product-price-438">
                                    @if ($product->has_discount)
                                        <span
                                            class="original-price-438">{{ $product->formatted_original_price }}</span>
                                    @endif
                                    <span class="final-price-438">{{ $product->formatted_price }}</span>
                                </div>

                                <!-- Wishlist Icon - FIXED -->
                                <span
                                    class="wishlist-icon-438 {{ in_array($product->id, $wishlistIds ?? []) ? 'active' : '' }}"
                                    data-product-id="{{ $product->id }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }}, this);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </span>

                                <span class="share-icon-438"
                                    onclick="event.preventDefault(); event.stopPropagation(); alert('Share product');">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="no-products-438">
                            <p>Belum ada produk Imlek</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>


        <!--SECTION 8 -->
        <section class="service">
            <div class="service-container">

                <!-- Service Item 1: Produsen -->
                <div class="produsen">
                    <img src="{{ asset('frontend/assets/img/service/Produsen.png') }}"
                        alt="100% Produk Asli Produsen" loading="lazy" decoding="async" />
                    <p>100% Produk Asli Produsen Langsung</p>
                </div>

                <!-- Service Item 2: Standby -->
                <div class="stanby">
                    <img src="{{ asset('frontend/assets/img/service/stanby.png') }}" alt="Admin Standby"
                        loading="lazy" decoding="async" />
                    <p>Admin dan Pelayanan Selalu Stanby</p>
                </div>

                <!-- Service Item 3: Customer Service -->
                <div class="cs">
                    <img src="{{ asset('frontend/assets/img/service/cs.png') }}" alt="CS Solutif" loading="lazy"
                        decoding="async" />
                    <p>CS yang solutif dan handal</p>
                </div>

                <!-- Service Item 4: Ongkir -->
                <div class="ongkir">
                    <img src="{{ asset('frontend/assets/img/service/ongkir.png') }}" alt="Gratis Ongkir"
                        loading="lazy" decoding="async" />
                    <p>Gratis Ongkir *</p>
                </div>

                <!-- Service Item 5: Garansi -->
                <div class="garansi">
                    <img src="{{ asset('frontend/assets/img/service/garansi.png') }}" alt="Garansi 100%"
                        loading="lazy" decoding="async" />
                    <p>Garansi 100% Barang terbaik</p>
                </div>

                <!-- Service Item 6: Return -->
                <div class="return">
                    <img src="{{ asset('frontend/assets/img/service/return.png') }}" alt="Minimal 14 Hari Garansi"
                        loading="lazy" decoding="async" />
                    <p>Minimal 14 Hari Garansi Barang 100% Kembali dan sesuai</p>
                </div>

            </div>
        </section>

        <!--SECTION 9 -->
        <section id="stores">
            <div class="stores">
                <div class="container">
                    <div class="map-dropdown-container">

                        <!-- Map Container -->
                        <div class="map-container">
                            <iframe id="storeMap" width="400" height="450" style="border: 0"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                                src="">
                            </iframe>
                        </div>

                        <!-- Dropdown and Store Info Container -->
                        <div class="dropdown-store-info">

                            <!-- Dropdown Container -->
                            <div class="dropdown-container">
                                <button class="dropdown-btn">Pilih Cabang Toko</button>
                                <div class="dropdown-content" id="dropdownContent">
                                    <ul>
                                        <li data-store="tki">Tokodus Taman Kopo Indah</li>
                                        <li data-store="cimahi">Tokodus Cimahi</li>
                                        <li data-store="cibaduyut">Tokodus Cibaduyut</li>
                                        <li data-store="pagarsih">Tokodus Pagarsih</li>
                                        <li data-store="buahbatu">Tokodus Buahbatu</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Store Info Display -->
                            <div class="store-info" id="storeInfo">
                                <h3>Nama Cabang Toko</h3>
                                <p>Tidak ada cabang toko yang dipilih</p>
                            </div>

                            <!-- Marketplace Links -->
                            <div class="marketplace-links">
                                <a id="tokopediaLink" href="#" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('frontend/assets/img/marketplace/Tokopedia icon.png') }}"
                                        alt="Tokopedia" />
                                </a>
                                <a id="shopeeLink" href="#" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('frontend/assets/img/marketplace/shopee icon.png') }}"
                                        alt="Shopee" />
                                </a>
                                <a id="lazadaLink" href="#" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('frontend/assets/img/marketplace/Lazada icon.png') }}"
                                        alt="Lazada" />
                                </a>
                                <a class="whatsapp" id="whatsappLink" href="#" target="_blank"
                                    rel="noopener noreferrer">
                                    <img src="{{ asset('frontend/assets/img/marketplace/Whatsapp icon.png') }}"
                                        alt="WhatsApp" />
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION DINONAKTIFKAN
<section class="next-section-placeholder"
    style="padding: clamp(40px, 8vw, 100px) clamp(20px, 4vw, 60px); background: #f5f5f5; min-height: 50vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div
            style="background: white; border-radius: 12px; padding: clamp(30px, 5vw, 60px); box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center;">
            <h2
                style="font-size: clamp(1.5rem, 4vw, 3rem); color: #1f4390; margin-bottom: clamp(15px, 3vw, 30px); font-weight: 700;">
                Section 2 - Test Container
            </h2>
            <p
                style="font-size: clamp(1rem, 2vw, 1.25rem); color: #666; line-height: 1.6; margin-bottom: clamp(20px, 4vw, 40px);">
                Ini adalah box container untuk testing jarak antar section.<br>
                Scroll ke atas dan ke bawah untuk melihat spacing yang dinamis.
            </p>

            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: clamp(15px, 3vw, 30px); margin-top: clamp(20px, 4vw, 40px);">
                <div
                    style="background: linear-gradient(135deg, #1f4390 0%, #2d5bb8 100%); padding: clamp(20px, 4vw, 30px); border-radius: 8px; color: white;">
                    <h3>Box 1</h3>
                    <p>Test Content</p>
                </div>
                <div
                    style="background: linear-gradient(135deg, #f7b963 0%, #f9c878 100%); padding: clamp(20px, 4vw, 30px); border-radius: 8px; color: #1f4390;">
                    <h3>Box 2</h3>
                    <p>Test Content</p>
                </div>
                <div
                    style="background: linear-gradient(135deg, #1f4390 0%, #2d5bb8 100%); padding: clamp(20px, 4vw, 30px); border-radius: 8px; color: white;">
                    <h3>Box 3</h3>
                    <p>Test Content</p>
                </div>
            </div>

            <div
                style="margin-top: clamp(30px, 6vw, 60px); padding: clamp(20px, 4vw, 30px); background: #f7b963; border-radius: 8px;">
                <h4>📏 Spacing Info</h4>
                <p>
                    <strong>Vertical:</strong> 40px - 100px<br>
                    <strong>Horizontal:</strong> 20px - 60px<br>
                    <strong>Padding Box:</strong> 30px - 60px
                </p>
            </div>
        </div>
    </div>
</section>
END SECTION -->


    </div>


    <!-- ========================================
         JAVASCRIPT - Simple Lazy Load
         ONLY for images with loading="lazy"
         SKIP product images completely!
         ======================================== -->

    <script>
        (function() {
            'use strict';

            // Device detection
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
            const isAndroid = /Android/i.test(navigator.userAgent);

            console.log('📱 Device:', isIOS ? 'iOS' : isAndroid ? 'Android' : 'Desktop');

            // Remove body opacity

            // ===================================================================
            // LAZY LOAD - ONLY for images with loading="lazy" attribute
            // PRODUCT IMAGES (.product-image-438, .product-image-441) = EXCLUDED!
            // ===================================================================
            function initLazyLoad() {
                // Select ONLY images with loading="lazy" 
                // EXCLUDE product card images
                const images = document.querySelectorAll('img[loading="lazy"]:not(.loaded)');

                console.log('🖼️ Lazy load images found:', images.length);

                // Simple approach: just mark as loaded when complete
                images.forEach(function(img) {
                    // Skip if it's a product image
                    const isProductImage = img.closest('.product-image-438') ||
                        img.closest('.product-image-441') ||
                        img.classList.contains('default-image-438') ||
                        img.classList.contains('default-image-441') ||
                        img.classList.contains('hover-image-438') ||
                        img.classList.contains('hover-image-441');

                    if (isProductImage) {
                        console.log('⏭️ Skipping product image:', img.alt);
                        return; // Skip product images
                    }

                    // Handle lazy load for non-product images
                    if (img.complete) {
                        img.classList.add('loaded');
                    } else {
                        img.addEventListener('load', function() {
                            img.classList.add('loaded');
                        });
                        img.addEventListener('error', function() {
                            img.classList.add(
                                'loaded'); // Still mark as loaded to prevent infinite loading
                        });
                    }
                });
            }

            // Run after DOM loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(initLazyLoad, 100);
                });
            } else {
                setTimeout(initLazyLoad, 100);
            }

            // Re-run on dynamic content (if needed)
            window.reinitLazyLoad = initLazyLoad;

            console.log('✅ Lazy load initialized (Product images excluded)');

        })();
    </script>

    <!-- 2. EXTERNAL LIBRARIES - Async Loading -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.3.0/flickity.pkgd.min.js" defer></script>

    <!-- 3. YOUR SCRIPTS - Defer Loading -->
    <script src="{{ asset('frontend/assets/js/index/banner.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/js/index/explore-category.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/js/index/rekomendasi-text.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/js/index/rekomendasi-card.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/js/index/new-release.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/js/index/special-product-pagination.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/js/index/store.js') }}" defer></script>

    <!-- 4. PERFORMANCE MONITORING (Optional) -->
    <script>
        window.addEventListener('load', function() {
            if (window.performance && window.performance.timing) {
                const perfData = window.performance.timing;
                const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;

                console.log('⚡ Page Load Time:', (pageLoadTime / 1000).toFixed(2), 's');
            }
        });
    </script>

    <!-- 5. RunningTExt Product Card-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Detect mobile device
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator
                .userAgent) || window.innerWidth <= 768;

            // ===================================
            // RUNNING TEXT untuk RECOMMENDED PRODUCTS (441)
            // ===================================
            const descriptions441 = document.querySelectorAll('.product-deskripsi-441 p');

            descriptions441.forEach(desc => {
                const textWidth = desc.scrollWidth;
                const containerWidth = desc.parentElement.offsetWidth;

                if (textWidth > containerWidth) {
                    desc.parentElement.style.textAlign = 'left';
                    const distance = textWidth - containerWidth + 20;
                    const animationDuration = isMobile ? '6s' : '5s';
                    desc.style.animation =
                        `scroll-alternate-441 ${animationDuration} ease-in-out 1s infinite alternate`;
                    desc.style.setProperty('--scroll-distance-441', `-${distance}px`);
                } else {
                    desc.parentElement.style.textAlign = 'center';
                }
            });

            // ===================================
            // RUNNING TEXT untuk SPECIAL EDITION PRODUCTS (438)
            // ===================================
            const descriptions438 = document.querySelectorAll('.product-deskripsi-438 p');

            descriptions438.forEach(desc => {
                const textWidth = desc.scrollWidth;
                const containerWidth = desc.parentElement.offsetWidth;

                if (textWidth > containerWidth) {
                    desc.parentElement.style.textAlign = 'left';
                    const distance = textWidth - containerWidth + 20;
                    const animationDuration = isMobile ? '6s' : '5s';
                    desc.style.animation =
                        `scroll-alternate-438 ${animationDuration} ease-in-out 1s infinite alternate`;
                    desc.style.setProperty('--scroll-distance-438', `-${distance}px`);
                } else {
                    desc.parentElement.style.textAlign = 'center';
                }
            });

            // ===================================
            // RE-CALCULATE saat RESIZE/ROTATION
            // ===================================
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    const isMobileNow = window.innerWidth <= 768;

                    // Re-calculate 441
                    descriptions441.forEach(desc => {
                        const textWidth = desc.scrollWidth;
                        const containerWidth = desc.parentElement.offsetWidth;

                        if (textWidth > containerWidth) {
                            desc.parentElement.style.textAlign = 'left';
                            const distance = textWidth - containerWidth + 20;
                            const animationDuration = isMobileNow ? '6s' : '5s';
                            desc.style.animation =
                                `scroll-alternate-441 ${animationDuration} ease-in-out 1s infinite alternate`;
                            desc.style.setProperty('--scroll-distance-441',
                                `-${distance}px`);
                        } else {
                            desc.parentElement.style.textAlign = 'center';
                            desc.style.animation = 'none';
                        }
                    });

                    // Re-calculate 438
                    descriptions438.forEach(desc => {
                        const textWidth = desc.scrollWidth;
                        const containerWidth = desc.parentElement.offsetWidth;

                        if (textWidth > containerWidth) {
                            desc.parentElement.style.textAlign = 'left';
                            const distance = textWidth - containerWidth + 20;
                            const animationDuration = isMobileNow ? '6s' : '5s';
                            desc.style.animation =
                                `scroll-alternate-438 ${animationDuration} ease-in-out 1s infinite alternate`;
                            desc.style.setProperty('--scroll-distance-438',
                                `-${distance}px`);
                        } else {
                            desc.parentElement.style.textAlign = 'center';
                            desc.style.animation = 'none';
                        }
                    });
                }, 250);
            });
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

</x-frontend-layout>
