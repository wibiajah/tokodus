<x-frontend-layout :title="$page_title">
    <div id="content">
        <!-- Breadcrumb -->
        <section class="breadcrumb-section" style="padding: 20px 0; background: #f8f9fa;">
            <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="list-style: none; display: flex; gap: 10px; margin: 0; padding: 0;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" style="color: #007bff; text-decoration: none;">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: #6c757d;">
                            {{ $category->name }}
                        </li>
                    </ol>
                </nav>
            </div>
        </section>

        <!-- Category Header -->
        <section class="category-header" style="padding: 40px 0; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                @if($category->photo)
                    <img src="{{ asset('storage/' . $category->photo) }}" 
                         alt="{{ $category->name }}" 
                         style="width: 100px; height: 100px; object-fit: contain; margin-bottom: 20px; filter: brightness(0) invert(1);" />
                @endif
                
                <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 10px;">
                    {{ $category->name }}
                </h1>
                
                @if($category->description)
                    <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto;">
                        {{ $category->description }}
                    </p>
                @endif
                
                <p style="margin-top: 15px; font-size: 0.95rem; opacity: 0.8;">
                    {{ $products->total() }} produk ditemukan
                </p>
            </div>
        </section>

        <!-- Products Grid -->
        <section class="products-grid" style="padding: 60px 0;">
            <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                @if($products->count() > 0)
                    <div class="products-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
                        @foreach($products as $product)
                            <div class="product-card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                <div class="product-image" style="position: relative; padding-top: 100%; overflow: hidden;">
                                    @if($product->image_default)
                                        <img src="{{ asset('storage/' . $product->image_default) }}" 
                                             alt="{{ $product->kode }}" 
                                             style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;" />
                                    @else
                                        <img src="{{ asset('frontend/assets/img/placeholder.png') }}" 
                                             alt="{{ $product->kode }}" 
                                             style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;" />
                                    @endif
                                </div>
                                
                                <div class="product-info" style="padding: 20px;">
                                    <div class="product-category" style="background: #667eea; color: white; display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; margin-bottom: 10px;">
                                        {{ $product->tipe ?? 'Product' }}
                                    </div>
                                    
                                    <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 8px; color: #333;">
                                        {{ $product->kode }}
                                    </h3>
                                    
                                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 15px;">
                                        {{ $product->dimensi ?? 'N/A' }}
                                    </p>
                                    
                                    <div class="product-actions" style="display: flex; gap: 10px; justify-content: space-between; align-items: center;">
                                        <a href="{{ route('product.detail', $product->id) }}" 
                                           style="flex: 1; background: #667eea; color: white; text-align: center; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: background 0.3s;">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper" style="margin-top: 40px; display: flex; justify-content: center;">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="no-products" style="text-align: center; padding: 60px 20px;">
                        <img src="{{ asset('frontend/assets/img/empty-state.png') }}" 
                             alt="No products" 
                             style="width: 200px; margin-bottom: 20px; opacity: 0.5;" />
                        <h3 style="color: #666; font-size: 1.3rem; margin-bottom: 10px;">
                            Belum ada produk di kategori ini
                        </h3>
                        <p style="color: #999;">
                            Silakan cek kategori lainnya atau kembali ke halaman utama
                        </p>
                        <a href="{{ route('home') }}" 
                           style="display: inline-block; margin-top: 20px; background: #667eea; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 500;">
                            Kembali ke Home
                        </a>
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-frontend-layout>