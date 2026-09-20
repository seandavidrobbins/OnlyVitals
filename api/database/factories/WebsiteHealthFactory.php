<?php

namespace Database\Factories;

use App\Enums\UptimeStatus;
use App\Models\Website;
use App\Models\WebsiteHealth;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WebsiteHealth>
 */
class WebsiteHealthFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'website_id' => Website::factory(),
            'ssl_expires_at' => fake()->optional()->dateTimeBetween('now', '+2 years'),
            'wordpress_version' => fake()->optional()->numerify('#.#.#'),
            'php_version' => fake()->optional()->randomElement(['8.2.0', '8.3.0', '8.4.0']),
            'uptime_status' => fake()->randomElement(UptimeStatus::cases()),
            'last_backup_at' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
            'last_checked_at' => fake()->optional()->dateTimeBetween('-7 days', 'now'),
        ];
    }
}
