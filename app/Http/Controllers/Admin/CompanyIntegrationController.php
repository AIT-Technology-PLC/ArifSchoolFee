<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCompanyIntegrationRequest;
use App\Models\Company;

class CompanyIntegrationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateCompanyIntegrationRequest $request, Company $company)
    {
        $company->integrations()->syncWithPivotValues(
            $request->validated('integrations'),
            ['is_enabled' => 1]
        );

        return back()->with('successMessage', 'Integrations have been updated successfully.');
    }
}
