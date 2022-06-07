<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobPlannerRequest;
use App\Models\BillOfMaterial;

class JobPlannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Job Planner');
    }

    public function create()
    {
        $billOfMaterials = BillOfMaterial::all();
        return view('job-planner.create', compact('billOfMaterials'));
    }

    public function store(StoreJobPlannerRequest $request)
    {

    }
}