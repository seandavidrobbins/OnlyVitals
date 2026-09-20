<?php

namespace Database\Factories;

use App\Models\Plugin;
use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plugin>
 */
class PluginFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currentVersion = fake()->numerify('#.#.#');

        return [
            'website_id' => Website::factory(),
            'name' => fake()->unique()->words(2, true),
            'current_version' => $currentVersion,
            'latest_version' => fake()->optional(0.5, $currentVersion)->numerify('#.#.#'),
        ];
    }
}
