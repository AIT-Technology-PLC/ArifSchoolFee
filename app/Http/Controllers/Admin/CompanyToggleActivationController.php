<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;

class CompanyToggleActivationController extends Controller
{
    public function __invoke(Company $company)
    {
        $company->toggleActivation();

        return back();
    }
}
