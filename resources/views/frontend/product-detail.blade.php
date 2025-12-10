<x-frontend-layout>
    <x-slot:title>{{ $page_title }}</x-slot:title>

    <section class="product-detail-section-789">
        <div class="container-detail-789">
            <!-- Breadcrumb -->
            <div class="breadcrumb-detail-789">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('catalog') }}">Catalog</a>
                @if($product->categories->count() > 0)
                    <span>/</span>
                    <a href="{{ route('category', $product->categories->first()->slug) }}">
                        {{ $product->categories->first()->name }}
                    </a>
                @endif
                <span>/</span>
                <span class="current-detail-789">{{ $product->title }}</span>
            </div>

            <!-- Product Main Info -->
            <div class="product-main-detail-789">
                <!-- Product Images -->
                <div class="product-images-detail-789">
                    <div class="main-image-detail-789">
                        <img id="mainProductImage" 
                             src="{{ $product->photo_urls[0] ?? asset('frontend/assets/img/placeholder-product.png') }}" 
                             alt="{{ $product->title }}">
                        
                        @if($product->has_discount)
                            <span class="discount-badge-detail-789">-{{ $product->discount_percentage }}%</span>
                        @endif
                        
                        @if(!$product->is_available)
                            <span class="stock-badge-detail-789">Stok Habis</span>
                        @endif
                    </div>
                    
                    @if(count($product->photo_urls) > 1)
                        <div class="thumbnail-images-detail-789">
                            @foreach($product->photo_urls as $index => $photoUrl)
                                <img src="{{ $photoUrl }}" 
                                     alt="{{ $product->title }} - {{ $index + 1 }}"
                                     class="thumbnail-detail-789 {{ $index === 0 ? 'active-thumb-789' : '' }}"
                                     onclick="changeMainImage('{{ $photoUrl }}', this)">
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="product-info-detail-789">
                    <div class="product-meta-detail-789">
                        <span class="sku-detail-789">SKU: {{ $product->sku }}</span>
                        <div class="rating-detail-789">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= floor($product->rating) ? 'filled-star-789' : 'empty-star-789' }}">★</span>
                            @endfor
                            <span class="rating-text-detail-789">({{ $product->rating }} / {{ $product->reviews_count }} reviews)</span>
                        </div>
                    </div>

                    <h1 class="product-title-detail-789">{{ $product->title }}</h1>

                    <div class="product-categories-detail-789">
                        @foreach($product->categories as $category)
                            <a href="{{ route('category', $category->slug) }}" class="category-tag-detail-789">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>

                    <div class="product-price-detail-789">
                        @if($product->has_discount)
                            <span class="original-price-detail-789">{{ $product->formatted_original_price }}</span>
                        @endif
                        <span class="final-price-detail-789">{{ $product->formatted_price }}</span>
                    </div>

                    <div class="product-stock-detail-789">
                        <i data-feather="package"></i>
                        <span>Total Stok: <strong>{{ number_format($product->total_stock) }}</strong></span>
                    </div>

                    <!-- Variants -->
                    @if($product->variants && is_array($product->variants) && count($product->variants) > 0)
                        <div class="product-variants-detail-789">
                            <h3>Varian Tersedia:</h3>
                            @foreach($product->variants as $variantType => $options)
                                <div class="variant-group-detail-789">
                                    <label>{{ ucfirst($variantType) }}:</label>
                                    <div class="variant-options-detail-789">
                                        @if(is_array($options))
                                            @foreach($options as $option)
                                                <span class="variant-option-detail-789">{{ $option }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Stock by Toko -->
                    @if($product->stocks->count() > 0)
                        <div class="stock-by-toko-detail-789">
                            <h3>Ketersediaan di Toko:</h3>
                            <div class="toko-list-detail-789">
                                @foreach($product->stocks->where('stock', '>', 0) as $stock)
                                    <div class="toko-item-detail-789">
                                        <span class="toko-name-detail-789">{{ $stock->toko->nama_toko }}</span>
                                        <span class="toko-stock-detail-789">{{ number_format($stock->stock) }} pcs</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="product-actions-detail-789">
                        <button class="btn-cart-detail-789" {{ !$product->is_available ? 'disabled' : '' }}>
                            <i data-feather="shopping-cart"></i>
                            {{ $product->is_available ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                        </button>
                        <button class="btn-wishlist-detail-789">
                            <i data-feather="heart"></i>
                        </button>
                        <button class="btn-share-detail-789">
                            <i data-feather="share-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Description -->
            @if($product->description)
                <div class="product-description-detail-789">
                    <h2>Deskripsi Produk</h2>
                    <div class="description-content-detail-789">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <div class="related-products-detail-789">
                    <h2 class="related-title-789">Produk Terkait</h2>
                    <div class="products-grid-detail-789">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="product-card-detail-789">
                                <div class="label-detail-789">{{ $relatedProduct->sku }}</div>
                                
                                <div class="product-image-detail-789">
                                    <a href="{{ route('product.detail', $relatedProduct->id) }}">
                                        <img src="{{ $relatedProduct->photo_urls[0] ?? asset('frontend/assets/img/placeholder-product.png') }}" 
                                             class="default-img-detail-789" 
                                             alt="{{ $relatedProduct->title }}">
                                    </a>
                                    
                                    @if($relatedProduct->has_discount)
                                        <span class="badge-discount-detail-789">-{{ $relatedProduct->discount_percentage }}%</span>
                                    @endif
                                </div>

                                <div class="product-detail-card-789">
                                    <div class="product-header-detail-789">
                                        <div class="product-title-card-789">
                                            {{ $relatedProduct->categories->first()->name ?? 'Produk' }}
                                        </div>
                                        <div class="ratings-detail-789">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="star-detail-789">{{ $i <= floor($relatedProduct->rating) ? '★' : '☆' }}</span>
                                            @endfor
                                        </div>
                                    </div>
                                    
                                    <div class="product-deskripsi-detail-789">{{ $relatedProduct->title }}</div>
                                    
                                    <div class="product-price-card-789">
                                        @if($relatedProduct->has_discount)
                                            <span class="original-price-card-789">{{ $relatedProduct->formatted_original_price }}</span>
                                        @endif
                                        <span class="final-price-card-789">{{ $relatedProduct->formatted_price }}</span>
                                    </div>
                                    
                                    <div class="icons-detail-789">
                                        <div class="icons-left-789">
                                            <span class="icon-detail-789">❤</span>
                                            <span class="icon-detail-789">📤</span>
                                        </div>
                                        <a href="{{ route('product.detail', $relatedProduct->id) }}" class="icon-cart-789">🛒</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <style>
        /* Product Detail Section - UNIQUE CSS 789 */
        .product-detail-section-789 {
            padding: 140px 7% 80px;
            background: var(--contrast);
            min-height: 100vh;
        }

        .container-detail-789 {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Breadcrumb */
        .breadcrumb-detail-789 {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 30px;
            font-size: 0.9rem;
            flex-wrap: wrap;
        }

        .breadcrumb-detail-789 a {
            color: var(--secondary);
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb-detail-789 a:hover {
            color: var(--primary);
        }

        .breadcrumb-detail-789 span {
            color: #999;
        }

        .breadcrumb-detail-789 .current-detail-789 {
            color: var(--tertiary);
            font-weight: 600;
        }

        /* Product Main */
        .product-main-detail-789 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-bottom: 60px;
        }

        /* Product Images */
        .product-images-detail-789 {
            position: sticky;
            top: 120px;
            height: fit-content;
        }

        .main-image-detail-789 {
            position: relative;
            width: 100%;
            aspect-ratio: 1;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .main-image-detail-789 img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .discount-badge-detail-789 {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #e74a3b;
            color: #fff;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1rem;
            z-index: 10;
        }

        .stock-badge-detail-789 {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(0,0,0,0.8);
            color: #fff;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            z-index: 10;
        }

        .thumbnail-images-detail-789 {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 15px;
        }

        .thumbnail-detail-789 {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s;
        }

        .thumbnail-detail-789:hover,
        .thumbnail-detail-789.active-thumb-789 {
            border-color: var(--secondary);
        }

        /* Product Info */
        .product-info-detail-789 {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .product-meta-detail-789 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .sku-detail-789 {
            color: #666;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .rating-detail-789 {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .filled-star-789 {
            color: #ffcc00;
            font-size: 1.2rem;
        }

        .empty-star-789 {
            color: #ddd;
            font-size: 1.2rem;
        }

        .rating-text-detail-789 {
            color: #666;
            font-size: 0.85rem;
            margin-left: 5px;
        }

        .product-title-detail-789 {
            font-size: 2.2rem;
            color: var(--tertiary);
            margin-bottom: 15px;
            line-height: 1.3;
            font-weight: 700;
        }

        .product-categories-detail-789 {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .category-tag-detail-789 {
            display: inline-block;
            padding: 6px 16px;
            background: var(--secondary);
            color: var(--contrast);
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .category-tag-detail-789:hover {
            background: var(--primary);
            transform: translateY(-2px);
        }

        .product-price-detail-789 {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .original-price-detail-789 {
            font-size: 1.3rem;
            color: #999;
            text-decoration: line-through;
        }

        .final-price-detail-789 {
            font-size: 2.5rem;
            color: var(--secondary);
            font-weight: 700;
        }

        .product-stock-detail-789 {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .product-stock-detail-789 i {
            color: var(--secondary);
            width: 20px;
            height: 20px;
        }

        .product-stock-detail-789 span {
            color: var(--tertiary);
        }

        /* Variants */
        .product-variants-detail-789 {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 12px;
        }

        .product-variants-detail-789 h3 {
            font-size: 1.1rem;
            color: var(--tertiary);
            margin-bottom: 15px;
            font-weight: 600;
        }

        .variant-group-detail-789 {
            margin-bottom: 15px;
        }

        .variant-group-detail-789:last-child {
            margin-bottom: 0;
        }

        .variant-group-detail-789 label {
            display: block;
            font-weight: 600;
            color: #666;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .variant-options-detail-789 {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .variant-option-detail-789 {
            padding: 8px 16px;
            background: #fff;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--tertiary);
            font-weight: 500;
        }

        /* Stock by Toko */
        .stock-by-toko-detail-789 {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 12px;
        }

        .stock-by-toko-detail-789 h3 {
            font-size: 1.1rem;
            color: var(--tertiary);
            margin-bottom: 15px;
            font-weight: 600;
        }

        .toko-list-detail-789 {
            display: grid;
            gap: 10px;
        }

        .toko-item-detail-789 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            background: #fff;
            border-radius: 8px;
        }

        .toko-name-detail-789 {
            color: var(--tertiary);
            font-weight: 500;
        }

        .toko-stock-detail-789 {
            color: var(--secondary);
            font-weight: 700;
        }

        /* Action Buttons */
        .product-actions-detail-789 {
            display: flex;
            gap: 15px;
        }

        .product-actions-detail-789 button {
            flex: 1;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .btn-cart-detail-789 {
            background: var(--secondary);
            color: var(--contrast);
            flex: 2;
        }

        .btn-cart-detail-789:hover:not(:disabled) {
            background: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(31, 67, 144, 0.3);
        }

        .btn-cart-detail-789:disabled {
            background: #ccc;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn-wishlist-detail-789,
        .btn-share-detail-789 {
            background: #f5f5f5;
            color: var(--tertiary);
            flex: 0 0 56px;
        }

        .btn-wishlist-detail-789:hover,
        .btn-share-detail-789:hover {
            background: var(--secondary);
            color: var(--contrast);
        }

        /* Product Description */
        .product-description-detail-789 {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            margin-bottom: 60px;
        }

        .product-description-detail-789 h2 {
            font-size: 1.8rem;
            color: var(--tertiary);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--secondary);
            font-weight: 700;
        }

        .description-content-detail-789 {
            color: #666;
            line-height: 1.8;
            font-size: 1.05rem;
        }

        /* Related Products - COMPLETELY ISOLATED CSS */
        .related-products-detail-789 {
            margin-top: 60px;
            width: 100%;
        }

        .related-products-detail-789 .related-title-789 {
            font-size: 2rem;
            color: var(--tertiary);
            margin-bottom: 30px;
            text-align: center;
            font-weight: 700;
        }

        .related-products-detail-789 .products-grid-detail-789 {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
            width: 100%;
        }

        /* Product Card for Related Products */
        .related-products-detail-789 .product-card-detail-789 {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .related-products-detail-789 .product-card-detail-789:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .related-products-detail-789 .label-detail-789 {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(255, 255, 255, 0.95);
            color: var(--tertiary);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            z-index: 5;
            letter-spacing: 0.5px;
        }

        .related-products-detail-789 .product-image-detail-789 {
            position: relative;
            width: 100%;
            height: 280px;
            overflow: hidden;
            background: #f5f5f5;
        }

        .related-products-detail-789 .product-image-detail-789 a {
            display: block;
            width: 100%;
            height: 100%;
        }

        .related-products-detail-789 .default-img-detail-789 {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .related-products-detail-789 .product-card-detail-789:hover .default-img-detail-789 {
            transform: scale(1.1);
        }

        .related-products-detail-789 .badge-discount-detail-789 {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #e74a3b;
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            z-index: 5;
        }

        .related-products-detail-789 .product-detail-card-789 {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
        }

        .related-products-detail-789 .product-header-detail-789 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .related-products-detail-789 .product-title-card-789 {
            color: var(--secondary);
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .related-products-detail-789 .ratings-detail-789 {
            display: flex;
            gap: 2px;
            font-size: 0.9rem;
        }

        .related-products-detail-789 .star-detail-789 {
            color: #ffcc00;
        }

        .related-products-detail-789 .product-deskripsi-detail-789 {
            color: var(--tertiary);
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.4;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            min-height: 44px;
        }

        .related-products-detail-789 .product-price-card-789 {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-top: auto;
        }

        .related-products-detail-789 .original-price-card-789 {
            color: #999;
            font-size: 0.9rem;
            text-decoration: line-through;
            font-weight: 500;
        }

        .related-products-detail-789 .final-price-card-789 {
            color: var(--secondary);
            font-size: 1.4rem;
            font-weight: 700;
        }

        .related-products-detail-789 .icons-detail-789 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid #f0f0f0;
            margin-top: 8px;
        }

        .related-products-detail-789 .icons-left-789 {
            display: flex;
            gap: 12px;
        }

        .related-products-detail-789 .icon-detail-789 {
            font-size: 1.3rem;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0.7;
        }

        .related-products-detail-789 .icon-detail-789:hover {
            opacity: 1;
            transform: scale(1.2);
        }

        .related-products-detail-789 .icon-cart-789 {
            font-size: 1.3rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .related-products-detail-789 .icon-cart-789:hover {
            transform: scale(1.2);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .product-main-detail-789 {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .product-images-detail-789 {
                position: relative;
                top: 0;
            }

            .related-products-detail-789 .products-grid-detail-789 {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .product-detail-section-789 {
                padding: 120px 5% 60px;
            }

            .product-title-detail-789 {
                font-size: 1.6rem;
            }

            .final-price-detail-789 {
                font-size: 2rem;
            }

            .product-info-detail-789 {
                padding: 25px;
            }

            .thumbnail-images-detail-789 {
                grid-template-columns: repeat(4, 1fr);
            }

            .product-actions-detail-789 {
                flex-direction: column;
            }

            .btn-cart-detail-789,
            .btn-wishlist-detail-789,
            .btn-share-detail-789 {
                flex: 1;
            }

            .related-products-detail-789 .products-grid-detail-789 {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .related-products-detail-789 .product-image-detail-789 {
                height: 200px;
            }

            .related-products-detail-789 .product-detail-card-789 {
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            .product-title-detail-789 {
                font-size: 1.4rem;
            }

            .final-price-detail-789 {
                font-size: 1.8rem;
            }

            .product-info-detail-789 {
                padding: 20px;
            }

            .related-products-detail-789 .products-grid-detail-789 {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .related-products-detail-789 .product-image-detail-789 {
                height: 250px;
            }
        }
    </style>

    <script>
        // Change main image - UNIQUE FUNCTION 789
        function changeMainImage(imageUrl, thumbnail) {
            const mainImg = document.getElementById('mainProductImage');
            if (mainImg) {
                mainImg.src = imageUrl;
            }
            
            // Update active thumbnail
            document.querySelectorAll('.thumbnail-detail-789').forEach(thumb => {
                thumb.classList.remove('active-thumb-789');
            });
            
            if (thumbnail) {
                thumbnail.classList.add('active-thumb-789');
            }
        }

        // Initialize Feather Icons
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
</x-frontend-layout>