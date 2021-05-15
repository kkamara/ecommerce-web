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
$factory->define(App\ProductReview::class, function (Faker $faker) {
    $user = App\User::inRandomOrder()->first();
    $product = App\Product::inRandomOrder()->first();

    return [
        'user_id' => $user->id,
        'product_id' => $product->id,
        'score' => mt_rand(0, 5),
        'content' => (mt_rand(0, 1) === 1) ? $faker->paragraph(mt_rand(0,5)) : null,
    ];
});


