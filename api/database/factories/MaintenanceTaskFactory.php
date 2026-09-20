<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\MaintenanceTask;
use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MaintenanceTask>
 */
class MaintenanceTaskFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(TaskStatus::cases());

        return [
            'website_id' => Website::factory(),
            'title' => fake()->sentence(4),
            'due_at' => fake()->optional()->dateTimeBetween('now', '+60 days'),
            'status' => $status,
            'completed_at' => $status === TaskStatus::Completed
                ? fake()->dateTimeBetween('-14 days', 'now')
                : null,
        ];
    }
}
