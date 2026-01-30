<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Product3DController;
use App\Http\Controllers\GoogleCustomerAuthController;

//  FRONTEND Controllers
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\CatalogFrontendController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\OrderController;

//  CUSTOMERS Controllers
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\ReviewController;

//  SUPER ADMIN Controllers (SEMUA DI FOLDER SuperAdmin)
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\TokoController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\ShippingSettingController;
use App\Http\Controllers\SuperAdmin\ProductController as SuperAdminProductController;
use App\Http\Controllers\SuperAdmin\CategoryController as SuperAdminCategoryController;
use App\Http\Controllers\SuperAdmin\StockDistributionController as SuperAdminStockDistributionController; // ✅ NEW
use App\Http\Controllers\SuperAdmin\StockRequestController as SuperAdminStockRequestController; // ✅ NEW
use App\Http\Controllers\SuperAdmin\VoucherController;

//  ADMIN Controllers (HANYA Product, Category, Stock, Voucher)
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\StockController as AdminStockController;
use App\Http\Controllers\Admin\StockDistributionController as AdminStockDistributionController; // ✅ NEW
use App\Http\Controllers\Admin\StockRequestController as AdminStockRequestController; // ✅ NEW
use App\Http\Controllers\Admin\VoucherController as AdminVoucherController;

//  KEPALA TOKO & STAFF Controllers
use App\Http\Controllers\KepalaToko\KepalaTokController;
use App\Http\Controllers\KepalaToko\StockController as KepalaTokStockController;
use App\Http\Controllers\KepalaToko\StockRequestController as KepalaTokStockRequestController; // ✅ NEW
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Staff\StockController as StaffStockController;
use App\Http\Controllers\Staff\StockRequestController as StaffStockRequestController; // ✅ NEW

// Homepage
Route::get('/', [FrontendController::class, 'index'])->name('home');

Route::get('/test-webhook/{orderNumber}', function ($orderNumber) {
    $order = \App\Models\Order::where('order_number', $orderNumber)->first();

    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    // Simulate Midtrans notification
    $order->update([
        'payment_status' => 'paid',
        'status' => 'processing'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Order status updated!',
        'order' => [
            'order_number' => $order->order_number,
            'payment_status' => $order->payment_status,
            'status' => $order->status
        ]
    ]);
});
// Route test untuk lihat variant photos
Route::get('/test-variants/{product}', function (\App\Models\Product $product) {
    $product->load('variants.children');

    return view('test-variants', compact('product'));
})->name('test.variants');
/*
|--------------------------------------------------------------------------
| CUSTOMER AUTH ROUTES (Guest Only - untuk register & login)
|--------------------------------------------------------------------------
*/

