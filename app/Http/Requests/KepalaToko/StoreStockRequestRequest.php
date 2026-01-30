<?php

namespace App\Http\Requests\KepalaToko;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ProductVariant;

class StoreStockRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User harus punya toko_id (KepalaToko atau Staff)
        return auth()->user()->toko_id !== null;
    }

    /**
     * Prepare data for validation
     */
    protected function prepareForValidation()
    {
        // Filter out variants dengan quantity 0
        $variants = $this->input('variants', []);
        
        $filteredVariants = array_values(array_filter($variants, function($variant) {
            return isset($variant['quantity']) && (int)$variant['quantity'] > 0;
        }));
        
        $this->merge([
            'variants' => $filteredVariants
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // ============================================
            // REQUEST INFO
            // ============================================
            'notes' => 'nullable|string|max:500',

            // ============================================
            // VARIANTS (LEAF NODES ONLY)
            // ============================================
            'variants' => 'required|array|min:1',
            'variants.*.variant_id' => 'required|exists:product_variants,id',
            'variants.*.quantity' => 'required|integer|min:1', // min:1 karena sudah difilter di prepareForValidation
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'variants.required' => 'Pilih minimal 1 varian',
            'variants.min' => 'Pilih minimal 1 varian dengan jumlah lebih dari 0',
            'variants.*.variant_id.required' => 'Varian harus dipilih',
            'variants.*.variant_id.exists' => 'Varian tidak valid',
            'variants.*.quantity.required' => 'Jumlah harus diisi',
            'variants.*.quantity.min' => 'Jumlah minimal 1',
        ];
    }

    /**
     * Custom validation attributes
     */
    public function attributes(): array
    {
        return [
            'variants' => 'varian produk',
            'notes' => 'catatan',
        ];
    }

    /**
     * After validation hook
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $variants = $this->input('variants', []);

            foreach ($variants as $index => $item) {
                $variant = ProductVariant::find($item['variant_id']);

                if (!$variant) {
                    continue;
                }

                // ============================================
                // VALIDASI 1: Tidak bisa request parent color yang punya children
                // ============================================
                if ($variant->isColor() && $variant->hasChildren()) {
                    $validator->errors()->add(
                        "variants.{$index}.variant_id",
                        "Tidak bisa request warna '{$variant->name}' yang memiliki ukuran. Pilih ukuran spesifik."
                    );
                }

                // ============================================
                // VALIDASI 2: Warning jika request melebihi stok warehouse
                // (Tidak error, hanya warning - approval akan cek lagi)
                // ============================================
                $requestedQty = (int) $item['quantity'];
                if ($requestedQty > $variant->stock_pusat) {
                    // Tidak error, tapi bisa ditambahkan warning message ke session
                    // Controller akan handle ini
                }
            }

            // ============================================
            // VALIDASI 3: Tidak boleh ada duplicate variant_id
            // ============================================
            $variantIds = collect($variants)->pluck('variant_id')->toArray();
            $duplicates = array_diff_assoc($variantIds, array_unique($variantIds));
            
            if (!empty($duplicates)) {
                $validator->errors()->add(
                    'variants',
                    'Tidak boleh memilih varian yang sama lebih dari sekali'
                );
            }

            // ============================================
            // VALIDASI 4: Cek apakah variant belong to product
            // ============================================
            $productId = $this->route('product')->id; // Get from route parameter
            
            foreach ($variants as $index => $item) {
                $variant = ProductVariant::find($item['variant_id']);
                
                if ($variant && $variant->product_id != $productId) {
                    $validator->errors()->add(
                        "variants.{$index}.variant_id",
                        "Varian tidak sesuai dengan produk yang dipilih"
                    );
                }
            }
        });
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        abort(403, 'Anda tidak memiliki akses untuk request stok. Hubungi admin.');
    }
}