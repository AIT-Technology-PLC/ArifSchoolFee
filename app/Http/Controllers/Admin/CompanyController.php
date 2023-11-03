<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CompanyDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\Feature;
use App\Models\Limit;
use App\Models\Plan;

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
        return view('admin.companies.create');
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
