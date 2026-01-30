<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\StockRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantStock;
use App\Models\StockDistributionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use App\Helpers\NotificationHelper;

class StockRequestController extends Controller
{
    /**
     * List all pending/approved/rejected stock requests
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $requests = StockRequest::with(['product', 'toko', 'requestedBy', 'processedBy'])
            ->when($status !== 'all', function($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        return view('superadmin.stock-requests.index', compact('requests', 'status'));
    }

    /**
     * Show single stock request detail
     */
    public function show(StockRequest $stockRequest)
    {
        $stockRequest->load([
            'product.variants' => function($query) {
                $query->with('children')->whereNull('parent_id');
            },
            'toko',
            'requestedBy',
            'processedBy'
        ]);

        // Parse items untuk mendapatkan variant details
        $itemsWithDetails = collect($stockRequest->items)->map(function($item) {
            $variant = ProductVariant::find($item['variant_id']);
            
            return [
                'variant_id' => $item['variant_id'],
                'quantity' => $item['quantity'],
                'variant' => $variant,
                'display_name' => $variant ? $variant->display_name : 'N/A',
                'current_stock_pusat' => $variant ? $variant->stock_pusat : 0,
            ];
        });

        return view('superadmin.stock-requests.show', compact('stockRequest', 'itemsWithDetails'));
    }

    /**
     * Approve stock request & execute distribution
     */
    public function approve(Request $request, StockRequest $stockRequest)
    {
        // ============================================
        // 1. AUTHORIZATION & STATUS CHECK
        // ============================================
        if ($stockRequest->status !== 'pending') {
            Log::warning('Attempt to approve non-pending request', [
                'request_id' => $stockRequest->id,
                'current_status' => $stockRequest->status,
                'user_id' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request ini sudah diproses sebelumnya!'
                ], 400);
            }

            return back()->withErrors(['error' => 'Request ini sudah diproses sebelumnya!']);
        }

        // ============================================
        // 2. RATE LIMITING (Prevent spam)
        // ============================================
        $rateLimitKey = 'stock_request_approve_' . auth()->id();
        
        if (Cache::has($rateLimitKey)) {
            Log::warning('Rate limit exceeded for approve', [
                'user_id' => auth()->id(),
                'request_id' => $stockRequest->id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak request. Tunggu beberapa detik.'
                ], 429);
            }

            return back()->withErrors(['error' => 'Terlalu banyak request. Tunggu beberapa detik.']);
        }

        // Set rate limit: 1 request per 3 seconds
        Cache::put($rateLimitKey, true, now()->addSeconds(3));

        // ============================================
        // 3. VALIDATE STOCK AVAILABILITY
        // ============================================
        DB::beginTransaction();

        try {
            $distributedItems = [];
            $insufficientStock = [];

            foreach ($stockRequest->items as $item) {
                $variant = ProductVariant::lockForUpdate()->find($item['variant_id']);

                if (!$variant) {
                    throw new \Exception("Variant ID {$item['variant_id']} tidak ditemukan");
                }

                $requestedQty = (int) $item['quantity'];

                // Validate stock
                if ($variant->stock_pusat < $requestedQty) {
                    $insufficientStock[] = [
                        'variant' => $variant->display_name,
                        'requested' => $requestedQty,
                        'available' => $variant->stock_pusat,
                    ];
                }
            }

            // Rollback jika ada stok tidak cukup
            if (!empty($insufficientStock)) {
                DB::rollBack();

                $errorMessage = 'Stok tidak mencukupi untuk beberapa varian:<br>';
                foreach ($insufficientStock as $item) {
                    $errorMessage .= "- {$item['variant']}: diminta {$item['requested']}, tersedia {$item['available']}<br>";
                }

                Log::warning('Insufficient stock for approval', [
                    'request_id' => $stockRequest->id,
                    'insufficient_items' => $insufficientStock
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => strip_tags($errorMessage)
                    ], 400);
                }

                return back()->withErrors(['error' => $errorMessage]);
            }

            // ============================================
            // 4. EXECUTE DISTRIBUTION
            // ============================================
            foreach ($stockRequest->items as $item) {
                $variant = ProductVariant::lockForUpdate()->findOrFail($item['variant_id']);
                $quantity = (int) $item['quantity'];

                // Kurangi stock_pusat
                $variant->decrement('stock_pusat', $quantity);

                // Tambah/update stock di toko
                $variantStock = ProductVariantStock::firstOrCreate(
                    [
                        'variant_id' => $variant->id,
                        'toko_id' => $stockRequest->toko_id,
                    ],
                    [
                        'product_id' => $variant->product_id,
                        'stock' => 0
                    ]
                );

                $stockBefore = $variantStock->stock;
                $variantStock->increment('stock', $quantity);

                // Update parent color stock (jika ini size)
                if ($variant->parent_id) {
                    $parent = $variant->parent;
                    $parent->updateParentStock();
                }

                $distributedItems[] = [
                    'variant_id' => $variant->id,
                    'variant_name' => $variant->display_name,
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $variantStock->stock,
                ];
            }

            // ============================================
            // 5. CREATE DISTRIBUTION LOGS
            // ============================================
            foreach ($distributedItems as $item) {
                StockDistributionLog::create([
                    'product_id' => $stockRequest->product_id,
                    'toko_id' => $stockRequest->toko_id,
                    'variant_id' => $item['variant_id'],
                    'source_id' => $stockRequest->id,
                    'performed_by' => auth()->id(),
                    'quantity' => $item['quantity'],
                    'stock_before' => $item['stock_before'],
                    'stock_after' => $item['stock_after'],
                    'notes' => 'Approved from request #' . $stockRequest->id . '. ' . $request->approval_notes,
                    'type' => 'from_request',
                ]);
            }

            // ============================================
            // 6. UPDATE REQUEST STATUS
            // ============================================
            $stockRequest->update([
                'status' => 'approved',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
                'approval_notes' => $request->approval_notes,
            ]);

            DB::commit();

            NotificationHelper::stockRequestApproved($stockRequest, $distributedItems, auth()->user());

