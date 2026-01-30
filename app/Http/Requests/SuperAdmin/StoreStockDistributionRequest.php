<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ProductVariant;

class StoreStockDistributionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // ============================================
            // DISTRIBUTION INFO
            // ============================================
            'toko_id' => 'required|exists:tokos,id|not_in:999', // tidak bisa distribute ke Head Office
            'notes' => 'nullable|string|max:500',

            // ============================================
            // VARIANTS (LEAF NODES ONLY - SIZE OR COLOR WITHOUT SIZES)
            // ============================================
            'variants' => 'required|array|min:1',
            'variants.*.variant_id' => 'required|exists:product_variants,id',
            'variants.*.quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'toko_id.required' => 'Pilih toko tujuan',
            'toko_id.exists' => 'Toko tidak valid',
            'toko_id.not_in' => 'Tidak bisa mendistribusikan ke Head Office',

            'variants.required' => 'Pilih minimal 1 varian',
            'variants.min' => 'Pilih minimal 1 varian',
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
            'toko_id' => 'toko tujuan',
            'variants' => 'varian produk',
        ];
    }

    /**
     * After validation hook - Custom business logic validation
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
                // VALIDASI 1: Tidak bisa distribute parent color yang punya children
                // ============================================
                if ($variant->isColor() && $variant->hasChildren()) {
                    $validator->errors()->add(
                        "variants.{$index}.variant_id",
                        "Tidak bisa mendistribusikan warna '{$variant->name}' yang memiliki ukuran. Pilih ukuran spesifik."
                    );
                }

                // ============================================
                // VALIDASI 2: Stok warehouse tidak cukup
                // ============================================
                $requestedQty = (int) $item['quantity'];
                if ($variant->stock_pusat < $requestedQty) {
                    $validator->errors()->add(
                        "variants.{$index}.quantity",
                        "Stok '{$variant->display_name}' tidak cukup. Tersedia: {$variant->stock_pusat}, diminta: {$requestedQty}"
                    );
                }

                // ============================================
                // VALIDASI 3: Quantity tidak boleh melebihi stok warehouse
                // ============================================
                if ($requestedQty > $variant->stock_pusat) {
                    $validator->errors()->add(
                        "variants.{$index}.quantity",
                        "Jumlah tidak boleh melebihi stok warehouse ({$variant->stock_pusat})"
                    );
                }
            }

            // ============================================
            // VALIDASI 4: Tidak boleh ada duplicate variant_id
            // ============================================
            $variantIds = collect($variants)->pluck('variant_id')->toArray();
            $duplicates = array_diff_assoc($variantIds, array_unique($variantIds));
            
            if (!empty($duplicates)) {
                $validator->errors()->add(
                    'variants',
                    'Tidak boleh memilih varian yang sama lebih dari sekali'
                );
            }
        });
    }
}