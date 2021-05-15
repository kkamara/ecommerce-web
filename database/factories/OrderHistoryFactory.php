<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
$factory->define(App\OrderHistory::class, function(Faker $faker) {
    $user = App\User::inRandomOrder()->first();
    $product = App\Product::inRandomOrder()->first();

    return [
        'user_id' => $user->id,
        'cost' => $product->cost,
        'user_payment_config_id' => $user->userPaymentConfig[0]->id,
    ];
});
