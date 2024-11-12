<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;

class CompanyToggleActivationController extends Controller
{
    public function __invoke(Company $school)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Activation'), 403);

        $school->toggleActivation();

        return back();
    }
}
