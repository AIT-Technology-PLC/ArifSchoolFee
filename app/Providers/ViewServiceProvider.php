<?php

namespace App\Providers;

use App\View\Composers\MerchandiseBatchComposer;
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
            'adjustments.partials.details-form',
            'bill-of-materials.details-form',
            'damages.partials.details-form',
            'gdns.details-form',
            'grns.partials.details-form',
            'job-extras.partials.details-form',
            'job-planners.create',
            'jobs.partials.details-form',
            'prices.partials.details-form',
            'proforma-invoices.partials.details-form',
            'purchases.details-form',
            'reservations.details-form',
            'returns.details-form',
            'sales.partials.details-form',
            'sivs.details-form',
            'transfers.details-form',
            'cost-updates.partials.details-form',
            'products.partials.details-form',
            'exchanges.partials.details-form',
        ];

        View::composer($views, ProductComposer::class);

        View::composer($views, MerchandiseBatchComposer::class);
    }
}
