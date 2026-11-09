<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use Illuminate\View\View;

class LoginActivityController extends Controller
{
    public function index(): View
    {
        return view('admin.login-activities.index', [
            'activities' => LoginActivity::query()
                ->orderByDesc('created_at')
                ->paginate(25),
        ]);
    }
}
