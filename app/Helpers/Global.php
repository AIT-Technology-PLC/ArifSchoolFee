<?php

use App\Models\Pad;
use App\Models\User;
use App\Models\Limit;
use App\Models\Feature;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

if (!function_exists('authUser')) {
    function authUser(): User
    {
        return auth()->user();
    }
}

if (!function_exists('userCompany')) {
    function userCompany()
    {
        return authUser()->employee->company;
    }
}

if (!function_exists('limitReached')) {
    function limitReached($limitName, $currentAmount)
    {
        return (new Limit())->isLimitReached($limitName, $currentAmount);
    }
}

if (!function_exists('isFeatureEnabled')) {
    function isFeatureEnabled(...$featureNames)
    {
        $enabledFeatures = Cache::store('array')->rememberForever(authUser()->id . '_' . 'enabledFeatures', function () {
            return Feature::getAllEnabledFeaturesOfCompany();
        });

        return $enabledFeatures->merge(pads()->pluck('name'))->intersect($featureNames)->isNotEmpty();
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
            ->when(Schema::hasColumn($table, 'warehouse_id'), fn($q) => $q->where('warehouse_id', authUser()->warehouse_id))
            ->max($column) + 1;
    }
}

if (!function_exists('quantity')) {
    function quantity($amount = 0.00, $unitOfMeasurement = 'Piece')
    {
        if (!$unitOfMeasurement) {
            $unitOfMeasurement = 'Piece';
        }

        return number_format($amount, 2) . ' ' . $unitOfMeasurement;
    }
}

if (!function_exists('pads')) {
    function pads($module = null)
    {
        $pads = Cache::store('array')->rememberForever(authUser()->id . '_' . 'pads', function () {
            return Pad::enabled()->get();
        });

        return $pads->when($module, fn($q) => $q->where('module', $module));
    }
}

if (!function_exists('getPadPermissions')) {
    function getPadPermissions()
    {
        return Cache::store('array')->rememberForever(authUser()->id . '_' . 'padPermissions', function () {
            return authUser()->padPermissions()->get();
        });
    }
}
