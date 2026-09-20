<?php

namespace Tests\Feature;

use App\Models\Website;
use App\Models\WebsiteHealth;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteHealthTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_creates_a_health_snapshot(): void
    {
        $health = WebsiteHealth::factory()->create();

        $this->assertDatabaseHas('website_health', [
            'id' => $health->id,
            'website_id' => $health->website_id,
        ]);
    }

    public function test_a_website_can_have_only_one_health_snapshot(): void
    {
        $website = Website::factory()->create();

        WebsiteHealth::factory()->create([
            'website_id' => $website->id,
        ]);

        $this->expectException(QueryException::class);

        WebsiteHealth::factory()->create([
            'website_id' => $website->id,
        ]);
    }
}
