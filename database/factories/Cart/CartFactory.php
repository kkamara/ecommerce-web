<?php

namespace Database\Factories\Cart;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product\Product;
use App\Models\Cart\Cart;
use App\Models\User;

class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $product = Product::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ];
    }
}
