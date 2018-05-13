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

$factory->define(App\User::class, function (Faker $faker) {

    $name = array();
    array_push($name, $faker->unique()->firstName);
    array_push($name, $faker->unique()->lastName);

    return [
        'slug' => str_slug($name[0] . ' ' . $name[1], '-'),
        'first_name' => $name[0],
        'last_name' => $name[1],
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'phone_number' => $faker->phonenumber,

        'building_name' => $faker->buildingnumber,
        'street_address1' => $faker->StreetAddress,
        'city' => $faker->city,
        'country' => $faker->country,
        'postcode' => $faker->postcode,
    ];
});

$factory->define(App\Product::class, function(Faker $faker) {
    $company = $faker->company;
    $user = App\User::inRandomOrder()->first();

    return [
        'user_id' => $user->id,
        'name' => $company,
        'slug' => str_slug($company, '-'),
        'image_path' => 'image/products/default/not-found.jpg',
        'cost' => $faker->randomNumber(2),
        'shippable' => rand(0, 1),
        'free_delivery' => rand(0, 1),
    ];
});

