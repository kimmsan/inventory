<?php

namespace App\Providers;

use App\Models\Permission;
use DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (DB::connection()->getDatabaseName()) {
            if (Schema::hasTable('permissions')) {
                $permissions = Permission::all();
                if (! empty($permissions)) {
                    foreach ($permissions as $permission) {
                        Gate::define($permission->slug, function ($user) use ($permission) {
                            return $user->hasPermissionTo($permission->slug);
                        });
                    }
                }
            }

            return;
        }
    }
}
