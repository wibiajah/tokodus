<x-frontend-layout>
    <x-slot:title>{{ $page_title }}</x-slot:title>
    
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/catalogproduct.css') }}">
    
    <!-- Page Header -->
    <div class="wishlist-header-modern">
        <div class="wishlist-header-container">
            <h1 class="wishlist-title">Wishlist Saya</h1>
            @if($wishlists->count() > 0)
                <form action="{{ route('customer.wishlist.clear') }}" method="POST" 
                      onsubmit="return confirm('Hapus semua wishlist?')" class="clear-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-clear-all">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Hapus Semua
                    </button>
                </form>
            @endif
        </div>
    </div>

    <section class="wishlist-section-modern">
        <div class="wishlist-container-modern">
            
            @if(session('success'))
                <div class="alert alert-success-modern">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error-modern">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @if($wishlists->count() > 0)
                <!-- Desktop Table View -->
                <div class="wishlist-table-wrapper">
                    <table class="wishlist-table">
                        <thead>
                            <tr>
                                <th class="th-remove"></th>
                                <th class="th-product">Product Name</th>
                                <th class="th-price">Unit Price</th>
                                <th class="th-stock">Stock Status</th>
                                <th class="th-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wishlists as $wishlist)
                                @php
                                    $product = $wishlist->product;
                                    $defaultPlaceholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="500"%3E%3Crect width="100%25" height="100%25" fill="%23f0f0f0"/%3E%3Ctext x="50%25" y="50%25" font-family="Arial" font-size="20" fill="%23999" text-anchor="middle" dominant-baseline="middle"%3ENo Image%3C/text%3E%3C/svg%3E';
                                @endphp
                                <tr class="wishlist-row">
                                    <td class="td-remove">
                                        <form action="{{ route('customer.wishlist.destroy', $wishlist->id) }}" method="POST" 
                                              class="remove-form"
                                              onsubmit="return confirm('Hapus dari wishlist?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-remove">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="td-product">
                                        <a href="{{ route('product.detail', $product->id) }}" class="product-link">
                                            <div class="product-info">
                                                <div class="product-image-wrapper">
                                                    @if($product->photo_urls && count($product->photo_urls) > 0)
                                                        <img src="{{ $product->photo_urls[0] }}" 
                                                             alt="{{ $product->sku }}"
                                                             class="product-img"
                                                             onerror="this.src='{{ $defaultPlaceholder }}'">
                                                    @else
                                                        <img src="{{ $defaultPlaceholder }}" 
                                                             alt="{{ $product->sku }}"
                                                             class="product-img">
                                                    @endif
                                                    @if($product->has_discount)
                                                        <span class="discount-badge">-{{ $product->discount_percentage }}%</span>
                                                    @endif
                                                </div>
                                                <div class="product-details">
                                                    <div class="product-name">{{ $product->title }}</div>
                                                    <div class="product-sku">SKU: {{ $product->sku }}</div>
                                                    @if($product->tipe_display)
                                                        <div class="product-type">{{ $product->tipe_display }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="td-price">
                                        <div class="price-wrapper">
                                            @if($product->has_discount)
                                                <div class="price-original">{{ $product->formatted_original_price }}</div>
                                            @endif
                                            <div class="price-final">{{ $product->formatted_price }}</div>
                                        </div>
                                    </td>
                                    <td class="td-stock">
                                        <span class="stock-badge stock-in">In Stock</span>
                                    </td>
                                    <td class="td-actions">
                                        <a href="{{ route('product.detail', $product->id) }}" class="btn-add-cart">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="9" cy="21" r="1"></circle>
                                                <circle cx="20" cy="21" r="1"></circle>
                                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                            </svg>
                                            Add to Cart
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="wishlist-mobile-cards">
                    @foreach($wishlists as $wishlist)
                        @php
                            $product = $wishlist->product;
                            $defaultPlaceholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="500"%3E%3Crect width="100%25" height="100%25" fill="%23f0f0f0"/%3E%3Ctext x="50%25" y="50%25" font-family="Arial" font-size="20" fill="%23999" text-anchor="middle" dominant-baseline="middle"%3ENo Image%3C/text%3E%3C/svg%3E';
                        @endphp
                        <div class="mobile-wishlist-card">
                            <form action="{{ route('customer.wishlist.destroy', $wishlist->id) }}" method="POST" 
                                  class="mobile-remove-form"
                                  onsubmit="return confirm('Hapus dari wishlist?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="mobile-btn-remove">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </form>
                            
                            <a href="{{ route('product.detail', $product->id) }}" class="mobile-product-link">
                                <div class="mobile-product-image">
                                    @if($product->photo_urls && count($product->photo_urls) > 0)
                                        <img src="{{ $product->photo_urls[0] }}" 
                                             alt="{{ $product->sku }}"
                                             onerror="this.src='{{ $defaultPlaceholder }}'">
                                    @else
                                        <img src="{{ $defaultPlaceholder }}" alt="{{ $product->sku }}">
                                    @endif
                                    @if($product->has_discount)
                                        <span class="mobile-discount-badge">-{{ $product->discount_percentage }}%</span>
                                    @endif
                                </div>
                                
                                <div class="mobile-product-info">
                                    <div class="mobile-product-name">{{ $product->title }}</div>
                                    <div class="mobile-product-sku">SKU: {{ $product->sku }}</div>
                                    @if($product->tipe_display)
                                        <div class="mobile-product-type">{{ $product->tipe_display }}</div>
                                    @endif
                                    
                                    <div class="mobile-price-wrapper">
                                        @if($product->has_discount)
                                            <span class="mobile-price-original">{{ $product->formatted_original_price }}</span>
                                        @endif
                                        <span class="mobile-price-final">{{ $product->formatted_price }}</span>
                                    </div>
                                    
                                    <div class="mobile-stock">
                                        <span class="mobile-stock-badge">In Stock</span>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="{{ route('product.detail', $product->id) }}" class="mobile-btn-cart">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                Add to Cart
                            </a>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="wishlist-empty">
                    <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="#d0d0d0" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    <h3 class="empty-title">Wishlist Kosong</h3>
                    <p class="empty-text">Anda belum menambahkan produk ke wishlist</p>
                    <a href="{{ route('catalog') }}" class="btn-start-shopping">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        Mulai Belanja
                    </a>
                </div>
            @endif

        </div>
    </section>

    <style>
        /* ========== RESET & BASE ========== */
        * {
            box-sizing: border-box;
        }

        /* ========== HEADER ========== */
        .wishlist-header-modern {
            background: transparent;
            border-bottom: none;
            padding: 24px 0;
            margin-bottom: 32px;
            margin-top: 100px;
        }

        .wishlist-header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .wishlist-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .clear-form {
            margin: 0;
        }

        .btn-clear-all {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #ef4444;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-clear-all:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-clear-all:active {
            transform: translateY(0);
        }

        /* ========== SECTION ========== */
        .wishlist-section-modern {
            padding: 0 0 80px 0;
            min-height: 400px;
            background: transparent;
        }

        .wishlist-container-modern {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ========== ALERTS ========== */
        .alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success-modern {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .alert-error-modern {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* ========== TABLE (DESKTOP) ========== */
        .wishlist-table-wrapper {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            display: block;
        }

        .wishlist-table {
            width: 100%;
            border-collapse: collapse;
        }

        .wishlist-table thead {
            background: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
        }

        .wishlist-table th {
            padding: 16px 20px;
            text-align: left;
            font-size: 13px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .th-remove {
            width: 60px;
        }

        .th-product {
            width: 45%;
        }

        .th-price {
            width: 18%;
        }

        .th-stock {
            width: 15%;
        }

        .th-actions {
            width: 22%;
        }

        .wishlist-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s ease;
        }

        .wishlist-table tbody tr:hover {
            background: #f9fafb;
        }

        .wishlist-table tbody tr:last-child {
            border-bottom: none;
        }

        .wishlist-table td {
            padding: 20px;
            vertical-align: middle;
        }

        /* Remove Button */
        .td-remove {
            text-align: center;
        }

        .remove-form {
            margin: 0;
        }

        .btn-remove {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #6b7280;
        }

        .btn-remove:hover {
            background: #fef2f2;
            border-color: #ef4444;
            color: #ef4444;
        }

        /* Product Info */
        .product-link {
            text-decoration: none;
            color: inherit;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .product-image-wrapper {
            position: relative;
            width: 90px;
            height: 90px;
            flex-shrink: 0;
            background: #f9fafb;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-link:hover .product-img {
            transform: scale(1.05);
        }

        .discount-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            background: #ef4444;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .product-details {
            flex: 1;
            min-width: 0;
        }

        .product-name {
            font-size: 15px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-sku {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .product-type {
            font-size: 12px;
            color: #9ca3af;
        }

        /* Price */
        .price-wrapper {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .price-original {
            font-size: 13px;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .price-final {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }

        /* Stock */
        .stock-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        .stock-in {
            background: #ecfdf5;
            color: #047857;
        }

        /* Actions Button */
        .btn-add-cart {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #1f2937;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-add-cart:hover {
            background: #111827;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(31, 41, 55, 0.3);
        }

        .btn-add-cart:active {
            transform: translateY(0);
        }

        /* ========== MOBILE CARDS ========== */
        .wishlist-mobile-cards {
            display: none;
            flex-direction: column;
            gap: 16px;
        }

        .mobile-wishlist-card {
            position: relative;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .mobile-remove-form {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 10;
            margin: 0;
        }

        .mobile-btn-remove {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #6b7280;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .mobile-btn-remove:hover,
        .mobile-btn-remove:active {
            background: #fef2f2;
            border-color: #ef4444;
            color: #ef4444;
        }

        .mobile-product-link {
            display: flex;
            gap: 16px;
            padding: 16px;
            text-decoration: none;
            color: inherit;
        }

        .mobile-product-image {
            position: relative;
            width: 110px;
            height: 110px;
            flex-shrink: 0;
            background: #f9fafb;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .mobile-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .mobile-discount-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            background: #ef4444;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .mobile-product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 0;
        }

        .mobile-product-name {
            font-size: 15px;
            font-weight: 600;
            color: #1f2937;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .mobile-product-sku {
            font-size: 12px;
            color: #6b7280;
        }

        .mobile-product-type {
            font-size: 12px;
            color: #9ca3af;
        }

        .mobile-price-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 4px;
        }

        .mobile-price-original {
            font-size: 13px;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .mobile-price-final {
            font-size: 17px;
            font-weight: 700;
            color: #1f2937;
        }

        .mobile-stock {
            margin-top: 4px;
        }

        .mobile-stock-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            background: #ecfdf5;
            color: #047857;
        }

        .mobile-btn-cart {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 0 16px 16px;
            padding: 12px;
            background: #1f2937;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mobile-btn-cart:active {
            background: #111827;
            transform: scale(0.98);
        }

        /* ========== EMPTY STATE ========== */
        .wishlist-empty {
            text-align: center;
            padding: 80px 20px;
            background: transparent;
            border-radius: 0;
            border: none;
        }

        .empty-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin: 20px 0 8px;
        }

        .empty-text {
            font-size: 15px;
            color: #6b7280;
            margin: 0 0 24px;
        }

        .btn-start-shopping {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            background: #1f4390;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-start-shopping:hover {
            background: #1a3876;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(31, 67, 144, 0.3);
        }

        .btn-start-shopping:active {
            transform: translateY(0);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            .wishlist-title {
                font-size: 24px;
            }

            .wishlist-table th {
                font-size: 12px;
                padding: 14px 16px;
            }

            .wishlist-table td {
                padding: 16px;
            }

            .product-image-wrapper {
                width: 80px;
                height: 80px;
            }

            .product-name {
                font-size: 14px;
            }

            .price-final {
                font-size: 16px;
            }
        }

        @media (max-width: 768px) {
            .wishlist-header-modern {
                padding: 20px 0;
                margin-bottom: 24px;
                margin-top: 90px;
            }

            .wishlist-header-container {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
                padding: 0 16px;
            }

            .wishlist-title {
                font-size: 22px;
                text-align: center;
                order: 1;
            }

            .clear-form {
                order: 2;
            }

            .btn-clear-all {
                width: 100%;
                justify-content: center;
                padding: 12px 20px;
            }

            .wishlist-section-modern {
                padding: 0 0 60px 0;
            }

            .wishlist-container-modern {
                padding: 0 16px;
            }

            /* Hide table, show cards */
            .wishlist-table-wrapper {
                display: none;
            }

            .wishlist-mobile-cards {
                display: flex;
            }

            .wishlist-empty {
                padding: 60px 20px;
            }

            .empty-title {
                font-size: 20px;
            }

            .empty-text {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .wishlist-header-modern {
                margin-top: 80px;
            }

            .wishlist-title {
                font-size: 20px;
            }

            .btn-clear-all {
                font-size: 13px;
                padding: 10px 16px;
            }

            .mobile-product-image {
                width: 100px;
                height: 100px;
            }

            .mobile-product-name {
                font-size: 14px;
            }

            .mobile-price-final {
                font-size: 16px;
            }

            .mobile-btn-cart {
                font-size: 13px;
                padding: 11px;
            }
        }

        /* ========== HOVER EFFECTS (DESKTOP ONLY) ========== */
        @media (hover: hover) {
            .btn-clear-all:hover {
                background: #dc2626;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            }

            .btn-remove:hover {
                background: #fef2f2;
                border-color: #ef4444;
                color: #ef4444;
            }

            .product-link:hover .product-img {
                transform: scale(1.05);
            }

            .wishlist-table tbody tr:hover {
                background: #f9fafb;
            }

            .btn-add-cart:hover {
                background: #111827;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(31, 41, 55, 0.3);
            }

            .btn-start-shopping:hover {
                background: #1a3876;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(31, 67, 144, 0.3);
            }
        }

        /* ========== PRINT ========== */
        @media print {
            .btn-clear-all,
            .btn-remove,
            .mobile-btn-remove,
            .btn-add-cart,
            .mobile-btn-cart {
                display: none;
            }
        }
    </style>
</x-frontend-layout>