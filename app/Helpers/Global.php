<?php

use App\Models\Feature;
use App\Models\Limit;
use App\Models\Pad;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

if (!function_exists('nextReferenceNumber')) {
    function nextReferenceNumber($table, $column = 'code')
    {
        return DB::table($table)
            ->where('company_id', userCompany()->id)
            ->where('warehouse_id', auth()->user()->warehouse_id)
            ->max($column) + 1;
    }
}

if (!function_exists('quantity')) {
    function quantity($amount = 0.00, $unitOfMeasurement = 'Piece')
    {
        return number_format($amount, 2) . ' ' . $unitOfMeasurement;
    }
}

if (!function_exists('pads')) {
    function pads($module)
    {
        $pads = Cache::store('array')->rememberForever(auth()->id() . '_' . 'pads', function () {
            return Pad::enabled()->get();
        });

        return $pads->where('module', $module);
    }
}
