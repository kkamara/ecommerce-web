<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = array(
            'firstName' => $this->faker->unique()->firstName,
            'lastName'  => $this->faker->unique()->lastName,
        );

        return [
            'slug' => Str::slug($name['firstName'] . ' ' . $name['lastName'], '-'),
            'first_name' => $name['firstName'],
            'last_name' => $name['lastName'],
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
