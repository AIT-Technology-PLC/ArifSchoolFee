<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobPlannerRequest;
use App\Models\BillOfMaterial;
use App\Services\Models\JobPlannerService;

class JobPlannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Job Management');
    }

    public function create()
    {
        $this->authorize('plan', Job::class);

        $warehouses = auth()->user()->getAllowedWarehouses('read');

        $billOfMaterials = BillOfMaterial::all();

        return view('job-planner.create', compact('billOfMaterials', 'warehouses'));
    }

    public function store(StoreJobPlannerRequest $request)
    {
        $report = JobPlannerService::finalReport($request->jobPlanner)->groupBy('index')->values();

        return back()->with('report', $report);
    }
}