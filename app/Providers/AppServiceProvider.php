<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Task;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Gate::define('access-payroll', function (User $user) {
            return in_array($user->role_id, [1, 2, 3]);
        });

        Gate::policy(Task::class, TaskPolicy::class);

    }
}