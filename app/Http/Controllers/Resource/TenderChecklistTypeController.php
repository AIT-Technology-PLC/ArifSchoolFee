<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\TenderChecklistTypeDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenderChecklistTypeRequest;
use App\Http\Requests\UpdateTenderChecklistTypeRequest;
use App\Models\TenderChecklistType;

class TenderChecklistTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');

        $this->authorizeResource(TenderChecklistType::class);
    }

    public function index(TenderChecklistTypeDatatable $datatable)
    {
        $datatable->builder()->setTableId('tender-checklist-types-datatable');

        $totalTenderChecklistTypes = TenderChecklistType::count();

        return $datatable->render('tender-checklist-types.index', compact('totalTenderChecklistTypes'));
    }

    public function create()
    {
        return view('tender-checklist-types.create');
    }

    public function store(StoreTenderChecklistTypeRequest $request)
    {
        TenderChecklistType::firstOrCreate(
            $request->only(['name']),
            $request->except(['name'])
        );

        return redirect()->route('tender-checklist-types.index');
    }

    public function edit(TenderChecklistType $tenderChecklistType)
    {
        return view('tender-checklist-types.edit', compact('tenderChecklistType'));
    }

    public function update(UpdateTenderChecklistTypeRequest $request, TenderChecklistType $tenderChecklistType)
    {
        $tenderChecklistType->update($request->validated());

        return redirect()->route('tender-checklist-types.index');
    }

    public function destroy(TenderChecklistType $tenderChecklistType)
    {
        $tenderChecklistType->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
