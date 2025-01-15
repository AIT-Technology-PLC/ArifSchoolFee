<?php

namespace App\Http\Controllers\ServiceCenter;

use App\DataTables\CollectSchoolFeeDatatable;
use App\DataTables\StudentFeeDatatable;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Warehouse;

class CollectFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Collect Fee');
    }

    public function index(CollectSchoolFeeDatatable $datatable)
    {
        abort_if(authUser()->cannot('Manage Schools Payment'), 403);

        $datatable->builder()->setTableId('collect-school-fees-datatable')->orderBy(0, 'asc');

        $schools = Company::enabled()->orderBy('name')->get(['id', 'name','company_code']);

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);

        $sections = Section::orderBy('name')->get(['id', 'name']);

        return $datatable->render('service-centers.collect-fees.index', compact('schools','branches', 'classes', 'sections'));
    }

    public function show(StudentFeeDatatable $datatable, Student $collectFee)
    {
        abort_if(authUser()->cannot('Manage Schools Payment'), 403);

        $datatable = new StudentFeeDatatable($collectFee->id);
        
        $datatable->builder()->setTableId('student-fees-datatable')->orderBy(0, 'asc');

        $collectFee->load(['company', 'warehouse', 'section', 'schoolClass', 'studentCategory', 'studentGroup']);

        return $datatable->render('service-centers.collect-fees.show', compact('collectFee'));
    }
}
