<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        $this->registerPolicies();

        // Define permissions based on the configuration
        // Ensure that the 'authorization.permissions' config exists and is an array

        $permissionsConfig = config('authorization.permissions');

        if (!is_array($permissionsConfig)) {
             abort(500, 'Authorization permissions configuration is not set or is not an array.') ;
        }

        foreach (config('authorization.permissions') as $permission => $value) {
            Gate::define($permission, function ($user) use ($permission) {

                return $user->authorization && in_array($permission, $user->authorization->permissions ?? []);
            });
        }

    }
}
