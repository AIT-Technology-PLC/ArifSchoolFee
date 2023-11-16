<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCompanyLimitRequest;
use App\Models\Company;

class CompanyLimitController extends Controller
{
    public function __invoke(UpdateCompanyLimitRequest $request, Company $company)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $company->limits()->sync(
            collect($request->validated('limit'))->whereNotNull('amount')->toArray()
        );

        return back()->with('successMessage', 'Resource limits have been updated successfully.');
    }
}
