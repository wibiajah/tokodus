<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Product3DController;
use App\Http\Controllers\FrontendController;

// 🔥 SUPER ADMIN Controllers (SEMUA DI FOLDER SuperAdmin)
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\TokoController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\ProductController as SuperAdminProductController;
use App\Http\Controllers\SuperAdmin\CategoryController as SuperAdminCategoryController;
use App\Http\Controllers\SuperAdmin\StockController as SuperAdminStockController;
use App\Http\Controllers\SuperAdmin\VoucherController as SuperAdminVoucherController;

// 🔥 ADMIN Controllers (HANYA Product, Category, Stock, Voucher)
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\StockController as AdminStockController;
use App\Http\Controllers\Admin\VoucherController as AdminVoucherController;

// 🔥 KEPALA TOKO & STAFF Controllers
use App\Http\Controllers\KepalaToko\KepalaTokController;
use App\Http\Controllers\KepalaToko\StockController as KepalaTokStockController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Staff\StockController as StaffStockController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [FrontendController::class, 'index'])->name('home');

// Catalog
Route::get('/catalog', [FrontendController::class, 'catalog'])->name('catalog');

// Product Detail (untuk kedepannya)
Route::get('/product/{id}', [FrontendController::class, 'productDetail'])->name('product.detail');

// Search (untuk kedepannya)
Route::get('/search', [FrontendController::class, 'search'])->name('search');

// Category (untuk kedepannya)
Route::get('/category/{slug}', [FrontendController::class, 'category'])->name('category');


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

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

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

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN ROUTES (SEMUA MANAGEMENT)
    |--------------------------------------------------------------------------
    */
    Route::prefix('superadmin')->name('superadmin.')->middleware('role:super_admin')->group(function () {
        Route::get('dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
        
        // ✅ TOKO MANAGEMENT (DIPINDAH KE SuperAdmin namespace)
        Route::resource('toko', TokoController::class);
        Route::post('toko/{toko}/toggle-status', [TokoController::class, 'toggleStatus'])->name('toko.toggleStatus');
        Route::post('toko/{toko}/update-kepala-toko', [TokoController::class, 'updateKepalaToko'])->name('toko.updateKepalaToko');

        // ✅ USER MANAGEMENT (DIPINDAH KE SuperAdmin namespace)
        Route::resource('user', UserController::class);
        
        // 🆕 PRODUK MANAGEMENT (Full CRUD)
        Route::resource('products', SuperAdminProductController::class);
        
        // 🆕 KATEGORI MANAGEMENT (Full CRUD)
        Route::resource('categories', SuperAdminCategoryController::class);
        
        // 🆕 STOK MANAGEMENT (Real Time)
         Route::prefix('stocks')->name('stocks.')->group(function () {
        // List semua stok
        Route::get('/', [SuperAdminStockController::class, 'index'])->name('index');

           Route::get('{product}/detail', [SuperAdminStockController::class, 'getDetail'])->name('detail');
        
        // Detail stok per produk
        Route::get('/{product}', [SuperAdminStockController::class, 'show'])->name('show');
        
        // 🔥 Update stok awal produk (initial stock)
        Route::patch('/{product}/initial', [SuperAdminStockController::class, 'updateInitialStock'])->name('update-initial');
        
        // 🔥 TAMBAHAN: Set stok ke toko tertentu
        Route::get('/{product}/create', [SuperAdminStockController::class, 'create'])->name('create');
        Route::post('/{product}', [SuperAdminStockController::class, 'store'])->name('store');
        
        // 🔥 TAMBAHAN: Edit stok toko tertentu
        Route::get('/{product}/stocks/{stock}/edit', [SuperAdminStockController::class, 'edit'])->name('edit');
        Route::patch('/{product}/stocks/{stock}', [SuperAdminStockController::class, 'update'])->name('update');
    });
        
        // 🆕 VOUCHER MANAGEMENT (dengan Quantity Discount)
        Route::resource('vouchers', SuperAdminVoucherController::class);
        Route::post('vouchers/apply/{product}', [SuperAdminVoucherController::class, 'applyToProduct'])->name('vouchers.apply');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES (TIDAK ADA TOKO & USER)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
        
        // 🆕 PRODUK MANAGEMENT (Full CRUD)
        Route::resource('products', AdminProductController::class);
        
        // 🆕 KATEGORI MANAGEMENT (Full CRUD)
        Route::resource('categories', AdminCategoryController::class);
        
        // 🆕 STOK MANAGEMENT (Real Time)
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', [AdminStockController::class, 'index'])->name('index');
            Route::get('/{product}', [AdminStockController::class, 'show'])->name('show');
            Route::put('/{product}/initial', [AdminStockController::class, 'updateInitialStock'])->name('updateInitial');
        });
        
        // 🆕 VOUCHER MANAGEMENT (dengan Quantity Discount)
        Route::resource('vouchers', AdminVoucherController::class);
        Route::post('vouchers/apply/{product}', [AdminVoucherController::class, 'applyToProduct'])->name('vouchers.apply');
    });

    /*
    |--------------------------------------------------------------------------
    | KEPALA TOKO ROUTES (Edit Stok Saja)
    |--------------------------------------------------------------------------
    */
    Route::prefix('kepala-toko')->name('kepala-toko.')->middleware('role:kepala_toko')->group(function () {
        Route::get('dashboard', [KepalaTokController::class, 'index'])->name('dashboard');
        
        // 🆕 STOK MANAGEMENT (Set & Edit Stok dari Stok Awal)
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', [KepalaTokStockController::class, 'index'])->name('index');
            Route::get('/{product}/create', [KepalaTokStockController::class, 'create'])->name('create');
            Route::post('/{product}', [KepalaTokStockController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [KepalaTokStockController::class, 'edit'])->name('edit');
            Route::put('/{product}', [KepalaTokStockController::class, 'update'])->name('update');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | STAFF ROUTES (Edit Stok Saja)
    |--------------------------------------------------------------------------
    */
    Route::prefix('staff')->name('staff.')->middleware('role:staff_admin')->group(function () {
        Route::get('dashboard', [StaffController::class, 'index'])->name('dashboard');
        
        // 🆕 STOK MANAGEMENT (Set & Edit Stok dari Stok Awal)
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', [StaffStockController::class, 'index'])->name('index');
            Route::get('/{product}/create', [StaffStockController::class, 'create'])->name('create');
            Route::post('/{product}', [StaffStockController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [StaffStockController::class, 'edit'])->name('edit');
            Route::put('/{product}', [StaffStockController::class, 'update'])->name('update');
        });
    });
});