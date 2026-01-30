<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantStock;
use App\Models\StockDistributionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // Validasi: hanya pending yang bisa diapprove
        if ($stockRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Request ini sudah diproses sebelumnya!']);
        }

        DB::beginTransaction();

        try {
            $distributedItems = [];
            $insufficientStock = [];

            // 1. Validate stock availability untuk semua items
            foreach ($stockRequest->items as $item) {
                $variant = ProductVariant::findOrFail($item['variant_id']);
                $requestedQty = (int) $item['quantity'];

                if ($variant->stock_pusat < $requestedQty) {
                    $insufficientStock[] = [
                        'variant' => $variant->display_name,
                        'requested' => $requestedQty,
                        'available' => $variant->stock_pusat,
                    ];
                }
            }

            // Jika ada stok tidak cukup, rollback
            if (!empty($insufficientStock)) {
                DB::rollBack();

                $errorMessage = 'Stok tidak mencukupi untuk beberapa varian:<br>';
                foreach ($insufficientStock as $item) {
                    $errorMessage .= "- {$item['variant']}: diminta {$item['requested']}, tersedia {$item['available']}<br>";
                }

                return back()->withErrors(['error' => $errorMessage]);
            }

            // 2. Execute distribution
            foreach ($stockRequest->items as $item) {
                $variant = ProductVariant::findOrFail($item['variant_id']);
                $quantity = (int) $item['quantity'];

                // Kurangi stock_pusat
                $variant->decrement('stock_pusat', $quantity);

                // Tambah stock di toko
                $variantStock = ProductVariantStock::firstOrCreate(
                    [
                        'variant_id' => $variant->id,
                        'toko_id' => $stockRequest->toko_id,
                    ],
                    [
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

            // 3. Create distribution log
            StockDistributionLog::create([
                'product_id' => $stockRequest->product_id,
                'toko_id' => $stockRequest->toko_id,
                'distributed_by' => auth()->id(),
                'items' => $distributedItems,
                'notes' => 'Approved from request #' . $stockRequest->id . '. ' . $request->approval_notes,
                'type' => 'from_request',
                'stock_request_id' => $stockRequest->id,
            ]);

            // 4. Update stock request status
            $stockRequest->update([
                'status' => 'approved',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
                'approval_notes' => $request->approval_notes,
            ]);

            DB::commit();

            // 🔥 KIRIM NOTIFIKASI ke toko yang request
            NotificationHelper::notifyTokoRoles(
                $stockRequest->toko_id,
                ['kepala_toko', 'staff_toko'],
                NotificationHelper::stockRequestApproved($stockRequest, auth()->user())
            );

            return redirect()
                ->route('superadmin.stock-requests.show', $stockRequest)
                ->with('success', 'Request berhasil diapprove dan stok sudah didistribusikan!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Gagal approve request: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject stock request
     */
    public function reject(Request $request, StockRequest $stockRequest)
    {
        // Validasi: hanya pending yang bisa direject
        if ($stockRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Request ini sudah diproses sebelumnya!']);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            $stockRequest->update([
                'status' => 'rejected',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            // 🔥 KIRIM NOTIFIKASI ke toko yang request
            NotificationHelper::notifyTokoRoles(
                $stockRequest->toko_id,
                ['kepala_toko', 'staff_toko'],
                NotificationHelper::stockRequestRejected($stockRequest, auth()->user())
            );

            return redirect()
                ->route('superadmin.stock-requests.show', $stockRequest)
                ->with('success', 'Request berhasil ditolak.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal reject request: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancel request (by SuperAdmin)
     */
    public function cancel(StockRequest $stockRequest)
    {
        // Hanya pending yang bisa dicancel
        if ($stockRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Hanya request pending yang bisa dibatalkan!']);
        }

        try {
            $stockRequest->update([
                'status' => 'cancelled',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // 🔥 KIRIM NOTIFIKASI
            NotificationHelper::notifyTokoRoles(
                $stockRequest->toko_id,
                ['kepala_toko', 'staff_toko'],
                NotificationHelper::stockRequestCancelled($stockRequest, auth()->user())
            );

            return redirect()
                ->route('superadmin.stock-requests.index')
                ->with('success', 'Request berhasil dibatalkan.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal cancel request: ' . $e->getMessage()]);
        }
    }

    /**
     * AJAX: Get pending requests count (for badge)
     */
    public function getPendingCount()
    {
        $count = StockRequest::where('status', 'pending')->count();

        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * AJAX: Quick approve (minimal validation)
     */
    public function quickApprove(StockRequest $stockRequest)
    {
        if ($stockRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Request sudah diproses sebelumnya'
            ], 400);
        }

        DB::beginTransaction();

        try {
            $distributedItems = [];

            // Validate & execute
            foreach ($stockRequest->items as $item) {
                $variant = ProductVariant::findOrFail($item['variant_id']);
                $quantity = (int) $item['quantity'];

                if ($variant->stock_pusat < $quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$variant->display_name} tidak mencukupi (tersedia: {$variant->stock_pusat})"
                    ], 400);
                }

                $variant->decrement('stock_pusat', $quantity);

                $variantStock = ProductVariantStock::firstOrCreate(
                    [
                        'variant_id' => $variant->id,
                        'toko_id' => $stockRequest->toko_id,
                    ],
                    ['stock' => 0]
                );

                $stockBefore = $variantStock->stock;
                $variantStock->increment('stock', $quantity);

                if ($variant->parent_id) {
                    $variant->parent->updateParentStock();
                }

                $distributedItems[] = [
                    'variant_id' => $variant->id,
                    'variant_name' => $variant->display_name,
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $variantStock->stock,
                ];
            }

            StockDistributionLog::create([
                'product_id' => $stockRequest->product_id,
                'toko_id' => $stockRequest->toko_id,
                'distributed_by' => auth()->id(),
                'items' => $distributedItems,
                'notes' => 'Quick approved from request #' . $stockRequest->id,
                'type' => 'from_request',
                'stock_request_id' => $stockRequest->id,
            ]);

            $stockRequest->update([
                'status' => 'approved',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
                'approval_notes' => 'Quick approved',
            ]);

            DB::commit();

            NotificationHelper::notifyTokoRoles(
                $stockRequest->toko_id,
                ['kepala_toko', 'staff_toko'],
                NotificationHelper::stockRequestApproved($stockRequest, auth()->user())
            );

            return response()->json([
                'success' => true,
                'message' => 'Request berhasil diapprove!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}