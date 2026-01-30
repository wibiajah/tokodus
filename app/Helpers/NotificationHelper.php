<?php

namespace App\Helpers;

use App\Models\User;
use App\Notifications\ActivityNotification;

class NotificationHelper
{
    /**
     * Kirim notifikasi ke role tertentu dengan filter hierarki
     * 
     * Hierarki:
     * - Super Admin: terima dari Admin, Kepala Toko, Staff
     * - Admin: terima dari Kepala Toko, Staff
     * - Kepala Toko: terima dari Staff (toko yang sama)
     * - Staff: tidak terima dari siapapun
     */
    public static function notifyRoles(array $roles, array $activity)
    {
        // Dapatkan actor (pembuat aksi)
        $actor = auth()->user();
        
        if (!$actor) {
            return; // Jika tidak ada user yang login, skip
        }

        $users = User::whereIn('role', $roles)->get();
        
        foreach ($users as $user) {
            // Skip jika user adalah actor sendiri (opsional, bisa dihapus jika mau terima notif sendiri)
            // if ($user->id === $actor->id) continue;
            
            // Filter: cek apakah user ini boleh menerima notif dari actor
            if (self::canReceiveNotification($user, $actor)) {
                $user->notify(new ActivityNotification($activity));
            }
        }
    }

    /**
     * Cek apakah user boleh menerima notifikasi dari actor
     */
    private static function canReceiveNotification($recipient, $actor)
    {
        // Jika recipient dan actor sama role, boleh terima (untuk sesama super admin, dll)
        // if ($recipient->role === $actor->role) return true;

        // Super Admin: terima dari Admin, Kepala Toko, Staff (dan sesama Super Admin)
        if ($recipient->role === 'super_admin') {
            return in_array($actor->role, ['super_admin', 'admin', 'kepala_toko', 'staff_admin']);
        }

        // Admin: terima dari Kepala Toko, Staff (dan sesama Admin)
        if ($recipient->role === 'admin') {
            return in_array($actor->role, ['admin', 'kepala_toko', 'staff_admin']);
        }

        // Kepala Toko: hanya terima dari Staff di toko yang sama
        if ($recipient->role === 'kepala_toko') {
            return $actor->role === 'staff_admin' && $recipient->toko_id === $actor->toko_id;
        }

        // Staff: tidak terima dari siapapun
        if ($recipient->role === 'staff_admin') {
            return false;
        }

        return false;
    }

