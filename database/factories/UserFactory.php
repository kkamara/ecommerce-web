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
        'password' => password_hash("secret", PASSWORD_DEFAULT),
        'remember_token' => str_random(10),
    ];
});


