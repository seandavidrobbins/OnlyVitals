<?php

namespace Database\Factories;

use App\Enums\IssueStatus;
use App\Enums\Severity;
use App\Models\SecurityIssue;
use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SecurityIssue>
 */
class SecurityIssueFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'website_id' => Website::factory(),
            'severity' => fake()->randomElement(Severity::cases()),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(IssueStatus::cases()),
        ];
    }
}
