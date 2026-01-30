<!DOCTYPE html>
<html>
<head>
    <title>Test Variant Photos - {{ $product->title }}</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .product-box { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .color-section { margin-bottom: 30px; border-left: 4px solid #4e73df; padding-left: 15px; }
        .size-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin-top: 15px; }
        .size-card { border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #fafafa; }
        .size-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 4px; margin-bottom: 10px; }
        .no-photo { width: 100%; height: 150px; background: #e0e0e0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; margin-bottom: 10px; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #28a745; color: white; }
        .badge-danger { background: #dc3545; color: white; }
        h1 { color: #333; }
        h3 { color: #4e73df; margin-top: 0; }
        h4 { color: #666; margin: 10px 0; }
        .info { font-size: 14px; color: #666; margin: 5px 0; }
    </style>
</head>
<body>
    <div class="product-box">
        <h1>📦 {{ $product->title }}</h1>
        <p class="info"><strong>SKU:</strong> {{ $product->sku }}</p>
        <p class="info"><strong>Total Variants:</strong> {{ $product->variants->count() }}</p>
    </div>

    @foreach($product->variants->whereNull('parent_id') as $color)
        <div class="product-box color-section">
            <h3>🎨 Warna: {{ $color->name }}</h3>
            
            <!-- Color Photo -->
            @if($color->photo)
                <div style="margin-bottom: 20px;">
                    <strong>Foto Warna:</strong><br>
                    <img src="{{ asset('storage/' . $color->photo) }}" 
                         style="max-width: 300px; height: 200px; object-fit: cover; border-radius: 8px; margin-top: 10px;">
                    <p class="info">Path: {{ $color->photo }}</p>
                </div>
            @else
                <p class="info">❌ Color tidak punya foto</p>
            @endif

            <!-- Sizes -->
            @if($color->children->count() > 0)
                <h4>📏 Ukuran dalam warna ini:</h4>
                <div class="size-grid">
                    @foreach($color->children as $size)
                        <div class="size-card">
                            <h4 style="margin: 0 0 10px 0;">{{ $size->name }}</h4>
                            
                            <!-- 🆕 SIZE PHOTO - INI YANG PENTING! -->
                            @if($size->photo)
                                <img src="{{ asset('storage/' . $size->photo) }}" alt="{{ $size->name }}">
                                <span class="badge badge-success">✅ Punya Foto</span>
                                <p class="info" style="word-break: break-all; font-size: 11px;">
                                    <strong>Path:</strong> {{ $size->photo }}
                                </p>
                            @else
                                <div class="no-photo">
                                    <span>📷 Tidak ada foto</span>
                                </div>
                                <span class="badge badge-danger">❌ Tidak ada foto</span>
                            @endif

                            <div class="info" style="margin-top: 10px;">
                                <strong>Stok:</strong> {{ $size->stock_pusat }} pcs<br>
                                <strong>Harga:</strong> {{ $size->price ? 'Rp ' . number_format($size->price, 0, ',', '.') : '-' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="info">Warna ini tidak punya ukuran (stok langsung di color: {{ $color->stock_pusat }})</p>
            @endif
        </div>
    @endforeach

    <div class="product-box" style="background: #e7f3ff; border-left: 4px solid #2196F3;">
        <h4>📊 Summary:</h4>
        <ul>
            <li><strong>Total Colors:</strong> {{ $product->variants->whereNull('parent_id')->count() }}</li>
            <li><strong>Total Sizes:</strong> {{ $product->variants->whereNotNull('parent_id')->count() }}</li>
            <li><strong>Sizes dengan foto:</strong> {{ $product->variants->whereNotNull('parent_id')->whereNotNull('photo')->count() }}</li>
            <li><strong>Sizes tanpa foto:</strong> {{ $product->variants->whereNotNull('parent_id')->whereNull('photo')->count() }}</li>
        </ul>
    </div>

    <div style="margin-top: 20px; text-align: center;">
        <a href="{{ route('superadmin.products.edit', $product) }}" 
           style="background: #4e73df; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
            ✏️ Edit Product
        </a>
        <a href="{{ route('superadmin.products.index') }}" 
           style="background: #858796; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-left: 10px;">
            ⬅️ Back to Products
        </a>
    </div>
</body>
</html>
