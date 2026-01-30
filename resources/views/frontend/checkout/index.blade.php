<x-frontend-layout>
    <x-slot:title>Checkout</x-slot:title>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/modalstore/store-selector-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/modalstore/cart_modal.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>

    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 100px auto 2rem;
            padding: 0 1rem;
        }

        /* Force show map preview */
        .map-preview {
            display: block !important;
        }

        .map-preview[style*="display: none"] {
            display: block !important;
        }

        .checkout-section {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f3f4f6;
            color: #111;
        }

        .address-info p {
            margin: 0.5rem 0;
            color: #4b5563;
        }

        .address-info strong {
            color: #111;
        }

        .alert {
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .alert-info {
            background: #dbeafe;
            border: 1px solid #3b82f6;
            color: #1e40af;
        }

        .store-group {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background: #fafbfc;
        }

        .store-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .store-name {
            font-weight: 700;
            color: #111;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
        }

        .store-name i {
            color: #1f4390;
            font-size: 1.125rem;
        }

        .store-subtotal {
            color: #6b7280;
            font-size: 0.938rem;
        }

        .store-subtotal strong {
            color: #111;
            font-size: 1.125rem;
        }

        .delivery-options {
            background: #f0f9ff;
            border: 2px solid #bfdbfe;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .delivery-radios {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .delivery-option {
            display: flex;
            align-items: flex-start;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .delivery-option:hover {
            border-color: #1f4390;
            box-shadow: 0 2px 8px rgba(31, 67, 144, 0.15);
        }

        .delivery-option input[type="radio"] {
            margin-right: 0.75rem;
            margin-top: 0.25rem;
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

        .delivery-option:has(input[type="radio"]:checked) {
            border-color: #1f4390;
            background: #f0f9ff;
            box-shadow: 0 0 0 3px rgba(31, 67, 144, 0.1);
        }

        .option-content {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            flex: 1;
        }

        .option-content i {
            font-size: 1.5rem;
            color: #6b7280;
            margin-top: 0.125rem;
        }

        .delivery-option:has(input[type="radio"]:checked) .option-content i {
            color: #1f4390;
        }

        .option-content div {
            flex: 1;
        }

        .option-content strong {
            display: block;
            color: #111;
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }

        .option-content small {
            display: block;
            color: #6b7280;
            font-size: 0.813rem;
            line-height: 1.4;
        }

        .delivery-info-readonly {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .delivery-info-readonly i {
            font-size: 1.5rem;
            color: #059669;
        }

        .delivery-info-readonly strong {
            display: block;
            color: #111;
            margin-bottom: 0.25rem;
        }

        .delivery-info-readonly small {
            color: #6b7280;
            font-size: 0.813rem;
        }

        .pickup-toko-selection {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .toko-address-display {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-list {
            margin-top: 1rem;
        }

        .product-row {
            display: flex;
            gap: 1rem;
            padding: 0.75rem;
            border-bottom: 1px solid #f3f4f6;
            background: white;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }

        .product-row:last-child {
            margin-bottom: 0;
        }

        .product-image {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-weight: 600;
            color: #111;
            margin-bottom: 0.35rem;
            font-size: 0.938rem;
        }

        .product-specs {
            font-size: 0.813rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .variant-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #f3f4f6;
            padding: 0.25rem 0.65rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #374151;
            margin-right: 0.35rem;
        }

        .variant-badge i {
            font-size: 0.7rem;
        }

        .product-price {
            text-align: right;
        }

        .price-value {
            font-weight: 700;
            color: #111;
            font-size: 1rem;
        }

        .payment-form {
            background: #fafbfc;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.938rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #1f4390;
            box-shadow: 0 0 0 3px rgba(31, 67, 144, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            font-size: 0.938rem;
        }

        .summary-row strong {
            color: #111;
        }

        .summary-total {
            border-top: 2px solid #f3f4f6;
            margin-top: 0.5rem;
            padding-top: 1rem;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .total-value {
            color: #e9078f;
            font-size: 1.5rem;
        }

        .btn-place-order {
            width: 100%;
            background: linear-gradient(135deg, #f9ef21 0%, #fbbf24 100%);
            color: #111;
            border: none;
            padding: 1rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-place-order:hover {
            background: linear-gradient(135deg, #e9078f 0%, #c7322f 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 7, 143, 0.4);
        }

        .btn-place-order:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .voucher-applied {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            border: 2px solid #10b981;
        }

        .voucher-applied i {
            font-size: 1.5rem;
            color: #10b981;
        }

        .voucher-applied strong {
            display: block;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .voucher-applied small {
            display: block;
            font-size: 0.813rem;
            opacity: 0.9;
        }

        .voucher-discount-badge {
            background: #10b981;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-left: auto;
        }

        @media (max-width: 768px) {
            .checkout-container {
                margin-top: 70px;
            }

            .product-row {
                flex-direction: column;
                gap: 0.75rem;
            }

            .product-price {
                text-align: left;
            }

            .product-image {
                width: 100%;
                height: auto;
                aspect-ratio: 1;
            }

            .delivery-radios {
                grid-template-columns: 1fr;
            }

            .store-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="checkout-container">
        <h2 class="mb-4" style="font-weight: 700; color: #111;">
            <i class="fas fa-shopping-cart"></i> Checkout
        </h2>

        <form id="checkoutForm">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <!-- Shipping Address -->
                    <div class="checkout-section">
                        <h5 class="section-title">
                            <i class="fas fa-map-marker-alt"></i> Informasi Pengiriman
                        </h5>
                        <div class="address-info">
                            <p><strong>{{ $customer->full_name }}</strong></p>
                            <p><i class="fas fa-phone"></i> {{ $customer->phone }}</p>
                            <p><i class="fas fa-home"></i> {{ $customer->address }}</p>
                            <p><i class="fas fa-city"></i>
                                {{ $customer->city }}{{ $customer->postal_code ? ', ' . $customer->postal_code : '' }}
                            </p>
                        </div>

                        <div class="payment-form">
                            <div class="form-group">
                                <label for="customer_phone">Nomor Telepon *</label>
                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone"
                                    value="{{ $customer->phone }}" required>
                            </div>
                            <div class="form-group" id="addressGroup">
                                <label for="shipping_address">Alamat Lengkap *</label>
                                <textarea class="form-control" id="shipping_address" name="shipping_address" required>{{ $customer->address }}</textarea>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="fas fa-info-circle"></i> Alamat ini akan digunakan untuk pengiriman (jika
                                    ada)
                                </small>
                            </div>
                            <div class="form-group mb-0">
                                <label for="notes">Catatan Pesanan (Opsional)</label>
                                <textarea class="form-control" id="notes" name="notes"
                                    placeholder="Contoh: Warna produk yang diinginkan, waktu pengambilan, dll."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Products by Store -->
                    <div class="checkout-section">
                        <h5 class="section-title">
                            <i class="fas fa-box"></i> Produk yang Dibeli ({{ $cartItems->sum('quantity') }} Item)
                        </h5>

                        @if ($groupedItems->count() > 1)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Pesanan dari {{ $groupedItems->count() }} toko akan dipisah menjadi
                                    {{ $groupedItems->count() }} order terpisah.</strong>
                                Setiap toko memiliki kebijakan pengiriman sendiri.
                            </div>
                        @endif

                        @foreach ($groupedItems as $tokoId => $items)
                            @php
                                $toko = $items->first()->toko;
                                $isCentralStore = (int) $tokoId === 999;
                                $tokoSubtotal = $items->sum('final_subtotal');
                            @endphp

                            <div class="store-group" data-toko-id="{{ $tokoId }}">
                                <div class="store-header">
                                    <div class="store-name">
                                        <i class="fas fa-store"></i>
                                        {{ $toko ? $toko->nama_toko : 'Central Store' }}
                                    </div>
                                    <div class="store-subtotal">
                                        Subtotal: <strong>Rp {{ number_format($tokoSubtotal, 0, ',', '.') }}</strong>
                                    </div>
                                </div>

                                {{-- ========================================
                                 CENTRAL STORE (ID = 999)
                                 ======================================== --}}
                                @if ($isCentralStore)
                                    <div class="delivery-options">
                                        <label
                                            style="display: block; font-weight: 600; margin-bottom: 0.75rem; color: #374151; font-size: 0.938rem;">
                                            <i class="fas fa-truck"></i> Pilih Metode Pengiriman:
                                        </label>
                                        <div class="delivery-radios">
                                            <label class="delivery-option">
                                                <input type="radio" name="delivery_method_{{ $tokoId }}"
                                                    value="delivery" checked
                                                    onchange="handleDeliveryChange({{ $tokoId }})">
                                                <span class="option-content">
                                                    <i class="fas fa-shipping-fast"></i>
                                                    <div>
                                                        <strong>Kirim ke Alamat</strong>
                                                        <small>Produk akan dikirim ke alamat Anda</small>
                                                    </div>
                                                </span>
                                            </label>
                                            <label class="delivery-option">
                                                <input type="radio" name="delivery_method_{{ $tokoId }}"
                                                    value="pickup"
                                                    onchange="handleDeliveryChange({{ $tokoId }})">
                                                <span class="option-content">
                                                    <i class="fas fa-store"></i>
                                                    <div>
                                                        <strong>Ambil di Toko</strong>
                                                        <small>Pilih toko untuk mengambil produk</small>
                                                    </div>
                                                </span>
                                            </label>
                                        </div>

                                        {{-- Shipping Type Selection --}}
                                        <div class="shipping-type-selection" id="shippingType_{{ $tokoId }}"
                                            style="margin-top: 1rem;">
                                            <label
                                                style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151; font-size: 0.875rem;">
                                                <i class="fas fa-shipping-fast"></i> Pilih Jenis Pengiriman:
                                            </label>
                                            <div class="delivery-radios">
                                                <label class="delivery-option">
                                                    <input type="radio" name="shipping_type_{{ $tokoId }}"
                                                        value="reguler" checked>
                                                    <span class="option-content">
                                                        <i class="fas fa-truck"></i>
                                                        <div>
                                                            <strong>Reguler</strong>
                                                            <small>Estimasi 2-3 hari</small>
                                                        </div>
                                                    </span>
                                                </label>
                                                <label class="delivery-option">
                                                    <input type="radio" name="shipping_type_{{ $tokoId }}"
                                                        value="instant">
                                                    <span class="option-content">
                                                        <i class="fas fa-bolt"></i>
                                                        <div>
                                                            <strong>Instant</strong>
                                                            <small>Estimasi 1-2 jam</small>
                                                        </div>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        {{-- Shipping Cost Display --}}
                                        <div class="shipping-cost-display" id="shippingCost_{{ $tokoId }}"
                                            style="display: none; margin-top: 1rem; padding: 1rem; background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 6px;">
                                            <div
                                                style="display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <strong style="color: #0284c7;">Biaya Pengiriman</strong>
                                                    <div
                                                        style="font-size: 0.813rem; color: #64748b; margin-top: 0.25rem;">
                                                        Jarak: <span id="distance_{{ $tokoId }}">-</span> km
                                                    </div>
                                                </div>
                                                <div style="font-size: 1.25rem; font-weight: 700; color: #0284c7;"
                                                    id="cost_{{ $tokoId }}">
                                                    Rp 0
                                                </div>
                                            </div>
                                            <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #64748b;">
                                                <i class="fas fa-clock"></i> Estimasi: <span
                                                    id="estimate_{{ $tokoId }}">-</span>
                                            </div>
                                        </div>

                                        {{-- ✅ MAP PREVIEW - SIMPLIFIED STRUCTURE --}}
                                        <div class="map-preview" id="mapPreview_{{ $tokoId }}"
                                            style="display: none; margin-top: 1rem;">
                                            <div id="map_{{ $tokoId }}"
                                                style="height: 250px; width: 100%; border-radius: 8px; border: 2px solid #bae6fd; z-index: 1;">
                                            </div>
                                            <div
                                                style="margin-top: 0.5rem; text-align: center; font-size: 0.75rem; color: #64748b;">
                                                <i class="fas fa-map-marked-alt"></i> Rute dari toko ke alamat Anda
                                            </div>
                                        </div>

                                        {{-- Pickup Toko Selection --}}
                                        <div class="pickup-toko-selection" id="pickupSelection_{{ $tokoId }}"
                                            style="display: none;">
                                            <label
                                                style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151; font-size: 0.875rem;">
                                                Pilih Toko untuk Pengambilan: *
                                            </label>

                                            <input type="hidden" name="pickup_toko_{{ $tokoId }}"
                                                id="pickupToko_{{ $tokoId }}" value="">

                                            <button type="button" class="btn-select-store-789"
                                                onclick="openStoreModalCheckout({{ $tokoId }})">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" width="20" height="20">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                    <circle cx="12" cy="10" r="3"></circle>
                                                </svg>
                                                <span id="selectedStoreName_{{ $tokoId }}">Pilih Toko untuk
                                                    Pengambilan</span>
                                            </button>

                                            <div class="toko-address-display" id="tokoAddress_{{ $tokoId }}"
                                                style="display: none;">
                                                <div
                                                    style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 1rem; margin-top: 0.75rem;">
                                                    <div style="font-weight: 600; color: #111; margin-bottom: 0.5rem;">
                                                        <i class="fas fa-map-marker-alt"></i> Alamat Toko:
                                                    </div>
                                                    <div style="color: #6b7280; font-size: 0.875rem;"
                                                        id="tokoAddressText_{{ $tokoId }}"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ========================================
                                 REGULAR TOKO (ID != 999)
                                 ======================================== --}}
                                @else
                                    <div class="delivery-options">
                                        <label
                                            style="display: block; font-weight: 600; margin-bottom: 0.75rem; color: #374151; font-size: 0.938rem;">
                                            <i class="fas fa-truck"></i> Pengiriman ke Alamat
                                        </label>

                                        {{-- Shipping Type Selection --}}
                                        <div class="shipping-type-selection" id="shippingType_{{ $tokoId }}">
                                            <label
                                                style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151; font-size: 0.875rem;">
                                                <i class="fas fa-shipping-fast"></i> Pilih Jenis Pengiriman:
                                            </label>
                                            <div class="delivery-radios">
                                                <label class="delivery-option">
                                                    <input type="radio" name="shipping_type_{{ $tokoId }}"
                                                        value="reguler" checked>
                                                    <span class="option-content">
                                                        <i class="fas fa-truck"></i>
                                                        <div>
                                                            <strong>Reguler</strong>
                                                            <small>Estimasi 2-3 hari</small>
                                                        </div>
                                                    </span>
                                                </label>
                                                <label class="delivery-option">
                                                    <input type="radio" name="shipping_type_{{ $tokoId }}"
                                                        value="instant">
                                                    <span class="option-content">
                                                        <i class="fas fa-bolt"></i>
                                                        <div>
                                                            <strong>Instant</strong>
                                                            <small>Estimasi 1-2 jam</small>
                                                        </div>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        {{-- Shipping Cost Display --}}
                                        <div class="shipping-cost-display" id="shippingCost_{{ $tokoId }}"
                                            style="display: none; margin-top: 1rem; padding: 1rem; background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 6px;">
                                            <div
                                                style="display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <strong style="color: #0284c7;">Biaya Pengiriman</strong>
                                                    <div
                                                        style="font-size: 0.813rem; color: #64748b; margin-top: 0.25rem;">
                                                        Jarak: <span id="distance_{{ $tokoId }}">-</span> km
                                                    </div>
                                                </div>
                                                <div style="font-size: 1.25rem; font-weight: 700; color: #0284c7;"
                                                    id="cost_{{ $tokoId }}">
                                                    Rp 0
                                                </div>
                                            </div>
                                            <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #64748b;">
                                                <i class="fas fa-clock"></i> Estimasi: <span
                                                    id="estimate_{{ $tokoId }}">-</span>
                                            </div>
                                        </div>

                                        {{-- ✅ MAP PREVIEW UNTUK TOKO BIASA --}}
                                        {{-- ✅ MAP PREVIEW - SIMPLIFIED STRUCTURE --}}
                                        <div class="map-preview" id="mapPreview_{{ $tokoId }}"
                                            style="display: none; margin-top: 1rem;">
                                            <div id="map_{{ $tokoId }}"
                                                style="height: 250px; width: 100%; border-radius: 8px; border: 2px solid #bae6fd; z-index: 1;">
                                            </div>
                                            <div
                                                style="margin-top: 0.5rem; text-align: center; font-size: 0.75rem; color: #64748b;">
                                                <i class="fas fa-map-marked-alt"></i> Rute dari toko ke alamat Anda
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="delivery_method_{{ $tokoId }}"
                                        value="delivery">
                                @endif

                                {{-- Product List --}}
                                <div class="product-list">
                                    @foreach ($items as $item)
                                        <div class="product-row">
                                            <img src="{{ $item->variant_photo }}" alt="{{ $item->product->title }}"
                                                class="product-image" loading="lazy"
                                                onerror="this.onerror=null; this.src='{{ asset('frontend/assets/img/placeholder-product.png') }}'">

                                            <div class="product-info">
                                                <div class="product-name">{{ $item->product->title }}</div>

                                                <div class="mb-2">
                                                    @if ($item->variant_color !== '-')
                                                        <span class="variant-badge">
                                                            <i class="fas fa-palette"></i>
                                                            {{ $item->variant_color }}
                                                        </span>
                                                    @endif

                                                    @if ($item->variant_size !== '-')
                                                        <span class="variant-badge">
                                                            <i class="fas fa-ruler"></i>
                                                            {{ $item->variant_size }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="product-specs">
                                                    <strong>Qty:</strong> {{ $item->quantity }}
                                                    @ Rp {{ number_format($item->final_price, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="product-price">
                                                <div class="price-value">
                                                    Rp {{ number_format($item->final_subtotal, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="checkout-section">
                        <h5 class="section-title">
                            <i class="fas fa-receipt"></i> Ringkasan Pembayaran
                        </h5>

                        @if ($appliedVoucher)
                            <div class="voucher-applied">
                                <i class="fas fa-check-circle"></i>
                                <div style="flex: 1;">
                                    <strong>{{ $appliedVoucher->name }}</strong>
                                    <small>Kode: {{ $appliedVoucher->code }}</small>
                                </div>
                                <span class="voucher-discount-badge">
                                    -Rp {{ number_format($discount, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif

                        <div class="summary-row">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} Produk)</span>
                            <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                        </div>

                        @if ($discount > 0)
                            <div class="summary-row" style="color: #10b981;">
                                <span><i class="fas fa-tag"></i> Diskon Voucher</span>
                                <strong>-Rp {{ number_format($discount, 0, ',', '.') }}</strong>
                            </div>
                        @endif

                        <div class="summary-row">
                            <span><i class="fas fa-shipping-fast"></i> Biaya Pengiriman</span>
                            <strong id="totalShippingCost" style="color: #0284c7;">Rp 0</strong>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Total Pembayaran</span>
                            <span class="total-value" id="grandTotal">Rp
                                {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="btn-place-order" id="btnPlaceOrder">
                            <i class="fas fa-check-circle"></i> Bayar Sekarang
                        </button>

                        <p class="text-center mt-3 text-muted" style="font-size: 0.813rem;">
                            <i class="fas fa-shield-alt"></i>
                            Pembayaran aman & terpercaya
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Store Selector Modal (sama persis dengan product detail) --}}
    {{-- Store Selector Modal --}}
    <div class="store-modal-overlay-789" id="storeModalOverlayCheckout-789">
        <div class="store-modal-789" id="storeModalCheckout-789">

            {{-- Header --}}
            <div class="store-modal-header-789">
                <h2>Choose Store Location</h2>
                <button type="button" class="store-modal-close-789" id="closeStoreModalCheckout-789">×</button>
            </div>

            {{-- Search --}}
            <div class="store-modal-search-789">
                <input type="text" class="store-search-input-789" id="storeSearchInputCheckout-789"
                    placeholder="Search store by name...">
            </div>

            {{-- Body with Filters --}}
            <div class="store-modal-body-789">

                {{-- Filter Section --}}
                <div class="store-filter-section-789">
                    <label class="store-filter-label-789">Filter by:</label>
                    <div class="store-filter-buttons-789">
                        {{-- Nearest Filter --}}
                        <button type="button" class="filter-btn-789" id="filterNearestCheckout-789"
                            onclick="filterByNearestCheckout()"
                            data-customer-postal="{{ $customer->postal_code ?? '' }}"
                            {{ !($customer->postal_code ?? false) ? 'disabled' : '' }}>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            {{ $customer->postal_code ?? false ? 'Nearest' : 'Nearest (Login)' }}
                        </button>

                        {{-- Reset Filter --}}
                        <button type="button" class="filter-btn-789" onclick="resetFilterCheckout()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="1 4 1 10 7 10"></polyline>
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>

                {{-- Store List --}}
                <div id="storeListContainerCheckout-789"></div>

            </div>

            {{-- Footer --}}
            <div class="store-modal-footer-789">
                <button type="button" class="store-modal-btn-789 store-modal-btn-cancel-789"
                    id="cancelStoreBtnCheckout-789">
                    Cancel
                </button>
                <button type="button" class="store-modal-btn-789 store-modal-btn-confirm-789"
                    id="confirmStoreBtnCheckout-789" disabled>
                    Confirm Selection
                </button>
            </div>

        </div>
    </div>

    <script>
        (function() {
            const form = document.getElementById('checkoutForm');
            const btnPlaceOrder = document.getElementById('btnPlaceOrder');
            const addressGroup = document.getElementById('addressGroup');
            const shippingAddress = document.getElementById('shipping_address');

            const urlParams = new URLSearchParams(window.location.search);
            const cartIds = urlParams.get('items') ? urlParams.get('items').split(',') : [];
            const voucherCode = urlParams.get('voucher') || '';

            if (!form) {
                console.error('Form not found');
                return;
            }

            window.handleDeliveryChange = function(tokoId) {
                const selectedMethod = document.querySelector(`input[name="delivery_method_${tokoId}"]:checked`)
                    ?.value;
                const pickupSelection = document.getElementById(`pickupSelection_${tokoId}`);
                const shippingTypeDiv = document.getElementById(`shippingType_${tokoId}`);
                const tokoAddressDiv = document.getElementById(`tokoAddress_${tokoId}`);

                if (selectedMethod === 'pickup') {
                    if (pickupSelection) pickupSelection.style.display = 'block';
                    if (shippingTypeDiv) shippingTypeDiv.style.display = 'none';

                    const anyDelivery = Array.from(document.querySelectorAll(
                            'input[name^="delivery_method_"]:checked'))
                        .some(radio => radio.value === 'delivery');

                    if (!anyDelivery) {
                        if (addressGroup) addressGroup.style.display = 'none';
                        if (shippingAddress) shippingAddress.required = false;
                    }
                } else {
                    if (pickupSelection) pickupSelection.style.display = 'none';
                    if (shippingTypeDiv) shippingTypeDiv.style.display = 'block';
                    if (tokoAddressDiv) tokoAddressDiv.style.display = 'none';

                    if (addressGroup) addressGroup.style.display = 'block';
                    if (shippingAddress) shippingAddress.required = true;
                }
            };

            window.showTokoAddress = function(tokoId) {
                const select = document.getElementById(`pickupToko_${tokoId}`);
                const addressDiv = document.getElementById(`tokoAddress_${tokoId}`);
                const addressText = document.getElementById(`tokoAddressText_${tokoId}`);

                if (!select || !addressDiv || !addressText) return;

                const selectedOption = select.options[select.selectedIndex];

                if (selectedOption.value) {
                    const address = selectedOption.dataset.address;
                    const city = selectedOption.dataset.city;
                    const phone = selectedOption.dataset.phone;

                    addressText.innerHTML = `
                        <div style="margin-bottom: 0.5rem;">${address}</div>
                        <div style="margin-bottom: 0.5rem;"><i class="fas fa-city"></i> ${city}</div>
                        <div><i class="fas fa-phone"></i> ${phone}</div>
                    `;
                    addressDiv.style.display = 'block';
                } else {
                    addressDiv.style.display = 'none';
                }
            };

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!btnPlaceOrder) return;

                const phone = document.getElementById('customer_phone').value.trim();

                if (!phone) {
                    alert('Nomor telepon harus diisi!');
                    return;
                }

                const deliveryMethods = {};

                const shippingTypes = {};

                document.querySelectorAll('input[type="radio"][name^="shipping_type_"]:checked').forEach(
                    radio => {
                        const tokoId = radio.name.replace('shipping_type_', '');
                        shippingTypes[tokoId] = radio.value;
                    });

                const pickupTokoIds = {};

                const radioButtons = document.querySelectorAll(
                    'input[type="radio"][name^="delivery_method_"]:checked');
                radioButtons.forEach(radio => {
                    const tokoId = radio.name.replace('delivery_method_', '');
                    deliveryMethods[tokoId] = radio.value;

                    if (radio.value === 'pickup') {
                        const pickupSelect = document.getElementById(`pickupToko_${tokoId}`);
                        if (pickupSelect) {
                            const selectedTokoId = pickupSelect.value;
                            if (!selectedTokoId) {
                                alert('Pilih toko untuk pengambilan produk!');
                                pickupSelect.focus();
                                throw new Error('Toko pickup tidak dipilih');
                            }
                            pickupTokoIds[tokoId] = selectedTokoId;
                        }
                    }
                });

                const hasDelivery = Object.values(deliveryMethods).includes('delivery');
                const address = document.getElementById('shipping_address').value.trim();

                if (hasDelivery && !address) {
                    alert('Alamat pengiriman harus diisi untuk metode pengiriman!');
                    return;
                }

                if (cartIds.length === 0) {
                    alert('Tidak ada item untuk checkout!');
                    return;
                }

                btnPlaceOrder.disabled = true;
                btnPlaceOrder.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

                const formData = {
                    cart_ids: cartIds,
                    customer_phone: phone,
                    shipping_address: address || null,
                    notes: document.getElementById('notes').value.trim(),
                    voucher_code: voucherCode,
                    delivery_methods: deliveryMethods,
                    shipping_types: shippingTypes,
                    pickup_toko_ids: pickupTokoIds
                };

                fetch('/customer/checkout/process', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (typeof toastr !== 'undefined') {
                                toastr.success(data.message);
                            } else {
                                alert(data.message);
                            }

                            setTimeout(() => {
                                window.location.href = data.data.redirect_url;
                            }, 1500);
                        } else {
                            throw new Error(data.message || 'Checkout gagal');
                        }
                    })
                    .catch(error => {
                        console.error('Checkout error:', error);

                        if (typeof toastr !== 'undefined') {
                            toastr.error(error.message || 'Terjadi kesalahan saat checkout');
                        } else {
                            alert(error.message || 'Terjadi kesalahan saat checkout');
                        }

                        btnPlaceOrder.disabled = false;
                        btnPlaceOrder.innerHTML = '<i class="fas fa-check-circle"></i> Bayar Sekarang';
                    });
            });
        })();
    </script>

    <script>
        // Store Modal for Checkout
        let storesDataCheckout = [];
        let selectedStoreCheckout = null;
        let filteredStoresCheckout = [];
        let currentFilterCheckout = null;
        let customerPostalCodeCheckout = '{{ $customer->postal_code ?? '' }}';
        let currentTokoGroupId = null;

        // 🔥 FIX: Initialize stores data (processed dari controller)
        storesDataCheckout = {!! json_encode(
            $availableTokos->map(function ($toko) use ($customer) {
                    $location = \App\Helpers\LocationHelper::getLocationFromPostalCode($toko->postal_code);
                    $distance = null;
        
                    if ($customer->postal_code && $toko->postal_code) {
                        $rawDistance = \App\Helpers\LocationHelper::calculatePostalCodeDistance(
                            $customer->postal_code,
                            $toko->postal_code,
                        );
                        $distance = \App\Helpers\LocationHelper::estimateDistanceCategory($rawDistance);
                        $distance['raw_distance'] = $rawDistance;
                    }
        
                    return [
                        'id' => $toko->id,
                        'nama_toko' => $toko->nama_toko,
                        'postal_code' => $toko->postal_code,
                        'alamat' => $toko->alamat ?? '',
                        'kota' => $toko->kota ?? '',
                        'telepon' => $toko->telepon ?? '',
                        'location' => $location,
                        'distance' => $distance,
                    ];
                })->values(),
        ) !!};

        filteredStoresCheckout = storesDataCheckout;

        // Open modal for specific toko group
        function openStoreModalCheckout(tokoId) {
            currentTokoGroupId = tokoId;
            const modal = document.getElementById('storeModalCheckout-789');
            const overlay = document.getElementById('storeModalOverlayCheckout-789');

            if (!modal || !overlay) return;

            filteredStoresCheckout = storesDataCheckout;
            currentFilterCheckout = null;
            updateFilterButtonsCheckout();
            renderStoreListCheckout(filteredStoresCheckout);

            overlay.classList.add('active');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Close modal
        function closeStoreModalCheckout() {
            const modal = document.getElementById('storeModalCheckout-789');
            const overlay = document.getElementById('storeModalOverlayCheckout-789');

            if (overlay) overlay.classList.remove('active');
            if (modal) modal.classList.remove('active');
            document.body.style.overflow = '';

            const searchInput = document.getElementById('storeSearchInputCheckout-789');
            if (searchInput) searchInput.value = '';
        }

        // Render store list
        function renderStoreListCheckout(stores) {
            const container = document.getElementById('storeListContainerCheckout-789');
            if (!container) return;

            container.innerHTML = '';

            if (stores.length === 0) {
                container.innerHTML = '<div class="store-empty-state-789"><p>No stores found</p></div>';
                return;
            }

            const listDiv = document.createElement('div');
            listDiv.className = 'store-list-789';

            stores.forEach((store, index) => {
                const storeDiv = document.createElement('div');
                storeDiv.className =
                    `store-item-789 ${selectedStoreCheckout?.id === store.id ? 'selected-789' : ''}`;
                storeDiv.dataset.storeId = store.id;
                storeDiv.onclick = () => selectStoreCheckout(store.id);

                let locationHTML = '';
                if (store.location && store.location.full_address) {
                    locationHTML = `
                <div class="store-location-info-789">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span>${store.location.full_address}</span>
                </div>
            `;
                } else if (store.postal_code) {
                    locationHTML = `
                <div class="store-location-info-789">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span>Kodepos: ${store.postal_code}</span>
                </div>
            `;
                }

                let distanceBadge = '';
                if (store.distance && store.distance.estimate) {
                    const badgeClass = `distance-badge-${store.distance.category}-789`;
                    distanceBadge =
                        `<div class="store-distance-badge-789 ${badgeClass}"><span>${store.distance.estimate}</span></div>`;
                }

                const numberBadge = currentFilterCheckout === 'nearest' ?
                    `<div class="store-rank-badge-789">#${index + 1}</div>` : '';

                storeDiv.innerHTML = `
            ${numberBadge}
            <div class="store-item-content-789">
                <div class="store-item-name-789">${store.nama_toko}</div>
                ${locationHTML}
            </div>
            ${distanceBadge}
        `;

                listDiv.appendChild(storeDiv);
            });

            container.appendChild(listDiv);
        }

        // Select store
        function selectStoreCheckout(storeId) {
            const store = storesDataCheckout.find(s => s.id === storeId);
            if (!store) return;

            selectedStoreCheckout = store;

            document.querySelectorAll('#storeListContainerCheckout-789 .store-item-789').forEach(item => {
                item.classList.remove('selected-789');
            });

            const selectedItem = document.querySelector(`#storeListContainerCheckout-789 [data-store-id="${storeId}"]`);
            if (selectedItem) selectedItem.classList.add('selected-789');

            const confirmBtn = document.getElementById('confirmStoreBtnCheckout-789');
            if (confirmBtn) confirmBtn.disabled = false;
        }

        // Confirm selection
        function confirmStoreSelectionCheckout() {
            if (!selectedStoreCheckout || !currentTokoGroupId) return;

            // Update hidden input
            const hiddenInput = document.getElementById(`pickupToko_${currentTokoGroupId}`);
            if (hiddenInput) hiddenInput.value = selectedStoreCheckout.id;

            // Update button text
            const buttonText = document.getElementById(`selectedStoreName_${currentTokoGroupId}`);
            if (buttonText) buttonText.textContent = selectedStoreCheckout.nama_toko;

            // Show address
            const addressDiv = document.getElementById(`tokoAddress_${currentTokoGroupId}`);
            const addressText = document.getElementById(`tokoAddressText_${currentTokoGroupId}`);

            if (addressText && addressDiv) {
                addressText.innerHTML = `
            <div style="margin-bottom: 0.5rem;">${selectedStoreCheckout.alamat}</div>
            <div style="margin-bottom: 0.5rem;"><i class="fas fa-city"></i> ${selectedStoreCheckout.kota}</div>
            <div><i class="fas fa-phone"></i> ${selectedStoreCheckout.telepon}</div>
        `;
                addressDiv.style.display = 'block';
            }

            closeStoreModalCheckout();
        }

        // Filter by nearest
        function filterByNearestCheckout() {
            if (!customerPostalCodeCheckout) {
                alert('Please login to use location filter');
                return;
            }

            currentFilterCheckout = 'nearest';

            filteredStoresCheckout = [...storesDataCheckout].sort((a, b) => {
                const hasPostalA = a.postal_code && a.postal_code.trim() !== '';
                const hasPostalB = b.postal_code && b.postal_code.trim() !== '';

                if (!hasPostalA && !hasPostalB) return 0;
                if (!hasPostalA) return 1;
                if (!hasPostalB) return -1;

                const distA = a.distance?.raw_distance ?? 999999;
                const distB = b.distance?.raw_distance ?? 999999;

                return distA - distB;
            });

            updateFilterButtonsCheckout();
            renderStoreListCheckout(filteredStoresCheckout);
        }

        // Reset filter
        function resetFilterCheckout() {
            currentFilterCheckout = null;
            filteredStoresCheckout = [...storesDataCheckout];
            updateFilterButtonsCheckout();
            renderStoreListCheckout(filteredStoresCheckout);
        }

        // Update filter buttons
        function updateFilterButtonsCheckout() {
            const nearestBtn = document.getElementById('filterNearestCheckout-789');
            if (nearestBtn) {
                if (currentFilterCheckout === 'nearest') {
                    nearestBtn.classList.add('active');
                } else {
                    nearestBtn.classList.remove('active');
                }
            }
        }

        // Search stores
        function searchStoresCheckout(query) {
            const searchTerm = query.toLowerCase().trim();

            if (searchTerm === '') {
                if (currentFilterCheckout === 'nearest') {
                    filterByNearestCheckout();
                } else {
                    filteredStoresCheckout = [...storesDataCheckout];
                    renderStoreListCheckout(filteredStoresCheckout);
                }
            } else {
                const searchResults = storesDataCheckout.filter(store =>
                    store.nama_toko.toLowerCase().includes(searchTerm)
                );

                if (currentFilterCheckout === 'nearest') {
                    filteredStoresCheckout = searchResults.sort((a, b) => {
                        const distA = a.distance?.raw_distance ?? 999999;
                        const distB = b.distance?.raw_distance ?? 999999;
                        return distA - distB;
                    });
                } else {
                    filteredStoresCheckout = searchResults;
                }

                renderStoreListCheckout(filteredStoresCheckout);
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Close button
            const closeBtn = document.getElementById('closeStoreModalCheckout-789');
            if (closeBtn) closeBtn.addEventListener('click', closeStoreModalCheckout);

            // Cancel button
            const cancelBtn = document.getElementById('cancelStoreBtnCheckout-789');
            if (cancelBtn) cancelBtn.addEventListener('click', closeStoreModalCheckout);

            // Confirm button
            const confirmBtn = document.getElementById('confirmStoreBtnCheckout-789');
            if (confirmBtn) confirmBtn.addEventListener('click', confirmStoreSelectionCheckout);

            // Search input
            const searchInput = document.getElementById('storeSearchInputCheckout-789');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        searchStoresCheckout(e.target.value);
                    }, 300);
                });
            }

            // Overlay click
            const overlay = document.getElementById('storeModalOverlayCheckout-789');
            if (overlay) {
                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) closeStoreModalCheckout();
                });
            }
        });
    </script>

    <script>
        const shippingCosts = {};
        const subtotal = {{ $subtotal }};
        const discount = {{ $discount }};

        // Hitung ongkir saat shipping type berubah
        // Hitung ongkir saat shipping type berubah
        document.querySelectorAll('input[name^="shipping_type_"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const tokoId = this.name.replace('shipping_type_', '');
                const shippingType = this.value;

                // ✅ DEBOUNCE: Cegah multiple calls
                if (window[`shippingTimeout_${tokoId}`]) {
                    clearTimeout(window[`shippingTimeout_${tokoId}`]);
                }

                window[`shippingTimeout_${tokoId}`] = setTimeout(() => {
                    calculateShippingCost(tokoId, shippingType);
                }, 100);
            });
        });

        function calculateShippingCost(tokoId, shippingType) {
            const costDiv = document.getElementById(`shippingCost_${tokoId}`);
            if (!costDiv) return;

            costDiv.style.display = 'block';

            // ✅ SHOW LOADING STATE
            document.getElementById(`cost_${tokoId}`).innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghitung...';
            document.getElementById(`distance_${tokoId}`).textContent = '...';
            document.getElementById(`estimate_${tokoId}`).textContent = '...';

            fetch('/customer/checkout/calculate-shipping', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        toko_id: tokoId,
                        shipping_type: shippingType
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        shippingCosts[tokoId] = data.data.cost;
                        document.getElementById(`distance_${tokoId}`).textContent = data.data.distance;
                        document.getElementById(`cost_${tokoId}`).textContent = data.data.formatted_cost;
                        document.getElementById(`estimate_${tokoId}`).textContent = data.data.estimate_time;
                        showMapPreview(tokoId, data.data.customer_coords, data.data.toko_coords);
                        updateTotalPayment();
                    } else {
                        alert(data.message);
                        costDiv.style.display = 'none';
                        shippingCosts[tokoId] = 0;
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById(`cost_${tokoId}`).textContent = 'Error';
                    costDiv.style.display = 'none';
                    shippingCosts[tokoId] = 0;
                });
        }

        function updateTotalPayment() {
            const totalShipping = Object.values(shippingCosts).reduce((sum, cost) => sum + cost, 0);
            const grandTotal = subtotal - discount + totalShipping;

            document.getElementById('totalShippingCost').textContent = 'Rp ' + totalShipping.toLocaleString('id-ID');
            document.getElementById('grandTotal').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        // Auto calculate saat page load (untuk delivery toko)
        // Auto calculate saat page load (untuk delivery toko)
        // Auto calculate saat page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== AUTO CALCULATE SHIPPING ===');

            setTimeout(() => {
                if (typeof L === 'undefined') {
                    console.error('❌ Leaflet not loaded');
                    return;
                }

                console.log('✅ Leaflet ready, starting auto calculation...');

                // ✅ AMBIL SEMUA RADIO SHIPPING TYPE YANG CHECKED
                document.querySelectorAll('input[name^="shipping_type_"]:checked').forEach(radio => {
                    const tokoId = radio.name.replace('shipping_type_', '');
                    const shippingType = radio.value;

                    console.log('🔍 Found shipping type for toko:', tokoId, '=', shippingType);

                    // ✅ CEK DELIVERY METHOD (UNTUK CENTRAL STORE)
                    const deliveryMethodRadio = document.querySelector(
                        `input[name="delivery_method_${tokoId}"]:checked`);

                    // ✅ KALAU ADA DELIVERY METHOD (CENTRAL STORE)
                    if (deliveryMethodRadio) {
                        if (deliveryMethodRadio.value === 'delivery') {
                            console.log('📦 Central Store - Calculating...');
                            calculateShippingCost(tokoId, shippingType);
                        } else {
                            console.log('⏩ Central Store - Pickup mode, skip');
                        }
                    }
                    // ✅ KALAU GA ADA DELIVERY METHOD (REGULAR TOKO) - LANGSUNG CALCULATE
                    else {
                        const hiddenDeliveryInput = document.querySelector(
                            `input[name="delivery_method_${tokoId}"][value="delivery"]`);

                        if (hiddenDeliveryInput) {
                            console.log('📦 Regular Toko - Auto calculating...');
                            calculateShippingCost(tokoId, shippingType);
                        } else {
                            console.log('⚠️ No delivery method found for toko:', tokoId);
                        }
                    }
                });

            }, 300);
        });

        function showMapPreview(tokoId, customerCoords, tokoCoords) {
            console.log('MAP PREVIEW - Toko:', tokoId);

            const custLat = parseFloat(customerCoords.lat);
            const custLng = parseFloat(customerCoords.lng);
            const tokoLat = parseFloat(tokoCoords.lat);
            const tokoLng = parseFloat(tokoCoords.lng);

            const mapPreview = document.getElementById(`mapPreview_${tokoId}`);
            const mapContainer = document.getElementById(`map_${tokoId}`);

            if (!mapPreview || !mapContainer || typeof L === 'undefined') return;

            mapPreview.style.display = 'block';

            // ✅ CEK KALAU MAP UDAH DIBUAT
            if (window[`leafletMap_${tokoId}`]) {
                console.log('✅ Map already exists, skipping...');
                return;
            }

            // ✅ LAZY LOAD - CREATE MAP HANYA KALAU VISIBLE DI VIEWPORT
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !window[`leafletMap_${tokoId}`]) {
                        console.log('📍 Map entering viewport, creating...');
                        createMap(tokoId, custLat, custLng, tokoLat, tokoLng, mapContainer);
                        observer.disconnect(); // Stop observing setelah map dibuat
                    }
                });
            }, {
                threshold: 0.1
            }); // Trigger kalau 10% map visible

            observer.observe(mapContainer);
        }

        // ✅ FUNGSI TERPISAH UNTUK CREATE MAP
        function createMap(tokoId, custLat, custLng, tokoLat, tokoLng, mapContainer) {
            try {
                const map = L.map(mapContainer).setView([custLat, custLng], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OSM'
                }).addTo(map);

                L.marker([custLat, custLng]).addTo(map).bindPopup('Anda');
                L.marker([tokoLat, tokoLng]).addTo(map).bindPopup('Toko');

                L.polyline([
                    [custLat, custLng],
                    [tokoLat, tokoLng]
                ], {
                    color: '#0284c7',
                    weight: 3
                }).addTo(map);

                map.fitBounds([
                    [custLat, custLng],
                    [tokoLat, tokoLng]
                ], {
                    padding: [50, 50]
                });

                window[`leafletMap_${tokoId}`] = map;

                setTimeout(() => map.invalidateSize(), 200);

                console.log('✅ MAP CREATED!');
            } catch (err) {
                console.error('Map error:', err);
            }
        }
    </script>


</x-frontend-layout>
