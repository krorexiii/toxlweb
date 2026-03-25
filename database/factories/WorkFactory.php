<?php

namespace Database\Factories;

use App\Models\Work;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Work>
 */
class WorkFactory extends Factory
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
            'image_path' => null,
            'gallery' => [],
            'client_name' => $this->faker->company(),
            'location' => $this->faker->city(),
            'completed_at' => $this->faker->date(),
            'sort_order' => 1,
            'is_featured' => false,
            'is_active' => true,
            'metrics' => [],
        ];
    }
}
