<?php

namespace Tests\Feature;

use App\Models\MaintenanceTask;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_creates_a_maintenance_task(): void
    {
        $task = MaintenanceTask::factory()->create();

        $this->assertDatabaseHas('maintenance_tasks', [
            'id' => $task->id,
            'website_id' => $task->website_id,
            'title' => $task->title,
        ]);
    }

    public function test_a_website_can_have_many_maintenance_tasks(): void
    {
        $website = Website::factory()->create();

        MaintenanceTask::factory()->count(2)->create([
            'website_id' => $website->id,
        ]);

        $this->assertDatabaseCount('maintenance_tasks', 2);
    }
}
