<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\GeneralTenderChecklistDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGeneralTenderChecklistRequest;
use App\Http\Requests\UpdateGeneralTenderChecklistRequest;
use App\Models\GeneralTenderChecklist;
use App\Models\TenderChecklistType;

class GeneralTenderChecklistController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');

        $this->authorizeResource(GeneralTenderChecklist::class);
    }

    public function index(GeneralTenderChecklistDatatable $datatable)
    {
        $datatable->builder()->setTableId('general-tender-checklists-datatable')->orderBy(0, 'asc');

        $totalGeneralTenderChecklists = GeneralTenderChecklist::count();

        return $datatable->render('general-tender-checklists.index', compact('totalGeneralTenderChecklists'));
    }

    public function create()
    {
        $tenderChecklistTypes = TenderChecklistType::orderBy('name')->get();

        return view('general-tender-checklists.create', compact('tenderChecklistTypes'));
    }

    public function store(StoreGeneralTenderChecklistRequest $request)
    {
        GeneralTenderChecklist::firstOrCreate(
            $request->only(['item'] + ['company_id' => userCompany()->id]),
            $request->except(['item'] + ['company_id' => userCompany()->id])
        );

        return redirect()->route('general-tender-checklists.index');
    }

    public function edit(GeneralTenderChecklist $generalTenderChecklist)
    {
        $tenderChecklistTypes = TenderChecklistType::get();

        return view('general-tender-checklists.edit', compact('generalTenderChecklist', 'tenderChecklistTypes'));
    }

    public function update(UpdateGeneralTenderChecklistRequest $request, GeneralTenderChecklist $generalTenderChecklist)
    {
        $generalTenderChecklist->update($request->validated());

        return redirect()->route('general-tender-checklists.index');
    }

    public function destroy(GeneralTenderChecklist $generalTenderChecklist)
    {
        $generalTenderChecklist->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
