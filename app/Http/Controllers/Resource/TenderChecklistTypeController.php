<?php

namespace App\Http\Controllers\Resource;

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

    public function index()
    {
        $tenderChecklistTypes = TenderChecklistType::withCount('generalTenderChecklists')
            ->with(['createdBy', 'updatedBy'])->get();

        $totalTenderChecklistTypes = TenderChecklistType::count();

        return view('tender-checklist-types.index', compact('tenderChecklistTypes', 'totalTenderChecklistTypes'));
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
        $tenderChecklistType->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
