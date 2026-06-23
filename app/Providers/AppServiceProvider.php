<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Gate to restrict admin backoffice access
        Gate::define('admin-access', function ($user) {
            // Admins are managed via the admins table — check if the authenticated
            // user's email matches an admin record (simple approach for web session auth).
            return \App\Models\Admin::where('email', $user->email)->exists();
        });
    }
}
