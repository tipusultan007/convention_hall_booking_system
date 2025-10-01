<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate; // <-- Make sure this is imported
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // ** THIS IS THE CORRECT AND FINAL LOCATION FOR THIS LOGIC **

        // Implicitly grant "super-admin" role all permissions.
        // This runs before all other authorization checks.
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}
