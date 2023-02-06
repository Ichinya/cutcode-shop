<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Providers\FakerProductProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->addProvider(new FakerProductProvider($this->faker));
        return [
            'title' => $this->faker->words(2, true),
            'brand_id' => Brand::query()->inRandomOrder()->value('id'),
            'thumbnail' => $this->faker->productImage('app/public/images/products'),
            'price' => $this->faker->numberBetween(1000, 100_000),
        ];
    }
}
