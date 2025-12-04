<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Voucher: ') . $voucher->code }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Action Buttons --}}
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('superadmin.vouchers.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    ← Kembali
                </a>
                <div class="flex gap-2">
                    <a href="{{ route('superadmin.vouchers.edit', $voucher) }}"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        ✏️ Edit Voucher
                    </a>
                    <form action="{{ route('superadmin.vouchers.destroy', $voucher) }}" method="POST" 
                        onsubmit="return confirm('Yakin hapus voucher ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Voucher Info Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">🎟️ Info Voucher</h3>

                        {{-- Voucher Code --}}
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6 text-white text-center mb-6">
                            <div class="text-4xl font-bold mb-2">{{ $voucher->code }}</div>
                            <div class="text-sm opacity-90">{{ $voucher->name }}</div>
                        </div>

                        {{-- Details --}}
                        <div class="space-y-4">
                            <div>
                                <span class="text-sm text-gray-500">Status:</span>
                                <div class="mt-1">
                                    @if($voucher->isValid())
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded font-semibold">
                                            ✅ Aktif
                                        </span>
                                    @elseif(now()->isBefore($voucher->start_date))
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded font-semibold">
                                            ⏳ Belum Mulai
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded font-semibold">
                                            ❌ Expired
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <span class="text-sm text-gray-500">Tipe Diskon:</span>
                                <p class="font-semibold">
                                    @if($voucher->discount_type === 'fixed')
                                        Fixed Amount
                                    @else
                                        Percentage
                                    @endif
                                </p>
                            </div>

                            <div>
                                <span class="text-sm text-gray-500">Nilai Diskon:</span>
                                <p class="text-2xl font-bold text-indigo-600">
                                    @if($voucher->discount_type === 'fixed')
                                        Rp {{ number_format($voucher->discount_value, 0, ',', '.') }}
                                    @else
                                        {{ $voucher->discount_value }}%
                                    @endif
                                </p>
                            </div>

                            <div class="border-t pt-4">
                                <span class="text-sm text-gray-500">Periode Berlaku:</span>
                                <div class="mt-2 space-y-1">
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-600">Mulai:</span>
                                        <span class="ml-2 font-semibold">{{ $voucher->start_date->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-600">Berakhir:</span>
                                        <span class="ml-2 font-semibold">{{ $voucher->end_date->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <span class="text-sm text-gray-500">Penggunaan:</span>
                                <div class="mt-2">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-2xl font-bold">{{ $voucher->usage_count }}</span>
                                        <span class="text-sm text-gray-500">
                                            @if($voucher->usage_limit)
                                                / {{ $voucher->usage_limit }}
                                            @else
                                                / Unlimited
                                            @endif
                                        </span>
                                    </div>
                                    @if($voucher->usage_limit)
                                        @php
                                            $percentage = ($voucher->usage_count / $voucher->usage_limit) * 100;
                                        @endphp
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($voucher->description)
                                <div class="border-t pt-4">
                                    <span class="text-sm text-gray-500">Deskripsi:</span>
                                    <p class="mt-1 text-sm text-gray-700">{{ $voucher->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Products & Quantity Discounts --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Products --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">📦 Produk yang Termasuk Diskon ({{ $voucher->products->count() }})</h3>

                            @if($voucher->products->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($voucher->products as $product)
                                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                                            <div class="flex gap-4">
                                                @if($product->photos && count($product->photos) > 0)
                                                    <img src="{{ Storage::url($product->photos[0]) }}" 
                                                        alt="{{ $product->title }}"
                                                        class="w-20 h-20 object-cover rounded">
                                                @else
                                                    <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                                        <span class="text-gray-400">📦</span>
                                                    </div>
                                                @endif
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-sm">{{ $product->title }}</h4>
                                                    <p class="text-xs text-gray-500 font-mono">{{ $product->sku }}</p>
                                                    <div class="mt-2">
                                                        <span class="text-sm font-bold text-indigo-600">
                                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                                        </span>
                                                        @if($product->discount_price)
                                                            <span class="text-xs text-red-600 block">
                                                                → Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center text-gray-500 py-8">Tidak ada produk yang dipilih</p>
                            @endif
                        </div>
                    </div>

                    {{-- Quantity Discounts --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">📊 Diskon Berdasarkan Kuantitas</h3>

                            @if($voucher->quantityDiscounts->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Min Kuantitas
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Diskon per Unit
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Contoh Total Diskon
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($voucher->quantityDiscounts->sortBy('min_quantity') as $qd)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded font-semibold">
                                                            ≥ {{ $qd->min_quantity }} pcs
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-lg font-bold text-green-600">
                                                            - Rp {{ number_format($qd->discount_amount, 0, ',', '.') }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @php
                                                            $exampleQty = $qd->min_quantity;
                                                            $totalDiscount = $qd->discount_amount * $exampleQty;
                                                        @endphp
                                                        <span class="text-sm text-gray-600">
                                                            Beli {{ $exampleQty }} = hemat 
                                                            <span class="font-bold text-red-600">
                                                                Rp {{ number_format($totalDiscount, 0, ',', '.') }}
                                                            </span>
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Example Calculation --}}
                                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-blue-900 mb-2">💡 Contoh Perhitungan:</h4>
                                    @php
                                        $firstQd = $voucher->quantityDiscounts->sortBy('min_quantity')->first();
                                        if($firstQd && $voucher->products->first()) {
                                            $product = $voucher->products->first();
                                            $qty = $firstQd->min_quantity;
                                            $normalPrice = $product->price * $qty;
                                            $discount = $firstQd->discount_amount * $qty;
                                            $finalPrice = $normalPrice - $discount;
                                        }
                                    @endphp
                                    @if(isset($product))
                                        <div class="text-sm text-blue-800 space-y-1">
                                            <p>Produk: <span class="font-semibold">{{ $product->title }}</span></p>
                                            <p>Harga Normal: {{ $qty }} × Rp {{ number_format($product->price, 0, ',', '.') }} 
                                                = <span class="font-semibold">Rp {{ number_format($normalPrice, 0, ',', '.') }}</span>
                                            </p>
                                            <p>Diskon: {{ $qty }} × Rp {{ number_format($firstQd->discount_amount, 0, ',', '.') }} 
                                                = <span class="font-semibold text-red-600">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                                            </p>
                                            <p class="text-lg font-bold text-green-700 pt-2 border-t border-blue-300">
                                                Harga Akhir: Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-center text-gray-500 py-8">
                                    Tidak ada diskon kuantitas khusus.<br>
                                    <span class="text-sm">Menggunakan diskon dasar voucher.</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>