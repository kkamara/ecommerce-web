<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product\FlaggedProductReview;
use App\Models\Product\ProductReview;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FlaggedProductReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FlaggedProductReview::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $productReview = ProductReview::inRandomOrder()->first();
        if (!$productReview) {
            $productReview = ProductReview::factory()->create();
        }
        for ($i = 0; $i <= 3; $i++) {
            FlaggedProductReview::create([
                'product_reviews_id' => $productReview->id,
                'flagged_from_ip' => $this->faker->ipv4(),
            ]);
        }
        return [
            'product_reviews_id' => $productReview->id,
            'flagged_from_ip' => $this->faker->ipv4(),
        ];
    }
}
