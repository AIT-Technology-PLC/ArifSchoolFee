<?php

namespace App\Providers;

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
        $this->loadHelpers();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {

            if (auth()->check()) {

                $view->with([
                    'unreadNotifications' => auth()->user()->unreadNotifications,
                ]);

            }

        });
    }

    protected function loadHelpers()
    {
        foreach (glob(__DIR__ . '/../Helpers/Global.php') as $filename) {
            require_once $filename;
        }
    }
}
