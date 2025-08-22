<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        // EventUser Policy
        'App\Models\EventUser' => 'App\Policies\EventUserPolicy',
        // User Policy
        'App\Models\User' => 'App\Policies\UserPolicy',
        // Event Policy
        'App\Models\Event' => 'App\Policies\EventPolicy',
        // Department Policy
        'App\Models\Department' => 'App\Policies\DepartmentPolicy',
        // EventQuery Policy
        'App\Models\EventQuery' => 'App\Policies\EventQueryPolicy',
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Admin can do everything
        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });

        // Organizer can manage events but not full system
        Gate::define('isOrganizer', function ($user) {
            return $user->role === 'organizer';
        });

        // Normal user, just attend or interact
        Gate::define('isUser', function ($user) {
            return $user->role === 'user';
        });
    }
}
