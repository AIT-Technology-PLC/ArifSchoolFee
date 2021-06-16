<?php

namespace App\Providers;

use App\Models\Feature;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__ . '/../Helpers/Global.php';
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $view->with([
                    'unreadNotifications' => auth()->user()->unreadNotifications,
                    'readNotifications' => auth()->user()->readNotifications,
                    'enabledFeatures' => Feature::getAllEnabledFeaturesOfCompany(),
                ]);
            }
        });
    }
}
