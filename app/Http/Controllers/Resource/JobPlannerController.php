<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobPlannerRequest;
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

        $warehouses = authUser()->getAllowedWarehouses('read');

        return view('job-planners.create', compact('warehouses'));
    }

    public function store(StoreJobPlannerRequest $request)
    {
        $this->authorize('plan', Job::class);

        $report = JobPlannerService::finalReport($request->jobPlanner)->groupBy('index')->values();

        return back()->with('report', $report)->withInput();
    }
}