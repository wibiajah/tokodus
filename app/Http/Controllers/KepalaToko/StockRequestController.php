<?php

namespace App\Http\Controllers\KepalaToko;

use App\Http\Controllers\Controller;
use App\Models\StockRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantStock;
use App\Http\Requests\KepalaToko\StoreStockRequestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;

class StockRequestController extends Controller
{
    /**
     * List my toko's stock requests
     */
   public function index(Request $request)
{
    $user = auth()->user();
    $status = $request->get('status', 'all');

    if (!$user->toko_id) {
        abort(403, 'Anda tidak terdaftar di toko manapun.');
    }

    // ✅ HAPUS whereHas() biar tetap muncul meski product null
    $requests = StockRequest::with(['product', 'toko', 'requestedBy', 'processedBy'])
        ->where('toko_id', $user->toko_id)
        ->when($status !== 'all', function($query) use ($status) {
            $query->where('status', $status);
        })
        ->latest()
        ->paginate(20);

    return view('kepala-toko.stock-requests.index', compact('requests', 'status'));
}

    /**
     * Form request stok untuk produk
     */
    public function create(Product $product)
    {
        $user = auth()->user();

        // Pastikan user punya toko_id
        if (!$user->toko_id) {
            abort(403, 'Anda tidak terdaftar di toko manapun.');
        }

        $product->load([
            'variants' => function($query) {
                $query->with('children')->whereNull('parent_id');
            }
        ]);

        // Get leaf variants (yang bisa direquest)
        $leafVariants = $product->variants->flatMap(function($color) {
            if ($color->hasChildren()) {
                return $color->children;
            }
            return [$color];
        });

        // Get current stock di toko ini
        $currentStocks = ProductVariantStock::whereIn('variant_id', $leafVariants->pluck('id'))
            ->where('toko_id', $user->toko_id)
            ->get()
            ->keyBy('variant_id');

        return view('kepala-toko.stock-requests.create', compact('product', 'leafVariants', 'currentStocks'));
    }

    /**
     * Submit stock request
     */
    public function store(StoreStockRequestRequest $request, Product $product)
    {
        $user = auth()->user();

        DB::beginTransaction();

        try {
            // Build items array dengan variant details
            $items = [];
            $warnings = [];

            foreach ($request->variants as $item) {
                $variant = ProductVariant::findOrFail($item['variant_id']);
                $quantity = (int) $item['quantity'];

                // Check: apakah request melebihi stok warehouse?
                if ($quantity > $variant->stock_pusat) {
                    $warnings[] = [
                        'variant' => $variant->display_name,
                        'requested' => $quantity,
                        'available' => $variant->stock_pusat,
                    ];
                }

                $items[] = [
                    'variant_id' => $variant->id,
                    'quantity' => $quantity,
                ];
            }

            // Create stock request
            $stockRequest = StockRequest::create([
                'product_id' => $product->id,
                'toko_id' => $user->toko_id,
                'requested_by' => $user->id,
                'items' => $items,
                'notes' => $request->notes,
                'status' => 'pending',
                'type' => 'request', // ✅ TAMBAHKAN INI
                'requested_at' => now(), 
            ]);

            DB::commit();

          NotificationHelper::stockRequestCreated($stockRequest, auth()->user());
         

            // Jika ada warning, tampilkan
            if (!empty($warnings)) {
                $warningMessage = 'Request berhasil dibuat, namun beberapa varian melebihi stok warehouse:<br>';
                foreach ($warnings as $warning) {
                    $warningMessage .= "- {$warning['variant']}: diminta {$warning['requested']}, tersedia {$warning['available']}<br>";
                }
                
                return redirect()
                    ->route('kepala-toko.stock-requests.show', $stockRequest)
                    ->with('warning', $warningMessage);
            }

            return redirect()
                ->route('kepala-toko.stock-requests.show', $stockRequest)
                ->with('success', 'Request stok berhasil dibuat dan menunggu approval.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal membuat request: ' . $e->getMessage()]);
        }
    }

    /**
     * Show single stock request detail (read-only)
     */
/**
 * Show single stock request detail (read-only)
 */
