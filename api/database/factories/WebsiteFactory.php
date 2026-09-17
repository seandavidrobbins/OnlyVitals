<?php

namespace Database\Factories;

use App\Enums\CmsType;
use App\Models\User;
use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Website>
 */
class WebsiteFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->company(),
            'url' => fake()->unique()->url(),
            'cms_type' => fake()->randomElement(CmsType::cases()),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
