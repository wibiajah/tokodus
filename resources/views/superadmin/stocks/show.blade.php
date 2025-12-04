<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Stok: ') . $product->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ route('superadmin.stocks.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    ← Kembali ke Overview
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Product Info --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">📦 Info Produk</h3>
                        
                        @if($product->photos && count($product->photos) > 0)
                            <img src="{{ Storage::url($product->photos[0]) }}" 
                                alt="{{ $product->title }}"
                                class="w-full h-48 object-cover rounded-lg mb-4">
                        @endif

                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-500">Nama Produk:</span>
                                <p class="font-semibold">{{ $product->title }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">SKU:</span>
                                <p class="font-mono font-semibold">{{ $product->sku }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Harga:</span>
                                <p class="font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Update Initial Stock Form --}}
                        <div class="mt-6 pt-6 border-t">
                            <h4 class="font-semibold mb-3">🔧 Update Stok Awal</h4>
                            <form action="{{ route('superadmin.stocks.updateInitial', $product) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="flex gap-2">
                                    <input type="number" name="initial_stock" 
                                        value="{{ $product->initial_stock }}" 
                                        min="0" required
                                        class="flex-1 rounded-md border-gray-300 shadow-sm">
                                    <button type="submit" 
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        💾
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Stock Summary --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Summary Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-600 font-medium">Stok Awal</p>
                            <p class="text-3xl font-bold text-blue-900 mt-2">{{ $product->initial_stock }}</p>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <p class="text-sm text-purple-600 font-medium">Telah Dialokasikan</p>
                            <p class="text-3xl font-bold text-purple-900 mt-2">{{ $product->stocks->sum('stock') }}</p>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-green-600 font-medium">Sisa Stok Awal</p>
                            <p class="text-3xl font-bold text-green-900 mt-2">{{ $product->remaining_initial_stock }}</p>
                        </div>
                    </div>

                    {{-- Stock per Toko --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">🏪 Distribusi Stok per Toko</h3>

                            @if($product->stocks->count() > 0)
                                <div class="space-y-4">
                                    @foreach($product->stocks as $stock)
                                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <h4 class="font-semibold text-gray-900">{{ $stock->toko->nama_toko }}</h4>
                                                    <p class="text-sm text-gray-500">{{ $stock->toko->alamat }}</p>
                                                    @if($stock->toko->kepala_toko)
                                                        <p class="text-xs text-gray-400 mt-1">
                                                            Kepala Toko: {{ $stock->toko->kepala_toko->name }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-3xl font-bold text-indigo-600">{{ $stock->stock }}</p>
                                                    <p class="text-xs text-gray-500">unit</p>
                                                </div>
                                            </div>

                                            {{-- Progress Bar --}}
                                            @php
                                                $percentage = $product->initial_stock > 0 
                                                    ? ($stock->stock / $product->initial_stock * 100) 
                                                    : 0;
                                                $barColor = $stock->stock > 10 ? 'bg-green-500' : ($stock->stock > 0 ? 'bg-yellow-500' : 'bg-red-500');
                                            @endphp
                                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                                <div class="{{ $barColor }} h-2 rounded-full" 
                                                    style="width: {{ min($percentage, 100) }}%"></div>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                {{ number_format($percentage, 1) }}% dari stok awal
                                            </p>

                                            {{-- Status Badge --}}
                                            <div class="mt-3">
                                                @if($stock->stock > 10)
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                                        ✅ Stok Cukup
                                                    </span>
                                                @elseif($stock->stock > 0)
                                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                                                        ⚠️ Stok Terbatas
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                                        ❌ Stok Habis
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12 text-gray-500">
                                    <div class="text-6xl mb-4">🏪</div>
                                    <p class="text-lg">Belum ada toko yang mengambil stok</p>
                                    <p class="text-sm mt-2">Kepala Toko atau Staff dapat mengalokasikan stok dari dashboard mereka</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Toko Belum Alokasi --}}
                    @php
                        $tokoWithStock = $product->stocks->pluck('toko_id');
                        $tokoWithoutStock = $tokos->whereNotIn('id', $tokoWithStock);
                    @endphp

                    @if($tokoWithoutStock->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-4">📋 Toko Belum Alokasi Stok</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($tokoWithoutStock as $toko)
                                        <div class="border rounded-lg p-3 bg-gray-50">
                                            <p class="font-medium text-gray-900">{{ $toko->nama_toko }}</p>
                                            <p class="text-xs text-gray-500">{{ $toko->alamat }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>