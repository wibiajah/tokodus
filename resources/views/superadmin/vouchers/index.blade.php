<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Voucher & Diskon') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold">🎟️ Daftar Voucher</h3>
                            <p class="text-sm text-gray-600">Kelola voucher dan diskon kuantitas</p>
                        </div>
                        <a href="{{ route('superadmin.vouchers.create') }}"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-semibold">
                            ➕ Buat Voucher
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penggunaan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($vouchers as $voucher)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded font-mono font-bold text-sm">
                                                {{ $voucher->code }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $voucher->name }}</div>
                                            @if($voucher->description)
                                                <div class="text-xs text-gray-500 max-w-xs truncate">{{ $voucher->description }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($voucher->discount_type === 'fixed')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Fixed</span>
                                            @else
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">Percentage</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($voucher->discount_type === 'fixed')
                                                <span class="font-semibold">Rp {{ number_format($voucher->discount_value, 0, ',', '.') }}</span>
                                            @else
                                                <span class="font-semibold">{{ $voucher->discount_value }}%</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-xs text-gray-600">
                                                <div>{{ $voucher->start_date->format('d M Y') }}</div>
                                                <div class="text-gray-400">s/d</div>
                                                <div>{{ $voucher->end_date->format('d M Y') }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-semibold">
                                                {{ $voucher->products->count() }} produk
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm">
                                                <span class="font-semibold">{{ $voucher->usage_count }}</span>
                                                @if($voucher->usage_limit)
                                                    <span class="text-gray-500">/ {{ $voucher->usage_limit }}</span>
                                                @else
                                                    <span class="text-gray-500">/ ∞</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($voucher->isValid())
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">
                                                    ✅ Aktif
                                                </span>
                                            @elseif(now()->isBefore($voucher->start_date))
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">
                                                    ⏳ Belum Mulai
                                                </span>
                                            @else
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">
                                                    ❌ Expired
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('superadmin.vouchers.show', $voucher) }}"
                                                    class="text-blue-600 hover:text-blue-900" title="Detail">
                                                    👁️
                                                </a>
                                                <a href="{{ route('superadmin.vouchers.edit', $voucher) }}"
                                                    class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                                    ✏️
                                                </a>
                                                <form action="{{ route('superadmin.vouchers.destroy', $voucher) }}"
                                                    method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Yakin hapus voucher ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                            <div class="text-6xl mb-4">🎟️</div>
                                            <p class="text-lg">Belum ada voucher</p>
                                            <a href="{{ route('superadmin.vouchers.create') }}"
                                                class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                                Buat Voucher Pertama
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($vouchers->hasPages())
                        <div class="mt-6">
                            {{ $vouchers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>