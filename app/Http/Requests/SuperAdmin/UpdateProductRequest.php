<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        // ✅ FIX: Get actual product ID instead of model object
        $product = $this->route('product');
        $productId = $product instanceof \App\Models\Product ? $product->id : $product;

        return [
            // ============================================
            // BASIC PRODUCT INFO
            // ============================================
            'title' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $productId, // ✅ FIXED
            'ukuran' => 'required|string|max:255',
            'jenis_bahan' => 'nullable|string|max:255',
            'tipe' => 'required|in:innerbox,masterbox',
            'cetak' => 'nullable|string|max:255',
            'finishing' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_active' => 'boolean',

            // ============================================
            // PHOTOS & VIDEO (UPDATE - nullable)
            // ============================================
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'existing_photos' => 'nullable|array',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:10240',
            'remove_video' => 'nullable|boolean',

            // ============================================
            // CATEGORIES
            // ============================================
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',

            // ============================================
            // VARIANTS (INLINE - UPDATE/CREATE/DELETE)
            // ============================================
            'colors' => 'required|array|min:1',
            'colors.*.id' => 'nullable|exists:product_variants,id',
            'colors.*.name' => 'required|string|max:50',
            'colors.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'colors.*.existing_photo' => 'nullable|string',
            'colors.*.stock_pusat' => 'nullable|integer|min:0',

            // Sizes (nested dalam color)
            'colors.*.sizes' => 'nullable|array',
            'colors.*.sizes.*.id' => 'nullable|exists:product_variants,id',
            'colors.*.sizes.*.name' => 'required_with:colors.*.sizes|string|max:50',
            'colors.*.sizes.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'colors.*.sizes.*.existing_photo' => 'nullable|string', // ✅ ADDED
            'colors.*.sizes.*.price' => 'required_with:colors.*.sizes|numeric|min:0',
            'colors.*.sizes.*.stock_pusat' => 'required_with:colors.*.sizes|integer|min:0',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            // Basic info
            'title.required' => 'Nama produk harus diisi',
            'sku.required' => 'SKU harus diisi',
            'sku.unique' => 'SKU sudah digunakan',
            'ukuran.required' => 'Ukuran harus diisi',
            'tipe.required' => 'Tipe produk harus dipilih',
            'price.required' => 'Harga harus diisi',
            'discount_price.lt' => 'Harga diskon harus lebih kecil dari harga normal',

            // Photos
            'photos.max' => 'Maksimal 5 foto',
            'photos.*.image' => 'File harus berupa gambar',
            'photos.*.max' => 'Ukuran foto maksimal 2MB',

            // Categories
            'category_ids.required' => 'Pilih minimal 1 kategori',
            'category_ids.*.exists' => 'Kategori tidak valid',

            // Variants - Colors
            'colors.required' => 'Minimal harus ada 1 varian warna',
            'colors.*.name.required' => 'Nama warna harus diisi',
            'colors.*.photo.image' => 'Foto warna harus berupa gambar',
            'colors.*.stock_pusat.min' => 'Stok tidak boleh negatif',

            // Variants - Sizes
            'colors.*.sizes.*.name.required_with' => 'Nama ukuran harus diisi',
            'colors.*.sizes.*.price.required_with' => 'Harga ukuran harus diisi',
            'colors.*.sizes.*.stock_pusat.required_with' => 'Stok ukuran harus diisi',
            'colors.*.sizes.*.stock_pusat.min' => 'Stok ukuran tidak boleh negatif',
            'colors.*.sizes.*.photo.image' => 'Foto ukuran harus berupa gambar',
            'colors.*.sizes.*.photo.max' => 'Ukuran foto ukuran maksimal 2MB',
        ];
    }

    /**
     * Custom validation attributes
     */
    public function attributes(): array
    {
        return [
            'title' => 'nama produk',
            'sku' => 'SKU',
            'ukuran' => 'ukuran',
            'tipe' => 'tipe produk',
            'price' => 'harga',
            'discount_price' => 'harga diskon',
            'category_ids' => 'kategori',
            'colors' => 'varian warna',
        ];
    }

    /**
     * After validation hook
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation: Jika color punya sizes, stock_pusat color tidak perlu diisi
            $colors = $this->input('colors', []);
            
            foreach ($colors as $index => $color) {
                $hasSizes = isset($color['sizes']) && count($color['sizes']) > 0;
                $hasStock = isset($color['stock_pusat']) && $color['stock_pusat'] > 0;
                
                // Jika tidak ada sizes, harus ada stock_pusat
                if (!$hasSizes && !$hasStock) {
                    $validator->errors()->add(
                        "colors.{$index}.stock_pusat",
                        "Stok warna harus diisi jika tidak ada ukuran"
                    );
                }

                // Validasi: Jika update color yang sudah punya sizes di DB,
                // tidak boleh hapus semua sizes
                if (isset($color['id']) && !$hasSizes) {
                    // Check apakah color ini di DB punya children
                    $existingColor = \App\Models\ProductVariant::find($color['id']);
                    if ($existingColor && $existingColor->children()->exists()) {
                        $validator->errors()->add(
                            "colors.{$index}.sizes",
                            "Tidak bisa menghapus semua ukuran dari warna yang sudah memiliki ukuran"
                        );
                    }
                }
            }
        });
    }
}