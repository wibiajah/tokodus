<x-frontend-layout title="Tokodus | Solusi Packaging Anda">
    
    <style>
        /* Product Card Styles */
        .product-card-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .product-card-1 {
            position: relative;
            width: 260px;
            height: 310px;
            background: #fff;
            border-radius: 20px;
            padding: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.4s ease;
            display: flex;
            flex-direction: column;
        }

        .product-card-1:hover {
            height: 360px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .label-1 {
            position: absolute;
            left: -60px;
            top: 0;
            width: 50px;
            height: 100%;
            background: #1f4390;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: left 0.4s ease;
            z-index: 5;
        }

        .product-card-1:hover .label-1 {
            left: 0;
        }

        .product-image-1 {
            position: relative;
            width: 100%;
            aspect-ratio: 3 / 4;
            margin-bottom: 10px;
            transition: all 0.4s ease;
            z-index: 1;
            overflow: hidden;
            border-radius: 12px;
        }

        .product-card-1:hover .product-image-1 {
            margin-left: 45px; 
            width: calc(100% - 45px);
        }

        .product-image-1 img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.4s ease;
        }

        .hover-img-1 { opacity: 0; }
        .product-card-1:hover .default-img-1 { opacity: 0; }
        .product-card-1:hover .hover-img-1 { opacity: 1; }

        .product-detail-1 {
            flex-grow: 1;
            transition: all 0.4s ease;
            text-align: left;
        }

        .product-card-1:hover .product-detail-1 {
            padding-left: 45px;
        }

        .product-header-1 {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-title-1 { font-size: 0.85rem; color: #888; }
        .ratings-1 { color: #ffcc00; font-size: 12px; }

        .product-deskripsi-1 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1f4390;
            margin: 8px 0;
            text-align: center;
        }

        .icons-1 {
            display: flex;
            justify-content: space-between;
            opacity: 0;
            transition: all 0.4s ease;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .product-card-1:hover .icons-1 {
            opacity: 1;
        }

        .icon-1 { font-size: 16px; color: #888; cursor: pointer; }
        .icon-1:hover { color: #1f4390; }
        /* Pengaturan untuk Tablet & HP */
@media screen and (max-width: 768px) {
    /* Perbaikan Product Card agar tidak kaku */
    .product-card-1 {
        width: 100% !important; /* Mengikuti lebar grid parent */
        max-width: 180px; /* Batasi maksimal agar pas 2 kolom */
        height: auto;
        min-height: 280px;
        margin: 0 auto;
    }

    .product-card-1:hover {
        height: auto; /* Hindari lonjakan tinggi di mobile */
    }

    .product-card-container {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important; /* Paksa 2 kolom di HP */
        gap: 12px !important;
        padding: 10px !important;
    }

    /* Hilangkan efek label geser di mobile karena menggangu user experience (UX) tap */
    .label-1 {
        left: 0 !important;
        width: 30px !important;
        font-size: 12px !important;
    }

    .product-image-1 {
        margin-left: 30px !important;
        width: calc(100% - 30px) !important;
    }

    .product-card-1:hover .product-image-1 {
        margin-left: 30px !important;
        width: calc(100% - 30px) !important;
    }

    .product-detail-1 {
        padding-left: 30px !important;
    }

    .product-deskripsi-1 {
        font-size: 0.95rem !important;
        font-weight: 600 !important;
        line-height: 1.2;
    }

    /* Paksa Icon muncul terus di HP (karena tidak ada hover) */
    .icons-1 {
        opacity: 1 !important;
        margin-top: 5px;
    }

    /* Small Banner Fix */
    .small-benner-container {
        display: flex;
        flex-direction: column; /* Tumpuk banner kecil ke bawah di HP */
        gap: 15px;
    }

    /* Store Map Fix */
    .map-dropdown-container {
        flex-direction: column;
    }
    
    #storeMap {
        width: 100% !important;
        height: 300px !important;
    }

    /* Service Section Fix */
    .service-container {
        grid-template-columns: repeat(2, 1fr) !important; /* 2 kolom untuk fitur */
        gap: 15px;
    }
}

/* Pengaturan khusus HP Kecil (Small Devices) */
@media screen and (max-width: 480px) {
    .product-card-1 {
        max-width: 100%; /* Gunakan sisa ruang */
    }
    
    .promo-title {
        font-size: 1.5rem !important;
    }
}
    </style>
    
    <div id="content">
        <!-- Banner Slider -->
        <div id="benner">
            <section class="benner-slider">
                <div class="slider-container">
                    <div class="benner-slides" id="benner-slides">
                        <!-- Banner 1 -->
                        <div class="benner1" style="position: relative;">
                            <div class="content1" style="position: relative; z-index: 3;">
                                <h1>Solusi Packaging Anda</h1>
                                <h2>High Quality Reasonable Price</h2>
                                <a href="#stores" class="cta-1" style="position: relative; z-index: 4;">Beli Sekarang</a>
                            </div>
                            <img class="image-1" src="{{ asset('frontend/assets/img/banner/hero-bg.png') }}" alt="Slide 1 Image" style="position: relative; z-index: 1; pointer-events: none;" />
                            <div class="background-benner1" style="position: absolute; z-index: 0;"></div>
                        </div>

                        <!-- Banner 2 -->
                        <div class="benner2" style="position: relative;">
                            <div class="content2" style="position: relative; z-index: 3;">
                                <h1>Solusi Ideal</h1>
                                <h2>untuk Mengemas Produk Anda dengan Elegan dan Aman!</h2>
                                <a href="#stores" class="cta-2" style="position: relative; z-index: 4;">Beli Sekarang</a>
                            </div>
                            <img class="image-2" src="{{ asset('frontend/assets/img/banner/hero-bg1.png') }}" alt="Slide 2 Image" style="position: relative; z-index: 1; pointer-events: none;" />
                            <div class="background-benner2" style="position: absolute; z-index: 0;"></div>
                        </div>

                        <!-- Banner 3 -->
                        <div class="benner3" style="position: relative;">
                            <div class="content3" style="position: relative; z-index: 3;">
                                <h1>Belanja Lebih Hemat dengan Diskon 5%</h1>
                                <h2>untuk Semua Produk Kami!</h2>
                                <div class="labels">
                                    <div class="label">High Quality</div>
                                    <div class="label">Reasonable Price</div>
                                    <div class="label">Eco Friendly</div>
                                </div>
                                <a href="#stores" class="cta-3" style="position: relative; z-index: 4;">Beli Sekarang</a>
                                <div class="terms">*Syarat & ketentuan berlaku</div>
                            </div>
                            <img class="image-3" src="{{ asset('frontend/assets/img/banner/hero-bg2.png') }}" alt="Slide 3 Image" style="position: relative; z-index: 1; pointer-events: none;" />
                            <div class="background-benner3" style="position: absolute; z-index: 0;"></div>
                        </div>
                    </div>
                    <button class="prev">&#10094;</button>
                    <button class="next">&#10095;</button>
                    <div class="dots">
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                </div>
            </section>
        </div>

        <!-- Explore Category -->
        <div id="explore">
            <section class="category-slide">
                <div class="category-container">
                    <h1 class="category-title">Explore <br /> Categories</h1>
                    <div class="image-container">
                        <img src="{{ asset('frontend/assets/img/category/Product Category.png') }}" 
                             alt="category-image" 
                             class="category-image" />
                    </div>
                </div>
                
                <div class="category-slider-container">
                    <div class="category-slider" id="categoryCardsContainer">
                        @forelse($categories as $category)
                            <div class="category-card">
                                @if($category->photo)
                                    <img src="{{ asset('storage/' . $category->photo) }}" 
                                         alt="icon {{ $category->name }}" 
                                         class="{{ Str::slug($category->name) }}" 
                                         onerror="this.src='{{ asset('frontend/assets/img/category/default.png') }}'" />
                                @else
                                    <img src="{{ asset('frontend/assets/img/category/Lainnya.png') }}" 
                                         alt="icon {{ $category->name }}" 
                                         class="default-icon" />
                                @endif
                                
                                <a href="{{ route('category', $category->slug) }}" 
                                   class="card-title">
                                    {{ $category->name }}
                                </a>
                            </div>
                        @empty
                            <p class="no-categories" style="text-align: center; padding: 40px; color: #999;">
                                Belum ada kategori tersedia
                            </p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>

       <!-- Our Recomended Product -->
<section class="promo-product">
    <div class="promo-content">
        <div class="promo-title-container">
            <h1 class="promo-title">Our <span>Recomended</span> Product</h1>
            <p class="promo-deskripsi">Don't wait. The time will never be just right.</p>
        </div>
    </div>
    <div class="countdown">
        <span class="days" id="days">0</span> Days
        <span class="time" id="hours">00</span> :
        <span class="time" id="minutes">00</span> :
        <span class="time" id="seconds">00</span>
    </div>
    <br>
    <br>
    <br>
    <br>
    <!-- Dynamic Product Cards -->
    <div class="product-card-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; max-width: 1200px; margin: 0 auto;">
        @forelse($recommendedProducts as $product)
            <div class="product-card-1">
                <div class="label-1">{{ $product->sku }}</div>
                
                <div class="product-image-1">
                    @if($product->photos && count($product->photos) > 0)
                        <img src="{{ asset('storage/' . $product->photos[0]) }}" 
                             class="default-img-1" 
                             alt="{{ $product->title }}"
                             onerror="this.src='{{ asset('frontend/assets/img/placeholder.png') }}'">
                        @if(count($product->photos) > 1)
                            <img src="{{ asset('storage/' . $product->photos[1]) }}" 
                                 class="hover-img-1" 
                                 alt="{{ $product->title }}"
                                 onerror="this.style.display='none'">
                        @endif
                    @else
                        <img src="{{ asset('frontend/assets/img/placeholder.png') }}" 
                             class="default-img-1" 
                             alt="{{ $product->title }}">
                    @endif
                </div>

                <div class="product-detail-1">
                    <div class="product-header-1">
                        <div class="product-title-1">
                            {{ $product->categories->first()->name ?? 'Produk' }}
                        </div>
                        <div class="ratings-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($product->rating))
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>
                    </div>
                    
                    <div class="product-deskripsi-1">{{ $product->title }}</div>
                    
                    <div class="icons-1">
                        <div style="display: flex; gap: 12px;">
                            <span class="icon-1">❤</span>
                            <span class="icon-1">📤</span>
                        </div>
                        <span class="icon-1">🛒</span>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #999;">
                <p>Belum ada produk yang direkomendasikan</p>
            </div>
        @endforelse
    </div>
</section>

        <!-- Small Banner -->
       <!-- Small Banner -->
<section class="small-benner">
    <div class="small-benner-container">
        <div class="small-benner1">
            <div class="content-1">
                <h1 class="sbennertitle">Pengiriman Aman, Ramah Lingkungan</h1>
                <h2 class="sbennersubtitle">Perlindungan Optimal untuk Setiap Kiriman</h2>
                <a href="#stores" class="scta-1">Beli Sekarang</a>
            </div>
            <img class="small-image-1" src="{{ asset('frontend/assets/img/banner/hero-bg.png') }}" alt="Slide 1 Image" />
            <div class="background-benner1-1"></div>
            <div class="background-benner1-2"></div>
        </div>
        <div class="small-benner2">
            <div class="content-2">
                <h1 class="sbennertitle">Solusi Pengemasan Optimal</h1>
                <h2 class="sbennersubtitle">Aman, kuat, dan efisien untuk kebutuhan kemasan</h2>
                <a href="#stores" class="scta-2">Beli Sekarang</a>
            </div>
            <img class="small-image-2" src="{{ asset('frontend/assets/img/banner/hero-bg3.png') }}" alt="Slide 2 Image" />
            <div class="background-benner2-1"></div>
            <div class="background-benner2-2"></div>
        </div>
    </div>
</section>

      <!-- Our Partner -->
<div id="projects">
    <section class="our-partner-logo">
        <div class="logo-slider-container">
            <div class="logo-slide">
                <img src="{{ asset('frontend/assets/img/our-partner/brodo-logo.png') }}" alt="Brodo" />
                <img src="{{ asset('frontend/assets/img/our-partner/prodigo-logo.png') }}" alt="Prodigo" />
                <img src="{{ asset('frontend/assets/img/our-partner/portegood-logo.png') }}" alt="Portegood" />
                <img src="{{ asset('frontend/assets/img/our-partner/Luxxe-Studio-logo.png') }}" alt="Luxxe Studio" />
                <img src="{{ asset('frontend/assets/img/our-partner/D\'lilac-logo.png') }}" alt="D'lilac" />
                <img src="{{ asset('frontend/assets/img/our-partner/heykama-logo.png') }}" alt="Heykama" />
                <img src="{{ asset('frontend/assets/img/our-partner/Babyloop-logo.png') }}" alt="Babyloop" />
                <img src="{{ asset('frontend/assets/img/our-partner/Bienbali-logo.png') }}" alt="Bienbali" />
                <img src="{{ asset('frontend/assets/img/our-partner/Enha-logo.png') }}" alt="Enha" />
                <img src="{{ asset('frontend/assets/img/our-partner/ilookshoes-logo.png') }}" alt="Ilook Shoes" />
                <img src="{{ asset('frontend/assets/img/our-partner/peony-logo.png') }}" alt="Peony" />
            </div>
        </div>
    </section>
</div>

        <!-- Product New Release / Special Edition -->
 <!-- Product New Release / Special Edition -->
<div id="New Release">
    <section class="new-release-container">
        <div class="header-new-release">
            <div class="content-header-new-release">
                <img src="{{ asset('frontend/assets/img/logo-icon.png') }}" alt="image" class="image-header-new-realease" />
                <h1 class="header-new-release-title">Tokodus</h1>
                <h2 class="header-new-release-subtitile">High Quality Reasonable price</h2>
            </div>
            <div class="promonewrelease-header">
                <h1 class="promonewrelease-header-title">Special Edition</h1>
                <p class="deskripsi-promonewrelease-header">Eksklusif untuk Anda</p>
                <a href="#stores" class="pcta">Beli Sekarang</a>
            </div>
        </div>
    </section>
    
    <section class="spesialeditionproduct">
        <div class="spesial-edition-container">
            <div class="newrealese-titile">
                <h2>Special Edition Products</h2>
            </div>

            <!-- Dynamic Product Cards -->
            <div class="product-card-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; max-width: 1200px; margin: 0 auto;">
                @forelse($specialProducts as $product)
                    <div class="product-card-1">
                        <div class="label-1">{{ $product->sku }}</div>
                        
                        <div class="product-image-1">
                            @if($product->photos && count($product->photos) > 0)
                                <img src="{{ asset('storage/' . $product->photos[0]) }}" 
                                     class="default-img-1" 
                                     alt="{{ $product->title }}"
                                     onerror="this.src='{{ asset('frontend/assets/img/placeholder.png') }}'">
                                @if(count($product->photos) > 1)
                                    <img src="{{ asset('storage/' . $product->photos[1]) }}" 
                                         class="hover-img-1" 
                                         alt="{{ $product->title }}"
                                         onerror="this.style.display='none'">
                                @endif
                            @else
                                <img src="{{ asset('frontend/assets/img/placeholder.png') }}" 
                                     class="default-img-1" 
                                     alt="{{ $product->title }}">
                            @endif
                        </div>

                        <div class="product-detail-1">
                            <div class="product-header-1">
                                <div class="product-title-1">
                                    {{ $product->categories->first()->name ?? 'Produk' }}
                                </div>
                                <div class="ratings-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->rating))
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            
                            <div class="product-deskripsi-1">{{ $product->title }}</div>
                            
                            <div class="icons-1">
                                <div style="display: flex; gap: 12px;">
                                    <span class="icon-1">❤</span>
                                    <span class="icon-1">📤</span>
                                </div>
                                <span class="icon-1">🛒</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #999;">
                        <p>Belum ada produk special edition</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    
    <!-- Service Section -->
    <section class="service" id="service">
        <div class="service-container">
            <div class="produsen">
                <img src="{{ asset('frontend/assets/img/service/Produsen.png') }}" alt="produsen" />
                <p>100% Produk Asli Produsen Langsung</p>
            </div>
            <div class="stanby">
                <img src="{{ asset('frontend/assets/img/service/stanby.png') }}" alt="stanby" />
                <p>Admin dan Pelayanan Selalu Stanby</p>
            </div>
            <div class="cs">
                <img src="{{ asset('frontend/assets/img/service/cs.png') }}" alt="cs" />
                <p>CS yang solutif dan handal</p>
            </div>
            <div class="ongkir">
                <img src="{{ asset('frontend/assets/img/service/ongkir.png') }}" alt="ongkir" />
                <p>Gratis Ongkir *</p>
            </div>
            <div class="garansi">
                <img src="{{ asset('frontend/assets/img/service/garansi.png') }}" alt="garansi" />
                <p>Garansi 100% Barang terbaik</p>
            </div>
            <div class="return">
                <img src="{{ asset('frontend/assets/img/service/return.png') }}" alt="return" />
                <p>Minimal 14 Hari Garansi Barang 100% Kembali dan sesuai</p>
            </div>
        </div>
    </section>
</div>
        
        <!-- Store Section -->
        <section id="stores">
            <div class="stores">
                <div class="container">
                    <div class="map-dropdown-container">
                        <div class="map-container">
                            <iframe id="storeMap" width="400" height="450" style="border: 0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>

                        <div class="dropdown-store-info">
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

                            <div class="store-info" id="storeInfo">
                                <h3>Bandung, Jawa Barat</h3>
                                <p><strong>Silakan pilih cabang toko untuk melihat informasi lebih lanjut.</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

  <script src="{{ asset('frontend/assets/js/tokodus-scripts.js') }}"></script>

</x-frontend-layout>