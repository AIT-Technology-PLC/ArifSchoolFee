<?php

use App\Models\Limit;

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

if (!function_exists('userWarehouse')) {

    function userWarehouse()
    {
        return auth()->user()->warehouse;
    }

}
