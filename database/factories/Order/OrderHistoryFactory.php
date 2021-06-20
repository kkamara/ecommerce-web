<?php

namespace Database\Factories\Order;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order\OrderHistoryProducts;
use App\Models\Order\OrderHistory;
use App\Models\Product\Product;
use App\Models\User;

class OrderHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderHistory::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        $orderHistoryProducts = new OrderHistoryProducts;
        return $this->afterCreating(function (OrderHistory $orderHistory) use ($orderHistoryProducts) {
            $count = mt_rand(1,10);

            $orderHistoryProducts->factory()
                ->count($count)
                ->create([
                    'order_history_id' => $orderHistory->id,
                ]);
        });
    }

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
            'reference_number' => (new OrderHistory)->generateRefNum(),
        ];
    }
}
