<?php

use App\Models\User;
use App\Models\Limit;
use App\Models\Feature;
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

if (!function_exists('notifiables')) {

    function notifiables($permission, $creator = null)
    {
        if (auth()->user()->can($permission) && auth()->user()->is($creator)) {
            return [];
        }

        if (auth()->user()->can($permission) && auth()->user()->isNot($creator)) {
            return $creator ?? [];
        }

        $users = User::permission($permission)
            ->whereIn('id', function ($query) {
                $query->select('user_id')
                    ->from('employees')
                    ->where('company_id', userCompany()->id)
                    ->where('id', '<>', auth()->user()->employee->id);
            })->get();

        if ($users->contains('warehouse_id', auth()->user()->warehouse_id)) {
            $users = $users->where('warehouse_id', auth()->user()->warehouse_id);
        }

        if ($creator) {
            $users->push($creator);
            $users = $users->unique();
        }

        return $users->except(auth()->id());
    }
}
