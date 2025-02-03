<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\StaffRole;
use App\Enums\StaffPermission;

class StaffPermissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['router']->aliasMiddleware('staff.permission', \App\Http\Middleware\CheckStaffPermission::class);

        $this->registerPermissionGates();

        $this->registerBladeDirectives();
    }

    public function register()
    {
        $this->app->singleton('staff.permissions', function ($app) {
            return array_map(fn($case) => $case->value, StaffPermission::cases());
        });
    }

    protected function registerPermissionGates()
    {
        Gate::before(function ($user) {
            if ($user->root_admin) {
                return true;
            }
        });

        foreach (StaffPermission::cases() as $permission) {
            Gate::define($permission->value, function ($user) use ($permission) {
                return $user->hasPermission($permission->value);
            });
        }
    }

    protected function registerBladeDirectives()
    {
        \Blade::if('permission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        \Blade::if('anypermission', function ($permissions) {
            return auth()->check() && auth()->user()->hasAnyPermission($permissions);
        });

        \Blade::if('allpermissions', function ($permissions) {
            return auth()->check() && auth()->user()->hasAllPermissions($permissions);
        });
    }
}
