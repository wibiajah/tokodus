<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\TokoController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\KepalaToko\KepalaTokController;
use App\Http\Controllers\Staff\StaffController;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});

Route::middleware('auth')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Profile (semua role bisa akses)
    Route::get('/profile', [ProfilController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfilController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfilController::class, 'update'])->name('profile.update');
    Route::get('/profile/password/edit', [ProfilController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('/profile/password/update', [ProfilController::class, 'updatePassword'])->name('profile.password.update');

    // Super Admin routes
    Route::prefix('superadmin')->middleware('role:super_admin')->group(function () {
        Route::get('dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    });

    // Admin routes (Super Admin & Admin bisa akses)
    Route::prefix('admin')->middleware('role:super_admin,admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Manajemen Toko
        Route::resource('toko', TokoController::class);
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