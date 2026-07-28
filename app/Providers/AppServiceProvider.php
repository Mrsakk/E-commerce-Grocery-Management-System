<?php

namespace App\Providers;

use App\Models\Wishlist;
use Illuminate\Database\Connectors\PostgresConnector;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Custom PostgreSQL connector that injects Neon endpoint ID into the DSN
 * to work around SNI issues on Windows with older libpq versions.
 */
class NeonPostgresConnector extends PostgresConnector
{
    protected function getDsn(array $config): string
    {
        $dsn = parent::getDsn($config);

        // Append Neon endpoint option to fix SNI issue on Windows old libpq
        if (! empty($config['neon_endpoint'])) {
            $dsn .= ";options=endpoint={$config['neon_endpoint']}";
        }

        return $dsn;
    }
}

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
        if (config('app.env') === 'production' || request()->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();

        // Register custom Neon PostgreSQL connector to fix SNI issue on Windows
        $this->app->bind('db.connector.pgsql', NeonPostgresConnector::class);

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
