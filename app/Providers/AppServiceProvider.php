<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Predis\Autoloader;
use App\Models\Cart\Cart;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @param String $name
     * @return Function
     */
    protected static function renderViewComposer($name) {
        return match($name) {
            'cartCount' => function($view) {
                $view->with('cartCount', (new Cart)->count());
            },
            'cartPrice' => function($view) {
                $view->with('cartPrice', (new Cart)->price());
            },
        };
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Paginator::useBootstrap();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (in_array(config('app.env'), ['production', 'staging',])) {
            $url->forceScheme('https');
        }

        Schema::defaultStringLength(191);

        Autoloader::register();

        view()->composer(
            [
                'order_history.create',
                'layouts.navbar',
                'cart.show',
            ], 
            self::renderViewComposer('cartCount')
        );

        view()->composer(['cart.show', 'order_history.create'], self::renderViewComposer('cartPrice'));
    }
}
