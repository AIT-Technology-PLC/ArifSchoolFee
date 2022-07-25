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
        $datatable->builder()->setTableId('leave-categories-datatable')->orderBy(1, 'asc');

        $totalLeaveCategories = LeaveCategory::count();

        return $datatable->render('leave-categories.index', compact('totalLeaveCategories'));
    }

    public function create()
    {
        return view('leave-categories.create');
    }

    public function store(StoreLeaveCategoryRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated('leaveCategory') as $leaveCategory) {
                leaveCategory::create($leaveCategory);
            }
        });

        return redirect()->route('leave-categories.index')->with('successMessage', 'New leave Category are added.');
    }

    public function edit(LeaveCategory $leaveCategory)
    {
        return view('leave-categories.edit', compact('leaveCategory'));
    }

    public function update(UpdateLeaveCategoryRequest $request, LeaveCategory $leaveCategory)
    {
        $leaveCategory->update($request->validated());

        return redirect()->route('leave-categories.index');
    }

    public function destroy(LeaveCategory $leaveCategory)
    {
        $leaveCategory->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}