<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\SectionDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Section Management');

        $this->authorizeResource(Section::class, 'section');
    }

    public function index(SectionDatatable $datatable)
    {
        $datatable->builder()->setTableId('sections-datatable')->orderBy(0, 'asc');

        $totalSections= Section::count();

        return $datatable->render('sections.index', compact('totalSections'));
    }

    public function create()
    {
        return view('sections.create');
    }

    public function store(StoreSectionRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated('section') as $section) {
                Section::create($section);
            }
        });

        return redirect()->route('sections.index')->with('successMessage', 'New Section Created Successfully.');
    }

    public function edit(Section $section)
    {
        return view('sections.edit', compact('section'));
    }

    public function update(UpdateSectionRequest $request, Section $section)
    {
        $section->update($request->validated());

        return redirect()->route('sections.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(Section $section)
    {
        $section->forceDelete();

        return back()->with('deleted', 'Deleted Successfully.');
    }
}
