<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_user_can_login_and_fetch_their_profile(): void
    {
        $user = User::factory()->create();

        $loginResponse = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $loginResponse->assertOk()->assertJsonStructure([
            'access_token',
            'token_type',
        ]);

        $this->withToken($loginResponse->json('access_token'))
            ->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('email', $user->email);
    }

    public function test_inactive_user_cannot_login_through_the_api(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertUnauthorized();
    }

    public function test_mobile_user_can_register_and_receive_a_token(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Mobile User',
            'email' => 'mobile@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertCreated()
            ->assertJsonStructure(['access_token', 'token_type']);

        $this->assertDatabaseHas('users', [
            'email' => 'mobile@example.com',
            'role' => 'kasir',
        ]);
    }
}
