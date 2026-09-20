<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register_and_receive_a_token(): void
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Sean Robbins',
            'email' => 'sean@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('user.name', 'Sean Robbins')
            ->assertJsonPath('user.email', 'sean@example.com')
            ->assertJsonMissingPath('user.password')
            ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token']);

        $this->assertDatabaseHas('users', [
            'email' => 'sean@example.com',
        ]);
        $this->assertNotEmpty($response->json('token'));
    }

    public function test_registration_requires_a_confirmed_password(): void
    {
        $this->postJson('/api/v1/register', [
            'name' => 'Sean Robbins',
            'email' => 'sean@example.com',
            'password' => 'password',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    public function test_registration_rejects_a_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'sean@example.com',
        ]);

        $this->postJson('/api/v1/register', [
            'name' => 'Sean Robbins',
            'email' => 'sean@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }
}
