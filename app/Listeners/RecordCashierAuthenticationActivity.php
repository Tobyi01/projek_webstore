<?php

namespace App\Listeners;

use App\Models\LoginActivity;
use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class RecordCashierAuthenticationActivity
{
    public function login(Login $event): void
    {
        if ($event->guard !== 'web' || ! ($event->user instanceof User) || $event->user->role !== 'kasir') {
            return;
        }

        $this->record($event->user, 'login');
    }

    public function failed(Failed $event): void
    {
        if ($event->guard !== 'web') {
            return;
        }

        $email = $event->credentials['email'] ?? null;
        if (! is_string($email)) {
            return;
        }

        $cashier = User::query()
            ->where('role', 'kasir')
            ->where('email', $email)
            ->first();

        if ($cashier) {
            $this->record($cashier, 'failed');
        }
    }

    public function logout(Logout $event): void
    {
        if ($event->guard !== 'web' || ! ($event->user instanceof User) || $event->user->role !== 'kasir') {
            return;
        }

        $this->record($event->user, 'logout');
    }

    private function record(User $cashier, string $activity): void
    {
        LoginActivity::query()->create([
            'user_id' => $cashier->id,
            'cashier_name' => $cashier->name,
            'cashier_email' => $cashier->email,
            'activity' => $activity,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
