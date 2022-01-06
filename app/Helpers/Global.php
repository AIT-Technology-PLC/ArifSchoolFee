<?php

use App\Models\Feature;
use App\Models\Limit;
use Illuminate\Support\Facades\Cache;

if (!function_exists('userCompany')) {
    function userCompany()
    {
        return auth()->user()->employee->company;
    }
}

if (!function_exists('limitReached')) {
    function limitReached($limitName, $currentAmount)
    {
        return (new Limit())->isLimitReached($limitName, $currentAmount);
    }
}

if (!function_exists('isFeatureEnabled')) {
    function isFeatureEnabled($featureName)
    {
        $enabledFeatures = Cache::store('array')->rememberForever(auth()->id() . '_' . 'enabledFeatures', function () {
            return Feature::getAllEnabledFeaturesOfCompany();
        });

        return $enabledFeatures->contains($featureName);
    }
}

if (!function_exists('money')) {
    function money($amount = 0.00, $currency = null)
    {
        $currency = $currency ?: userCompany()->currency;

        return $currency . '. ' . number_format($amount, 2);
    }
}
