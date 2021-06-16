<?php

namespace App\Http\View\Composers;

use App\Models\Feature;
use Illuminate\View\View;

class FeatureAuthComposer
{
    public function compose(View $view)
    {
        if (auth()->check()) {
            $view->with([
                'enabledFeatures' => Feature::getAllEnabledFeaturesOfCompany(),
            ]);
        }
    }
}
