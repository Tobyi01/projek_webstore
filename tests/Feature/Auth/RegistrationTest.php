<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_is_not_available_to_public(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_new_cashier_must_be_created_by_admin(): void
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.cashiers.store'), [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertRedirect(route('admin.cashiers.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'kasir',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $admin->email,
            'role' => 'admin',
        ]);
    }
}
