<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $plans = Plan::withCount(['companies', 'features'])->orderBy('is_enabled', 'DESC')->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function show(Plan $plan)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $plan->loadCount(['companies', 'features'])->load(['companies', 'features']);

        return view('admin.plans.show', compact('plan'));
    }
}
