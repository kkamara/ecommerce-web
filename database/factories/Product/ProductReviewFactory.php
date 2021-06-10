<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Product\Product;
use App\Models\Product\ProductReview;

class ProductReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductReview::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'product_id' => Product::inRandomOrder()->first()->id,
            'score' => mt_rand(0, 5),
            'content' => mt_rand(0, 1) === 1 ? $this->faker->paragraph(mt_rand(0,5)) : null,
        ];
    }
}
