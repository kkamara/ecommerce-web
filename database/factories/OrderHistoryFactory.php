<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\OrderHistory;
use App\Product;

class OrderHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'cost' => Product::inRandomOrder()->first()->cost,
            'user_payment_config_id' => $user->userPaymentConfig[0]->id,
            'users_addresses_id' => $user->userAddress[0]->id,
            'reference_number' => OrderHistory::generateRefNum(),
        ];
    }
}
