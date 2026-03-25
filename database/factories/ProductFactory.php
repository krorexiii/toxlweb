<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
        return [
            'slug' => $this->faker->unique()->slug(),
            'sku' => strtoupper($this->faker->bothify('PRD-###')),
            'image_path' => null,
            'gallery' => [],
            'sort_order' => 1,
            'is_featured' => false,
            'is_active' => true,
            'specifications' => [],
        ];
    }
}
