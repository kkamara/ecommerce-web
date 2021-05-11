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


