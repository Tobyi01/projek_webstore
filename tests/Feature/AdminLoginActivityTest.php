<?php

namespace Tests\Feature;

use App\Models\LoginActivity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_login_and_logout_are_recorded_and_visible_to_admin(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->withServerVariables([
            'REMOTE_ADDR' => '203.0.113.8',
            'HTTP_USER_AGENT' => 'Test cashier browser',
        ])->post(route('login'), [
            'email' => $cashier->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('login_activities', [
            'user_id' => $cashier->id,
            'cashier_email' => $cashier->email,
            'activity' => 'login',
            'ip_address' => '203.0.113.8',
            'user_agent' => 'Test cashier browser',
        ]);

        $this->post(route('logout'))->assertRedirect('/');

        $this->assertDatabaseHas('login_activities', [
            'user_id' => $cashier->id,
            'activity' => 'logout',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.login-activities.index'))
            ->assertOk()
            ->assertSee($cashier->name)
            ->assertSee($cashier->email)
            ->assertSee('Login berhasil')
            ->assertSee('Logout')
            ->assertSee('203.0.113.8');
    }

    public function test_failed_cashier_login_is_recorded_but_unknown_and_admin_attempts_are_not(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->post(route('login'), [
            'email' => $cashier->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->post(route('login'), [
            'email' => 'unknown@example.test',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseHas('login_activities', [
            'user_id' => $cashier->id,
            'activity' => 'failed',
        ]);
        $this->assertDatabaseCount('login_activities', 1);
    }

    public function test_only_admin_can_view_login_activities_and_activity_remains_after_cashier_deletion(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $cashier = User::factory()->create(['role' => 'kasir']);
        $activity = LoginActivity::query()->create([
            'user_id' => $cashier->id,
            'cashier_name' => $cashier->name,
            'cashier_email' => $cashier->email,
            'activity' => 'login',
        ]);

        $this->actingAs($cashier)
            ->get(route('admin.login-activities.index'))
            ->assertForbidden();

        $cashier->delete();

        $this->assertDatabaseHas('login_activities', [
            'id' => $activity->id,
            'user_id' => null,
            'cashier_email' => $cashier->email,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.login-activities.index'))
            ->assertOk()
            ->assertSee($cashier->email);
    }
}
