<?php

namespace Tests\Feature;

use App\Enums\CmsType;
use App\Models\User;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_list_websites(): void
    {
        $this->getJson('/api/v1/websites')->assertUnauthorized();
    }

    public function test_a_user_can_create_and_list_their_websites(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/v1/websites', [
                'name' => 'Acme',
                'url' => 'https://acme.example',
                'cms_type' => CmsType::WordPress->value,
                'notes' => 'Primary site',
            ])
            ->assertCreated()
            ->assertJsonPath('website.name', 'Acme')
            ->assertJsonPath('website.url', 'https://acme.example')
            ->assertJsonPath('website.cms_type', CmsType::WordPress->value)
            ->assertJsonMissingPath('website.user_id')
            ->assertJsonStructure([
                'website' => [
                    'id',
                    'name',
                    'url',
                    'cms_type',
                    'notes',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->actingAs($user)
            ->getJson('/api/v1/websites')
            ->assertOk()
            ->assertJsonCount(1, 'websites')
            ->assertJsonPath('websites.0.name', 'Acme');
    }

    public function test_a_user_does_not_see_another_users_websites(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $website = Website::factory()->for($owner)->create();

        $this->actingAs($stranger)
            ->getJson('/api/v1/websites')
            ->assertOk()
            ->assertJsonCount(0, 'websites');

        $this->actingAs($stranger)
            ->getJson("/api/v1/websites/{$website->id}")
            ->assertForbidden();
    }

    public function test_a_user_can_update_and_delete_their_website(): void
    {
        $user = User::factory()->create();
        $website = Website::factory()->for($user)->create([
            'name' => 'Old Name',
            'url' => 'https://old.example',
        ]);

        $this->actingAs($user)
            ->putJson("/api/v1/websites/{$website->id}", [
                'name' => 'New Name',
                'url' => 'https://old.example',
                'cms_type' => CmsType::Other->value,
                'notes' => null,
            ])
            ->assertOk()
            ->assertJsonPath('website.name', 'New Name')
            ->assertJsonPath('website.cms_type', CmsType::Other->value);

        $this->actingAs($user)
            ->deleteJson("/api/v1/websites/{$website->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('websites', ['id' => $website->id]);
    }

    public function test_store_validates_cms_type_and_unique_url_per_user(): void
    {
        $user = User::factory()->create();
        Website::factory()->for($user)->create([
            'url' => 'https://acme.example',
        ]);

        $this->actingAs($user)
            ->postJson('/api/v1/websites', [
                'name' => 'Acme',
                'url' => 'https://acme.example',
                'cms_type' => 'drupal',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['cms_type']);

        $this->actingAs($user)
            ->postJson('/api/v1/websites', [
                'name' => 'Acme',
                'url' => 'https://acme.example',
                'cms_type' => CmsType::WordPress->value,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['url']);
    }
}
