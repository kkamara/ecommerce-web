<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Company\Company;
use App\Models\Product\Product;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $productName = $faker->productName;

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'company_id' => Company::inRandomOrder()->first()->id,
            'slug' => Str::slug($productName),
            'name' => $productName,
            'short_description' => substr($this->faker->paragraph(), 0, 191),
            'long_description' => $this->faker->paragraph(4),
            'product_details' => $this->faker->paragraph(5),
            'image_path' => config('filesystems.defaultImagePath'),
            'cost' => $this->faker->randomNumber(3),
            'shippable' => mt_rand(0, 1),
            'free_delivery' => mt_rand(0, 1),
        ];
    }
}
