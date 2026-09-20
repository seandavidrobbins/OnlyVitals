<?php

namespace Database\Factories;

use App\Models\CoreWebVital;
use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CoreWebVital>
 */
class CoreWebVitalFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'website_id' => Website::factory(),
            'lcp' => fake()->randomFloat(3, 0.5, 6),
            'inp' => fake()->randomFloat(2, 50, 500),
            'cls' => fake()->randomFloat(3, 0, 0.4),
            'measured_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
