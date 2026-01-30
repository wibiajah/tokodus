<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::with(['products', 'categories', 'customers'])
            ->withCount('usages')
            ->latest()
            ->paginate(20);

        return view('superadmin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)
            ->orderBy('title')
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        $customers = Customer::orderBy('firstname')
            ->orderBy('lastname')
            ->get();

        return view('superadmin.vouchers.create', compact('products', 'categories', 'customers'));
    }

    public function store(Request $request)
    {
        // ✅ VALIDASI DASAR
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'distribution_type' => 'required|in:public,private',
            'code' => 'nullable|string|unique:vouchers,code|max:50',

            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',

            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',

            'usage_limit_total' => 'nullable|integer|min:1',
            'usage_limit_per_customer' => 'nullable|integer|min:1',
            'can_combine' => 'boolean',

            'scope' => 'required|in:all_products,specific_products,specific_categories',

            'customers' => 'nullable|array',
            'customers.*' => 'exists:customers,id',

            'is_active' => 'boolean',
        ];

        // ✅ VALIDASI KONDISIONAL: Produk wajib jika scope = specific_products
        if ($request->scope === 'specific_products') {
            $rules['products'] = 'required|array|min:1';
            $rules['products.*'] = 'exists:products,id';
        }

        // ✅ VALIDASI KONDISIONAL: Kategori wajib jika scope = specific_categories
        if ($request->scope === 'specific_categories') {
            $rules['categories'] = 'required|array|min:1';
            $rules['categories.*'] = 'exists:categories,id';
        }

        $validated = $request->validate($rules, [
            'products.required' => 'Silakan pilih minimal 1 produk untuk voucher produk tertentu',
            'products.min' => 'Silakan pilih minimal 1 produk',
            'categories.required' => 'Silakan pilih minimal 1 kategori untuk voucher kategori tertentu',
            'categories.min' => 'Silakan pilih minimal 1 kategori',
        ]);

        // Generate kode otomatis untuk public voucher
        if ($validated['distribution_type'] === 'public' && empty($validated['code'])) {
            $validated['code'] = $this->generateVoucherCode();
        }

        // ✅ FORCE UPPERCASE untuk kode voucher
        if (!empty($validated['code'])) {
            $validated['code'] = strtoupper($validated['code']);
        }

        // Private voucher tidak boleh punya kode
        if ($validated['distribution_type'] === 'private') {
            $validated['code'] = null;
        }

        // ✅ FORCE UPPERCASE untuk kode voucher saat update
        if (!empty($validated['code'])) {
            $validated['code'] = strtoupper($validated['code']);
        }

        // Validasi percentage
        if ($validated['discount_type'] === 'percentage' && empty($validated['max_discount'])) {
            return back()
                ->withErrors(['max_discount' => 'Maksimal diskon wajib diisi untuk diskon persentase'])
                ->withInput();
        }

        $validated['min_purchase'] = $validated['min_purchase'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['can_combine'] = $request->has('can_combine') ? true : false;

        // Create voucher
        $voucher = Voucher::create($validated);

        // Attach products
        if ($validated['scope'] === 'specific_products' && !empty($validated['products'])) {
            $voucher->products()->attach($validated['products']);
        }

        // Attach categories
        if ($validated['scope'] === 'specific_categories' && !empty($validated['categories'])) {
            $voucher->categories()->attach($validated['categories']);
        }

        // Attach customers
        if (!empty($validated['customers'])) {
            $voucher->customers()->attach($validated['customers']);
        }

        NotificationHelper::voucherCreated($voucher, auth()->user());

        return redirect()
            ->route('superadmin.vouchers.index')
            ->with('success', 'Voucher berhasil dibuat!');
    }

    public function show(Voucher $voucher)
    {
        $voucher->load(['products', 'categories', 'customers', 'usages.customer', 'usages.order']);

        return view('superadmin.vouchers.show', compact('voucher'));
    }

    public function edit(Voucher $voucher)
    {
        $products = Product::where('is_active', true)
            ->orderBy('title')
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        $customers = Customer::orderBy('firstname')
            ->orderBy('lastname')
            ->get();

        $voucher->load(['products', 'categories', 'customers']);

        return view('superadmin.vouchers.edit', compact('voucher', 'products', 'categories', 'customers'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'distribution_type' => 'required|in:public,private',
            'code' => 'nullable|string|unique:vouchers,code,' . $voucher->id . '|max:50',

            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',

            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',

            'usage_limit_total' => 'nullable|integer|min:1',
            'usage_limit_per_customer' => 'nullable|integer|min:1',
            'can_combine' => 'boolean',

            'scope' => 'required|in:all_products,specific_products,specific_categories',

            'customers' => 'nullable|array',
            'customers.*' => 'exists:customers,id',

            'is_active' => 'boolean',
        ];

        if ($request->scope === 'specific_products') {
            $rules['products'] = 'required|array|min:1';
            $rules['products.*'] = 'exists:products,id';
        }

        if ($request->scope === 'specific_categories') {
            $rules['categories'] = 'required|array|min:1';
            $rules['categories.*'] = 'exists:categories,id';
        }

        $validated = $request->validate($rules, [
            'products.required' => 'Silakan pilih minimal 1 produk untuk voucher produk tertentu',
            'products.min' => 'Silakan pilih minimal 1 produk',
            'categories.required' => 'Silakan pilih minimal 1 kategori untuk voucher kategori tertentu',
            'categories.min' => 'Silakan pilih minimal 1 kategori',
        ]);

        if ($validated['distribution_type'] === 'private') {
            $validated['code'] = null;
        }

        if ($validated['discount_type'] === 'percentage' && empty($validated['max_discount'])) {
            return back()
                ->withErrors(['max_discount' => 'Maksimal diskon wajib diisi untuk diskon persentase'])
                ->withInput();
        }

        $validated['min_purchase'] = $validated['min_purchase'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['can_combine'] = $request->has('can_combine') ? true : false;

        $voucher->update($validated);

        if ($validated['scope'] === 'specific_products') {
            $voucher->products()->sync($validated['products'] ?? []);
        } else {
            $voucher->products()->detach();
        }

        if ($validated['scope'] === 'specific_categories') {
            $voucher->categories()->sync($validated['categories'] ?? []);
        } else {
            $voucher->categories()->detach();
        }

        $voucher->customers()->sync($validated['customers'] ?? []);

        NotificationHelper::voucherUpdated($voucher, auth()->user());


        return redirect()
            ->route('superadmin.vouchers.index')
            ->with('success', 'Voucher berhasil diupdate!');
    }

    public function destroy(Voucher $voucher)
    {
        if ($voucher->usage_count > 0) {
            return back()
                ->withErrors(['error' => 'Voucher tidak dapat dihapus karena sudah pernah digunakan.']);
        }

        $voucher->delete();

        NotificationHelper::voucherDeleted($voucherName, $voucherCode, auth()->user());

        return redirect()
            ->route('superadmin.vouchers.index')
            ->with('success', 'Voucher berhasil dihapus!');
    }

    public function toggleStatus(Voucher $voucher)
    {
        $voucher->update([
            'is_active' => !$voucher->is_active
        ]);

        $status = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';

        NotificationHelper::voucherStatusChanged($voucher, auth()->user());

        return redirect()
            ->route('superadmin.vouchers.index')
            ->with('success', "Voucher berhasil {$status}!");
    }

    private function generateVoucherCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Voucher::where('code', $code)->exists());

        return $code;
    }

    public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'customer_id' => 'required|exists:customers,id',
            'cart_total' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode voucher tidak ditemukan'
            ], 404);
        }

        $validation = $voucher->isValidForCustomer($request->customer_id, $request->cart_total);

        if ($validation['valid']) {
            $discount = $voucher->calculateDiscount($request->cart_total);

            return response()->json([
                'valid' => true,
                'voucher' => $voucher,
                'discount' => $discount,
                'final_total' => max(0, $request->cart_total - $discount)
            ]);
        }

        return response()->json($validation, 400);
    }
}
