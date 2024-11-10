<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreateSchoolAction;
use App\DataTables\Admin\CompanyDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCompanyRequest;
use App\Http\Requests\Admin\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\Feature;
use App\Models\Limit;
use App\Models\Plan;
use App\Models\SchoolType;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index(CompanyDatatable $datatable)
    {
        $enabledCompanies = Company::enabled()->count();

        $disabledCompanies = Company::disabled()->count();

        $companies = $enabledCompanies + $disabledCompanies;

        return $datatable->render('admin.schools.index', compact('enabledCompanies', 'disabledCompanies', 'companies'));
    }

    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $plans = Plan::enabled()->get();

        $limits = Limit::all();

        $schoolTypes = SchoolType::all();

        return view('admin.schools.create', compact('plans', 'limits','schoolTypes'));
    }

    public function store(StoreCompanyRequest $request, CreateSchoolAction $createCompanyAction)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $user = DB::transaction(function () use ($request, $createCompanyAction) {
            $user = $createCompanyAction->execute($request->safe()->except('limit'));

            $user->employee->company->limits()->sync($request->validated('limit'));

            return $user;
        });

        return redirect()->route('admin.schools.show', $user->employee->company_id);
    }

    public function edit(Company $company)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $company->load(['plan','schoolType']);

        $schoolTypes = SchoolType::all();

        $plans = Plan::enabled()->get()->push($company->plan)->unique();

        return view('admin.schools.edit', compact('company', 'plans', 'schoolTypes'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $company->update($request->validated());

        return redirect()->route('admin.schools.show', $company);
    }

    public function show(Company $company)
    {
        $company->load(['pads', 'limits', 'plan.limits', 'subscriptions']);

        $companyLimits = Limit::getAllLimitsOfCompany($company);

        $limits = Limit::all();

        $planFeatures = $company->plan->features()->get();

        $companyFeatures = $company->features()->get();

        $features = Feature::all();

        $plans = Plan::enabled()->get()->push($company->plan)->unique();

        return view('admin.schools.show', compact('company', 'companyLimits', 'planFeatures', 'companyFeatures', 'limits','features', 'plans'));
    }
}
