<?php

namespace App\Helpers;

use App\Models\User;
use App\Notifications\ActivityNotification;

class NotificationHelper
{
    /**
     * Kirim notifikasi ke role tertentu
     */
    public static function notifyRoles(array $roles, array $activity)
    {
        $users = User::whereIn('role', $roles)->get();
        
        foreach ($users as $user) {
            // Kirim notifikasi ke semua user termasuk diri sendiri
            $user->notify(new ActivityNotification($activity));
        }
    }

    /**
     * Template notifikasi User Created
     */
    public static function userCreated($user, $creator)
    {
        $roleName = User::ROLES[$user->role] ?? $user->role;
        
        // 🔥 HANDLE HEAD OFFICE
        $tokoInfo = '';
        if ($user->toko) {
            if ($user->toko_id == 999) {
                $tokoInfo = " di Head Office";
            } else {
                $tokoInfo = " di {$user->toko->nama_toko}";
            }
        }
        
        return [
            'type' => 'user_created',
            'message' => "User baru '{$user->name}' ({$roleName}) ditambahkan{$tokoInfo}",
            'actor_name' => $creator->name,
            'icon' => 'fas fa-user-plus text-success',
            'url' => route('superadmin.user.index'), // ✅ Fixed route
        ];
    }

    /**
     * Template notifikasi User Updated
     */
    public static function userUpdated($user, $updater)
    {
        $roleName = User::ROLES[$user->role] ?? $user->role;
        
        return [
            'type' => 'user_updated',
            'message' => "Data user '{$user->name}' ({$roleName}) diperbarui",
            'actor_name' => $updater->name,
            'icon' => 'fas fa-user-edit text-warning',
            'url' => route('superadmin.user.index'), // ✅ Fixed route
        ];
    }

    /**
     * Template notifikasi User Deleted
     */
    public static function userDeleted($userName, $deleter)
    {
        return [
            'type' => 'user_deleted',
            'message' => "User '{$userName}' telah dihapus dari sistem",
            'actor_name' => $deleter->name,
            'icon' => 'fas fa-user-times text-danger',
            'url' => route('superadmin.user.index'), // ✅ Fixed route
        ];
    }

    /**
     * Template notifikasi Toko Created
     */
    public static function tokoCreated($toko, $creator)
    {
        $statusInfo = $toko->status === 'aktif' ? ' (Status: Aktif)' : ' (Status: Tidak Aktif)';
        
        return [
            'type' => 'toko_created',
            'message' => "Toko '{$toko->nama_toko}' berhasil ditambahkan{$statusInfo}",
            'actor_name' => $creator->name,
            'icon' => 'fas fa-store text-success',
            'url' => route('superadmin.toko.index'),
        ];
    }

    /**
     * Template notifikasi Toko Updated
     */
    public static function tokoUpdated($toko, $updater)
    {
        return [
            'type' => 'toko_updated',
            'message' => "Data toko '{$toko->nama_toko}' telah diperbarui",
            'actor_name' => $updater->name,
            'icon' => 'fas fa-store text-warning',
            'url' => route('superadmin.toko.index'),
        ];
    }

    /**
     * Template notifikasi Toko Deleted
     */
    public static function tokoDeleted($namaToko, $deleter)
    {
        return [
            'type' => 'toko_deleted',
            'message' => "Toko '{$namaToko}' telah dihapus dari sistem",
            'actor_name' => $deleter->name,
            'icon' => 'fas fa-trash-alt text-danger',
            'url' => route('superadmin.toko.index'),
        ];
    }

    /**
     * Template notifikasi Kepala Toko Changed
     */
    public static function kepalaTokoChanged($toko, $newKepala, $changer)
    {
        return [
            'type' => 'kepala_toko_changed',
            'message' => "Kepala Toko '{$toko->nama_toko}' diganti menjadi {$newKepala->name}",
            'actor_name' => $changer->name,
            'icon' => 'fas fa-user-tie text-info',
            'url' => route('superadmin.toko.index'),
        ];
    }

