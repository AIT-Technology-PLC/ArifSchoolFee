<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\SchoolTypeDatatable;
use App\Models\SchoolType;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\StoreSchoolTypeRequest;
use App\Http\Requests\Admin\UpdateSchoolTypeRequest;

class SchoolTypeController extends Controller
{
    public function index(SchoolTypeDatatable $datatable)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $datatable->builder()->setTableId('school-types-datatable')->orderBy(1, 'asc');

        $totaltypes = SchoolType::count();

        return $datatable->render('admin.school-types.index', compact('totaltypes'));
    }

    public function create()
    {
        return view('admin.school-types.create');
    }

    public function store(StoreSchoolTypeRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated('schoolType') as $schoolType) {
                SchoolType::create($schoolType);
            }
        });

        return redirect()->route('admin.school-types.index')->with('successMessage', 'Created successfully.');
    }

    public function edit(SchoolType $schoolType)
    {
        return view('admin.school-types.edit', compact('schoolType'));
    }

    public function update(UpdateSchoolTypeRequest $request, SchoolType $schoolType)
    {
        $schoolType->update($request->validated());

        return redirect()->route('admin.school-types.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(SchoolType $schoolType)
    {
        $schoolType->forceDelete();

        return back()->with('successMessage', 'Deleted successfully.');
    }
}
