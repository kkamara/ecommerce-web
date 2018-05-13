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
    $name = $faker->company;
    $company = App\Company::inRandomOrder()->first();

    return [
        'company_id' => $company->id,
        'name' => $name,
        'short_description' => substr($faker->paragraph(), 0, 191),
        'long_description' => $faker->paragraph(4),
        'product_details' => $faker->paragraph(5),
        'slug' => str_slug($name, '-'),
        'image_path' => 'image/products/default/not-found.jpg',
        'cost' => $faker->randomNumber(2),
        'shippable' => rand(0, 1),
        'free_delivery' => rand(0, 1),
    ];
});