    /**
     * Template notifikasi User Created
     */
    public static function userCreated($user, $creator)
    {
        $roleName = User::ROLES[$user->role] ?? $user->role;
        
        $tokoInfo = '';
        if ($user->toko) {
            if ($user->toko_id == 999) {
                $tokoInfo = " di Head Office";
            } else {
                $tokoInfo = " di {$user->toko->nama_toko}";
            }
        }
        
        $activity = [
            'type' => 'user_created',
            'message' => "User baru '{$user->name}' ({$roleName}) ditambahkan{$tokoInfo}",
            'actor_name' => $creator->name,
            'icon' => 'fas fa-user-plus text-success',
            'url' => route('superadmin.user.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi User Updated
     */
    public static function userUpdated($user, $updater)
    {
        $roleName = User::ROLES[$user->role] ?? $user->role;
        
        $activity = [
            'type' => 'user_updated',
            'message' => "Data user '{$user->name}' ({$roleName}) diperbarui",
            'actor_name' => $updater->name,
            'icon' => 'fas fa-user-edit text-warning',
            'url' => route('superadmin.user.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi User Deleted
     */
    public static function userDeleted($userName, $deleter)
    {
        $activity = [
            'type' => 'user_deleted',
            'message' => "User '{$userName}' telah dihapus dari sistem",
            'actor_name' => $deleter->name,
            'icon' => 'fas fa-user-times text-danger',
            'url' => route('superadmin.user.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi Toko Created
     */
    public static function tokoCreated($toko, $creator)
    {
        $statusInfo = $toko->status === 'aktif' ? ' (Status: Aktif)' : ' (Status: Tidak Aktif)';
        
        $activity = [
            'type' => 'toko_created',
            'message' => "Toko '{$toko->nama_toko}' berhasil ditambahkan{$statusInfo}",
            'actor_name' => $creator->name,
            'icon' => 'fas fa-store text-success',
            'url' => route('superadmin.toko.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi Toko Updated
     */
    public static function tokoUpdated($toko, $updater)
    {
        $activity = [
            'type' => 'toko_updated',
            'message' => "Data toko '{$toko->nama_toko}' telah diperbarui",
            'actor_name' => $updater->name,
            'icon' => 'fas fa-store text-warning',
            'url' => route('superadmin.toko.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi Toko Deleted
     */
    public static function tokoDeleted($namaToko, $deleter)
    {
        $activity = [
            'type' => 'toko_deleted',
            'message' => "Toko '{$namaToko}' telah dihapus dari sistem",
            'actor_name' => $deleter->name,
            'icon' => 'fas fa-trash-alt text-danger',
            'url' => route('superadmin.toko.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi Kepala Toko Changed
     */
    public static function kepalaTokoChanged($toko, $newKepala, $changer)
    {
        $activity = [
            'type' => 'kepala_toko_changed',
            'message' => "Kepala Toko '{$toko->nama_toko}' diganti menjadi {$newKepala->name}",
            'actor_name' => $changer->name,
            'icon' => 'fas fa-user-tie text-info',
            'url' => route('superadmin.toko.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi Kepala Toko Removed
     */
    public static function kepalaTokoRemoved($toko, $remover)
    {
        $activity = [
            'type' => 'kepala_toko_removed',
            'message' => "Kepala Toko dari '{$toko->nama_toko}' telah dihapus (Status: Tidak Aktif)",
            'actor_name' => $remover->name,
            'icon' => 'fas fa-user-minus text-warning',
            'url' => route('superadmin.toko.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi Toko Status Changed
     */
    public static function tokoStatusChanged($toko, $changer)
    {
        $statusText = $toko->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        $iconClass = $toko->status === 'aktif' ? 'text-success' : 'text-secondary';
        
        $activity = [
            'type' => 'toko_status_changed',
            'message' => "Toko '{$toko->nama_toko}' berhasil {$statusText}",
            'actor_name' => $changer->name,
            'icon' => "fas fa-toggle-" . ($toko->status === 'aktif' ? 'on' : 'off') . " {$iconClass}",
            'url' => route('superadmin.toko.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi Kepala Toko Replaced
     */
    public static function kepalaTokoReplaced($oldKepala, $newKepala, $toko, $changer)
    {
        $activity = [
            'type' => 'kepala_toko_replaced',
            'message' => "Kepala Toko '{$toko->nama_toko}' diganti dari {$oldKepala->name} ke {$newKepala->name}",
            'actor_name' => $changer->name,
            'icon' => 'fas fa-exchange-alt text-warning',
            'url' => route('superadmin.toko.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    // ========================================
    // 🔥 CATEGORY NOTIFICATION TEMPLATES
    // ========================================

    /**
     * Template notifikasi Category Created
     */
    public static function categoryCreated($category, $creator)
    {
        $statusInfo = $category->is_active ? ' (Status: Aktif)' : ' (Status: Tidak Aktif)';
        
        $activity = [
            'type' => 'category_created',
            'message' => "Kategori '{$category->name}' berhasil ditambahkan{$statusInfo}",
            'actor_name' => $creator->name,
            'icon' => 'fas fa-tags text-success',
            'url' => route('superadmin.categories.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi Category Updated
     */
    public static function categoryUpdated($category, $updater)
    {
        $activity = [
            'type' => 'category_updated',
            'message' => "Kategori '{$category->name}' telah diperbarui",
            'actor_name' => $updater->name,
            'icon' => 'fas fa-tag text-warning',
            'url' => route('superadmin.categories.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi Category Deleted
     */
    public static function categoryDeleted($categoryName, $deleter)
    {
        $activity = [
            'type' => 'category_deleted',
            'message' => "Kategori '{$categoryName}' telah dihapus dari sistem",
            'actor_name' => $deleter->name,
            'icon' => 'fas fa-trash-alt text-danger',
            'url' => route('superadmin.categories.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    // ========================================
    // 🔥 PRODUCT NOTIFICATION TEMPLATES
    // ========================================

    /**
     * Template notifikasi Product Created
     */
    public static function productCreated($product, $creator)
    {
        $categoryNames = $product->categories->pluck('name')->join(', ');
        $categoryInfo = $categoryNames ? " (Kategori: {$categoryNames})" : '';
        
        $activity = [
            'type' => 'product_created',
            'message' => "Produk '{$product->title}' berhasil ditambahkan{$categoryInfo}",
            'actor_name' => $creator->name,
            'icon' => 'fas fa-box text-success',
            'url' => route('superadmin.products.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi Product Updated
     */
    public static function productUpdated($product, $updater)
    {
        $activity = [
            'type' => 'product_updated',
            'message' => "Produk '{$product->title}' telah diperbarui",
            'actor_name' => $updater->name,
            'icon' => 'fas fa-box-open text-warning',
            'url' => route('superadmin.products.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

    /**
     * Template notifikasi Product Deleted
     */
    public static function productDeleted($productTitle, $deleter)
    {
        $activity = [
            'type' => 'product_deleted',
            'message' => "Produk '{$productTitle}' telah dihapus dari sistem",
            'actor_name' => $deleter->name,
            'icon' => 'fas fa-trash-alt text-danger',
            'url' => route('superadmin.products.index'),
        ];

        // 🔥 KIRIM NOTIFIKASI
        self::notifyRoles(['super_admin', 'admin'], $activity);
    }

public static function stockDistributed($product, $toko, $distributedItems, $distributor)
{
    $totalQty = collect($distributedItems)->sum('quantity');
    $variantCount = count($distributedItems);
    
    $activity = [
        'type' => 'stock_distributed',
        'message' => "Stok produk '{$product->title}' didistribusikan ke {$toko->nama_toko} ({$variantCount} varian, total {$totalQty} unit)",
        'actor_name' => $distributor->name,
        'icon' => 'fas fa-truck text-info',
        'url' => route('superadmin.stocks.detail', $product->id), // ✅ FIXED: ganti route yang benar
    ];

    // 🔥 KIRIM NOTIFIKASI
    self::notifyRoles(['super_admin', 'admin'], $activity);
}

/**
 * Template notifikasi Stock Received (untuk toko penerima)
 */
public static function stockReceived($product, $toko, $distributedItems, $distributor)
{
    $totalQty = collect($distributedItems)->sum('quantity');
    $variantCount = count($distributedItems);
    
    $activity = [
        'type' => 'stock_received',
        'message' => "Toko Anda menerima stok produk '{$product->title}' ({$variantCount} varian, total {$totalQty} unit)",
        'actor_name' => $distributor->name,
        'icon' => 'fas fa-box-open text-success',
        'url' => '#', // Bisa diganti dengan route ke stock list kepala toko
    ];

    // 🔥 KIRIM NOTIFIKASI ke Kepala Toko & Staff di toko tersebut
    $users = \App\Models\User::where('toko_id', $toko->id)
        ->whereIn('role', ['kepala_toko', 'staff_admin'])
        ->get();
    
    foreach ($users as $user) {
        $user->notify(new \App\Notifications\ActivityNotification($activity));
    }
}

// ========================================
// 🔥 STOCK REQUEST NOTIFICATION TEMPLATES
// ========================================

/**
 * Template notifikasi Stock Request Created (dari Kepala Toko/Staff)
 */
public static function stockRequestCreated($stockRequest, $requester)
{
    $totalQty = collect($stockRequest->items)->sum('quantity');
    $variantCount = count($stockRequest->items);
    
    $activity = [
        'type' => 'stock_request_created',
        'message' => "Permintaan stok baru dari {$stockRequest->toko->nama_toko} untuk produk '{$stockRequest->product->title}' ({$variantCount} varian, total {$totalQty} unit)",
        'actor_name' => $requester->name,
        'icon' => 'fas fa-paper-plane text-primary',
        'url' => route('superadmin.stock-requests.show', $stockRequest->id),
    ];

    // 🔥 KIRIM NOTIFIKASI
    self::notifyRoles(['super_admin', 'admin'], $activity);
}

/**
 * Template notifikasi Stock Request Approved (oleh Super Admin/Admin)
 */
public static function stockRequestApproved($stockRequest, $distributedItems, $approver)
{
    $totalQty = collect($distributedItems)->sum('quantity');
    $variantCount = count($distributedItems);
    
    $activity = [
        'type' => 'stock_request_approved',
        'message' => "Permintaan stok untuk produk '{$stockRequest->product->title}' telah disetujui ({$variantCount} varian, total {$totalQty} unit)",
        'actor_name' => $approver->name,
        'icon' => 'fas fa-check-circle text-success',
        'url' => route('superadmin.stock-requests.show', $stockRequest->id),
    ];

    // 🔥 KIRIM NOTIFIKASI ke hierarki atas
    self::notifyRoles(['super_admin', 'admin'], $activity);
}

/**
 * Template notifikasi Stock Request Received (untuk toko penerima)
 */
public static function stockRequestReceivedByToko($stockRequest, $distributedItems, $approver)
{
    $totalQty = collect($distributedItems)->sum('quantity');
    $variantCount = count($distributedItems);
    
    $activity = [
        'type' => 'stock_request_received',
        'message' => "Permintaan stok Anda untuk produk '{$stockRequest->product->title}' telah disetujui dan dikirim ({$variantCount} varian, total {$totalQty} unit)",
        'actor_name' => $approver->name,
        'icon' => 'fas fa-box-open text-success',
        'url' => '#', // Bisa route ke stock list kepala toko
    ];

    // 🔥 KIRIM NOTIFIKASI ke Kepala Toko & Staff di toko tersebut
    $users = \App\Models\User::where('toko_id', $stockRequest->toko_id)
        ->whereIn('role', ['kepala_toko', 'staff_admin'])
        ->get();
    
    foreach ($users as $user) {
        $user->notify(new \App\Notifications\ActivityNotification($activity));
    }
}

/**
 * Template notifikasi Stock Request Rejected
 */
public static function stockRequestRejected($stockRequest, $rejecter)
{
    $activity = [
        'type' => 'stock_request_rejected',
        'message' => "Permintaan stok untuk produk '{$stockRequest->product->title}' ditolak. Alasan: {$stockRequest->rejection_reason}",
        'actor_name' => $rejecter->name,
        'icon' => 'fas fa-times-circle text-danger',
        'url' => '#',
    ];

    // 🔥 KIRIM NOTIFIKASI ke pembuat request (Kepala Toko/Staff)
    $stockRequest->requestedBy->notify(new \App\Notifications\ActivityNotification($activity));
}

/**
 * Template notifikasi Stock Request Cancelled
 */
public static function stockRequestCancelled($stockRequest, $canceller)
{
    $activity = [
        'type' => 'stock_request_cancelled',
        'message' => "Permintaan stok dari {$stockRequest->toko->nama_toko} untuk produk '{$stockRequest->product->title}' telah dibatalkan",
        'actor_name' => $canceller->name,
        'icon' => 'fas fa-ban text-warning',
        'url' => route('superadmin.stock-requests.show', $stockRequest->id),
    ];

    // 🔥 KIRIM NOTIFIKASI
    self::notifyRoles(['super_admin', 'admin'], $activity);
}

// ========================================
// 🔥 MANUAL STOCK EDIT NOTIFICATION
// ========================================

/**
 * Template notifikasi Manual Stock Edit (oleh Kepala Toko/Staff)
 */
public static function stockManuallyEdited($product, $toko, $editedItems, $editor)
{
    $totalChanges = collect($editedItems)->sum(function($item) {
        return abs($item['change']);
    });
    $variantCount = count($editedItems);
    
    // Hitung total tambah dan kurang
    $totalAdded = collect($editedItems)->where('change', '>', 0)->sum('change');
    $totalReduced = collect($editedItems)->where('change', '<', 0)->sum(function($item) {
        return abs($item['change']);
    });
    
    $changeText = [];
    if ($totalAdded > 0) {
        $changeText[] = "+{$totalAdded} unit";
    }
    if ($totalReduced > 0) {
        $changeText[] = "-{$totalReduced} unit";
    }
    $changeDescription = implode(', ', $changeText);
    
    $activity = [
        'type' => 'stock_manually_edited',
        'message' => "{$toko->nama_toko} melakukan edit stok manual untuk produk '{$product->title}' ({$variantCount} varian: {$changeDescription})",
        'actor_name' => $editor->name,
        'icon' => 'fas fa-edit text-warning',
        'url' => route('superadmin.stocks.detail', $product->id),
    ];

    // 🔥 KIRIM NOTIFIKASI ke Super Admin & Admin
    self::notifyRoles(['super_admin', 'admin'], $activity);
}

// ========================================
// 🔥 PRODUCT IN TOKO NOTIFICATION TEMPLATES
// ========================================

/**
 * Template notifikasi Product Added to Toko
 */
public static function productAddedToToko($product, $toko, $adder)
{
    $activity = [
        'type' => 'product_added_to_toko',
        'message' => "{$toko->nama_toko} menambahkan produk '{$product->title}' ke tokonya",
        'actor_name' => $adder->name,
        'icon' => 'fas fa-plus-circle text-success',
        'url' => route('superadmin.stocks.detail', $product->id),
    ];

    // 🔥 KIRIM NOTIFIKASI ke Super Admin & Admin
    self::notifyRoles(['super_admin', 'admin'], $activity);
}

/**
 * Template notifikasi Product Status Toggled in Toko
 */
public static function productStatusToggledInToko($product, $toko, $newStatus, $toggler)
{
    $statusText = $newStatus ? 'mengaktifkan' : 'menonaktifkan';
    $iconClass = $newStatus ? 'text-success' : 'text-warning';
    
    $activity = [
        'type' => 'product_status_toggled',
        'message' => "{$toko->nama_toko} {$statusText} produk '{$product->title}' di tokonya",
        'actor_name' => $toggler->name,
        'icon' => "fas fa-toggle-" . ($newStatus ? 'on' : 'off') . " {$iconClass}",
        'url' => route('superadmin.stocks.detail', $product->id),
    ];

    // 🔥 KIRIM NOTIFIKASI ke Super Admin & Admin
    self::notifyRoles(['super_admin', 'admin'], $activity);
}

// ========================================
// 🔥 VOUCHER NOTIFICATION TEMPLATES
// ========================================

/**
 * Template notifikasi Voucher Created
 */
public static function voucherCreated($voucher, $creator)
{
    $activity = [
        'type' => 'voucher_created',
        'message' => "Voucher '{$voucher->name}' ({$voucher->code}) berhasil dibuat dengan diskon {$voucher->discount_display}",
        'actor_name' => $creator->name,
        'icon' => 'fas fa-ticket-alt text-success',
        'url' => route('superadmin.vouchers.show', $voucher->id),
    ];

    // 🔥 KIRIM NOTIFIKASI
    self::notifyRoles(['super_admin', 'admin'], $activity);
}

/**
 * Template notifikasi Voucher Updated
 */
public static function voucherUpdated($voucher, $updater)
{
    $activity = [
        'type' => 'voucher_updated',
        'message' => "Voucher '{$voucher->name}' ({$voucher->code}) telah diperbarui",
        'actor_name' => $updater->name,
        'icon' => 'fas fa-edit text-warning',
        'url' => route('superadmin.vouchers.show', $voucher->id),
    ];

    // 🔥 KIRIM NOTIFIKASI
    self::notifyRoles(['super_admin', 'admin'], $activity);
}

/**
 * Template notifikasi Voucher Deleted
 */
public static function voucherDeleted($voucherName, $voucherCode, $deleter)
{
    $activity = [
        'type' => 'voucher_deleted',
        'message' => "Voucher '{$voucherName}' ({$voucherCode}) telah dihapus dari sistem",
        'actor_name' => $deleter->name,
        'icon' => 'fas fa-trash-alt text-danger',
        'url' => route('superadmin.vouchers.index'),
    ];

    // 🔥 KIRIM NOTIFIKASI
    self::notifyRoles(['super_admin', 'admin'], $activity);
}

/**
 * Template notifikasi Voucher Status Changed
 */
public static function voucherStatusChanged($voucher, $changer)
{
    $statusText = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';
    
    $activity = [
        'type' => 'voucher_status_changed',
        'message' => "Voucher '{$voucher->name}' ({$voucher->code}) berhasil {$statusText}",
        'actor_name' => $changer->name,
        'icon' => 'fas fa-toggle-' . ($voucher->is_active ? 'on' : 'off') . ' text-info',
        'url' => route('superadmin.vouchers.show', $voucher->id),
    ];

    // 🔥 KIRIM NOTIFIKASI
    self::notifyRoles(['super_admin', 'admin'], $activity);
}

/**
 * Template notifikasi Voucher Used (saat customer pakai voucher)
 */
public static function voucherUsed($voucher, $customer, $order, $discountAmount)
{
    $formattedDiscount = 'Rp ' . number_format($discountAmount, 0, ',', '.');
    
    $activity = [
        'type' => 'voucher_used',
        'message' => "Voucher '{$voucher->name}' digunakan oleh {$customer->full_name} dengan diskon {$formattedDiscount} (Order #{$order->order_number})",
        'actor_name' => $customer->full_name,
        'icon' => 'fas fa-shopping-cart text-primary',
        'url' => route('superadmin.vouchers.show', $voucher->id),
    ];

    // 🔥 KIRIM NOTIFIKASI
    self::notifyRoles(['super_admin', 'admin'], $activity);
}
}