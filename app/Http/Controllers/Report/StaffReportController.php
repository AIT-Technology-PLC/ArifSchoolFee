<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Reports\StaffReport;
use App\Charts\StaffByDesignationChart;
use App\Charts\StaffByDepartmentChart;
use App\Charts\StaffByBranchChart;

class StaffReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Staff Report');
    }

    public function index(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Staff Report'), 403);

        $branches = authUser()->getAllowedWarehouses('transactions');
        $departments = Department::orderBy('name')->get(['id', 'name']);
        $designations = Designation::orderBy('name')->get(['id', 'name']);

        $staffReport = new StaffReport($request->validated());

        $chart = new StaffByDesignationChart($staffReport);
        $chartS = new StaffByDepartmentChart($staffReport);
        $chartT = new StaffByBranchChart($staffReport);

        return view('reports.staff',  
        ['chart' => $chart->build(), 'chartS' => $chartS->build(), 'chartT' => $chartT->build()], 
        compact('staffReport', 'branches', 'designations', 'departments'));
    }
}
