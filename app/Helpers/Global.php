<?php

if (!function_exists('userCompany')) {

    function userCompany()
    {
        return auth()->user()->employee->company;
    }

}
