<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\ProductReview;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share pending orders count ke semua view superadmin
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $newOrdersCount = Order::where('status', 'pending')->count();
                $view->with('newOrdersCount', $newOrdersCount);
            }
        });

         // ✅ Share unviewed reviews count ke sidebar (PRODUCTION-READY)
        View::composer('*', function ($view) {
            // Hanya untuk super_admin yang login
            if (auth()->check() && auth()->user()->role === 'super_admin') {
                // ✅ FAST QUERY - hanya count dengan index
                $unviewedReviewsCount = ProductReview::getUnviewedCount();
                
                $view->with('unviewedReviewsCount', $unviewedReviewsCount);
            }
        });
    }

    public function register()
    {
        //
    }
}