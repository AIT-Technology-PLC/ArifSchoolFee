<?php

namespace App\Http\Controllers\Report;

use App\DataTables\StudentHistoryReportDatatable;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Warehouse;

class StudentHistoryReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Student History Report');
    }

    public function index(StudentHistoryReportDatatable $datatable)
    {
        abort_if(authUser()->cannot('Read Student History Report'), 403);
        
        $datatable->builder()->setTableId('student-history-reports-datatable')->orderBy(1, 'asc');

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();
        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);
        $sections = Section::orderBy('name')->get(['id', 'name']);
        $academicYears = AcademicYear::orderBy('year')->get(['id', 'year']);

        return $datatable->render('reports.student-history', compact('branches', 'classes', 'sections','academicYears'));
    }
}
