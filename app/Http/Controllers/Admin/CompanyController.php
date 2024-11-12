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
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $enabledSchools = Company::enabled()->count();

        $disabledSchools = Company::disabled()->count();

        $schools = $enabledSchools + $disabledSchools;

        return $datatable->render('admin.schools.index', compact('enabledSchools', 'disabledSchools', 'schools'));
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

    public function edit(Company $school)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $school->load(['plan','schoolType']);

        $schoolTypes = SchoolType::all();

        $plans = Plan::enabled()->get()->push($school->plan)->unique();

        return view('admin.schools.edit', compact('school', 'plans', 'schoolTypes'));
    }

    public function update(UpdateCompanyRequest $request, Company $school)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $school->update($request->validated());

        return redirect()->route('admin.schools.show', $school);
    }

    public function show(Company $school)
    {
        $school->load(['limits', 'plan.limits', 'subscriptions']);

        $schoolLimits = Limit::getAllLimitsOfCompany($school);

        $planFeatures = $school->plan->features()->get();

        $schoolFeatures = $school->features()->get();

        $limits = Limit::all();

        $features = Feature::all();

        $plans = Plan::enabled()->get()->push($school->plan)->unique();

        return view('admin.schools.show', compact('school', 'schoolLimits', 'planFeatures', 'schoolFeatures', 'limits','features', 'plans'));
    }
}
