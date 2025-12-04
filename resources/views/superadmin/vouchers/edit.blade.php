<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Voucher: ') . $voucher->code }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('superadmin.vouchers.update', $voucher) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Info Voucher --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">🎟️ Informasi Voucher</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Kode Voucher <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="code" value="{{ old('code', $voucher->code) }}" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 uppercase">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Voucher <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $voucher->name) }}" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Deskripsi
                                    </label>
                                    <textarea name="description" rows="3"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $voucher->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Diskon --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">💰 Pengaturan Diskon</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipe Diskon <span class="text-red-500">*</span>
                                    </label>
                                    <select name="discount_type" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="fixed" {{ old('discount_type', $voucher->discount_type) === 'fixed' ? 'selected' : '' }}>
                                            Fixed Amount (Rp)
                                        </option>
                                        <option value="percentage" {{ old('discount_type', $voucher->discount_type) === 'percentage' ? 'selected' : '' }}>
                                            Percentage (%)
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nilai Diskon <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="discount_value" value="{{ old('discount_value', $voucher->discount_value) }}" required min="0" step="0.01"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                        </div>

                        {{-- Periode --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">📅 Periode Berlaku</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="start_date" value="{{ old('start_date', $voucher->start_date->format('Y-m-d')) }}" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Berakhir <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="end_date" value="{{ old('end_date', $voucher->end_date->format('Y-m-d')) }}" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Batas Penggunaan (opsional)
                                    </label>
                                    <input type="number" name="usage_limit" value="{{ old('usage_limit', $voucher->usage_limit) }}" min="1"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <p class="text-xs text-gray-500 mt-1">Sudah digunakan: {{ $voucher->usage_count }} kali</p>
                                </div>

                                <div>
                                    <label class="flex items-center mt-6">
                                        <input type="checkbox" name="is_active" value="1" 
                                            {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                        <span class="ml-2 text-sm text-gray-700">Voucher Aktif</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Produk --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">📦 Produk yang Termasuk Diskon</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-h-96 overflow-y-auto border rounded-lg p-4">
                                @foreach($products as $product)
                                    <label class="flex items-start space-x-3 p-3 border rounded hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="products[]" value="{{ $product->id }}"
                                            {{ $voucher->products->contains($product->id) ? 'checked' : '' }}
                                            class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm">
                                        <div class="flex-1">
                                            @if($product->photos && count($product->photos) > 0)
                                                <img src="{{ Storage::url($product->photos[0]) }}" 
                                                    alt="{{ $product->title }}"
                                                    class="w-full h-20 object-cover rounded mb-2">
                                            @endif
                                            <div class="text-sm font-medium text-gray-900">{{ $product->title }}</div>
                                            <div class="text-xs text-gray-500">{{ $product->sku }}</div>
                                            <div class="text-xs font-semibold text-indigo-600 mt-1">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Quantity Discounts --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">📊 Diskon Berdasarkan Kuantitas</h3>
                            
                            <div id="quantity-discounts-container" class="space-y-3">
                                @forelse($voucher->quantityDiscounts as $index => $qd)
                                    <div class="quantity-discount-item border rounded-lg p-4 bg-gray-50">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-xs text-gray-600 mb-1">Min Kuantitas</label>
                                                <input type="number" name="quantity_discounts[{{ $index }}][min_quantity]" 
                                                    value="{{ $qd->min_quantity }}" min="1"
                                                    class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-600 mb-1">Diskon per Unit (Rp)</label>
                                                <input type="number" name="quantity_discounts[{{ $index }}][discount_amount]" 
                                                    value="{{ $qd->discount_amount }}" min="0" step="0.01"
                                                    class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" onclick="removeQuantityDiscount(this)"
                                                    class="w-full px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm">
                                                    🗑️ Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="quantity-discount-item border rounded-lg p-4 bg-gray-50">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-xs text-gray-600 mb-1">Min Kuantitas</label>
                                                <input type="number" name="quantity_discounts[0][min_quantity]" min="1"
                                                    class="w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="10">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-600 mb-1">Diskon per Unit (Rp)</label>
                                                <input type="number" name="quantity_discounts[0][discount_amount]" min="0" step="0.01"
                                                    class="w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="1000">
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" onclick="removeQuantityDiscount(this)"
                                                    class="w-full px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm">
                                                    🗑️ Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            
                            <button type="button" onclick="addQuantityDiscount()"
                                class="mt-3 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                ➕ Tambah Diskon Kuantitas
                            </button>
                        </div>

                        {{-- Submit --}}
                        <div class="flex justify-between items-center pt-6 border-t">
                            <a href="{{ route('superadmin.vouchers.index') }}"
                                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                ← Batal
                            </a>
                            <button type="submit"
                                class="px-8 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-semibold">
                                💾 Update Voucher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

 
    <script>
        let quantityDiscountIndex = {{ $voucher->quantityDiscounts->count() }};

        function addQuantityDiscount() {
            const container = document.getElementById('quantity-discounts-container');
            const item = document.createElement('div');
            item.className = 'quantity-discount-item border rounded-lg p-4 bg-gray-50';
            item.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Min Kuantitas</label>
                        <input type="number" name="quantity_discounts[${quantityDiscountIndex}][min_quantity]" min="1"
                            class="w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="10">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Diskon per Unit (Rp)</label>
                        <input type="number" name="quantity_discounts[${quantityDiscountIndex}][discount_amount]" min="0" step="0.01"
                            class="w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="1000">
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="removeQuantityDiscount(this)"
                            class="w-full px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm">
                            🗑️ Hapus
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(item);
            quantityDiscountIndex++;
        }

        function removeQuantityDiscount(btn) {
            btn.closest('.quantity-discount-item').remove();
        }
    </script>

</x-admin-layout>