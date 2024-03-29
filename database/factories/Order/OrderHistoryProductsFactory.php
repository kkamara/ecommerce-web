<?php

namespace Database\Factories\Order;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order\OrderHistoryProducts;
use App\Models\Order\OrderHistory;
use App\Models\Product\Product;

class OrderHistoryProductsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderHistoryProducts::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $orderHistory = OrderHistory::inRandomOrder()->first();
        if (!$orderHistory) {
            $orderHistory = OrderHistory::factory()->create();
        }
        $product = Product::inRandomOrder()->first();
        if (!$product) {
            $product = Product::factory()->create();
        }
        
        $amount  = mt_rand(1, 4);
        $cost    = $amount * $product->cost;

        return [
            'order_history_id' => $orderHistory->id,
            'product_id'       => $product->id,
            'amount'           => $amount,
            'cost'             => $cost,
            'shippable'        => mt_rand(0, 1),
            'free_delivery'    => mt_rand(0, 1),
        ];
    }
}
