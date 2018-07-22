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

// Factories should be run individually in the order of which they are defined
// Create and user only a single user or hardcode $userid field in each factory
$factory->define(App\User::class, function (Faker $faker) {

    $name = array(
        'firstName' => $faker->unique()->firstName,
        'lastName'  => $faker->unique()->lastName,
    );

    return [
        'slug' => str_slug($name['firstName'] . ' ' . $name['lastName'], '-'),
        'first_name' => $name['firstName'],
        'last_name' => $name['lastName'],
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\UsersAddress::class, function (Faker $faker) {
    $param = str_shuffle("00000111112222233333444445555566666777778888899999");
    $user = App\User::inRandomOrder()->first();

    return [
        'user_id' => $user->id,
        'phone_number_extension' => '+'.mb_substr($param, 2, 3),
        'phone_number' => $faker->phonenumber,
        'building_name' => $faker->buildingnumber,
        'street_address1' => $faker->StreetAddress,
        'city' => $faker->city,
        'country' => $faker->country,
        'postcode' => $faker->postcode,
    ];
});

$factory->define(App\UserPaymentConfig::class, function(Faker $faker) {
    $user = App\User::inRandomOrder()->first();

    $param = str_shuffle("00000111112222233333444445555566666777778888899999");
    $card_number   = mb_substr($param, 0, 16);

    return [
        'user_id' => $user->id,
        'card_holder_name' => strtoupper($user->first_name . ' ' . $user->last_name),
        'card_number' => $card_number,
        'expiry_month' => mt_rand(1, 12),
        'expiry_year' => mt_rand(2020, 2024),
        'phone_number_extension' => '+'.mb_substr($param, 2, 3),
        'phone_number' => $faker->phonenumber,
        'building_name' => $faker->buildingnumber,
        'street_address1' => $faker->StreetAddress,
        'city' => $faker->city,
        'country' => $faker->country,
        'postcode' => $faker->postcode,
    ];
});

$factory->define(App\Company::class, function(Faker $faker) {
    $name = $faker->company;
    $user = App\User::inRandomOrder()->first();

    return [
        'user_id' => $user->id,
        'name' => $name,
        'slug' => str_slug($name, '-'),
        'phone_number' => $faker->phonenumber,
        'building_name' => $faker->buildingnumber,
        'street_address1' => $faker->StreetAddress,
        'city' => $faker->city,
        'country' => $faker->country,
        'postcode' => $faker->postcode,
    ];
});

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

$factory->define(App\OrderHistory::class, function(Faker $faker) {
    $user = App\User::inRandomOrder()->first();
    $product = App\Product::inRandomOrder()->first();

    return [
        'user_id' => $user->id,
        'product_id' => $product->id,
        'cost' => $product->cost,
        'user_payment_config_id' => $user->userPaymentConfig[0]->id,
    ];
});

$factory->define(App\Cart::class, function(Faker $faker) {
    $user = App\User::inRandomOrder()->first();
    $product = App\Product::inRandomOrder()->first();

    return [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ];
});
