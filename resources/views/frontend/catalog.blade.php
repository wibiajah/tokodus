<x-frontend-layout>
    <x-slot:title>{{ $page_title }}</x-slot:title>

      <section class="catalog-section" id="catalog">
        <div class="catalog-container">
            <div class="catalog-header">
                <h1>Katalog Produk</h1>
                <p>Temukan produk packaging terbaik untuk kebutuhan Anda</p>
            </div>

            <div class="filter-search-container">
              <div class="search-box">
    <svg class="search-icon-svg" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"></circle>
        <path d="m21 21-4.35-4.35"></path>
    </svg>
    <input type="text" id="searchInput" placeholder="Cari produk..." value="{{ $activeFilters['search'] ?? '' }}" />
</div>
                <button class="filter-toggle-btn" id="filterToggleBtn">
                    <i data-feather="filter"></i><span>Filter</span>
                </button>
                <select id="sortSelect" class="sort-select">
                    <option value="newest" {{ ($activeFilters['sort'] ?? '') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ ($activeFilters['sort'] ?? '') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="name-asc" {{ ($activeFilters['sort'] ?? '') == 'name-asc' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="name-desc" {{ ($activeFilters['sort'] ?? '') == 'name-desc' ? 'selected' : '' }}>Nama Z-A</option>
                    <option value="price-low" {{ ($activeFilters['sort'] ?? '') == 'price-low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price-high" {{ ($activeFilters['sort'] ?? '') == 'price-high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="rating" {{ ($activeFilters['sort'] ?? '') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                </select>
            </div>

            <div class="catalog-content">
                <aside class="filter-sidebar" id="filterSidebar">
                    <div class="filter-header">
                        <h3>Filter Produk</h3>
                        <button class="close-filter" id="closeFilterBtn"><i data-feather="x"></i></button>
                    </div>

                    <div class="filter-group">
                        <h4>Rentang Stok</h4>
                        <label>
                            <input type="radio" name="stock-range" value="all" {{ empty($activeFilters['stock_range']) || $activeFilters['stock_range'] == 'all' ? 'checked' : '' }} />
                            <span>Semua Stok</span>
                        </label>
                        <label>
                            <input type="radio" name="stock-range" value="1-50" {{ ($activeFilters['stock_range'] ?? '') == '1-50' ? 'checked' : '' }} />
                            <span>1 - 50</span>
                        </label>
                        <label>
                            <input type="radio" name="stock-range" value="51-100" {{ ($activeFilters['stock_range'] ?? '') == '51-100' ? 'checked' : '' }} />
                            <span>51 - 100</span>
                        </label>
                        <label>
                            <input type="radio" name="stock-range" value="101-500" {{ ($activeFilters['stock_range'] ?? '') == '101-500' ? 'checked' : '' }} />
                            <span>101 - 500</span>
                        </label>
                        <label>
                            <input type="radio" name="stock-range" value="500+" {{ ($activeFilters['stock_range'] ?? '') == '500+' ? 'checked' : '' }} />
                            <span>500+</span>
                        </label>
                    </div>

                    <div class="filter-group">
                        <h4>Ketersediaan</h4>
                        <label>
                            <input type="radio" name="availability" value="all" {{ empty($activeFilters['availability']) || $activeFilters['availability'] == 'all' ? 'checked' : '' }} />
                            <span>Semua Produk</span>
                        </label>
                        <label>
                            <input type="radio" name="availability" value="available" {{ ($activeFilters['availability'] ?? '') == 'available' ? 'checked' : '' }} />
                            <span>Tersedia</span>
                        </label>
                        <label>
                            <input type="radio" name="availability" value="out-of-stock" {{ ($activeFilters['availability'] ?? '') == 'out-of-stock' ? 'checked' : '' }} />
                            <span>Stok Habis</span>
                        </label>
                    </div>

                    <div class="filter-group">
                        <h4>Kategori</h4>
                        <div class="filter-scrollable">
                            @php
                                $selectedCategories = is_array($activeFilters['categories'] ?? null) 
                                    ? $activeFilters['categories'] 
                                    : (isset($activeFilters['categories']) ? explode(',', $activeFilters['categories']) : []);
                            @endphp
                            @foreach($categories as $category)
                            <label>
                                <input type="checkbox" name="category" value="{{ $category->id }}" 
                                    {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }} />
                                <span>{{ $category->name }}</span>
                                <small>({{ $category->products_count }})</small>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-group">
                        <h4>Toko</h4>
                        <div class="filter-scrollable">
                            @php
                                $selectedTokos = is_array($activeFilters['tokos'] ?? null) 
                                    ? $activeFilters['tokos'] 
                                    : (isset($activeFilters['tokos']) ? explode(',', $activeFilters['tokos']) : []);
                            @endphp
                            @foreach($tokos as $toko)
                            <label>
                                <input type="checkbox" name="toko" value="{{ $toko->id }}" 
                                    {{ in_array($toko->id, $selectedTokos) ? 'checked' : '' }} />
                                <span>{{ $toko->nama_toko }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-group">
                        <h4>Range Harga</h4>
                        <div class="price-inputs">
                            <input type="number" id="minPrice" placeholder="Min" min="0" value="{{ $activeFilters['min_price'] ?? '' }}" />
                            <span>-</span>
                            <input type="number" id="maxPrice" placeholder="Max" min="0" value="{{ $activeFilters['max_price'] ?? '' }}" />
                        </div>
                        <button class="apply-btn" id="applyPriceBtn">Terapkan</button>
                    </div>

                    <div class="filter-group">
                        <h4>Diskon</h4>
                        <label>
                            <input type="checkbox" name="discount" value="true" {{ ($activeFilters['discount'] ?? '') == 'true' ? 'checked' : '' }} />
                            <span>Produk Diskon</span>
                        </label>
                    </div>

                    <button class="reset-btn" id="resetFilterBtn">
                        <i data-feather="refresh-cw"></i>Reset Filter
                    </button>
                </aside>

                <div class="products-container">
                    @if(!empty(array_filter($activeFilters)))
                    <div class="active-filters" id="activeFilters">
                        <span>Filter Aktif:</span>
                        <div class="filter-tags" id="filterTags">
                            @if(!empty($activeFilters['search']))
                                <span class="filter-tag">Search: {{ $activeFilters['search'] }}</span>
                            @endif
                            @if(!empty($selectedCategories))
                                @foreach($categories->whereIn('id', $selectedCategories) as $cat)
                                    <span class="filter-tag">{{ $cat->name }}</span>
                                @endforeach
                            @endif
                            @if(!empty($selectedTokos))
                                @foreach($tokos->whereIn('id', $selectedTokos) as $toko)
                                    <span class="filter-tag">{{ $toko->nama_toko }}</span>
                                @endforeach
                            @endif
                            @if(!empty($activeFilters['discount']))
                                <span class="filter-tag">Diskon</span>
                            @endif
                            @if(!empty($activeFilters['stock_range']) && $activeFilters['stock_range'] != 'all')
                                <span class="filter-tag">Stok: {{ $activeFilters['stock_range'] }}</span>
                            @endif
                        </div>
                        <button class="clear-all" id="clearAllFilters">Hapus Semua</button>
                    </div>
                    @endif

                    <div class="products-grid" id="productsGrid">
                        @php
                            $defaultPlaceholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="500"%3E%3Crect width="100%25" height="100%25" fill="%23e0e0e0"/%3E%3Ctext x="50%25" y="50%25" font-family="Arial" font-size="20" fill="%23666" text-anchor="middle" dominant-baseline="middle"%3ENo Image%3C/text%3E%3C/svg%3E';
                        @endphp
                        
                        @forelse($products as $product)
                        <div class="product-card-1">
                            <div class="label-1">{{ $product->sku }}</div>
                            
                            <div class="product-image-1">
                                    <a href="{{ route('product.detail', $product->id) }}">
                                @if($product->photo_urls && count($product->photo_urls) > 0)
                                    <img src="{{ $product->photo_urls[0] }}" 
                                         class="default-img-1" 
                                         alt="{{ $product->title }}"
                                         onerror="this.src='{{ $defaultPlaceholder }}'">
                                    @if(count($product->photo_urls) > 1)
                                        <img src="{{ $product->photo_urls[1] }}" 
                                             class="hover-img-1" 
                                             alt="{{ $product->title }}"
                                             onerror="this.style.display='none'">
                                    @endif
                                @else
                                    <img src="{{ $product->thumbnail ?? $defaultPlaceholder }}" 
                                         class="default-img-1" 
                                         alt="{{ $product->title }}"
                                         onerror="this.src='{{ $defaultPlaceholder }}'">
                                @endif
                                
                                @if($product->has_discount)
                                <span class="badge-discount">-{{ $product->discount_percentage }}%</span>
                                @endif
                                @if(!$product->is_available)
                                <span class="badge-stock">Stok Habis</span>
                                @endif
                            </div>

                            <div class="product-detail-1">
                                <div class="product-header-1">
                                    <div class="product-title-1">
                                        @if($product->categories && $product->categories->count() > 0)
                                            @foreach($product->categories as $index => $category)
                                                {{ $category->name }}{{ $index < $product->categories->count() - 1 ? ', ' : '' }}
                                            @endforeach
                                        @else
                                            Produk
                                        @endif
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
                                
                                <div class="product-stock-1">
                                    <i data-feather="package"></i>
                                    <span>Stok: {{ number_format($product->total_stock) }}</span>
                                </div>
                                
                                <div class="product-price-1">
                                    @if($product->has_discount)
                                    <span class="original-price-1">{{ $product->formatted_original_price }}</span>
                                    @endif
                                    <span class="final-price-1">{{ $product->formatted_price }}</span>
                                </div>
                                
                                @if($product->variants && is_array($product->variants) && count($product->variants) > 0)
                                <div class="product-variants-1">
                                    <div class="variant-label">Varian:</div>
                                    <div class="variant-list">
                                        @foreach($product->variants as $variantType => $options)
                                            @if(is_array($options))
                                                @foreach($options as $option)
                                                    <span class="variant-item">{{ $option }}</span>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                <div class="icons-1">
                                    <div style="display: flex; gap: 12px;">
                                        <span class="icon-1">❤</span>
                                        <span class="icon-1">📤</span>
                                    </div>
                                    <a href="{{ route('product.detail', $product->id) }}" class="icon-1" style="text-decoration: none;">🛒</a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="no-products">
                            <i data-feather="inbox"></i>
                            <h3>Tidak ada produk</h3>
                            <p>Tidak ada produk yang sesuai dengan filter Anda</p>
                        </div>
                        @endforelse
                    </div>

                    @if($products->hasPages())
                    <div class="pagination">{{ $products->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
 <style>
      /* Catalog Section Styles */
.catalog-section {
    padding: 120px 7% 80px;
    background: var(--contrast);
    min-height: 100vh;
}

.catalog-container {
    max-width: 1400px;
    margin: 0 auto;
}

.catalog-header {
    text-align: center;
    margin-bottom: 50px;
}

.catalog-header h1 {
    font-size: 3rem;
    color: var(--secondary);
    margin-bottom: 10px;
}

.catalog-header p {
    font-size: 1.2rem;
    color: var(--tertiary);
}

/* Filter Search Container - FORCE ALIGNMENT SOLUTION */
.filter-search-container {
    display: flex;
    gap: 15px;
    margin-bottom: 40px;
    align-items: stretch;
    height: 48px;
}

.search-box {
    flex: 1;
    position: relative;
    height: 48px;
    display: flex;
    align-items: center;
}

.search-icon-svg {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary);
    z-index: 1;
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

.search-box i svg {
    width: 18px !important;
    height: 18px !important;
    display: block;
}

.search-box input {
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

.search-box input:focus {
    border-color: var(--secondary);
    outline: none;
}

.filter-toggle-btn {
    display: none;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 0 20px;
    background: var(--secondary);
    color: #fff;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s;
    height: 48px !important;
    line-height: 48px;
    box-sizing: border-box;
    white-space: nowrap;
    flex-shrink: 0;
}

.filter-toggle-btn:hover {
    background: var(--primary);
    color: var(--tertiary);
}

.filter-toggle-btn i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

.filter-toggle-btn i svg {
    width: 18px !important;
    height: 18px !important;
    display: block;
}

.sort-select {
    padding: 0 20px;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    font-size: 1rem;
    cursor: pointer;
    background: #fff;
    transition: all 0.3s;
    height: 48px !important;
    line-height: 44px;
    box-sizing: border-box;
    min-width: 200px;
    flex-shrink: 0;
}

.sort-select:focus {
    border-color: var(--secondary);
    outline: none;
}

/* Catalog Content */
.catalog-content {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 40px;
}

/* Filter Sidebar */
.filter-sidebar {
    background: #fff;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.filter-header h3 {
    font-size: 1.5rem;
    color: var(--secondary);
}

.close-filter {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px;
}

.close-filter i svg {
    width: 24px !important;
    height: 24px !important;
}

.filter-group {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
}

.filter-group:last-of-type {
    border-bottom: none;
}

.filter-group h4 {
    font-size: 1.1rem;
    color: var(--tertiary);
    margin-bottom: 15px;
}

.filter-group label {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    cursor: pointer;
    font-size: 0.95rem;
}

.filter-group input[type="checkbox"],
.filter-group input[type="radio"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.filter-group small {
    color: #999;
    margin-left: auto;
}

.filter-scrollable {
    max-height: 200px;
    overflow-y: auto;
}

.filter-scrollable::-webkit-scrollbar {
    width: 6px;
}

.filter-scrollable::-webkit-scrollbar-thumb {
    background: #ddd;
    border-radius: 3px;
}

/* Price Inputs */
.price-inputs {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.price-inputs input {
    flex: 1;
    width: 0;
    min-width: 0;
    padding: 10px 8px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 0.9rem;
    text-align: center;
    box-sizing: border-box;
}

.price-inputs input:focus {
    border-color: var(--secondary);
    outline: none;
}

.price-inputs span {
    color: #999;
    font-weight: 500;
    flex-shrink: 0;
}

.apply-btn {
    width: 100%;
    padding: 10px;
    background: var(--secondary);
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.apply-btn:hover {
    background: var(--primary);
    color: var(--tertiary);
}

.reset-btn {
    width: 100%;
    padding: 12px;
    background: #f5f5f5;
    color: var(--tertiary);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 1rem;
    transition: all 0.3s;
}

.reset-btn:hover {
    background: #e0e0e0;
}

.reset-btn i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
}

.reset-btn i svg {
    width: 18px !important;
    height: 18px !important;
}

/* Active Filters */
.active-filters {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
    padding: 15px;
    background: #f9f9f9;
    border-radius: 10px;
    flex-wrap: wrap;
}

.active-filters > span {
    font-weight: 600;
    color: var(--tertiary);
}

.filter-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    flex: 1;
}

.filter-tag {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: var(--secondary);
    color: #fff;
    border-radius: 20px;
    font-size: 0.85rem;
}

.filter-tag button {
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;
    padding: 0;
    display: flex;
    align-items: center;
}

.clear-all {
    padding: 6px 15px;
    background: #e74a3b;
    color: #fff;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-size: 0.85rem;
    white-space: nowrap;
}

/* Product Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 20px;
}

/* Product Card Styles */
.product-card-1 {
    position: relative;
    width: 100%;
    height: 360px;
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
    height: 440px;
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

.hover-img-1 {
    opacity: 0;
}

.product-card-1:hover .default-img-1 {
    opacity: 0;
}

.product-card-1:hover .hover-img-1 {
    opacity: 1;
}

.badge-discount {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    background: #e74a3b;
    color: #fff;
    z-index: 2;
}

.badge-stock {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    background: rgba(0,0,0,0.7);
    color: #fff;
    z-index: 2;
}

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
    margin-bottom: 8px;
}

.product-title-1 {
    font-size: 0.85rem;
    color: #888;
}

.ratings-1 {
    color: #ffcc00;
    font-size: 12px;
}

.product-deskripsi-1 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1f4390;
    margin: 8px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.6rem;
}

.product-stock-1 {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
    color: #666;
    font-size: 0.9rem;
}

.product-stock-1 i {
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-stock-1 i svg {
    width: 16px !important;
    height: 16px !important;
}

.product-variants-1 {
    margin-bottom: 10px;
    font-size: 0.85rem;
}

.variant-label {
    font-weight: 600;
    color: #666;
    margin-bottom: 5px;
}

.variant-list {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.variant-item {
    color: #1f4390;
    font-weight: 500;
    padding-left: 10px;
    position: relative;
}

.variant-item::before {
    content: "•";
    position: absolute;
    left: 0;
    color: #1f4390;
}

.product-price-1 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.original-price-1 {
    text-decoration: line-through;
    color: #999;
    font-size: 0.9rem;
}

.final-price-1 {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--secondary);
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

.icon-1 {
    font-size: 16px;
    color: #888;
    cursor: pointer;
}

.icon-1:hover {
    color: #1f4390;
}

/* No Products/Results */
.no-products,
.no-results {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
}

.no-products i,
.no-results i {
    width: 80px;
    height: 80px;
    color: #ddd;
    margin-bottom: 20px;
}

.no-products i svg,
.no-results i svg {
    width: 80px !important;
    height: 80px !important;
}

.no-products h3,
.no-results h3 {
    font-size: 1.5rem;
    color: var(--tertiary);
    margin-bottom: 10px;
}

.no-products p,
.no-results p {
    color: #666;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 40px;
    gap: 10px;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .catalog-content {
        grid-template-columns: 1fr;
    }

    .filter-sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        width: 300px;
        height: 100vh;
        z-index: 10000;
        overflow-y: auto;
        transition: left 0.3s;
    }

    .filter-sidebar.active {
        left: 0;
    }

    .close-filter {
        display: block;
    }

    .filter-toggle-btn {
        display: flex;
    }
}

@media screen and (max-width: 768px) {
    .catalog-section {
        padding: 100px 5% 60px;
    }

    .catalog-header h1 {
        font-size: 2rem;
    }

    .filter-search-container {
        flex-direction: column;
        height: auto;
    }

    .search-box {
        width: 100%;
    }

    .sort-select {
        width: 100%;
        min-width: unset;
    }

    .filter-toggle-btn {
        width: 100%;
        justify-content: center;
    }

    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .product-card-1 {
        width: 100%;
        max-width: 100%;
        height: auto;
        min-height: 200px;
    }

    .product-card-1:hover {
        height: auto;
    }

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

    .icons-1 {
        opacity: 1 !important;
        margin-top: 5px;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .filter-sidebar {
        width: 100%;
        left: -100%;
    }

    .product-card-1 {
        max-width: 100%;
    }
}
        
    </style>
    <script src="{{ asset('frontend/assets/js/catalog-filter.js') }}"></script>
</x-frontend-layout>