// Notif 2: Ke Kepala Toko & Staff di toko penerima
NotificationHelper::stockRequestReceivedByToko($stockRequest, $distributedItems, auth()->user());

            // ============================================
            // 7. LOGGING & NOTIFICATION
            // ============================================
            Log::info('Stock request approved successfully', [
                'request_id' => $stockRequest->id,
                'toko_id' => $stockRequest->toko_id,
                'product_id' => $stockRequest->product_id,
                'total_items' => count($distributedItems),
                'processed_by' => auth()->id()
            ]);

            // TODO: Send notification to toko
            // NotificationHelper::notifyToko($stockRequest->toko, ...);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Request berhasil diapprove dan stok sudah didistribusikan!',
                    'data' => [
                        'request_id' => $stockRequest->id,
                        'total_distributed' => count($distributedItems)
                    ]
                ]);
            }

            return redirect()
                ->route('superadmin.stock-requests.show', $stockRequest)
                ->with('success', 'Request berhasil diapprove dan stok sudah didistribusikan!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to approve stock request', [
                'request_id' => $stockRequest->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal approve request: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Gagal approve request: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject stock request
     */
    public function reject(Request $request, StockRequest $stockRequest)
    {
        // ============================================
        // 1. STATUS CHECK
        // ============================================
        if ($stockRequest->status !== 'pending') {
            Log::warning('Attempt to reject non-pending request', [
                'request_id' => $stockRequest->id,
                'current_status' => $stockRequest->status,
                'user_id' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request ini sudah diproses sebelumnya!'
                ], 400);
            }

            return back()->withErrors(['error' => 'Request ini sudah diproses sebelumnya!']);
        }

        // ============================================
        // 2. RATE LIMITING
        // ============================================
        $rateLimitKey = 'stock_request_reject_' . auth()->id();
        
        if (Cache::has($rateLimitKey)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak request. Tunggu beberapa detik.'
                ], 429);
            }

            return back()->withErrors(['error' => 'Terlalu banyak request. Tunggu beberapa detik.']);
        }

        Cache::put($rateLimitKey, true, now()->addSeconds(3));

        // ============================================
        // 3. VALIDATION
        // ============================================
        try {
            $validated = $request->validate([
                'rejection_reason' => 'required|string|min:10|max:500',
            ], [
                'rejection_reason.required' => 'Alasan reject harus diisi',
                'rejection_reason.min' => 'Alasan reject minimal 10 karakter',
                'rejection_reason.max' => 'Alasan reject maksimal 500 karakter',
            ]);
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->errors()['rejection_reason'][0] ?? 'Validation failed'
                ], 422);
            }
            throw $e;
        }

        // ============================================
        // 4. EXECUTE REJECTION
        // ============================================
        try {
            $stockRequest->update([
                'status' => 'rejected',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            Log::info('Stock request rejected', [
                'request_id' => $stockRequest->id,
                'reason' => $validated['rejection_reason'],
                'processed_by' => auth()->id()
            ]);

        // 🔥 KIRIM NOTIFIKASI ke Kepala Toko & Staff pembuat request
NotificationHelper::stockRequestRejected($stockRequest, auth()->user());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Request berhasil ditolak.'
                ]);
            }

            return redirect()
                ->route('superadmin.stock-requests.show', $stockRequest)
                ->with('success', 'Request berhasil ditolak.');

        } catch (\Exception $e) {
            Log::error('Failed to reject stock request', [
                'request_id' => $stockRequest->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal reject request: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Gagal reject request: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancel request (by SuperAdmin)
     */
    public function cancel(StockRequest $stockRequest)
    {
        if ($stockRequest->status !== 'pending') {
            Log::warning('Attempt to cancel non-pending request', [
                'request_id' => $stockRequest->id,
                'current_status' => $stockRequest->status,
                'user_id' => auth()->id()
            ]);

            return back()->withErrors(['error' => 'Hanya request pending yang bisa dibatalkan!']);
        }

        try {
            $stockRequest->update([
                'status' => 'cancelled',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            Log::info('Stock request cancelled', [
                'request_id' => $stockRequest->id,
                'processed_by' => auth()->id()
            ]);

            return redirect()
                ->route('superadmin.stock-requests.index')
                ->with('success', 'Request berhasil dibatalkan.');

        } catch (\Exception $e) {
            Log::error('Failed to cancel stock request', [
                'request_id' => $stockRequest->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Gagal cancel request: ' . $e->getMessage()]);
        }
    }

    /**
     * AJAX: Get pending requests count (for badge)
     */
    public function getPendingCount()
    {
        try {
            $count = Cache::remember('stock_requests_pending_count', 60, function() {
                return StockRequest::where('status', 'pending')->count();
            });

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get pending count', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }

    /**
     * AJAX: Quick approve (minimal validation)
     */
    public function quickApprove(StockRequest $stockRequest)
    {
        // Reuse approve method logic
        $request = request();
        $request->merge(['approval_notes' => 'Quick approved']);
        
        return $this->approve($request, $stockRequest);
    }
}