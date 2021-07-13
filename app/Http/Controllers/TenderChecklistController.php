<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenderChecklistRequest;
use App\Http\Requests\UpdateTenderChecklistRequest;
use App\Models\GeneralTenderChecklist;
use App\Models\Tender;
use App\Models\TenderChecklist;
use Illuminate\Http\Request;

class TenderChecklistController extends Controller
{
    public function __construct()
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Tender Management');
    }

    public function create(Tender $tender, GeneralTenderChecklist $generalTenderChecklist)
    {
        $tender = $tender->with('tenderChecklists')->find(request('tender'));

        $this->authorize('create', $tender);

        $generalTenderChecklists = $generalTenderChecklist->getAll();

        return view('tender_checklists.create', compact('generalTenderChecklists', 'tender'));
    }

    public function store(StoreTenderChecklistRequest $request, Tender $tender)
    {
        $tender = $tender->find(request('tender'));

        $this->authorize('create', $tender);

        $tender->tenderChecklists()->createMany($request->checklists);

        return redirect()->route('tenders.show', $tender->id);
    }

    public function edit(TenderChecklist $tenderChecklist)
    {
        if ($tenderChecklist->generalTenderChecklist->tenderChecklistType->isSensitive()
            && !auth()->user()->can('Read Tender Sensitive Data')) {
            abort(403);
        }

        $this->authorize('update', $tenderChecklist->tender);

        return view('tender_checklists.edit', compact('tenderChecklist'));
    }

    public function update(UpdateTenderChecklistRequest $request, TenderChecklist $tenderChecklist)
    {
        $this->authorize('update', $tenderChecklist->tender);

        if ($tenderChecklist->generalTenderChecklist->tenderChecklistType->isSensitive()
            && !auth()->user()->can('Read Tender Sensitive Data')) {
            abort(403);
        }

        $tenderChecklist->update($request->all());

        return redirect()->route('tenders.show', $tenderChecklist->tender_id);
    }

    public function destroy(TenderChecklist $tenderChecklist)
    {
        $this->authorize('delete', $tenderChecklist->tender);

        if ($tenderChecklist->generalTenderChecklist->tenderChecklistType->isSensitive()
            && !auth()->user()->can('Read Tender Sensitive Data')) {
            abort(403);
        }

        $tenderChecklist->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
