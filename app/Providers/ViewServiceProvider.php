<?php

namespace App\Providers;

use App\Http\View\Composers\FeatureAuthComposer;
use App\Http\View\Composers\MerchandiseIndexComposer;
use App\Http\View\Composers\NotificationComposer;
use Illuminate\Support\Facades\View;
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
        View::composer('*', NotificationComposer::class);

        View::composer('*', FeatureAuthComposer::class);

        View::composer('merchandises.index', MerchandiseIndexComposer::class);
    }
}
