<?php

namespace App\Http\View\Composers;

use App\Models\Feature;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FeatureAuthComposer
{
    public function compose(View $view)
    {
        if (!auth()->check()) {
            return;
        }

        $enabledFeatures = Cache::store('array')
            ->rememberForever(auth()->id() . '_' . 'enabledFeatures', function () {
                return Feature::getAllEnabledFeaturesOfCompany();
            });

        $view->with('enabledFeatures', $enabledFeatures);
    }
}
