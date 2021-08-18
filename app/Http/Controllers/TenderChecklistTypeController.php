<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenderChecklistTypeRequest;
use App\Http\Requests\UpdateTenderChecklistTypeRequest;
use App\Models\TenderChecklistType;

class TenderChecklistTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Tender Management');

        $this->authorizeResource(TenderChecklistType::class);
    }

    public function index()
    {
        $tenderChecklistTypes = TenderChecklistType::companyTenderChecklistType()
            ->withCount('generalTenderChecklists')->with(['createdBy', 'updatedBy'])->get();

        $totalTenderChecklistTypes = $tenderChecklistTypes->count();

        return view('tender_checklist_types.index', compact('tenderChecklistTypes', 'totalTenderChecklistTypes'));
    }

    public function create()
    {
        return view('tender_checklist_types.create');
    }

    public function store(StoreTenderChecklistTypeRequest $request)
    {
        TenderChecklistType::firstOrCreate(
            $request->only(['name', 'company_id']),
            $request->except(['name', 'company_id'])
        );

        return redirect()->route('tender-checklist-types.index');
    }

    public function edit(TenderChecklistType $tenderChecklistType)
    {
        return view('tender_checklist_types.edit', compact('tenderChecklistType'));
    }

    public function update(UpdateTenderChecklistTypeRequest $request, TenderChecklistType $tenderChecklistType)
    {
        $tenderChecklistType->update($request->all());

        return redirect()->route('tender-checklist-types.index');
    }

    public function destroy(TenderChecklistType $tenderChecklistType)
    {
        $tenderChecklistType->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
