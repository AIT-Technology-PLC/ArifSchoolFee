<?php

namespace App\Providers;

use App\View\Composers\ProductComposer;
use Illuminate\Support\Facades\Blade;
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
        Blade::if('canpad', function ($action, $pad) {
            return authUser()->hasRole('System Manager') ||
            getPadPermissions()->where('pad_id', $pad->id)->where('name', $action)->count();
        });

        $this->productViewComposer();
    }

    private function productViewComposer()
    {
        $views = [
            'gdns.details-form',
            'proforma-invoices.partials.details-form',
            'reservations.details-form',
            'sales.partials.details-form',
            'grns.partials.details-form',
            'adjustments.partials.details-form',
            'damages.partials.details-form',
            'jobs.partials.details-form',
            'job-extras.partials.details-form',
            'transfers.details-form',
            'returns.details-form',
            'sivs.details-form',
            'purchases.details-form',
        ];

        View::composer($views, ProductComposer::class);
    }
}
