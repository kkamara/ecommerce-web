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
$factory->define(App\Product::class, function(Faker $faker) {
    $company = App\Company::inRandomOrder()->first();
    $user = App\User::inRandomOrder()->first();
    $productName = $faker->company;

    return [
        'user_id' => $user->id,
        'company_id' => $company->id,
        'name' => $productName,
        'short_description' => substr($faker->paragraph(), 0, 191),
        'long_description' => $faker->paragraph(4),
        'product_details' => $faker->paragraph(5),
        'image_path' => '/image/products/default/not-found.jpg',
        'cost' => $faker->randomNumber(2),
        'shippable' => mt_rand(0, 1),
        'free_delivery' => mt_rand(0, 1),
    ];
});


