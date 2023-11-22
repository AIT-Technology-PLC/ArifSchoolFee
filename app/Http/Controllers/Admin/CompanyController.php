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
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $plans = Plan::enabled()->get();

        $limits = Limit::all();

        $integrations = Integration::enabled()->get();

        return view('admin.companies.create', compact('plans', 'limits', 'integrations'));
    }

    public function store(StoreCompanyRequest $request, CreateCompanyAction $createCompanyAction)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

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
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $company->load(['plan']);

        $plans = Plan::enabled()->get()->push($company->plan)->unique();

        return view('admin.companies.edit', compact('company', 'plans'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $company->update($request->validated());

        return redirect()->route('admin.companies.show', $company);
    }

    public function show(Company $company)
    {
        $company->load(['integrations', 'pads', 'customFields', 'limits', 'plan.limits', 'subscriptions']);

        $companyLimits = Limit::getAllLimitsOfCompany($company);

        $limits = Limit::all();

        $planFeatures = $company->plan->features()->get();

        $companyFeatures = $company->features()->get();

        $integrations = Integration::enabled()->get();

        $features = Feature::all();

        $plans = Plan::enabled()->get()->push($company->plan)->unique();

        $tables = ['brands', 'product_categories', 'products', 'contacts', 'customers', 'suppliers', 'purchases', 'prices', 'price_increments'];

        return view('admin.companies.show', compact('company', 'companyLimits', 'planFeatures', 'companyFeatures', 'limits', 'integrations', 'features', 'tables', 'plans'));
    }
}
