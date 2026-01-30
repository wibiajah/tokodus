<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Customer harus login
        if (!auth('customer')->check()) {
            return false;
        }
        
        // ✅ Deteksi apakah ini UPDATE atau STORE
        $isUpdate = $this->route('review') !== null;
        
        if ($isUpdate) {
            // AUTHORIZATION UNTUK UPDATE
            $review = $this->route('review');
            return $review->customer_id === auth('customer')->id();
        }
        
        // AUTHORIZATION UNTUK STORE
        $order = $this->route('order');
        
        if (!$order) {
            return false;
        }
        
        // Order harus milik customer yang login
        if ($order->customer_id !== auth('customer')->id()) {
            return false;
        }
        
        // Order harus status COMPLETED
        if (!$order->canBeReviewed()) {
            return false;
        }
        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // max 2MB per foto
            'existing_photos' => 'nullable|array', // ✅ Untuk update
            'existing_photos.*' => 'string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'rating.required' => 'Rating wajib diisi',
            'rating.integer' => 'Rating harus berupa angka',
            'rating.min' => 'Rating minimal 1 bintang',
            'rating.max' => 'Rating maksimal 5 bintang',
            'review.string' => 'Ulasan harus berupa teks',
            'review.max' => 'Ulasan maksimal 1000 karakter',
            'photos.array' => 'Format foto tidak valid',
            'photos.max' => 'Maksimal 5 foto',
            'photos.*.image' => 'File harus berupa gambar',
            'photos.*.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'photos.*.max' => 'Ukuran foto maksimal 2MB',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // ✅ Skip validation untuk UPDATE
            $isUpdate = $this->route('review') !== null;
            if ($isUpdate) {
                return; // Update tidak perlu validasi order/product
            }
            
            // VALIDASI UNTUK STORE
            $order = $this->route('order');
            $product = $this->route('product');
            $customer = auth('customer')->user();
            
            if (!$order || !$product) {
                return;
            }
            
            // Validasi 1: Produk harus ada di order items
            $hasProduct = $order->items()->where('product_id', $product->id)->exists();
            if (!$hasProduct) {
                $validator->errors()->add('product', 
                    'Produk ini tidak ada dalam pesanan Anda'
                );
            }
            
            // Validasi 2: Customer belum pernah review produk ini di order ini
            if ($customer && $customer->hasReviewedProduct($product->id, $order->id)) {
                $validator->errors()->add('review', 
                    'Anda sudah memberikan ulasan untuk produk ini'
                );
            }
        });
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'rating' => 'rating',
            'review' => 'ulasan',
            'photos' => 'foto',
            'photos.*' => 'foto',
        ];
    }
}