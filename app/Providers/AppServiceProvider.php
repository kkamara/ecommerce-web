<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use \App\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Paginator::useBootstrap();

        view()->composer(['layouts.navbar', 'cart.show', 'order_history.create'], function($view) {
            $view->with('cartCount', Cart::count());
        });

        view()->composer(['cart.show', 'order_history.create'], function($view) {
            $view->with('cartPrice', Cart::price());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
