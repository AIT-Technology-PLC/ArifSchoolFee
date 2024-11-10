<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Charts\CompaniesPlanChart;
use App\Http\Requests\Admin\StorePlanRequest;
use App\Http\Requests\Admin\UpdatePlanRequest;
use App\Models\Feature;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function index()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $plans = Plan::withCount(['companies', 'features'])->orderBy('is_enabled', 'DESC')->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $features = Feature::all();

        return view('admin.plans.create', compact('features'));
    }

    public function store(StorePlanRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        DB::transaction(function () use ($request) {
            $plan = Plan::create($request->safe()->except('feature_id'));

            $plan->features()->sync($request->validated('feature_id'));
        });

        return redirect()->route('admin.plans.index')->with('successMessage', 'Plan reated successfully');
    }

    public function edit(Plan $plan)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $features = Feature::all();

        return view('admin.plans.edit', compact('plan', 'features'));
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        DB::transaction(function () use ($request, $plan) {
            $plan->update($request->safe()->except('feature_id'));

            $plan->features()->sync($request->validated('feature_id'));
        });

        return redirect()->route('admin.plans.index')->with('successMessage', 'Plan updated successfully');
    }

    public function show(Plan $plan)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $plan->loadCount(['companies', 'features'])->load(['companies', 'features']);

        $chart = new CompaniesPlanChart($plan);

        return view('admin.plans.show',  ['chart' => $chart->build()], compact('plan'));
    }
}
