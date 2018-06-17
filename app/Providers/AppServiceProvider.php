<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer(['layouts.navbar', 'cart.show', 'order_history.create'], function($view) {
            $view->with('cartCount', \App\Cart::count());
        });

        view()->composer(['cart.show', 'order_history.create'], function($view) {
            $view->with('cartPrice', \App\Cart::price());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
