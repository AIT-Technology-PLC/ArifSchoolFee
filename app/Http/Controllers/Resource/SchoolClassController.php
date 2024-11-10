<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\DataTables\SchoolClassDatatable;
use App\Http\Requests\StoreSchoolClassRequest;
use App\Http\Requests\UpdateSchoolClassRequest;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

class SchoolClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Class Management');

        $this->authorizeResource(SchoolClass::class);
    }

    public function index(SchoolClassDatatable $datatable)
    {
        $datatable->builder()->setTableId('school-classes-datatable')->orderBy(1, 'asc');

        $totalClasses = SchoolClass::count();

        return $datatable->render('school-classes.index', compact('totalClasses'));
    }

    public function create()
    {
        $sections = Section::orderBy('name')->get(['id', 'name']);

        return view('school-classes.create', compact('sections'));
    }

    public function store(StoreSchoolClassRequest $request)
    {
        DB::transaction(function () use ($request) {
            $schoolClass = SchoolClass::create($request->safe()->except('section_id'));

            $schoolClass->sections()->sync($request->validated('section_id'));
        });

        return redirect()->route('school-classes.index')->with('successMessage', 'New Class Created Successfully.');
    }

    public function edit(SchoolClass $schoolClass)
    {
        $sections = Section::orderBy('name')->get(['id', 'name']);

        return view('school-classes.edit', compact('sections','schoolClass'));
    }

    public function update(UpdateSchoolClassRequest $request, SchoolClass $schoolClass)
    {
        DB::transaction(function () use ($request, $schoolClass) {
            $schoolClass->update($request->safe()->except('section_id'));

            $schoolClass->sections()->sync($request->validated('section_id'));
        });

        return redirect()->route('school-classes.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(SchoolClass $schoolClass)
    {
        $schoolClass->delete();

        return back()->with('deleted', 'Deleted Successfully.');
    }
}