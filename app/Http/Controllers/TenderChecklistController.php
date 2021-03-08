<?php

namespace App\Http\Controllers;

use App\Models\TenderChecklist;
use Illuminate\Http\Request;

class TenderChecklistController extends Controller
{
    public function __construct()
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function edit(TenderChecklist $tenderChecklist)
    {
        return view('tender_checklists.edit', compact('tenderChecklist'));
    }

    public function update(Request $request,TenderChecklist $tenderChecklist)
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
