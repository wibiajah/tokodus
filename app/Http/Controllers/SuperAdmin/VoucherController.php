<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Product;
use App\Models\VoucherQuantityDiscount;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // List voucher
    public function index()
    {
        $vouchers = Voucher::with(['products', 'quantityDiscounts'])
            ->latest()
            ->paginate(20);

        return view('superadmin.vouchers.index', compact('vouchers'));
    }

    // Form tambah voucher
    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('superadmin.vouchers.create', compact('products'));
    }

    // Simpan voucher baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:vouchers,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
            'quantity_discounts' => 'nullable|array',
            'quantity_discounts.*.min_quantity' => 'required|integer|min:1',
            'quantity_discounts.*.discount_amount' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'usage_limit' => $validated['usage_limit'] ?? null,
        ]);

        // Attach produk yang kena diskon
        if ($request->has('products')) {
            $voucher->products()->attach($validated['products']);
        }

        // Simpan diskon kuantitas (10pcs potong 1000, 100pcs potong 2000)
        if ($request->has('quantity_discounts')) {
            foreach ($validated['quantity_discounts'] as $qd) {
                VoucherQuantityDiscount::create([
                    'voucher_id' => $voucher->id,
                    'min_quantity' => $qd['min_quantity'],
                    'discount_amount' => $qd['discount_amount'],
                ]);
            }
        }

        return redirect()
            ->route('superadmin.vouchers.index')
            ->with('success', 'Voucher berhasil dibuat!');
    }

    // Detail voucher
    public function show(Voucher $voucher)
    {
        $voucher->load(['products', 'quantityDiscounts']);
        return view('superadmin.vouchers.show', compact('voucher'));
    }

    // Form edit voucher
    public function edit(Voucher $voucher)
    {
        $products = Product::where('is_active', true)->get();
        $voucher->load(['products', 'quantityDiscounts']);
        
        return view('superadmin.vouchers.edit', compact('voucher', 'products'));
    }

    // Update voucher
    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:vouchers,code,' . $voucher->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
            'quantity_discounts' => 'nullable|array',
            'quantity_discounts.*.min_quantity' => 'required|integer|min:1',
            'quantity_discounts.*.discount_amount' => 'required|numeric|min:0',
        ]);

        $voucher->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'usage_limit' => $validated['usage_limit'] ?? null,
        ]);

        // Sync produk
        if ($request->has('products')) {
            $voucher->products()->sync($validated['products']);
        }

        // Update quantity discounts
        $voucher->quantityDiscounts()->delete();
        if ($request->has('quantity_discounts')) {
            foreach ($validated['quantity_discounts'] as $qd) {
                VoucherQuantityDiscount::create([
                    'voucher_id' => $voucher->id,
                    'min_quantity' => $qd['min_quantity'],
                    'discount_amount' => $qd['discount_amount'],
                ]);
            }
        }

        return redirect()
            ->route('superadmin.vouchers.index')
            ->with('success', 'Voucher berhasil diupdate!');
    }

    // Hapus voucher
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return redirect()
            ->route('superadmin.vouchers.index')
            ->with('success', 'Voucher berhasil dihapus!');
    }

    // Apply voucher ke produk (untuk menghitung harga diskon)
    public function applyToProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'voucher_code' => 'required|string|exists:vouchers,code',
            'quantity' => 'required|integer|min:1',
        ]);

        $voucher = Voucher::where('code', $validated['voucher_code'])->first();

        if (!$voucher->isValid()) {
            return back()->withErrors(['voucher_code' => 'Voucher tidak valid atau sudah expired.']);
        }

        // Cek apakah produk termasuk dalam voucher
        if (!$voucher->products->contains($product->id)) {
            return back()->withErrors(['voucher_code' => 'Produk ini tidak termasuk dalam voucher.']);
        }

        // Hitung diskon
        $discount = $voucher->calculateDiscount($product->price, $validated['quantity']);
        $finalPrice = max(0, $product->price - $discount);

        // Update harga diskon di produk
        $product->update([
            'discount_price' => $finalPrice,
        ]);

        return back()->with('success', 'Voucher berhasil diterapkan! Harga akhir: Rp ' . number_format($finalPrice, 0, ',', '.'));
    }
}