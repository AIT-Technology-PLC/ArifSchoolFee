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
            'layouts.create_menu',
            'layouts.notification_box',
            'layouts.menu',
            'layouts.footer',
            'assets.js',
            'components.delete_button',
            'components.deleted_message',
            'warehouses.merchandises.on-hand',
            'warehouses.merchandises.out-of'
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
