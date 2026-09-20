<?php

namespace Tests\Feature;

use App\Models\SecurityIssue;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityIssueTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_creates_a_security_issue(): void
    {
        $issue = SecurityIssue::factory()->create();

        $this->assertDatabaseHas('security_issues', [
            'id' => $issue->id,
            'website_id' => $issue->website_id,
            'title' => $issue->title,
        ]);
    }

    public function test_a_website_can_have_many_security_issues(): void
    {
        $website = Website::factory()->create();

        SecurityIssue::factory()->count(2)->create([
            'website_id' => $website->id,
        ]);

        $this->assertDatabaseCount('security_issues', 2);
    }
}
