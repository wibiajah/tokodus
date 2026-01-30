<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the customer's wishlist page
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        // Get wishlist dengan eager load product (termasuk variants, categories, dll)
        $wishlists = Wishlist::where('customer_id', $customer->id)
            ->with([
                'product' => function($query) {
                    $query->with(['categories', 'variants'])
                        ->where('is_active', true);
                }
            ])
            ->latest()
            ->get();

        // Filter out products yang sudah dihapus/tidak aktif
        $wishlists = $wishlists->filter(function($wishlist) {
            return $wishlist->product !== null;
        });

        $page_title = 'Wishlist Saya';

        return view('frontend.wishlist.index', compact('wishlists', 'page_title'));
    }

    /**
     * Toggle wishlist (add/remove) - AJAX
     */
    public function toggle(Product $product)
{
    try {
        $customer = Auth::guard('customer')->user();
        
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak tersedia'
            ], 404);
        }

        $result = Wishlist::toggle($customer->id, $product->id);
        $count = Wishlist::where('customer_id', $customer->id)->count();

        return response()->json([
            'success' => true,
            'action' => $result['action'], // 'added' or 'removed'
            'message' => $result['action'] === 'added' 
                ? 'Produk ditambahkan ke wishlist!' 
                : 'Produk dihapus dari wishlist',
            'count' => $count,
            'in_wishlist' => $result['action'] === 'added'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

public function destroy(Wishlist $wishlist)
{
    try {
        $customer = Auth::guard('customer')->user();

        if ($wishlist->customer_id !== $customer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        $wishlist->delete();
        $count = Wishlist::where('customer_id', $customer->id)->count();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari wishlist',
            'count' => $count
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus produk dari wishlist'
        ], 500);
    }
}

public function clear()
{
    try {
        $customer = Auth::guard('customer')->user();
        
        Wishlist::where('customer_id', $customer->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semua wishlist berhasil dihapus'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus wishlist'
        ], 500);
    }
}

    /**
     * Get wishlist count - AJAX
     */
    public function count()
    {
        try {
            $customer = Auth::guard('customer')->user();
            $count = Wishlist::where('customer_id', $customer->id)->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }

    /**
     * Check if product is in wishlist - AJAX
     */
    public function check(Product $product)
    {
        try {
            $customer = Auth::guard('customer')->user();
            $exists = Wishlist::exists($customer->id, $product->id);

            return response()->json([
                'success' => true,
                'in_wishlist' => $exists
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'in_wishlist' => false
            ], 500);
        }
    }

    /**
     * Move all wishlist items to cart - BONUS FEATURE
     */
    public function moveToCart()
    {
        try {
            $customer = Auth::guard('customer')->user();
        
            $wishlists = Wishlist::where('customer_id', $customer->id)
                ->with('product')
                ->get();

            $movedCount = 0;
            $errors = [];

            foreach ($wishlists as $wishlist) {
                $product = $wishlist->product;
                
                if (!$product || !$product->is_active) {
                    continue;
                }

                // Check stock availability
                // Assuming you have a method to get available stock
                // You need to adjust this based on your stock logic
                
                try {
                    // Add to cart logic here
                    // Example: Cart::create([...]);
                    
                    // Remove from wishlist after successfully added to cart
                    $wishlist->delete();
                    $movedCount++;
                    
                } catch (\Exception $e) {
                    $errors[] = $product->title;
                }
            }

            if ($movedCount > 0) {
                $message = "$movedCount produk berhasil dipindahkan ke keranjang";
                if (count($errors) > 0) {
                    $message .= ". Beberapa produk gagal dipindahkan.";
                }
                return redirect()->route('customer.cart.index')
                    ->with('success', $message);
            } else {
                return redirect()->route('customer.wishlist.index')
                    ->with('error', 'Tidak ada produk yang bisa dipindahkan ke keranjang');
            }

        } catch (\Exception $e) {
            return redirect()->route('customer.wishlist.index')
                ->with('error', 'Gagal memindahkan produk ke keranjang');
        }
    }

    /**
     * Clear all wishlist - BONUS FEATURE
     */
    
}