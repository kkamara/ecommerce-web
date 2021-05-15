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

/**
 * Factories should be run individually in the order of which they are defined
 * Create and user only a single user or hardcode $userid field in each factory
 */
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


