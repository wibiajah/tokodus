<x-app-layout>
    <x-slot name="title">Produk 3D - Tokodus</x-slot>

    <div style="padding: 60px 20px; margin-top: 80px;">
        <div style="max-width: 1400px; margin: 0 auto;">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 60px;">
                <h1 style="font-size: 40px; font-weight: 700; margin-bottom: 12px;">Katalog Produk 3D</h1>
                <p style="font-size: 16px; color: #666;">
                    Lihat preview 3D interaktif dari semua produk packaging kami
                </p>
            </div>

            <!-- Products Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px;">
                @forelse($products as $product)
                    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.1); transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 32px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'">
                        <!-- Icon Area -->
                        <div style="background: linear-gradient(135deg, #f9ef21 0%, #f0e800 100%); padding: 50px 20px; text-align: center; font-size: 48px;">
                            📦
                        </div>

                        <!-- Content -->
                        <div style="padding: 20px;">
                            <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px; color: #333;">{{ $product->name }}</h3>
                            
                            <div style="background: #f5f5f5; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; color: #666;">
                                <p style="margin: 4px 0;"><strong>Ukuran:</strong></p>
                                <p style="margin: 4px 0;">{{ $product->width }}×{{ $product->height }}×{{ $product->depth }} cm</p>
                            </div>

                            <div style="margin-bottom: 16px;">
                                <p style="font-size: 13px; color: #999; margin-bottom: 4px;">Material: <strong>{{ ucfirst($product->material) }}</strong></p>
                                @if($product->price)
                                    <p style="font-size: 18px; font-weight: 700; color: #f9ef21;">Rp {{ number_format($product->price) }}</p>
                                @endif
                            </div>

                            <a href="{{ route('products-3d.show', $product) }}" style="display: block; background: linear-gradient(135deg, #f9ef21 0%, #f0e800 100%); color: #333; padding: 12px; border-radius: 6px; text-decoration: none; text-align: center; font-weight: 600; transition: all 0.3s ease;">
                                Lihat 3D Viewer →
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                        <p style="font-size: 16px; color: #999;">Belum ada produk 3D yang tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>