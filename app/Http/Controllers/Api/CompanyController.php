<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:General Settings');
    }

    public function show()
    {
        return userCompany();
    }
}
