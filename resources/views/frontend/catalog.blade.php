<x-frontend-layout>
    <x-slot:title>{{ $page_title }}</x-slot:title>
    <!-- ✅ LOAD CSS SPECIAL PRODUCT -->
    <!-- Preconnect untuk performance -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="preload" as="style" href="{{ asset('frontend/assets/css/catalogproduct.css') }}"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/catalogproduct.css') }}">
    </noscript>

    <!-- TAMBAHKAN INI -->

    <!-- Page Header -->
    <div class="page-header-323">
        <h1>Katalog Produk</h1>
        <p>Temukan produk packaging terbaik untuk kebutuhan Anda</p>
    </div>

    <section class="catalog-section-323" id="catalog">
        <div class="catalog-container-323">
            <!-- MOBILE SEARCH & FILTER (GABUNG) - Tampil hanya di mobile -->
            <div class="mobile-search-filter-wrapper-323">
                <div class="mobile-search-filter-323">
                    <!-- Search Input dengan Icon -->
                    <div class="mobile-search-box-323">
                        <svg class="mobile-search-icon-323" xmlns="http://www.w3.org/2000/svg" width="18"
                            height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" id="mobileSearchInput" placeholder="Search..."
                            value="{{ $activeFilters['search'] ?? '' }}" />
                    </div>

                    <!-- Filter Button (Buka Sidebar) -->
                    <button class="mobile-filter-btn-323" id="mobileFilterBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        <span>Filter</span>
                    </button>
                </div>
            </div>
            <!-- Filter Search Container -->
            <div class="filter-search-container-323">
                <div class="search-box-323">
                    <svg class="search-icon-svg-323" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari produk..."
                        value="{{ $activeFilters['search'] ?? '' }}" />
                </div>
                <button class="filter-toggle-btn-323" id="filterToggleBtn">
                    <i data-feather="filter"></i><span>Filter</span>
                </button>
                <select id="sortSelect" class="sort-select-323">
                    <option value="newest" {{ ($activeFilters['sort'] ?? '') == 'newest' ? 'selected' : '' }}>Terbaru
                    </option>
                    <option value="oldest" {{ ($activeFilters['sort'] ?? '') == 'oldest' ? 'selected' : '' }}>Terlama
                    </option>
                    <option value="name-asc" {{ ($activeFilters['sort'] ?? '') == 'name-asc' ? 'selected' : '' }}>Nama
                        A-Z</option>
                    <option value="name-desc" {{ ($activeFilters['sort'] ?? '') == 'name-desc' ? 'selected' : '' }}>Nama
                        Z-A</option>
                    <option value="price-low" {{ ($activeFilters['sort'] ?? '') == 'price-low' ? 'selected' : '' }}>
                        Harga Terendah</option>
                    <option value="price-high" {{ ($activeFilters['sort'] ?? '') == 'price-high' ? 'selected' : '' }}>
                        Harga Tertinggi</option>
                    <option value="rating" {{ ($activeFilters['sort'] ?? '') == 'rating' ? 'selected' : '' }}>Rating
                        Tertinggi</option>
                </select>
            </div>

            <div class="catalog-content-323">
                <!-- NEW SIDEBAR DESIGN -->
                <aside class="filter-sidebar-323" id="filterSidebar">
                    <div class="filter-header-323">
                        <h3>Filter Produk</h3>
                        <button class="close-filter-323" id="closeFilterBtn">
                            <i data-feather="x"></i>
                        </button>
                    </div>

                    <!-- Availability Filter -->
                    <div class="filter-group-323">
                        <h4>Ketersediaan</h4>
                        <select class="filter-select-323" name="availability">
                            <option value="all"
                                {{ empty($activeFilters['availability']) || $activeFilters['availability'] == 'all' ? 'selected' : '' }}>
                                Semua Produk</option>
                            <option value="available"
                                {{ ($activeFilters['availability'] ?? '') == 'available' ? 'selected' : '' }}>Tersedia
                            </option>
                            <option value="out-of-stock"
                                {{ ($activeFilters['availability'] ?? '') == 'out-of-stock' ? 'selected' : '' }}>Stok
                                Habis</option>
                        </select>
                    </div>

                    <!-- Tipe Produk -->
                    <div class="filter-group-323">
                        <h4>Tipe Produk</h4>
                        <select class="filter-select-323" name="tipe">
                            <option value="" {{ empty($activeFilters['tipe']) ? 'selected' : '' }}>Semua Tipe
                            </option>
                            <option value="innerbox"
                                {{ ($activeFilters['tipe'] ?? '') == 'innerbox' ? 'selected' : '' }}>Innerbox</option>
                            <option value="masterbox"
                                {{ ($activeFilters['tipe'] ?? '') == 'masterbox' ? 'selected' : '' }}>Masterbox
                            </option>
                        </select>
                    </div>

                    @if (isset($jenisBahan) && $jenisBahan->count() > 0)
                        <!-- Jenis Bahan -->
                        <div class="filter-group-323">
                            <h4>Jenis Bahan</h4>
                            <select class="filter-select-323" name="jenis_bahan">
                                <option value="" {{ empty($activeFilters['jenis_bahan']) ? 'selected' : '' }}>
                                    Semua Bahan</option>
                                @foreach ($jenisBahan as $bahan)
                                    <option value="{{ $bahan }}"
                                        {{ ($activeFilters['jenis_bahan'] ?? '') == $bahan ? 'selected' : '' }}>
                                        {{ $bahan }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Rentang Stok -->
                    <div class="filter-group-323">
                        <h4>Rentang Stok</h4>
                        <select class="filter-select-323" name="stock-range">
                            <option value="all"
                                {{ empty($activeFilters['stock_range']) || $activeFilters['stock_range'] == 'all' ? 'selected' : '' }}>
                                Semua Stok</option>
                            <option value="1-50"
                                {{ ($activeFilters['stock_range'] ?? '') == '1-50' ? 'selected' : '' }}>1 - 50</option>
                            <option value="51-100"
                                {{ ($activeFilters['stock_range'] ?? '') == '51-100' ? 'selected' : '' }}>51 - 100
                            </option>
                            <option value="101-500"
                                {{ ($activeFilters['stock_range'] ?? '') == '101-500' ? 'selected' : '' }}>101 - 500
                            </option>
                            <option value="500+"
                                {{ ($activeFilters['stock_range'] ?? '') == '500+' ? 'selected' : '' }}>500+</option>
                        </select>
                    </div>

                    <!-- Categories -->
                    <div class="filter-group-323">
                        <h4>Kategori</h4>
                        <div class="categories-list-323">
                            @php
                                $selectedCategories = is_array($activeFilters['categories'] ?? null)
                                    ? $activeFilters['categories']
                                    : (isset($activeFilters['categories'])
                                        ? explode(',', $activeFilters['categories'])
                                        : []);
                            @endphp
                            @foreach ($categories as $category)
                                <div class="category-item-323 {{ in_array($category->id, $selectedCategories) ? 'active' : '' }}"
                                    data-category="{{ $category->id }}">
                                    <input type="checkbox" name="category" value="{{ $category->id }}"
                                        {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
                                        style="display:none;" />
                                    <span class="category-name-323">{{ $category->name }}</span>
                                    <span class="category-count-323">({{ $category->products_count }})</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-group-323">
                        <h4>Range Harga</h4>
                        <div class="price-range-323">
                            <div class="price-input-323">
                                <label>Min</label>
                                <input type="number" id="minPrice" placeholder="0" min="0"
                                    value="{{ $activeFilters['min_price'] ?? '' }}" />
                            </div>
                            <div class="price-input-323">
                                <label>Max</label>
                                <input type="number" id="maxPrice" placeholder="50000" min="0"
                                    value="{{ $activeFilters['max_price'] ?? '' }}" />
                            </div>
                        </div>
                        <button class="apply-price-btn-323" id="applyPriceBtn">
                            <i data-feather="search"></i>Cari Produk
                        </button>
                    </div>

                    <!-- Diskon -->
                    <div class="filter-group-323">
                        <h4>Diskon</h4>
                        <label class="checkbox-label-323">
                            <input type="checkbox" name="discount" value="true"
                                {{ ($activeFilters['discount'] ?? '') == 'true' ? 'checked' : '' }} />
                            <span>Produk Diskon</span>
                        </label>
                    </div>

                    <button class="reset-btn-323" id="resetFilterBtn">
                        <i data-feather="refresh-cw"></i>Reset Filter
                    </button>
                </aside>

                <div class="products-container-323">
                    <!-- Products Header -->
                    <div class="products-header-323">
                        <h2>Menampilkan <strong>{{ $products->total() }}</strong> produk</h2>
                    </div>

                    @if (!empty(array_filter($activeFilters)))
                        <div class="active-filters-323" id="activeFilters">
                            <span>Filter Aktif:</span>
                            <div class="filter-tags-323" id="filterTags">
                                @if (!empty($activeFilters['search']))
                                    <span class="filter-tag-323">Search: {{ $activeFilters['search'] }}</span>
                                @endif
                                @if (!empty($activeFilters['tipe']))
                                    <span class="filter-tag-323">Tipe: {{ ucfirst($activeFilters['tipe']) }}</span>
                                @endif
                                @if (!empty($activeFilters['jenis_bahan']))
                                    <span class="filter-tag-323">Bahan: {{ $activeFilters['jenis_bahan'] }}</span>
                                @endif
                                @if (!empty($selectedCategories))
                                    @foreach ($categories->whereIn('id', $selectedCategories) as $cat)
                                        <span class="filter-tag-323">{{ $cat->name }}</span>
                                    @endforeach
                                @endif
                                @if (!empty($activeFilters['discount']))
                                    <span class="filter-tag-323">Diskon</span>
                                @endif
                                @if (!empty($activeFilters['stock_range']) && $activeFilters['stock_range'] != 'all')
                                    <span class="filter-tag-323">Stok: {{ $activeFilters['stock_range'] }}</span>
                                @endif
                                @if (!empty($activeFilters['min_price']) || !empty($activeFilters['max_price']))
                                    <span class="filter-tag-323">
                                        Harga: Rp{{ number_format($activeFilters['min_price'] ?? 0, 0, ',', '.') }} -
                                        Rp{{ number_format($activeFilters['max_price'] ?? 50000, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                            <button class="clear-all-323" id="clearAllFilters">Hapus Semua</button>
                        </div>
                    @endif

                    <div class="products-grid-323" id="productsGrid">
                        @php
                            $defaultPlaceholder =
                                'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="500"%3E%3Crect width="100%25" height="100%25" fill="%23e0e0e0"/%3E%3Ctext x="50%25" y="50%25" font-family="Arial" font-size="20" fill="%23666" text-anchor="middle" dominant-baseline="middle"%3ENo Image%3C/text%3E%3C/svg%3E';
                        @endphp

                        @forelse($products as $product)
                            <a href="{{ route('product.detail', $product->id) }}" class="product-card-323"
                                style="text-decoration: none; color: inherit;">
                                <div class="product-image-323">
                                    @if ($product->photo_urls && count($product->photo_urls) > 0)
                                        <img alt="{{ $product->sku }}" class="default-image-323"
                                            src="{{ $product->photo_urls[0] }}"
                                            onerror="this.src='{{ $defaultPlaceholder }}'" />
                                        @if (count($product->photo_urls) > 1)
                                            <img alt="{{ $product->sku }}" class="hover-image-323"
                                                src="{{ $product->photo_urls[1] }}"
                                                onerror="this.style.display='none'" />
                                        @endif
                                    @else
                                        <img alt="{{ $product->sku }}" class="default-image-323"
                                            src="{{ $product->thumbnail ?? $defaultPlaceholder }}" loading="lazy"
                                            onerror="this.src='{{ $defaultPlaceholder }}'" />
                                    @endif

                                    @if ($product->has_discount)
                                        <span class="badge-discount-323">-{{ $product->discount_percentage }}%</span>
                                    @endif
                                    @if (!$product->is_available)
                                        <span class="badge-stock-323">Stok Habis</span>
                                    @endif
                                </div>

                                <div class="product-label-323">
                                    <span>{{ $product->sku }}</span>
                                </div>

                                <div class="product-detail-323">
                                    <div class="product-title-323">
                                        <p>{{ $product->tipe_display }}</p>
                                    </div>
                                    <div class="ratings-323" title="{{ $product->rating }} dari 5">
                                        @php
                                            $fullStars = floor($product->rating);
                                            $halfStar = $product->rating - $fullStars >= 0.5;
                                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                        @endphp

                                        @for ($i = 0; $i < $fullStars; $i++)
                                            <span class="star-323 filled-323">★</span>
                                        @endfor

                                        @if ($halfStar)
                                            <span class="star-323 half-323">★</span>
                                        @endif

                                        @for ($i = 0; $i < $emptyStars; $i++)
                                            <span class="star-323 empty-323">☆</span>
                                        @endfor
                                    </div>
                                    <div class="product-deskripsi-323">
                                        <p>{{ $product->title }}</p>
                                    </div>
                                    <div class="product-price-323">
                                        @if ($product->has_discount)
                                            <span
                                                class="original-price-323">{{ $product->formatted_original_price }}</span>
                                        @endif
                                        <span class="final-price-323">{{ $product->formatted_price }}</span>
                                    </div>

                                    <!-- Icon Actions - Stop Propagation -->
                                    <span
                                        class="wishlist-icon-323 {{ in_array($product->id, $wishlistIds ?? []) ? 'active' : '' }}"
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
                                    <span class="share-icon-323"
                                        onclick="event.preventDefault(); event.stopPropagation(); shareProduct({{ $product->id }});">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="22" y1="2" x2="11" y2="13">
                                            </line>
                                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                        </svg>
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="no-products-323">
                                <i data-feather="inbox"></i>
                                <h3>Tidak ada produk</h3>
                                <p>Tidak ada produk yang sesuai dengan filter Anda</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($products->hasPages())
                        <div class="pagination-323">{{ $products->links() }}</div>
                    @endif
                </div>
            </div>

        </div>

    </section>

    <style>
        /* ===================================
   CATALOG PRODUK - FULL CSS
   Fixed Responsive & Dynamic Layout
   =================================== */

        /* ROOT VARIABLES */
        .root-323,
        .catalog-section-323 {
            --primary-323: #FF6B35;
            --secondary-323: #1f4390;
            --subprimary-323: #95E1D3;
            --edition-323: #2C3E50;
            --lebaran-323: #00A86B;
            --christmas-323: #C41E3A;
            --imlek-323: #DC143C;
            --tertiary: #555;
            --contrast: #f5f5f5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ===================================
   PAGE HEADER
   =================================== */
        .page-header-323 {
            text-align: center;
            padding: 140px 0 40px;
            background: #f5f5f5;
        }

        .page-header-323 h1 {
            font-size: 2.5rem;
            color: var(--secondary-323);
            margin-bottom: 0.5rem;
        }

        .page-header-323 p {
            font-size: 1rem;
            color: #666;
        }

        /* ===================================
   CATALOG SECTION
   =================================== */
        .catalog-section-323 {
            padding: 0 0 80px;
            background: var(--contrast);
            min-height: 100vh;
        }

        .catalog-container-323 {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 8%;
        }

        /* ===================================
   FILTER SEARCH CONTAINER
   =================================== */
        .filter-search-container-323 {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            align-items: stretch;
            height: 48px;
        }

        .search-box-323 {
            flex: 1;
            position: relative;
            height: 48px;
            display: flex;
            align-items: center;
        }

        .search-icon-svg-323 {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-323);
            z-index: 1;
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .search-box-323 input {
            width: 100%;
            padding: 0 15px 0 45px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 1rem;
            transition: all 0.3s;
            height: 48px !important;
            line-height: 44px;
            box-sizing: border-box;
            display: block;
        }

        .search-box-323 input:focus {
            border-color: var(--secondary-323);
            outline: none;
        }

        .filter-toggle-btn-323 {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 20px;
            background: var(--secondary-323);
            color: #fff;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            height: 48px !important;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .filter-toggle-btn-323:hover {
            background: var(--primary-323);
        }

        .sort-select-323 {
            padding: 0 40px 0 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            background: #fff;
            transition: all 0.3s;
            height: 48px !important;
            line-height: 44px;
            box-sizing: border-box;
            min-width: 200px;
            flex-shrink: 0;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%231f4390' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 18px center;
            background-size: 16px;
            color: var(--secondary-323);
        }

        .sort-select-323:hover {
            border-color: var(--secondary-323);
            box-shadow: 0 2px 8px rgba(31, 67, 144, 0.15);
        }

        .sort-select-323 option {
            padding: 12px 16px;
            font-size: 0.95rem;
            color: #333;
            background: #fff;
        }

        .sort-select-323 option:hover {
            background: #f5f5f5;
        }

        .sort-select-323 option:checked {
            background: var(--secondary-323);
            color: #fff;
            font-weight: 600;
        }

        .sort-select-323:focus {
            border-color: var(--secondary-323);
            outline: none;
        }

        /* ===================================
   CATALOG CONTENT LAYOUT
   =================================== */
        .catalog-content-323 {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 30px;
        }

        /* ===================================
   FILTER SIDEBAR
   =================================== */
        .filter-sidebar-323 {
            background: #fff;
            padding: 20px 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            height: fit-content;
            position: sticky;
            top: 100px;
            width: 350px;
        }

        .filter-header-323 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }

        .filter-header-323 h3 {
            font-size: 1.5rem;
            color: var(--secondary-323);
        }

        .close-filter-323 {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        .filter-group-323 {
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        .filter-group-323:last-of-type {
            border-bottom: none;
        }

        .filter-group-323 h4 {
            font-size: 1.1rem;
            color: var(--tertiary);
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .filter-select-323 {
            width: 100%;
            padding: 0.7rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-select-323:focus {
            border-color: var(--secondary-323);
            outline: none;
        }

        .categories-list-323 {
            max-height: 250px;
            overflow-y: auto;
        }

        .categories-list-323::-webkit-scrollbar {
            width: 6px;
        }

        .categories-list-323::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 3px;
        }

        .category-item-323 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0.8rem;
            margin-bottom: 0.3rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1.1rem;
        }

        .category-item-323:hover {
            background: #f5f5f5;
        }

        .category-item-323.active {
            background: var(--secondary-323);
            color: white;
        }

        .category-name-323 {
            flex: 1;
        }

        .category-count-323 {
            background: #f0f0f0;
            padding: 0.15rem 0.5rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            color: #666;
        }

        .category-item-323.active .category-count-323 {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .price-range-323 {
            display: flex;
            gap: 0.8rem;
            margin-top: 0.5rem;
        }

        .price-input-323 {
            flex: 1;
        }

        .price-input-323 label {
            display: block;
            font-size: 0.8rem;
            color: #666;
            font-weight: normal;
            margin-bottom: 0.3rem;
        }

        .price-input-323 input {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
            text-align: center;
            box-sizing: border-box;
        }

        .price-input-323 input:focus {
            border-color: var(--secondary-323);
            outline: none;
        }

        /* Tombol Apply Price - TAMBAHKAN INI */
        .apply-price-btn-323 {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s;
            background: var(--secondary-323);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .apply-price-btn-323:hover {
            background: var(--primary-323);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(31, 67, 144, 0.3);
        }

        .apply-price-btn-323:active {
            transform: translateY(0);
        }

        .checkbox-label-323 {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .checkbox-label-323 input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .reset-btn-323 {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95rem;
            transition: all 0.3s;
            background: #f5f5f5;
            color: var(--tertiary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }

        .reset-btn-323:hover {
            background: #e0e0e0;
        }

        /* ===================================
   PRODUCTS SECTION - FLEXBOX RESPONSIVE
   =================================== */
        .products-container-323 {
            flex: 1;
            width: 100%;
            max-width: 100%;
        }

        .products-header-323 {
            background: white;
            padding: 1rem 1.2rem;
            border-radius: 8px;
            margin-bottom: 0.8rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .products-header-323 h2 {
            font-size: 0.95rem;
            color: #666;
        }

        .products-header-323 strong {
            color: var(--secondary-323);
        }

        /* ===================================
   ACTIVE FILTERS
   =================================== */
        .active-filters-323 {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 10px;
            flex-wrap: wrap;
        }

        .active-filters-323>span {
            font-weight: 600;
            color: var(--tertiary);
        }

        .filter-tags-323 {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            flex: 1;
        }

        .filter-tag-323 {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--secondary-323);
            color: #fff;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .clear-all-323 {
            padding: 6px 15px;
            background: #e74a3b;
            color: #fff;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.85rem;
            white-space: nowrap;
        }



        /* ===================================
   NO PRODUCTS
   =================================== */
        .no-products-323 {
            width: 100%;
            text-align: center;
            padding: 60px 20px;
        }

        .no-products-323 i {
            width: 80px;
            height: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .no-products-323 h3 {
            font-size: 1.5rem;
            color: var(--tertiary);
            margin-bottom: 10px;
        }

        .no-products-323 p {
            color: #666;
        }

        /* ===================================
   PAGINATION
   =================================== */
        /* ===================================
   PAGINATION STYLING - LARGER & MOBILE FRIENDLY
   =================================== */
        .pagination-323 {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Laravel Pagination Links */
        .pagination-323 nav {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .pagination-323 ul {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-323 li {
            display: inline-block;
        }

        .pagination-323 a,
        .pagination-323 span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 45px;
            min-height: 45px;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--secondary-323);
            background: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        /* Hover Effect */
        .pagination-323 a:hover {
            background: var(--secondary-323);
            color: #fff;
            border-color: var(--secondary-323);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(31, 67, 144, 0.2);
        }

        /* Active Page */
        .pagination-323 .active span {
            background: var(--secondary-323);
            color: #fff;
            border-color: var(--secondary-323);
            font-weight: 700;
        }

        /* Disabled (Previous/Next when not available) */
        .pagination-323 .disabled span {
            background: #f5f5f5;
            color: #ccc;
            border-color: #e0e0e0;
            cursor: not-allowed;
        }

        /* Previous & Next Arrows */
        .pagination-323 a[rel="prev"],
        .pagination-323 a[rel="next"] {
            font-weight: 700;
            padding: 12px 20px;
        }

        /* Dots (...) */
        .pagination-323 .dots {
            border: none;
            background: transparent;
            color: #999;
            cursor: default;
            pointer-events: none;
        }

        /* TAMBAHKAN RESPONSIVE MOBILE */
        @media (max-width: 768px) {
            .products-header-323 {
                padding: 1.5rem 1.2rem;
                /* Lebih tinggi di mobile */
                margin-top: 5px;
                /* Jarak dari search bar */
            }
        }

        /* ===================================
   FIX MOBILE: Gedein "Menampilkan X produk" & Kecilkan "Category"
   =================================== */

        /* Gedein dropdown Category di mobile */
        @media (max-width: 768px) {
            .mobile-category-select-323 {
                font-size: 1.5rem !important;
                /* Lebih kecil dari 0.85rem */
                font-weight: 400 !important;
                /* Kurangi bold */
            }

            /* Gedein teks "Menampilkan X produk" */
            .products-header-323 h2 {
                font-size: 1.5rem !important;
                /* Lebih besar dari 0.95rem */
            }

            .products-header-323 strong {
                font-size: 1.5rem !important;
                /* Lebih besar lagi untuk angka */
            }
        }

        /* ===================================
   RESPONSIVE PAGINATION
   =================================== */
        @media (max-width: 768px) {

            .pagination-323 a,
            .pagination-323 span {
                min-width: 42px;
                min-height: 42px;
                padding: 10px 14px;
                font-size: 0.95rem;
            }

            .pagination-323 a[rel="prev"],
            .pagination-323 a[rel="next"] {
                padding: 10px 16px;
            }
        }

        @media (max-width: 480px) {
            .pagination-323 {
                gap: 6px;
            }

            .pagination-323 a,
            .pagination-323 span {
                min-width: 38px;
                min-height: 38px;
                padding: 8px 12px;
                font-size: 0.9rem;
            }

            .pagination-323 a[rel="prev"],
            .pagination-323 a[rel="next"] {
                padding: 8px 14px;
            }
        }

        /* ===================================
   RESPONSIVE - TABLET (1024px)
   =================================== */
        @media (max-width: 1024px) {
            .catalog-container-323 {
                padding: 0 3%;
            }

            .catalog-content-323 {
                grid-template-columns: 1fr;
            }

            .filter-sidebar-323 {
                position: fixed;
                top: 0;
                left: -100%;
                width: 300px;
                height: 100vh;
                z-index: 10000;
                overflow-y: auto;
                transition: left 0.3s;
            }

            .filter-sidebar-323.active {
                left: 0;
            }

            .close-filter-323 {
                display: block;
            }

            .filter-toggle-btn-323 {
                display: flex;
            }

            .products-grid-323 {
                justify-content: center;
            }


        }

        /* ===================================
   RESPONSIVE - MOBILE (768px)
   =================================== */
        @media screen and (max-width: 768px) {
            .catalog-container-323 {
                padding: 0 4%;
            }

            /* ✅ TEKS SHAPUS SEMUA di mobile */
                .clear-all-323 {
        font-size: 1rem !important; /* ✅ GEDEIN di mobile */
        font-weight: 600 !important; /* ✅ Lebih tebal */
        padding: 8px 18px !important; /* ✅ Padding lebih besar */
    }

    /* ✅ TEKS FILTER AKTIF di mobile */
    .active-filters-323 > span {
        font-size: 1.1rem !important; /* ✅ GEDEIN di mobile */
        font-weight: 700 !important; /* ✅ Lebih tebal */
    }
            .page-header-323 {
                padding: 100px 0 30px;
            }

            .page-header-323 h1 {
                font-size: 2.8rem;
            }

            .page-header-323 p {
                font-size: 1.3rem;
                color: #666;
            }

            .filter-search-container-323 {
                flex-direction: column;
                height: auto;
            }

            .search-box-323,
            .sort-select-323,
            .filter-toggle-btn-323 {
                width: 100%;
            }

        }

        /* ===================================
   RESPONSIVE - SMALL MOBILE (480px)
   =================================== */
        @media (max-width: 480px) {
            .catalog-container-323 {
                padding: 0 3%;
            }

            .filter-sidebar-323 {
                width: 100%;
            }

        }

        /* Disable animation saat scroll untuk performa */
        @media (max-width: 768px) {
            .scrolling .product-deskripsi-323 p {
                animation-play-state: paused !important;
            }
        }
    </style>



    <style>
        /* ===================================
   MOBILE SEARCH & FILTER - GABUNG
   Hanya tampil di mobile (max-width: 768px)
   =================================== */
        /* Filter Button - Fixed Width */
        .mobile-filter-btn-323 {
            position: relative;
            width: 90px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0 12px;
            border: 1.5px solid #e0e0e0;
            border-radius: 20px;
            font-size: 1.5rem;

            color: #333;
            background: #fff;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            flex-shrink: 0;
        }

        .mobile-filter-btn-323 svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .mobile-filter-btn-323:hover,
        .mobile-filter-btn-323:active {
            border-color: var(--secondary-323);
            background: var(--secondary-323);
            color: #fff;
        }

        .mobile-filter-btn-323:hover svg,
        .mobile-filter-btn-323:active svg {
            stroke: #fff;
        }

        .mobile-search-filter-wrapper-323 {
            display: none;
            /* Hidden di desktop */
            width: 100%;
            padding: 8px 0 5px 0;
            background: transparent;
            margin-bottom: 0;
        }

        .mobile-search-filter-323 {
            display: flex;
            gap: 10px;
            align-items: center;
            width: 100%;
        }

        /* Search Box - Flex Grow */
        .mobile-search-box-323 {
            flex: 1;
            position: relative;
            height: 40px;
            display: flex;
            align-items: center;
        }

        .mobile-search-icon-323 {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            z-index: 1;
            width: 16px;
            height: 16px;
        }

        .mobile-search-box-323 input {
            width: 100%;
            height: 40px;
            padding: 0 12px 0 36px;
            border: 1.5px solid #e0e0e0;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #333;
            background: #fff;
            transition: all 0.3s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .mobile-search-box-323 input::placeholder {
            color: #999;
        }

        .mobile-search-box-323 input:focus {
            border-color: var(--secondary-323);
            background: #fff;
            outline: none;
            box-shadow: 0 2px 8px rgba(31, 67, 144, 0.15);
        }

        /* Filter Dropdown - Fixed Width */
        .mobile-filter-dropdown-323 {
            position: relative;
            width: 110px;
            flex-shrink: 0;
        }

        .mobile-category-select-323 {
            width: 100%;
            height: 40px;
            padding: 0 28px 0 12px;
            border: 1.5px solid #e0e0e0;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #333;
            background: #fff;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            transition: all 0.3s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .mobile-category-select-323:focus {
            border-color: var(--secondary-323);
            background: #fff;
            outline: none;
            box-shadow: 0 2px 8px rgba(31, 67, 144, 0.15);
        }

        .mobile-dropdown-arrow-323 {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            pointer-events: none;
            width: 12px;
            height: 12px;
        }

        /* ===================================
   RESPONSIVE - MOBILE ONLY
   =================================== */
        @media (max-width: 768px) {
            .mobile-search-filter-wrapper-323 {
                display: block !important;
                /* Force tampil di mobile */
            }

            /* SEMBUNYIKAN search & filter desktop di mobile */
            .filter-search-container-323 {
                display: none !important;
            }
        }

        @media (max-width: 480px) {
            .mobile-search-filter-wrapper-323 {
                padding: 12px 0;
            }

            .mobile-search-filter-323 {
                gap: 8px;
            }

            .mobile-search-box-323 input {
                font-size: 0.9rem;
            }

            .mobile-category-select-323 {
                font-size: 0.85rem;
                width: 110px;
            }
        }

        @media (max-width: 360px) {
            .mobile-filter-dropdown-323 {
                width: 100px;
            }

            .mobile-category-select-323 {
                padding: 0 28px 0 12px;
                font-size: 0.8rem;
            }
        }
    </style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileSearchInput = document.getElementById('mobileSearchInput');
    const mobileFilterBtn = document.getElementById('mobileFilterBtn');
    const desktopSearchInput = document.getElementById('searchInput');
    const filterSidebar = document.getElementById('filterSidebar');
    const closeFilterBtn = document.getElementById('closeFilterBtn');

    // ✅ SYNC mobile search dengan desktop search DAN TRIGGER REALTIME
    if (mobileSearchInput && desktopSearchInput) {
        mobileSearchInput.addEventListener('input', function() {
            desktopSearchInput.value = this.value; // Sync value
            
            // ✅ TRIGGER REALTIME SEARCH (debounce 500ms)
            clearTimeout(window.mobileSearchTimeout);
            window.mobileSearchTimeout = setTimeout(() => {
                // Trigger event input pada desktop search
                const event = new Event('input', { bubbles: true });
                desktopSearchInput.dispatchEvent(event);
            }, 500);
        });
    }

    // Handle mobile filter button - BUKA SIDEBAR
    if (mobileFilterBtn && filterSidebar) {
        mobileFilterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            filterSidebar.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close filter sidebar
    if (closeFilterBtn && filterSidebar) {
        closeFilterBtn.addEventListener('click', function() {
            filterSidebar.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // Close on outside click
    if (filterSidebar) {
        filterSidebar.addEventListener('click', function(e) {
            if (e.target === filterSidebar) {
                filterSidebar.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
});
</script>
    <script>
        // Pause animation saat scroll di mobile
        if (window.innerWidth <= 768) {
            let scrollTimer;
            window.addEventListener('scroll', function() {
                document.body.classList.add('scrolling');
                clearTimeout(scrollTimer);
                scrollTimer = setTimeout(function() {
                    document.body.classList.remove('scrolling');
                }, 150);
            }, {
                passive: true
            });
        }
    </script>
    <script src="{{ asset('frontend/assets/js/catalog-filter.js') }}" defer></script>
    {{-- Load external wishlist handler --}}
    <script src="{{ asset('frontend/assets/js/wishlist/wishlist-handler.js') }}" defer></script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator
                .userAgent) || window.innerWidth <= 768;

            if (isIOS) {
                console.log('iOS detected: Running text animation disabled');
                return;
            }

            const descriptions = document.querySelectorAll('.product-deskripsi-323 p');
            const maxAnimated = isMobile ? 5 : 15;

            Array.from(descriptions).slice(0, maxAnimated).forEach(desc => {
                const textWidth = desc.scrollWidth;
                const containerWidth = desc.parentElement.offsetWidth;

                if (textWidth > containerWidth) {
                    // ✅ UBAH: Hanya atur desc, BUKAN parentElement
                    desc.style.textAlign = 'left';
                    const distance = textWidth - containerWidth + 20;
                    const animationDuration = isMobile ? '12s' : '8s';

                    desc.style.animation =
                        `scroll-alternate ${animationDuration} ease-in-out 3s infinite alternate`;
                    desc.style.setProperty('--scroll-distance', `-${distance}px`);
                } else {
                    // ✅ UBAH: Hanya atur desc, BUKAN parentElement
                    desc.style.textAlign = 'center';
                }
            });

            if (!isMobile) {
                let resizeTimer;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(function() {
                        Array.from(descriptions).slice(0, 15).forEach(desc => {
                            const textWidth = desc.scrollWidth;
                            const containerWidth = desc.parentElement.offsetWidth;

                            if (textWidth > containerWidth) {
                                // ✅ UBAH: Hanya atur desc, BUKAN parentElement
                                desc.style.textAlign = 'left';
                                const distance = textWidth - containerWidth + 20;
                                desc.style.animation =
                                    `scroll-alternate 8s ease-in-out 3s infinite alternate`;
                                desc.style.setProperty('--scroll-distance',
                                    `-${distance}px`);
                            } else {
                                // ✅ UBAH: Hanya atur desc, BUKAN parentElement
                                desc.style.textAlign = 'center';
                                desc.style.animation = 'none';
                            }
                        });
                    }, 1000);
                }, {
                    passive: true
                });
            }
        });
    </script>

    {{-- Set konfigurasi wishlist --}}
    <script>
        window.wishlistConfig = {
            isAuthenticated: {{ auth('customer')->check() ? 'true' : 'false' }},
            loginUrl: "{{ route('home') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>

</x-frontend-layout>
