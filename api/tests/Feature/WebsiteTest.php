<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Website;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_creates_a_website(): void
    {
        $website = Website::factory()->create();

        $this->assertDatabaseHas('websites', [
            'id' => $website->id,
            'user_id' => $website->user_id,
            'url' => $website->url,
        ]);
    }

    public function test_the_same_user_cannot_have_duplicate_website_urls(): void
    {
        $user = User::factory()->create();

        Website::factory()->create([
            'user_id' => $user->id,
            'url' => 'https://example.com',
        ]);

        $this->expectException(QueryException::class);

        Website::factory()->create([
            'user_id' => $user->id,
            'url' => 'https://example.com',
        ]);
    }
}
