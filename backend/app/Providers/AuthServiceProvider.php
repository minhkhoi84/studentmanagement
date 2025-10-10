<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Grant all abilities to super admin without assigning permissions/policies
        Gate::before(function ($user, string $ability = null) {
            return ($user && ($user->role ?? null) === 'super_admin') ? true : null;
        });
    }
}



