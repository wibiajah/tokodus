<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Stok Real-Time') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                                <span class="text-2xl">📦</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Produk</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $products->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                                <span class="text-2xl">🏪</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Toko</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $tokos->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                                <span class="text-2xl">📊</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Stok Awal</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $products->sum('initial_stock') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-full p-3">
                                <span class="text-2xl">✅</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Dialokasikan</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ $products->sum(fn($p) => $p->stocks->sum('stock')) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stock Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">📦 Stok per Produk & Toko</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase sticky left-0 bg-gray-50">
                                        Produk
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                        Stok Awal
                                    </th>
                                    @foreach($tokos as $toko)
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                            {{ $toko->nama_toko }}
                                        </th>
                                    @endforeach
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase bg-blue-50">
                                        Sisa Stok
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white">
                                            <div class="flex items-center">
                                                @if($product->photos && count($product->photos) > 0)
                                                    <img src="{{ Storage::url($product->photos[0]) }}" 
                                                        alt="{{ $product->title }}"
                                                        class="h-10 w-10 rounded-md object-cover">
                                                @else
                                                    <div class="h-10 w-10 bg-gray-200 rounded-md flex items-center justify-center">
                                                        <span class="text-xs text-gray-400">📦</span>
                                                    </div>
                                                @endif
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $product->title }}</div>
                                                    <div class="text-xs text-gray-500">{{ $product->sku }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-3 py-1 text-sm font-bold bg-gray-100 text-gray-800 rounded">
                                                {{ $product->initial_stock }}
                                            </span>
                                        </td>

                                        @foreach($tokos as $toko)
                                            @php
                                                $stock = $product->stocks->where('toko_id', $toko->id)->first();
                                                $stockValue = $stock ? $stock->stock : 0;
                                                $color = $stockValue > 10 ? 'green' : ($stockValue > 0 ? 'yellow' : 'gray');
                                            @endphp
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($stock)
                                                    <span class="px-3 py-1 text-sm font-semibold bg-{{ $color }}-100 text-{{ $color }}-800 rounded">
                                                        {{ $stockValue }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 text-sm">-</span>
                                                @endif
                                            </td>
                                        @endforeach

                                        @php
                                            $remaining = $product->remaining_initial_stock;
                                            $remainingColor = $remaining > 50 ? 'green' : ($remaining > 0 ? 'yellow' : 'red');
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-center bg-blue-50">
                                            <span class="px-3 py-1 text-sm font-bold bg-{{ $remainingColor }}-100 text-{{ $remainingColor }}-800 rounded">
                                                {{ $remaining }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <a href="{{ route('superadmin.stocks.show', $product) }}"
                                                class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                                                Detail →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                @if($products->isEmpty())
                                    <tr>
                                        <td colspan="{{ 4 + $tokos->count() }}" class="px-6 py-12 text-center text-gray-500">
                                            <div class="text-6xl mb-4">📦</div>
                                            <p class="text-lg">Belum ada produk</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{-- Legend --}}
                    <div class="mt-6 flex justify-center gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 bg-green-100 rounded"></span>
                            <span class="text-gray-600">Stok Cukup (&gt;10)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 bg-yellow-100 rounded"></span>
                            <span class="text-gray-600">Stok Terbatas (1-10)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 bg-red-100 rounded"></span>
                            <span class="text-gray-600">Stok Habis (0)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>