    /**
     * Template notifikasi Kepala Toko Removed
     */
    public static function kepalaTokoRemoved($toko, $remover)
    {
        return [
            'type' => 'kepala_toko_removed',
            'message' => "Kepala Toko dari '{$toko->nama_toko}' telah dihapus (Status: Tidak Aktif)",
            'actor_name' => $remover->name,
            'icon' => 'fas fa-user-minus text-warning',
            'url' => route('superadmin.toko.index'),
        ];
    }

    /**
     * Template notifikasi Toko Status Changed
     */
    public static function tokoStatusChanged($toko, $changer)
    {
        $statusText = $toko->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        $iconClass = $toko->status === 'aktif' ? 'text-success' : 'text-secondary';
        
        return [
            'type' => 'toko_status_changed',
            'message' => "Toko '{$toko->nama_toko}' berhasil {$statusText}",
            'actor_name' => $changer->name,
            'icon' => "fas fa-toggle-" . ($toko->status === 'aktif' ? 'on' : 'off') . " {$iconClass}",
            'url' => route('superadmin.toko.index'),
        ];
    }

    /**
     * 🔥 Template notifikasi Kepala Toko Replaced (untuk UserController)
     */
    public static function kepalaTokoReplaced($oldKepala, $newKepala, $toko, $changer)
    {
        return [
            'type' => 'kepala_toko_replaced',
            'message' => "Kepala Toko '{$toko->nama_toko}' diganti dari {$oldKepala->name} ke {$newKepala->name}",
            'actor_name' => $changer->name,
            'icon' => 'fas fa-exchange-alt text-warning',
            'url' => route('superadmin.toko.index'),
        ];
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
        
        return [
            'type' => 'category_created',
            'message' => "Kategori '{$category->name}' berhasil ditambahkan{$statusInfo}",
            'actor_name' => $creator->name,
            'icon' => 'fas fa-tags text-success',
            'url' => route('superadmin.categories.index'),
        ];
    }

    /**
     * Template notifikasi Category Updated
     */
    public static function categoryUpdated($category, $updater)
    {
        return [
            'type' => 'category_updated',
            'message' => "Kategori '{$category->name}' telah diperbarui",
            'actor_name' => $updater->name,
            'icon' => 'fas fa-tag text-warning',
            'url' => route('superadmin.categories.index'),
        ];
    }

    /**
     * Template notifikasi Category Deleted
     */
    public static function categoryDeleted($categoryName, $deleter)
    {
        return [
            'type' => 'category_deleted',
            'message' => "Kategori '{$categoryName}' telah dihapus dari sistem",
            'actor_name' => $deleter->name,
            'icon' => 'fas fa-trash-alt text-danger',
            'url' => route('superadmin.categories.index'),
        ];
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
        
        return [
            'type' => 'product_created',
            'message' => "Produk '{$product->title}' berhasil ditambahkan{$categoryInfo}",
            'actor_name' => $creator->name,
            'icon' => 'fas fa-box text-success',
            'url' => route('superadmin.products.index'),
        ];
    }

    /**
     * Template notifikasi Product Updated
     */
    public static function productUpdated($product, $updater)
    {
        return [
            'type' => 'product_updated',
            'message' => "Produk '{$product->title}' telah diperbarui",
            'actor_name' => $updater->name,
            'icon' => 'fas fa-box-open text-warning',
            'url' => route('superadmin.products.index'),
        ];
    }

    /**
     * Template notifikasi Product Deleted
     */
    public static function productDeleted($productTitle, $deleter)
    {
        return [
            'type' => 'product_deleted',
            'message' => "Produk '{$productTitle}' telah dihapus dari sistem",
            'actor_name' => $deleter->name,
            'icon' => 'fas fa-trash-alt text-danger',
            'url' => route('superadmin.products.index'),
        ];
    }
}