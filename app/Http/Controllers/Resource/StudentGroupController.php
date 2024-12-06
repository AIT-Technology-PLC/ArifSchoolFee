<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\DataTables\StudentGroupDatatable;
use App\Models\StudentGroup;
use App\Http\Requests\UpdateStudentGroupRequest;
use App\Http\Requests\StoreStudentGroupRequest;

class StudentGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Student Group');

        $this->authorizeResource(StudentGroup::class);
    }

    public function index(StudentGroupDatatable $datatable)
    {
        $datatable->builder()->setTableId('student-groups-datatable')->orderBy(1, 'asc');

        $totalGroups = StudentGroup::count();

        return $datatable->render('student-groups.index', compact('totalGroups'));
    }

    public function create()
    {
        return view('student-groups.create');
    }

    public function store(StoreStudentGroupRequest $request)
    {
        StudentGroup::firstOrCreate(
            $request->safe()->only(['name'] + ['company_id' => userCompany()->id]),
            $request->safe()->except(['name'] + ['company_id' => userCompany()->id]),
        );

        return redirect()->route('student-groups.index')->with('successMessage', 'New Group Created Successfully.');
    }
    
    public function edit(StudentGroup $studentGroup)
    {
        return view('student-groups.edit', compact('studentGroup'));
    }

    public function update(UpdateStudentGroupRequest $request, StudentGroup $studentGroup)
    {
        $studentGroup->update($request->validated());

        return redirect()->route('student-groups.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(StudentGroup $studentGroup)
    {
        if ($studentGroup->students()->exists()) {
            return back()->with(['failedMessage' => 'This Student Group data is being used and cannot be deleted.']);
        }

        $studentGroup->delete();

        return back()->with('deleted', 'Deleted Successfully.');
    }
}