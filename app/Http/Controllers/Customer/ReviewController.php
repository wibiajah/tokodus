<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Http\Requests\StoreProductReviewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Halaman form review
     * GET /customer/orders/{order}/products/{product}/review
     */
    public function create(Order $order, Product $product)
    {
        // Validasi order milik customer
        if ($order->customer_id !== auth('customer')->id()) {
            abort(403, 'Unauthorized');
        }
        
        // Validasi order harus completed
        if (!$order->canBeReviewed()) {
            return redirect()
                ->route('customer.orders.show', $order->order_number)
                ->with('error', 'Order harus selesai terlebih dahulu untuk memberikan ulasan');
        }
        
        // Validasi produk ada di order
        $orderItem = $order->items()->where('product_id', $product->id)->first();
        if (!$orderItem) {
            return redirect()
                ->route('customer.orders.show', $order->order_number)
                ->with('error', 'Produk tidak ditemukan dalam pesanan ini');
        }
        
        // Validasi belum pernah review
        if (auth('customer')->user()->hasReviewedProduct($product->id, $order->id)) {
            return redirect()
                ->route('customer.orders.show', $order->order_number)
                ->with('error', 'Anda sudah memberikan ulasan untuk produk ini');
        }
        
        return view('customer.reviews.create', compact('order', 'product', 'orderItem'));
    }

    /**
     * Submit review (AUTO APPROVED)
     * POST /customer/orders/{order}/products/{product}/review
     */
    public function store(StoreProductReviewRequest $request, Order $order, Product $product)
    {
        // Upload photos (jika ada)
        $photosPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('reviews', 'public');
                $photosPaths[] = $path;
            }
        }
        
        // Create review (LANGSUNG APPROVED)
        $review = ProductReview::create([
            'product_id' => $product->id,
            'customer_id' => auth('customer')->id(),
            'order_id' => $order->id,
            'rating' => $request->rating,
            'review' => $request->review,
            'photos' => $photosPaths,
            'is_approved' => true, // ✅ AUTO APPROVED
        ]);
        
        // Rating produk otomatis terupdate via Model Boot
        
        return redirect()
            ->route('customer.orders.show', $order->order_number)
            ->with('success', 'Terima kasih! Ulasan Anda telah berhasil dikirim.');
    }

    /**
     * AJAX - Check apakah customer bisa review produk
     * GET /customer/orders/{order}/products/{product}/can-review
     */
    public function canReview(Order $order, Product $product)
    {
        // Validasi order milik customer
        if ($order->customer_id !== auth('customer')->id()) {
            return response()->json([
                'can_review' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $canReview = auth('customer')->user()
            ->canReviewProduct($product->id, $order->id);
        
        $message = '';
        if (!$canReview) {
            if (!$order->canBeReviewed()) {
                $message = 'Order harus selesai terlebih dahulu';
            } elseif (!$order->items()->where('product_id', $product->id)->exists()) {
                $message = 'Produk tidak ada dalam pesanan ini';
            } elseif (auth('customer')->user()->hasReviewedProduct($product->id, $order->id)) {
                $message = 'Anda sudah memberikan ulasan untuk produk ini';
            }
        }
        
        return response()->json([
            'can_review' => $canReview,
            'message' => $message
        ]);
    }

    /**
     * BONUS: Halaman my reviews
     * GET /customer/my-reviews
     */
    public function myReviews()
    {
        $reviews = auth('customer')->user()
            ->reviews()
            ->with(['product', 'order'])
            ->latest()
            ->paginate(10);
        
        return view('customer.reviews.index', compact('reviews'));
    }

    /**
     * BONUS: Lihat detail review
     * GET /customer/reviews/{review}
     */
    public function show(ProductReview $review)
    {
        // Validasi review milik customer
        if ($review->customer_id !== auth('customer')->id()) {
            abort(403, 'Unauthorized');
        }
        
        $review->load(['product', 'order']);
        
        return view('customer.reviews.show', compact('review'));
    }

    public function update(StoreProductReviewRequest $request, ProductReview $review)
{
    // Validasi review milik customer
    if ($review->customer_id !== auth('customer')->id()) {
        abort(403, 'Unauthorized');
    }
    
    // Handle photos
    $photosPaths = $review->photos ?? []; // Keep existing photos
    
    // Remove deleted photos
    if ($request->has('existing_photos')) {
        $photosPaths = $request->existing_photos;
    } else {
        // If no existing_photos sent, user deleted all
        $photosPaths = [];
    }
    
    // Upload new photos
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photo) {
            // Max 5 photos total
            if (count($photosPaths) >= 5) {
                break;
            }
            $path = $photo->store('reviews', 'public');
            $photosPaths[] = $path;
        }
    }
    
    // Update review
    $review->update([
        'rating' => $request->rating,
        'review' => $request->review,
        'photos' => $photosPaths,
        'is_approved' => true, // Tetap approved
    ]);
    
    // Log ke Laravel log
    \Log::info("Customer #{$review->customer_id} updated review #{$review->id}: Rating {$review->rating}");
    
    // Rating produk otomatis terupdate via Model Boot
    
    return redirect()
        ->route('customer.reviews.show', $review->id)
        ->with('success', 'Ulasan berhasil diperbarui!');
}
}