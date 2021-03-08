<?php

namespace App\Http\Controllers;

use App\Models\GeneralTenderChecklist;
use App\Models\Tender;
use App\Models\TenderChecklist;
use Illuminate\Http\Request;

class TenderChecklistController extends Controller
{
    public function create(Tender $tender, GeneralTenderChecklist $generalTenderChecklist)
    {
        $generalTenderChecklists = $generalTenderChecklist->getAll();

        $tender = $tender->with('tenderChecklists')->find(request('tender'));

        return view('tender_checklists.create', compact('generalTenderChecklists', 'tender'));
    }

    public function store(Request $request, Tender $tender)
    {
        $tenderChecklistData = $request->validate([
            'checklists' => 'required|array',
        ]);

        $tenderChecklistData = $tenderChecklistData['checklists'];

        $tender = $tender->find(request('tender'));

        $tender->tenderChecklists()->createMany($tenderChecklistData);

        return redirect()->route('tenders.show', $tender->id);
    }

    public function edit(TenderChecklist $tenderChecklist)
    {
        return view('tender_checklists.edit', compact('tenderChecklist'));
    }

    public function update(Request $request, TenderChecklist $tenderChecklist)
    {
        $tenderChecklistData = $request->validate([
            'status' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        $tenderChecklist->update($tenderChecklistData);

        return redirect()->route('tenders.show', $tenderChecklist->tender_id);
    }

    public function destroy(TenderChecklist $tenderChecklist)
    {
        $tenderChecklist->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
