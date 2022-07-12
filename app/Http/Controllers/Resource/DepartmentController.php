<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\DepartmentDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index(DepartmentDatatable $datatable)
    {
        $datatable->builder()->setTableId('departments-datatable')->orderBy(1, 'asc');

        $totalDepartments = Department::count();

        return $datatable->render('departments.index', compact('totalDepartments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(StoreDepartmentRequest $request)
    {
        $departments = collect($request->validated('department'));

        if ($departments->duplicates('name')->count()) {
            return back()->withInput()->with('failedMessage', 'Department name should be unique.');
        }

        DB::transaction(function () use ($departments) {
            foreach ($departments as $department) {
                Department::firstOrCreate($department);
            }
        });

        return redirect()->route('departments.index')->with('successMessage', 'New department are added.');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $department->update($request->validated());

        return redirect()->route('departments.index');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}