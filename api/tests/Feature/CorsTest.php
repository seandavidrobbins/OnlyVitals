<?php

namespace Tests\Feature;

use Tests\TestCase;

class CorsTest extends TestCase
{
    public function test_next_origin_is_allowed_on_api_routes(): void
    {
        $this->getJson('/api/v1/health', [
            'Origin' => 'http://localhost:3000',
        ])
            ->assertOk()
            ->assertHeader('Access-Control-Allow-Origin', 'http://localhost:3000');
    }

    public function test_unknown_origin_is_not_reflected(): void
    {
        $response = $this->getJson('/api/v1/health', [
            'Origin' => 'http://evil.example',
        ]);

        $response->assertOk();
        $this->assertNotSame(
            'http://evil.example',
            $response->headers->get('Access-Control-Allow-Origin'),
        );
    }
}