Route::get('/customer/auth/google', [GoogleCustomerAuthController::class, 'redirect'])->name('customer.auth.google');
Route::get('/customer/auth/google/callback', [GoogleCustomerAuthController::class, 'callback']);
Route::get('/customer/email/verify/{id}/{hash}', function (Request $request) {
    $customer = \App\Models\Customer::findOrFail($request->route('id'));

    // Verify signature
    if (! hash_equals(sha1($customer->getEmailForVerification()), (string) $request->route('hash'))) {
        abort(403, 'Link verifikasi tidak valid atau sudah kadaluarsa.');
    }

    // Cek apakah sudah verified sebelumnya
    if ($customer->hasVerifiedEmail()) {
        return redirect()->route('home')
            ->with('info', 'Akun Anda sudah terverifikasi sebelumnya. Silakan login untuk melanjutkan.');
    }

    // Mark as verified (baru pertama kali)
    $customer->markEmailAsVerified();

    return redirect()->route('home')
        ->with('success', 'Aktivasi berhasil! Email Anda telah diverifikasi. Silakan login untuk melanjutkan.');
})->middleware(['signed'])->name('customer.verification.verify');
Route::prefix('customer')->name('customer.')->middleware('guest:customer')->group(function () {
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER PROTECTED ROUTES (Authenticated Customer)
|--------------------------------------------------------------------------
*/
Route::prefix('customer')->name('customer.')->middleware('customer.auth')->group(function () {
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

    // Profil Customer
    Route::get('/profile', [CustomerProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [CustomerProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/set-password', [CustomerProfileController::class, 'setPassword'])->name('profile.set-password');
    Route::post('/profile/change-password', [CustomerProfileController::class, 'changePassword'])->name('profile.change-password');

    // Cart Routes
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/', [CartController::class, 'store'])->name('store');
        Route::post('/add', [CartController::class, 'store'])->name('add');
        Route::put('/{cart}', [CartController::class, 'update'])->name('update');
        Route::delete('/{cart}', [CartController::class, 'destroy'])->name('destroy');
        Route::delete('/', [CartController::class, 'clear'])->name('clear');
        Route::get('/count', [CartController::class, 'count'])->name('count');
        Route::post('/apply-voucher', [CartController::class, 'applyVoucher'])->name('apply-voucher');
        Route::post('/remove-voucher', [CartController::class, 'removeVoucher'])->name('remove-voucher');
        Route::post('/get-voucher', [CartController::class, 'getAppliedVoucher'])->name('get-voucher');
        Route::post('/available-vouchers', [CartController::class, 'getAvailableVouchers'])->name('available-vouchers');
        Route::post('/stock', [CartController::class, 'getStock'])->name('stock');
    });

    // Wishlist Routes
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/toggle/{product}', [WishlistController::class, 'toggle'])->name('toggle');
        Route::delete('/{wishlist}', [WishlistController::class, 'destroy'])->name('destroy');
        Route::get('/count', [WishlistController::class, 'count'])->name('count');
        Route::get('/check/{product}', [WishlistController::class, 'check'])->name('check');
        Route::post('/move-to-cart', [WishlistController::class, 'moveToCart'])->name('move-to-cart');
        Route::delete('/clear', [WishlistController::class, 'clear'])->name('clear');
    });

    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Customer\CustomerChatController::class, 'index'])->name('index');
        Route::post('/order/{order}', [\App\Http\Controllers\Customer\CustomerChatController::class, 'createFromOrder'])->name('create-from-order');
        Route::get('/{chatRoom}', [\App\Http\Controllers\Customer\CustomerChatController::class, 'show'])->name('show');
        Route::post('/{chatRoom}/message', [\App\Http\Controllers\Customer\CustomerChatController::class, 'sendMessage'])->name('send-message');
        Route::get('/{chatRoom}/messages', [\App\Http\Controllers\Customer\CustomerChatController::class, 'getMessages'])->name('get-messages');
        Route::post('/{chatRoom}/read', [\App\Http\Controllers\Customer\CustomerChatController::class, 'markRead'])->name('mark-read');
        Route::get('/unread/count', [\App\Http\Controllers\Customer\CustomerChatController::class, 'unreadCount'])->name('unread-count');
        Route::get('/{chatRoom}/new-messages', [\App\Http\Controllers\Customer\CustomerChatController::class, 'getNewMessages'])->name('new-messages');
    });

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{identifier}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::post('/orders/{order}/report-problem', [OrderController::class, 'reportProblem'])->name('orders.report-problem');

    Route::post('/payment/create/{order}', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/finish/{order}', [PaymentController::class, 'finish'])->name('payment.finish');

    Route::get('/payment/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/create-multiple', [PaymentController::class, 'createMultiplePayment'])->name('payment.create-multiple');
    Route::get('/payment/finish-multiple', [PaymentController::class, 'finishMultiple'])->name('payment.finish-multiple');

    Route::post('/checkout/calculate-shipping', [CheckoutController::class, 'calculateShipping'])->name('checkout.calculate-shipping');

    // ✅ REVIEW ROUTES
    Route::prefix('orders/{order}/products/{product}')->name('reviews.')->group(function () {
        Route::get('/review', [ReviewController::class, 'create'])->name('create');
        Route::post('/review', [ReviewController::class, 'store'])->name('store');
        Route::get('/can-review', [ReviewController::class, 'canReview'])->name('can-review');
    });

    // ✅ MY REVIEWS (BONUS)
    Route::get('/my-reviews', [ReviewController::class, 'myReviews'])->name('my-reviews');
    Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
});


