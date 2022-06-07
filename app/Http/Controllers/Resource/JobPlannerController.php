<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobPlannerRequest;

class JobPlannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Job Planner');
    }

    public function create()
    {
        return view('job-planners.create');
    }

    public function store(StoreJobPlannerRequest $request)
    {

    }
}