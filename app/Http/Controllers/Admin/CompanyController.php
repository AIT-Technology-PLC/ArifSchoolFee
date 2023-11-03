<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreateCompanyAction;
use App\DataTables\Admin\CompanyDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCompanyRequest;
use App\Http\Requests\Admin\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\Feature;
use App\Models\Integration;
use App\Models\Limit;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index(CompanyDatatable $datatable)
    {
        $enabledCompanies = Company::enabled()->count();

        $disabledCompanies = Company::disabled()->count();

        $companies = $enabledCompanies + $disabledCompanies;

        return $datatable->render('admin.companies.index', compact('enabledCompanies', 'disabledCompanies', 'companies'));
    }

    public function create()
    {
        $plans = Plan::enabled()->get();

        $limits = Limit::all();

        $integrations = Integration::enabled()->get();

        return view('admin.companies.create', compact('plans', 'limits', 'integrations'));
    }

    public function store(StoreCompanyRequest $request, CreateCompanyAction $createCompanyAction)
    {
        $user = DB::transaction(function () use ($request, $createCompanyAction) {
            $user = $createCompanyAction->execute($request->safe()->except('limit'));

            $user->employee->company->limits()->sync($request->validated('limit'));

            $user->employee->company->integrations()->syncWithPivotValues($request->validated('integrations'), ['is_enabled' => 1]);

            return $user;
        });

        return redirect()->route('admin.companies.show', $user->employee->company_id);
    }

    public function edit(Company $company)
    {
        $plans = Plan::enabled()->get();

        return view('admin.companies.edit', compact('company', 'plans'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->update($request->validated());

        return redirect()->route('admin.companies.show', $company);
    }

    public function show(Company $company)
    {
        $company->load(['integrations', 'pads', 'customFields']);

        $limits = Limit::getAllLimitsOfCompany($company);

        $features = Feature::getAllEnabledFeaturesOfCompany($company->id);

        return view('admin.companies.show', compact('company', 'limits', 'features'));
    }
}
