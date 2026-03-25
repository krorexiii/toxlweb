<?php

namespace Database\Factories;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteSetting>
 */
class SiteSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'site_name' => $this->faker->company(),
            'site_tagline' => $this->faker->catchPhrase(),
            'logo_path' => null,
            'favicon_path' => null,
            'primary_phone' => $this->faker->phoneNumber(),
            'secondary_phone' => null,
            'primary_email' => $this->faker->safeEmail(),
            'address' => $this->faker->address(),
            'map_embed_url' => 'https://maps.google.com',
            'social_links' => [],
            'localized_content' => [],
        ];
    }
}
