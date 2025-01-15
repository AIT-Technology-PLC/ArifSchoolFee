<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\AssignFeeDiscountDatatable;
use App\Http\Controllers\Controller;
use App\Models\AssignFeeDiscount;
use App\Models\FeeDiscount;
use Illuminate\Http\Request;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentCategory;
use App\Models\Warehouse;

class AssignFeeDiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Fee Discount');
    }

    public function show(AssignFeeDiscountDatatable $datatable, FeeDiscount $assignDiscountFee)
    {
        $datatable->builder()->setTableId('assign-discount-fees-datatable')->orderBy(0, 'asc');

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);

        $sections = Section::orderBy('name')->get(['id', 'name']);

        $categories = StudentCategory::orderBy('name')->get(['id', 'name']);

        return $datatable->render('assign-discount-fees.show', compact('assignDiscountFee', 'branches', 'classes', 'sections', 'categories'));
    }


    public function update(Request $request, FeeDiscount $assignDiscountFee)
    {
        if ($assignDiscountFee->whereHas('feePayments')->exists()) {
            return back()->with(['failedMessage' => 'Unable to edit the data since some of the assigned discount has already been utilized.']);
        }
        
        $validatedData = $request->validate([
            'student_id' => 'nullable|array',
            'student_id.*' => 'exists:students,id',
        ]);

        $action = $request->input('action');

        $currentStudentIds = AssignFeeDiscount::where('fee_discount_id', $assignDiscountFee->id)->pluck('student_id')->toArray();

        $newStudentIds = $validatedData['student_id'] ?? [];

        if ($action == 'remove') {
            AssignFeeDiscount::where('fee_discount_id', $assignDiscountFee->id)
                            ->whereIn('student_id', $newStudentIds)
                            ->delete();

            return redirect()->back()->with('successMessage', 'Fee Discount Removed successfully.');
        }

        if ($action == 'assign') {
            $studentsToAdd = array_diff($newStudentIds, $currentStudentIds);
    
            foreach ($studentsToAdd as $studentId) {
                AssignFeeDiscount::updateOrCreate(
                    [
                        'company_id' => userCompany()->id,
                        'fee_discount_id' => $assignDiscountFee->id,
                        'student_id' => $studentId,
                    ]
                );
            }

            return redirect()->back()->with('successMessage', ' Fee Discount Assigned successfully.');
        }
    }
}
