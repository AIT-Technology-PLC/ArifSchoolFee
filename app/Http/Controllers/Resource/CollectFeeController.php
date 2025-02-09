<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CollectFeeDatatable;
use App\DataTables\StudentFeeDatatable;
use App\Http\Controllers\Controller;
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

    public function index(CollectFeeDatatable $datatable)
    {
        abort_if(authUser()->cannot('Read Collect Fee'), 403);

        $datatable->builder()->setTableId('collect-fees-datatable')->orderBy(0, 'asc');

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);

        $sections = Section::orderBy('name')->get(['id', 'name']);

        return $datatable->render('collect-fees.index', compact('branches', 'classes', 'sections'));
    }

    public function show(Student $collectFee)
    {
        abort_if(authUser()->cannot('Update Collect Fee'), 403);

        $datatable = new StudentFeeDatatable($collectFee->id, $collectFee->company->currency);
        
        $datatable->builder()->setTableId('student-fees-datatable')->orderBy(0, 'asc');

        $collectFee->load(['warehouse', 'section', 'schoolClass', 'studentCategory', 'studentGroup']);

        return $datatable->render('collect-fees.show', compact('collectFee'));
    }
}