// Midtrans Notification (NO AUTH - untuk webhook)
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');

// Catalog Routes (PUBLIC)
Route::controller(CatalogFrontendController::class)->group(function () {
    Route::get('/catalog', 'catalog')->name('catalog');
    Route::get('/category/{slug}', 'category')->name('category');
    Route::get('/search', 'search')->name('search');
    Route::get('/product/{id}', 'productDetail')->name('product.detail');
});

// Guest Routes
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

        // ✅ TOKO MANAGEMENT
        Route::resource('toko', TokoController::class);
        Route::post('toko/{toko}/toggle-status', [TokoController::class, 'toggleStatus'])->name('toko.toggleStatus');
        Route::post('toko/{toko}/update-kepala-toko', [TokoController::class, 'updateKepalaToko'])->name('toko.updateKepalaToko');

        // ✅ USER MANAGEMENT
        Route::resource('user', UserController::class);

        // ✅ PRODUK MANAGEMENT (dengan Variants)
        Route::resource('products', SuperAdminProductController::class);
        Route::post('products/{product}/toggle-status', [SuperAdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::delete('products/variants/{variant}', [SuperAdminProductController::class, 'deleteVariant'])->name('products.variants.delete');

        // ✅ KATEGORI MANAGEMENT
        Route::resource('categories', SuperAdminCategoryController::class);

        // ✅ STOCK MANAGEMENT - SIMPLIFIED (3 VIEWS ONLY)
        Route::prefix('stocks')->name('stocks.')->group(function () {
            // 1. INDEX - Overview semua produk
            Route::get('/', [SuperAdminStockDistributionController::class, 'index'])->name('index');

            // 2. DETAIL - Breakdown stok + Form distribusi dalam 1 halaman (2 tabs)
            Route::get('/{product}', [SuperAdminStockDistributionController::class, 'detail'])->name('detail');
            Route::post('/{product}/distribute', [SuperAdminStockDistributionController::class, 'store'])->name('distribute.store');
            Route::post('/{product}/update-stock', [SuperAdminStockDistributionController::class, 'updateStock'])->name('update-stock');

            // 3. HISTORY - Logs distribusi (terpisah karena kompleks)
            Route::get('/{product}/history', [SuperAdminStockDistributionController::class, 'history'])->name('history');

            // AJAX Endpoints
            Route::get('/variants/{variant}/stock', [SuperAdminStockDistributionController::class, 'getVariantStock'])->name('variants.stock');
            Route::get('/{product}/tokos/{toko}/stock', [SuperAdminStockDistributionController::class, 'getTokoStock'])->name('tokos.stock');
        });

        // ✅ STOCK REQUESTS MANAGEMENT
        Route::prefix('stock-requests')->name('stock-requests.')->group(function () {
            Route::get('/', [SuperAdminStockRequestController::class, 'index'])->name('index');
            Route::get('/{stockRequest}', [SuperAdminStockRequestController::class, 'show'])->name('show');
            Route::post('/{stockRequest}/approve', [SuperAdminStockRequestController::class, 'approve'])->name('approve');
            Route::post('/{stockRequest}/reject', [SuperAdminStockRequestController::class, 'reject'])->name('reject');
            Route::post('/{stockRequest}/cancel', [SuperAdminStockRequestController::class, 'cancel'])->name('cancel');

            // AJAX routes
            Route::get('/pending/count', [SuperAdminStockRequestController::class, 'getPendingCount'])->name('pending.count');
            Route::post('/{stockRequest}/quick-approve', [SuperAdminStockRequestController::class, 'quickApprove'])->name('quick-approve');
        });

        // ✅ SHIPPING SETTINGS
        Route::prefix('shipping-settings')->name('shipping-settings.')->group(function () {
            Route::get('/', [ShippingSettingController::class, 'index'])->name('index');
            Route::get('/{shippingSetting}/edit', [ShippingSettingController::class, 'edit'])->name('edit');
            Route::put('/{shippingSetting}', [ShippingSettingController::class, 'update'])->name('update');
            Route::post('/{shippingSetting}/toggle-status', [ShippingSettingController::class, 'toggleStatus'])->name('toggle-status');
        });

        // ✅ ORDERS MANAGEMENT
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'show'])->name('show');
            Route::post('/{order}/update-status', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'updateStatus'])->name('update-status');
            Route::post('/{order}/confirm-payment', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'confirmPayment'])->name('confirm-payment');
            Route::post('/{order}/cancel', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'cancel'])->name('cancel');
        });


        // ✅ VOUCHER MANAGEMENT
        Route::resource('vouchers', VoucherController::class);
        Route::post('vouchers/{voucher}/toggle-status', [VoucherController::class, 'toggleStatus'])->name('vouchers.toggle-status');
        Route::post('vouchers/validate-code', [VoucherController::class, 'validateCode'])->name('vouchers.validate-code');

        // ✅ REVIEW MANAGEMENT (READ & DELETE ONLY)
        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::get('/', [\App\Http\Controllers\SuperAdmin\ReviewController::class, 'index'])
                ->name('index');

            // ✅ AJAX endpoint untuk realtime badge count
            Route::get('/unviewed-count', [\App\Http\Controllers\SuperAdmin\ReviewController::class, 'getUnviewedCount'])
                ->name('unviewed-count');

            Route::get('/statistics', [\App\Http\Controllers\SuperAdmin\ReviewController::class, 'statistics'])
                ->name('statistics');

            Route::get('/{review}', [\App\Http\Controllers\SuperAdmin\ReviewController::class, 'show'])
                ->name('show');

            Route::delete('/{review}', [\App\Http\Controllers\SuperAdmin\ReviewController::class, 'destroy'])
                ->name('destroy');

            Route::post('/bulk-delete', [\App\Http\Controllers\SuperAdmin\ReviewController::class, 'bulkDelete'])
                ->name('bulk-delete');
            // AJAX routes
            Route::get('/product/{product}', [\App\Http\Controllers\SuperAdmin\ReviewController::class, 'byProduct'])
                ->name('by-product');
            Route::get('/customer/{customer}', [\App\Http\Controllers\SuperAdmin\ReviewController::class, 'byCustomer'])
                ->name('by-customer');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES (TIDAK ADA TOKO & USER)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');

        // ✅ PRODUK MANAGEMENT (dengan Variants)
        Route::resource('products', AdminProductController::class);
        Route::delete('products/variants/{variant}', [AdminProductController::class, 'deleteVariant'])->name('products.variants.delete'); // ✅ NEW
        Route::get('products/{product}/detail', [SuperAdminProductController::class, 'getDetail'])->name('products.detail');
        // ✅ KATEGORI MANAGEMENT
        Route::resource('categories', AdminCategoryController::class);

        // ✅ STOCK DISTRIBUTION MANAGEMENT (NEW!)
        Route::prefix('products/{product}/stock')->name('products.stock.')->group(function () {
            Route::get('/distribute', [AdminStockDistributionController::class, 'create'])->name('distribute');
            Route::post('/distribute', [AdminStockDistributionController::class, 'store'])->name('distribute.store');
            Route::get('/overview', [AdminStockDistributionController::class, 'overview'])->name('overview');
            Route::get('/history', [AdminStockDistributionController::class, 'history'])->name('history');
        });

        // ✅ STOCK DISTRIBUTION LOGS
        Route::get('stock-distribution-logs/{log}', [AdminStockDistributionController::class, 'showLog'])->name('stock-logs.show');

        // ✅ STOCK DISTRIBUTION AJAX
        Route::get('variants/{variant}/stock', [AdminStockDistributionController::class, 'getVariantStock'])->name('variants.stock');
        Route::get('products/{product}/tokos/{toko}/stock', [AdminStockDistributionController::class, 'getTokoStock'])->name('products.tokos.stock');

        // ✅ STOCK REQUESTS MANAGEMENT (NEW!)
        Route::prefix('stock-requests')->name('stock-requests.')->group(function () {
            Route::get('/', [AdminStockRequestController::class, 'index'])->name('index');
            Route::get('/{stockRequest}', [AdminStockRequestController::class, 'show'])->name('show');
            Route::post('/{stockRequest}/approve', [AdminStockRequestController::class, 'approve'])->name('approve');
            Route::post('/{stockRequest}/reject', [AdminStockRequestController::class, 'reject'])->name('reject');
            Route::post('/{stockRequest}/cancel', [AdminStockRequestController::class, 'cancel'])->name('cancel');

            // AJAX routes
            Route::get('/pending/count', [AdminStockRequestController::class, 'getPendingCount'])->name('pending.count');
            Route::post('/{stockRequest}/quick-approve', [AdminStockRequestController::class, 'quickApprove'])->name('quick-approve');
        });

        // ✅ OLD STOCK MANAGEMENT (akan deprecated)
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', [AdminStockController::class, 'index'])->name('index');
            Route::get('/{product}', [AdminStockController::class, 'show'])->name('show');
            Route::put('/{product}/initial', [AdminStockController::class, 'updateInitialStock'])->name('updateInitial');
        });

        // ✅ VOUCHER MANAGEMENT
        Route::resource('vouchers', AdminVoucherController::class);
        Route::post('vouchers/apply/{product}', [AdminVoucherController::class, 'applyToProduct'])->name('vouchers.apply');
    });

    /*
    |--------------------------------------------------------------------------
    | KEPALA TOKO ROUTES (Request Stock)
    |--------------------------------------------------------------------------
    */
    Route::prefix('kepala-toko')->name('kepala-toko.')->middleware('role:kepala_toko')->group(function () {
        Route::get('dashboard', [KepalaTokController::class, 'index'])->name('dashboard');

        // ✅ PRODUCTS MANAGEMENT (READ ONLY + REQUEST STOK)
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [\App\Http\Controllers\KepalaToko\ProductController::class, 'index'])->name('index');
            Route::get('/{product}', [\App\Http\Controllers\KepalaToko\ProductController::class, 'show'])->name('show');
        });

        // ✅ STOCK REQUEST MANAGEMENT (DISABLED TEMPORARILY - Using manual stock editing instead)
        // Route::prefix('stock-requests')->name('stock-requests.')->group(function () {
        //     Route::get('/', [KepalaTokStockRequestController::class, 'index'])->name('index');
        //     Route::get('/products/{product}/create', [KepalaTokStockRequestController::class, 'create'])->name('create');
        //     Route::post('/products/{product}', [KepalaTokStockRequestController::class, 'store'])->name('store');
        //     Route::get('/{stockRequest}', [KepalaTokStockRequestController::class, 'show'])->name('show');
        //     Route::post('/{stockRequest}/cancel', [KepalaTokStockRequestController::class, 'cancel'])->name('cancel');

        //     // AJAX routes
        //     Route::get('/products/{product}/stock', [KepalaTokStockRequestController::class, 'getTokoStock'])->name('products.stock');
        //     Route::get('/products/{product}/check', [KepalaTokStockRequestController::class, 'checkCanRequest'])->name('check');
        //     Route::get('/statistics', [KepalaTokStockRequestController::class, 'statistics'])->name('statistics');
        // });

        // ✅ STOCK MANAGEMENT (Manual Editing)
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', [KepalaTokStockController::class, 'index'])->name('index');
            Route::post('/{product}/add-to-toko', [KepalaTokStockController::class, 'addProductToToko'])->name('add-to-toko');
            Route::post('/{product}/toggle-status', [KepalaTokStockController::class, 'toggleProductStatus'])->name('toggle-status');
            Route::get('/{product}/create', [KepalaTokStockController::class, 'create'])->name('create');
            Route::post('/{product}', [KepalaTokStockController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [KepalaTokStockController::class, 'edit'])->name('edit');
            Route::put('/{product}', [KepalaTokStockController::class, 'update'])->name('update');
        });

        // ✅ ORDERS MANAGEMENT
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\KepalaToko\OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [\App\Http\Controllers\KepalaToko\OrderController::class, 'show'])->name('show');
            Route::post('/{order}/update-status', [\App\Http\Controllers\KepalaToko\OrderController::class, 'updateStatus'])->name('update-status');
            Route::post('/{order}/confirm-payment', [\App\Http\Controllers\KepalaToko\OrderController::class, 'confirmPayment'])->name('confirm-payment'); // 🆕
        });

        // 🔥 CHAT ROUTES - UPDATED WITH NEW ENDPOINT
        Route::prefix('chat')->name('chat.')->group(function () {
            // Inbox
            Route::get('/', [\App\Http\Controllers\KepalaToko\KepalaTokoChatController::class, 'index'])
                ->name('index');

            // 🔥 NEW: Get chat list untuk polling realtime
            Route::get('/list', [\App\Http\Controllers\KepalaToko\KepalaTokoChatController::class, 'getChatList'])
                ->name('list');

            // Show chat room
            Route::get('/{chatRoom}', [\App\Http\Controllers\KepalaToko\KepalaTokoChatController::class, 'show'])
                ->name('show');

            // Send message
            Route::post('/{chatRoom}/message', [\App\Http\Controllers\KepalaToko\KepalaTokoChatController::class, 'sendMessage'])
                ->name('send-message');

            // Get messages (AJAX)
            Route::get('/{chatRoom}/messages', [\App\Http\Controllers\KepalaToko\KepalaTokoChatController::class, 'getMessages'])
                ->name('get-messages');

            // Mark as read
            Route::post('/{chatRoom}/read', [\App\Http\Controllers\KepalaToko\KepalaTokoChatController::class, 'markRead'])
                ->name('mark-read');

            // Unread count
            Route::get('/unread/count', [\App\Http\Controllers\KepalaToko\KepalaTokoChatController::class, 'unreadCount'])
                ->name('unread-count');

            // Escalate to super admin
            Route::post('/{chatRoom}/escalate', [\App\Http\Controllers\KepalaToko\KepalaTokoChatController::class, 'escalateToSuperAdmin'])
                ->name('escalate');

            // 🔥 Polling: Get new messages
            Route::get('/{chatRoom}/new-messages', [\App\Http\Controllers\KepalaToko\KepalaTokoChatController::class, 'getNewMessages'])
                ->name('new-messages');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | STAFF ROUTES (Request Stock)
    |--------------------------------------------------------------------------
    */
    Route::prefix('staff')->name('staff.')->middleware('role:staff_admin')->group(function () {
        Route::get('dashboard', [StaffController::class, 'index'])->name('dashboard');

        // ✅ STOCK REQUEST MANAGEMENT (DISABLED TEMPORARILY - Using manual stock editing instead)
        // Route::prefix('stock-requests')->name('stock-requests.')->group(function () {
        //     Route::get('/', [StaffStockRequestController::class, 'index'])->name('index');
        //     Route::get('/products/{product}/create', [StaffStockRequestController::class, 'create'])->name('create');
        //     Route::post('/products/{product}', [StaffStockRequestController::class, 'store'])->name('store');
        //     Route::get('/{stockRequest}', [StaffStockRequestController::class, 'show'])->name('show');
        //     Route::post('/{stockRequest}/cancel', [StaffStockRequestController::class, 'cancel'])->name('cancel');

        //     // AJAX routes
        //     Route::get('/products/{product}/stock', [StaffStockRequestController::class, 'getTokoStock'])->name('products.stock');
        //     Route::get('/products/{product}/check', [StaffStockRequestController::class, 'checkCanRequest'])->name('check');
        //     Route::get('/statistics', [StaffStockRequestController::class, 'statistics'])->name('statistics');
        // });

        // ✅ OLD STOCK MANAGEMENT (akan deprecated)
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', [StaffStockController::class, 'index'])->name('index');
            Route::get('/{product}/create', [StaffStockController::class, 'create'])->name('create');
            Route::post('/{product}', [StaffStockController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [StaffStockController::class, 'edit'])->name('edit');
            Route::put('/{product}', [StaffStockController::class, 'update'])->name('update');
        });
    });
});
