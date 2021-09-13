<?php

namespace App\Http\View\Composers;

use App\Models\Feature;
use Illuminate\View\View;

class FeatureAuthComposer
{
    public function compose(View $view)
    {
        $excludedViews = collect([
            'layouts.app',
            'assets.css',
            'pwa.tags',
            'layouts.header',
            'layouts.create-menu',
            'layouts.notification-box',
            'layouts.menu',
            'layouts.footer',
            'assets.js',
            'components.delete-button',
            'components.deleted-message',
            'components.product-list',
            'warehouses.merchandises.on-hand',
            'warehouses.merchandises.out-of',
            'merchandises.on-hand',
            'merchandises.available',
            'merchandises.reserved',
            'merchandises.out-of',
            'datatables::script',
        ]);

        if ($excludedViews->contains($view->getName())) {
            return false;
        }

        if (!auth()->check()) {
            return false;
        }

        $view->with([
            'enabledFeatures' => Feature::getAllEnabledFeaturesOfCompany(),
        ]);
    }
}
