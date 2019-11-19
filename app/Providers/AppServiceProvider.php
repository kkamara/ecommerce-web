<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        Schema::defaultStringLength(191);

        if(env('REDIRECT_HTTPS')) {
            $url->forceScheme('https');
        }

        if ($this->app->environment('local', 'testing', 'staging')) {
            $this->app->register(DuskServiceProvider::class);
        }
        /*
            view()->composer(['layouts.navbar', 'cart.show', 'order_history.create'], function($view) {
                $view->with('cartCount', \App\Cart::count());
            });

            view()->composer(['cart.show', 'order_history.create'], function($view) {
                $view->with('cartPrice', \App\Cart::price());
            });
        */
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
