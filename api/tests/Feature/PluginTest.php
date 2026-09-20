<?php

namespace Tests\Feature;

use App\Models\Plugin;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PluginTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_creates_a_plugin(): void
    {
        $plugin = Plugin::factory()->create();

        $this->assertDatabaseHas('plugins', [
            'id' => $plugin->id,
            'website_id' => $plugin->website_id,
            'name' => $plugin->name,
        ]);
    }

    public function test_a_website_can_have_many_plugins(): void
    {
        $website = Website::factory()->create();

        Plugin::factory()->count(2)->create([
            'website_id' => $website->id,
        ]);

        $this->assertDatabaseCount('plugins', 2);
    }
}
