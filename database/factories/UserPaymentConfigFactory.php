<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\UserPaymentConfig;

class UserPaymentConfigFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserPaymentConfig::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $param = str_shuffle("00000111112222233333444445555566666777778888899999");

        return [
            'user_id' => $user->id,
            'card_holder_name' => strtoupper($user->first_name . ' ' . $user->last_name),
            'card_number' => mb_substr($param, 0, 16),
            'expiry_month' => mt_rand(1, 12),
            'expiry_year' => mt_rand(2020, 2024),
            'phone_number_extension' => '+'.mb_substr($param, 2, 3),
            'phone_number' => $this->faker->phonenumber,
            'building_name' => $this->faker->buildingnumber,
            'street_address1' => $this->faker->StreetAddress,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'postcode' => $this->faker->postcode,
        ];
    }
}
