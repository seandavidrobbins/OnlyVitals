<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MeTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_user_can_view_themselves(): void
    {
        $user = User::factory()->create([
            'name' => 'Sean Robbins',
            'email' => 'sean@example.com',
        ]);
        $token = $user->createToken('auth')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/me')
            ->assertOk()
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.name', 'Sean Robbins')
            ->assertJsonPath('user.email', 'sean@example.com')
            ->assertJsonMissingPath('user.password');
    }

    public function test_me_requires_a_token(): void
    {
        $this->getJson('/api/v1/me')->assertUnauthorized();
    }
}
