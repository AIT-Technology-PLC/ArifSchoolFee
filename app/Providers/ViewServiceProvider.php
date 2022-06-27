<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        Blade::if('canpad', function ($action, $pad) {
            return authUser()->hasRole('System Manager') ||
            getPadPermissions()->where('pad_id', $pad->id)->where('name', $action)->count();
        });
    }
}
