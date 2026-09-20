<?php

namespace Tests\Feature;

use App\Models\CoreWebVital;
use App\Models\Website;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoreWebVitalTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_creates_core_web_vitals(): void
    {
        $vitals = CoreWebVital::factory()->create();

        $this->assertDatabaseHas('core_web_vitals', [
            'id' => $vitals->id,
            'website_id' => $vitals->website_id,
        ]);
    }

    public function test_a_website_can_have_only_one_core_web_vitals_row(): void
    {
        $website = Website::factory()->create();

        CoreWebVital::factory()->create([
            'website_id' => $website->id,
        ]);

        $this->expectException(QueryException::class);

        CoreWebVital::factory()->create([
            'website_id' => $website->id,
        ]);
    }
}
