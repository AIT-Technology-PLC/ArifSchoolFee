<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\AssignFeeDatatable;
use App\Http\Controllers\Controller;
use App\Models\AssignFeeMaster;
use App\Models\FeeMaster;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentCategory;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class AssignFeeMasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Fee Master');
    }

    public function show(AssignFeeDatatable $datatable, FeeMaster $assignFee)
    {
        $datatable->builder()->setTableId('assign-fees-datatable')->orderBy(0, 'asc');

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);

        $sections = Section::orderBy('name')->get(['id', 'name']);

        $categories = StudentCategory::orderBy('name')->get(['id', 'name']);

        return $datatable->render('assign-fees.show', compact('assignFee', 'branches', 'classes', 'sections', 'categories'));
    }

    public function update(Request $request, FeeMaster $assignFee)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|array',
            'student_id.*' => 'exists:students,id',
        ]);

        $currentStudentIds = AssignFeeMaster::where('fee_master_id', $assignFee->id)->pluck('student_id')->toArray();

        $studentsToRemove = array_diff($currentStudentIds, $validatedData['student_id']);

        AssignFeeMaster::where('fee_master_id', $assignFee->id)
                   ->whereIn('student_id', $studentsToRemove)
                   ->delete();

        foreach ($validatedData['student_id'] as $studentId) {
            AssignFeeMaster::updateOrCreate(
                [
                    'company_id' => userCompany()->id,
                    'fee_master_id' => $assignFee->id,
                    'student_id' => $studentId,
                ]
            );
        }

        return redirect()->route('assign-fees.show', $assignFee->id)->with('successMessage', ' Fee Master Assigned successfully.');
    }
}
