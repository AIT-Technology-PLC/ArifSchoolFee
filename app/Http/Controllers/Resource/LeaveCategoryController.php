<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\LeaveCategoryDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveCategoryRequest;
use App\Http\Requests\UpdateLeaveCategoryRequest;
use App\Models\LeaveCategory;
use Illuminate\Support\Facades\DB;

class LeaveCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Leave Management');

        $this->authorizeResource(LeaveCategory::class);
    }

    public function index(LeaveCategoryDatatable $datatable)
    {
        $datatable->builder()->setTableId('leave_categories-datatable')->orderBy(1, 'asc');

        $totalLeaveCategories = LeaveCategory::count();

        return $datatable->render('leave_categories.index', compact('totalLeaveCategories'));
    }

    public function create()
    {
        return view('leave_categories.create');
    }

    public function store(StoreLeaveCategoryRequest $request)
    {
        $leaveCategories = collect($request->validated('leaveCategory'));

        DB::transaction(function () use ($leaveCategories) {
            foreach ($leaveCategories as $leaveCategory) {
                leaveCategory::firstOrCreate($leaveCategory);
            }
        });

        return redirect()->route('leave_categories.index')->with('successMessage', 'New leave Category are added.');
    }

    public function edit(LeaveCategory $leaveCategory)
    {
        return view('leave_categories.edit', compact('leaveCategory'));
    }

    public function update(UpdateLeaveCategoryRequest $request, LeaveCategory $leaveCategory)
    {
        $leaveCategory->update($request->validated());

        return redirect()->route('leave_categories.index');
    }

    public function destroy(LeaveCategory $leaveCategory)
    {
        $leaveCategory->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}