public function show(StockRequest $stockRequest)
{
    $user = auth()->user();

    // Pastikan request ini milik toko user
    if ($stockRequest->toko_id !== $user->toko_id) {
        abort(403, 'Anda tidak memiliki akses ke request ini.');
    }

    // ✅ Load relasi dasar dulu
    $stockRequest->load(['toko', 'requestedBy', 'processedBy']);

    // ✅ Load product dengan variants HANYA jika product masih ada
    if ($stockRequest->product) {
        $stockRequest->load([
            'product.variants' => function($query) {
                $query->with('children')->whereNull('parent_id');
            }
        ]);
    }

    // ✅ Parse items dengan variant details + null check
    $itemsWithDetails = collect($stockRequest->items)->map(function($item) {
        $variant = ProductVariant::find($item['variant_id']);
        
        return [
            'variant_id' => $item['variant_id'],
            'quantity' => $item['quantity'],
            'variant' => $variant,
            'display_name' => $variant ? $variant->display_name : '[Varian Dihapus]', // ✅ NULL CHECK
            'current_stock_pusat' => $variant ? $variant->stock_pusat : 0,
        ];
    });

    return view('kepala-toko.stock-requests.show', compact('stockRequest', 'itemsWithDetails'));
}

    /**
     * Cancel request (only pending)
     */
    public function cancel(StockRequest $stockRequest)
    {
        $user = auth()->user();

        // Pastikan request ini milik toko user
        if ($stockRequest->toko_id !== $user->toko_id) {
            abort(403, 'Anda tidak memiliki akses ke request ini.');
        }

        // Hanya pending yang bisa dicancel
        if ($stockRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Hanya request pending yang bisa dibatalkan!']);
        }

        try {
            $stockRequest->update([
                'status' => 'cancelled',
                'processed_by' => $user->id,
                'processed_at' => now(),
            ]);

            // 🔥 KIRIM NOTIFIKASI ke SuperAdmin & Admin
         NotificationHelper::stockRequestCancelled($stockRequest, $user);

            return redirect()
                ->route('kepala-toko.stock-requests.index')
                ->with('success', 'Request berhasil dibatalkan.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal cancel request: ' . $e->getMessage()]);
        }
    }

    /**
     * AJAX: Get current stock di toko ini
     */
    public function getTokoStock(Product $product)
    {
        $user = auth()->user();

        if (!$user->toko_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak terdaftar di toko manapun'
            ], 403);
        }

        $product->load([
            'variants' => function($query) use ($user) {
                $query->with([
                    'children.stocks' => function($q) use ($user) {
                        $q->where('toko_id', $user->toko_id);
                    },
                    'stocks' => function($q) use ($user) {
                        $q->where('toko_id', $user->toko_id);
                    }
                ])->whereNull('parent_id');
            }
        ]);

        $stockData = [];

        foreach ($product->variants as $color) {
            if ($color->hasChildren()) {
                foreach ($color->children as $size) {
                    $stock = $size->stocks->first();
                    $stockData[] = [
                        'variant_id' => $size->id,
                        'display_name' => $color->name . ' - ' . $size->name,
                        'stock_toko' => $stock ? $stock->stock : 0,
                        'stock_pusat' => $size->stock_pusat,
                    ];
                }
            } else {
                $stock = $color->stocks->first();
                $stockData[] = [
                    'variant_id' => $color->id,
                    'display_name' => $color->name,
                    'stock_toko' => $stock ? $stock->stock : 0,
                    'stock_pusat' => $color->stock_pusat,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'stocks' => $stockData
        ]);
    }

    /**
     * AJAX: Check if can request (validate business rules)
     */
    public function checkCanRequest(Product $product)
    {
        $user = auth()->user();

        if (!$user->toko_id) {
            return response()->json([
                'can_request' => false,
                'reason' => 'Anda tidak terdaftar di toko manapun'
            ]);
        }

        // Check: ada pending request untuk produk ini?
        $hasPending = StockRequest::where('product_id', $product->id)
            ->where('toko_id', $user->toko_id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return response()->json([
                'can_request' => false,
                'reason' => 'Anda sudah memiliki pending request untuk produk ini'
            ]);
        }

        return response()->json([
            'can_request' => true
        ]);
    }

    /**
     * Get request statistics for dashboard
     */
    public function statistics()
    {
        $user = auth()->user();

        if (!$user->toko_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak terdaftar di toko manapun'
            ], 403);
        }

        $stats = [
            'pending' => StockRequest::where('toko_id', $user->toko_id)
                ->where('status', 'pending')
                ->count(),
            'approved' => StockRequest::where('toko_id', $user->toko_id)
                ->where('status', 'approved')
                ->count(),
            'rejected' => StockRequest::where('toko_id', $user->toko_id)
                ->where('status', 'rejected')
                ->count(),
            'total' => StockRequest::where('toko_id', $user->toko_id)
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'statistics' => $stats
        ]);
    }
}