<?php

namespace Tests\Feature;

use App\Enums\UptimeStatus;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteHealth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteHealthEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_view_or_update_health(): void
    {
        $website = Website::factory()->create();

        $this->getJson("/api/v1/websites/{$website->id}/health")
            ->assertUnauthorized();

        $this->putJson("/api/v1/websites/{$website->id}/health", [
            'uptime_status' => UptimeStatus::Up->value,
        ])->assertUnauthorized();
    }

    public function test_show_returns_not_found_when_health_is_missing(): void
    {
        $user = User::factory()->create();
        $website = Website::factory()->for($user)->create();

        $this->actingAs($user)
            ->getJson("/api/v1/websites/{$website->id}/health")
            ->assertNotFound();
    }

    public function test_a_user_can_upsert_and_show_health_for_their_website(): void
    {
        $user = User::factory()->create();
        $website = Website::factory()->for($user)->create();

        $this->actingAs($user)
            ->putJson("/api/v1/websites/{$website->id}/health", [
                'ssl_expires_at' => '2027-01-15T00:00:00Z',
                'wordpress_version' => '6.7.1',
                'php_version' => '8.3.0',
                'uptime_status' => UptimeStatus::Up->value,
                'last_backup_at' => '2026-09-01T12:00:00Z',
                'last_checked_at' => '2026-09-21T18:00:00Z',
            ])
            ->assertOk()
            ->assertJsonPath('health.wordpress_version', '6.7.1')
            ->assertJsonPath('health.php_version', '8.3.0')
            ->assertJsonPath('health.uptime_status', UptimeStatus::Up->value)
            ->assertJsonMissingPath('health.website_id')
            ->assertJsonStructure([
                'health' => [
                    'id',
                    'ssl_expires_at',
                    'wordpress_version',
                    'php_version',
                    'uptime_status',
                    'last_backup_at',
                    'last_checked_at',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->actingAs($user)
            ->putJson("/api/v1/websites/{$website->id}/health", [
                'uptime_status' => UptimeStatus::Down->value,
                'wordpress_version' => '6.8.0',
                'php_version' => null,
                'ssl_expires_at' => null,
                'last_backup_at' => null,
                'last_checked_at' => null,
            ])
            ->assertOk()
            ->assertJsonPath('health.uptime_status', UptimeStatus::Down->value)
            ->assertJsonPath('health.wordpress_version', '6.8.0')
            ->assertJsonPath('health.php_version', null);

        $this->assertDatabaseCount('website_health', 1);

        $this->actingAs($user)
            ->getJson("/api/v1/websites/{$website->id}/health")
            ->assertOk()
            ->assertJsonPath('health.uptime_status', UptimeStatus::Down->value)
            ->assertJsonPath('health.wordpress_version', '6.8.0');
    }

    public function test_a_user_cannot_view_or_update_another_users_health(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $website = Website::factory()->for($owner)->create();
        WebsiteHealth::factory()->for($website)->create();

        $this->actingAs($stranger)
            ->getJson("/api/v1/websites/{$website->id}/health")
            ->assertForbidden();

        $this->actingAs($stranger)
            ->putJson("/api/v1/websites/{$website->id}/health", [
                'uptime_status' => UptimeStatus::Down->value,
            ])
            ->assertForbidden();
    }

    public function test_upsert_validates_uptime_status_and_dates(): void
    {
        $user = User::factory()->create();
        $website = Website::factory()->for($user)->create();

        $this->actingAs($user)
            ->putJson("/api/v1/websites/{$website->id}/health", [
                'uptime_status' => 'flaky',
                'ssl_expires_at' => 'not-a-date',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['uptime_status', 'ssl_expires_at']);
    }
}
