<?php

namespace Database\Factories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Language>
 */
class LanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->languageCode(),
            'code' => $this->faker->unique()->lexify('??'),
            'native_name' => $this->faker->word(),
            'is_default' => false,
            'is_active' => true,
            'sort_order' => 1,
        ];
    }
}
