<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_log_in_and_receive_a_token(): void
    {
        $user = User::factory()->create([
            'email' => 'sean@example.com',
        ]);

        $this->postJson('/api/v1/login', [
            'email' => 'sean@example.com',
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.email', 'sean@example.com')
            ->assertJsonMissingPath('user.password')
            ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token']);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'sean@example.com',
        ]);

        $this->postJson('/api/v1/login', [
            'email' => 'sean@example.com',
            'password' => 'wrong-password',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }
}
