<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

require_once app_path() . "/Helpers/CommonHelper.php";

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        require_once app_path() . "/Helpers/SessionCartHelper.php";
    }
}
