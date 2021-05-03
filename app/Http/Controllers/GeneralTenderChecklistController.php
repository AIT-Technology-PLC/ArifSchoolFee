<?php

namespace App\Http\Controllers;

use App\Models\GeneralTenderChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class GeneralTenderChecklistController extends Controller
{
    private $generalTenderChecklist;

    public function __construct(GeneralTenderChecklist $generalTenderChecklist)
    {
        $this->authorizeResource(GeneralTenderChecklist::class);

        $this->generalTenderChecklist = $generalTenderChecklist;
    }

    public function index()
    {
        $generalTenderChecklists = $this->generalTenderChecklist->getAll()->load(['createdBy', 'updatedBy']);

        return view('general_tender_checklists.index', compact('generalTenderChecklists'));
    }

    public function create()
    {
        return view('general_tender_checklists.create');
    }

    public function store(Request $request)
    {
        $generalTenderChecklistData = $request->validate([
            'item' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $generalTenderChecklistData['company_id'] = userCompany()->id;
        $generalTenderChecklistData['created_by'] = auth()->id();
        $generalTenderChecklistData['updated_by'] = auth()->id();

        $this->generalTenderChecklist->firstOrCreate(
            Arr::only($generalTenderChecklistData, ['item', 'company_id']),
            Arr::except($generalTenderChecklistData, ['item', 'company_id'])
        );

        return redirect()->route('general-tender-checklists.index');
    }

    public function edit(GeneralTenderChecklist $generalTenderChecklist)
    {
        return view('general_tender_checklists.edit', compact('generalTenderChecklist'));
    }

    public function update(Request $request, GeneralTenderChecklist $generalTenderChecklist)
    {
        $generalTenderChecklistData = $request->validate([
            'item' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $generalTenderChecklistData['updated_by'] = auth()->id();

        $generalTenderChecklist->update($generalTenderChecklistData);

        return redirect()->route('general-tender-checklists.index');
    }

    public function destroy(GeneralTenderChecklist $generalTenderChecklist)
    {
        $generalTenderChecklist->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
