<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\TokoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\KepalaToko\KepalaTokController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Product3DController;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

// ✅ 3D PRODUCT ROUTES (PUBLIC - TIDAK PERLU LOGIN)
Route::prefix('products-3d')->group(function () {
    Route::get('/', [Product3DController::class, 'index'])->name('products-3d.index');
    Route::get('{product3d}', [Product3DController::class, 'show'])->name('products-3d.show');
    Route::get('{product3d}/data', [Product3DController::class, 'getProductData'])->name('products-3d.data');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 🔥 NOTIFIKASI ROUTES (Semua user bisa akses)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllRead');
    });

    // Profile (semua role bisa akses)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/password/edit', [ProfileController::class, 'editPassword'])->name('password.edit');
        Route::put('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    // 🔥 Super Admin routes (HANYA Super Admin)
    Route::prefix('superadmin')->middleware('role:super_admin')->group(function () {
        Route::get('dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
        
        // 🔥 Manajemen Toko (HANYA Super Admin)
        Route::resource('toko', TokoController::class);
        Route::post('toko/{toko}/toggle-status', [TokoController::class, 'toggleStatus'])->name('toko.toggleStatus');
        Route::post('toko/{toko}/update-kepala-toko', [TokoController::class, 'updateKepalaToko'])->name('toko.updateKepalaToko');

        // 🔥 Manajemen User (HANYA Super Admin)
        Route::resource('user', UserController::class);
    });

    // Admin routes (TIDAK ADA akses Toko & User)
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    // Kepala Toko routes
    Route::prefix('kepala-toko')->middleware('role:kepala_toko')->group(function () {
        Route::get('dashboard', [KepalaTokController::class, 'index'])->name('kepala-toko.dashboard');
    });

    // Staff Admin routes
    Route::prefix('staff')->middleware('role:staff_admin')->group(function () {
        Route::get('dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    });
});