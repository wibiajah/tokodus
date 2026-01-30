<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Index - List semua review
     * ✅ AUTO MARK AS VIEWED saat halaman dibuka
     */
    public function index(Request $request)
    {
        $query = ProductReview::with(['customer', 'product', 'order']);
        
        // Filter by product
        if ($request->filled('product_id')) {
            $query->byProduct($request->product_id);
        }
        
        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->byCustomer($request->customer_id);
        }
        
        // Filter by rating
        if ($request->filled('rating')) {
            $query->byRating($request->rating);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($q2) use ($search) {
                    $q2->where('firstname', 'like', "%{$search}%")
                       ->orWhere('lastname', 'like', "%{$search}%");
                })
                ->orWhereHas('product', function($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%");
                })
                ->orWhere('review', 'like', "%{$search}%");
            });
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'newest');
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'highest_rating':
                $query->highestRating();
                break;
            case 'lowest_rating':
                $query->lowestRating();
                break;
            default:
                $query->latest();
        }
        
        $reviews = $query->paginate(20);
        
        // ✅ IMPROVED: Mark only reviews on current page as viewed (lebih efficient)
        // Daripada mark ALL reviews, kita mark yang ada di halaman ini saja
        $reviewIds = $reviews->pluck('id')->toArray();
        if (!empty($reviewIds)) {
            ProductReview::whereIn('id', $reviewIds)
                ->whereNull('viewed_at')
                ->update(['viewed_at' => now()]);
        }
        
        // Data untuk filter dropdown
        $products = Product::orderBy('title')->get(['id', 'title']);
        $customers = Customer::orderBy('firstname')
            ->get(['id', 'firstname', 'lastname']);
        
        // Statistics
        $stats = [
            'total' => ProductReview::count(),
            'average_rating' => round(ProductReview::avg('rating'), 1) ?? 0,
            'total_with_photos' => ProductReview::whereNotNull('photos')
                ->where('photos', '!=', '[]')
                ->count(),
            'reviews_today' => ProductReview::whereDate('created_at', today())->count(),
            'unviewed' => ProductReview::getUnviewedCount(), // ✅ Show unviewed count
        ];
        
        return view('superadmin.reviews.index', 
            compact('reviews', 'products', 'customers', 'stats')
        );
    }

    /**
     * Show - Detail review
     */
    public function show(ProductReview $review)
    {
        // ✅ Mark this specific review as viewed
        $review->markAsViewed();
        
        $review->load(['customer', 'product', 'order.items']);
        
        return view('superadmin.reviews.show', compact('review'));
    }

    /**
     * ✅ AJAX - Get unviewed count (untuk badge realtime)
     */
    public function getUnviewedCount()
    {
        return response()->json([
            'count' => ProductReview::getUnviewedCount()
        ]);
    }

    /**
     * Delete review
     */
    public function destroy(ProductReview $review)
    {
        try {
            // Hapus foto jika ada
            if ($review->photos && is_array($review->photos)) {
                foreach ($review->photos as $photo) {
                    Storage::disk('public')->delete($photo);
                }
            }
            
            $review->delete();
            
            return redirect()
                ->route('superadmin.reviews.index')
                ->with('success', 'Review berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus review: ' . $e->getMessage());
        }
    }

    /**
     * AJAX - Get statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => ProductReview::count(),
            'average_rating' => round(ProductReview::avg('rating'), 1) ?? 0,
            'total_with_photos' => ProductReview::whereNotNull('photos')
                ->where('photos', '!=', '[]')
                ->count(),
            'reviews_today' => ProductReview::whereDate('created_at', today())->count(),
            'reviews_this_week' => ProductReview::whereBetween('created_at', 
                [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'reviews_this_month' => ProductReview::whereMonth('created_at', 
                now()->month)->count(),
            
            // Rating distribution
            'rating_5' => ProductReview::byRating(5)->count(),
            'rating_4' => ProductReview::byRating(4)->count(),
            'rating_3' => ProductReview::byRating(3)->count(),
            'rating_2' => ProductReview::byRating(2)->count(),
            'rating_1' => ProductReview::byRating(1)->count(),
            
            // ✅ Unviewed count
            'unviewed' => ProductReview::getUnviewedCount(),
        ];
        
        return response()->json($stats);
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:product_reviews,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $reviews = ProductReview::whereIn('id', $request->review_ids)->get();
            $count = $reviews->count();
            
            // Hapus foto untuk setiap review
            foreach ($reviews as $review) {
                if ($review->photos && is_array($review->photos)) {
                    foreach ($review->photos as $photo) {
                        Storage::disk('public')->delete($photo);
                    }
                }
            }
            
            ProductReview::whereIn('id', $request->review_ids)->delete();
            
            DB::commit();
            
            return back()->with('success', $count . ' review berhasil dihapus');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus review: ' . $e->getMessage());
        }
    }

    /**
     * AJAX - Get reviews by product
     */
    public function byProduct(Product $product)
    {
        $reviews = $product->reviews()
            ->with(['customer', 'order'])
            ->latest()
            ->paginate(10);
        
        return response()->json([
            'reviews' => $reviews,
            'product' => [
                'id' => $product->id,
                'title' => $product->title,
                'rating' => $product->rating,
                'review_count' => $product->review_count,
            ]
        ]);
    }

    /**
     * AJAX - Get reviews by customer
     */
    public function byCustomer(Customer $customer)
    {
        $reviews = $customer->reviews()
            ->with(['product', 'order'])
            ->latest()
            ->paginate(10);
        
        return response()->json([
            'reviews' => $reviews,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->full_name,
                'total_reviews' => $customer->total_reviews ?? $customer->reviews()->count(),
            ]
        ]);
    }

    /**
     * ✅ BONUS: Mark all as viewed (manual trigger if needed)
     * POST /superadmin/reviews/mark-all-viewed
     */
    public function markAllAsViewed()
    {
        $count = ProductReview::markAllAsViewed();
        
        return response()->json([
            'success' => true,
            'message' => $count . ' review ditandai sudah dilihat',
            'count' => $count
        ]);
    }
    
}