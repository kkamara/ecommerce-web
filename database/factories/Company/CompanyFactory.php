<?php

namespace Database\Factories\Company;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Company\Company;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->company;

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'name' => $name,
            'slug' => Str::slug($name, '-'),
            'phone_number' => $this->faker->phonenumber,
            'building_name' => $this->faker->buildingnumber,
            'street_address1' => $this->faker->StreetAddress,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'postcode' => $this->faker->postcode,
        ];
    }
}
