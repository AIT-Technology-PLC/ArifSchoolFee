<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Reports\StudentReport;
use App\Charts\StudentsByGenderChart;
use App\Charts\StudentsByBranchChart;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;

class StudentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Student Report');
    }

    public function index(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Student Report'), 403);

        $branches = authUser()->getAllowedWarehouses('transactions');
        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);
        $sections = Section::orderBy('name')->get(['id', 'name']);
        $academicYears = AcademicYear::orderBy('year')->get(['id', 'year']);

        $studentReport = new StudentReport($request->validated());

        $chart = new StudentsByGenderChart($studentReport);
        $chartS = new StudentsByBranchChart($studentReport);

        return view('reports.student',  
        ['chart' => $chart->build(), 'chartS' => $chartS->build()], 
        compact('studentReport', 'branches', 'classes', 'sections', 'academicYears'));
    }
}
