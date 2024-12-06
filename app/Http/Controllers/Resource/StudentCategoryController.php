<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\DataTables\StudentCategoryDatatable;
use App\Models\StudentCategory;
use App\Http\Requests\UpdateStudentCategoryRequest;
use App\Http\Requests\StoreStudentCategoryRequest;

class StudentCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Student Category');

        $this->authorizeResource(StudentCategory::class);
    }

    public function index(StudentCategoryDatatable $datatable)
    {
        $datatable->builder()->setTableId('student-categories-datatable')->orderBy(1, 'asc');

        $totalCategories = StudentCategory::count();

        return $datatable->render('student-categories.index', compact('totalCategories'));
    }

    public function create()
    {
        return view('student-categories.create');
    }

    public function store(StoreStudentCategoryRequest $request)
    {
        StudentCategory::firstOrCreate(
            $request->safe()->only(['name'] + ['company_id' => userCompany()->id]),
            $request->safe()->except(['name'] + ['company_id' => userCompany()->id]),
        );

        return redirect()->route('student-categories.index')->with('successMessage', 'New Group Created Successfully.');
    }
    
    public function edit(StudentCategory $studentCategory)
    {
        return view('student-categories.edit', compact('studentCategory'));
    }

    public function update(UpdateStudentCategoryRequest $request, StudentCategory $studentCategory)
    {
        $studentCategory->update($request->validated());

        return redirect()->route('student-categories.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(StudentCategory $studentCategory)
    {
        if ($studentCategory->students()->exists()) {
            return back()->with(['failedMessage' => 'This Student Category data is being used and cannot be deleted.']);
        }

        $studentCategory->delete();

        return back()->with('deleted', 'Deleted Successfully.');
    }
}