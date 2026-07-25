<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Wishlist;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // View composer to share wishlist product IDs once per request lifecycle
        View::composer(['partials.product-card', 'customer.products.show'], function ($view) {
            if (auth()->check() && auth()->user()->customer) {
                static $wishlistIds = null;
                if ($wishlistIds === null) {
                    $wishlistIds = Wishlist::where('customer_id', auth()->user()->customer->id)
                        ->pluck('product_id')
                        ->toArray();
                }
                $view->with('wishlistProductIds', $wishlistIds);
            } else {
                $view->with('wishlistProductIds', []);
            }
        });
    }
}

