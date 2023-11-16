<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;

class CompanyToggleActivationController extends Controller
{
    public function __invoke(Company $company)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $company->toggleActivation();

        return back();
    }
}
