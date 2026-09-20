<?php

namespace Tests\Feature;

use App\Models\CoreWebVital;
use App\Models\MaintenanceTask;
use App\Models\Plugin;
use App\Models\SecurityIssue;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteHealth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_domain_relationships_are_wired(): void
    {
        $user = User::factory()->create();
        $website = Website::factory()->for($user)->create();
        $health = WebsiteHealth::factory()->for($website)->create();
        $plugin = Plugin::factory()->for($website)->create();
        $issue = SecurityIssue::factory()->for($website)->create();
        $task = MaintenanceTask::factory()->for($website)->create();
        $vitals = CoreWebVital::factory()->for($website)->create();

        $this->assertTrue($user->websites->contains($website));
        $this->assertTrue($website->user->is($user));
        $this->assertTrue($website->health->is($health));
        $this->assertTrue($website->plugins->contains($plugin));
        $this->assertTrue($website->securityIssues->contains($issue));
        $this->assertTrue($website->maintenanceTasks->contains($task));
        $this->assertTrue($website->vitals->is($vitals));
        $this->assertTrue($health->website->is($website));
        $this->assertTrue($plugin->website->is($website));
        $this->assertTrue($issue->website->is($website));
        $this->assertTrue($task->website->is($website));
        $this->assertTrue($vitals->website->is($website));
    }
}
