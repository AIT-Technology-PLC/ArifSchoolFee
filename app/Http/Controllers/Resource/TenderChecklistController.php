<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenderChecklistRequest;
use App\Http\Requests\UpdateTenderChecklistRequest;
use App\Models\GeneralTenderChecklist;
use App\Models\Tender;
use App\Models\TenderChecklist;
use App\Models\TenderChecklistType;
use Illuminate\Support\Facades\DB;

class TenderChecklistController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');
    }

    public function create(Tender $tender)
    {
        $tender->loadCount('tenderChecklists');

        $this->authorize('view', $tender);

        $this->authorize('create', $tender);

        $tenderChecklistTypes = TenderChecklistType::query()
            ->has('generalTenderChecklists')
            ->with('generalTenderChecklists', function ($query) use ($tender) {
                return $query->whereNotIn('id', $tender->tenderChecklists()->pluck('general_tender_checklist_id'))->orderBy('item');
            })
            ->get();

        $totalGeneralTenderChecklists = GeneralTenderChecklist::count();

        return view('tender-checklists.create', compact('tenderChecklistTypes', 'tender', 'totalGeneralTenderChecklists'));
    }

    public function store(StoreTenderChecklistRequest $request, Tender $tender)
    {
        $this->authorize('view', $tender);

        $this->authorize('create', $tender);

        DB::transaction(function () use ($request, $tender) {
            $tender->tenderChecklists()->createMany($request->checklists);
        });

        return redirect()->route('tenders.show', $tender->id);
    }

    public function edit(TenderChecklist $tenderChecklist)
    {
        abort_if(
            $tenderChecklist->generalTenderChecklist->tenderChecklistType->isSensitive() &&
            !auth()->user()->can('Read Tender Sensitive Data'), 403
        );

        abort_if(
            $tenderChecklist->assigned_to &&
            (auth()->id() != $tenderChecklist->assigned_to || !auth()->user()->hasRole('System Manager')), 403
        );

        $this->authorize('update', $tenderChecklist->tender);

        return view('tender-checklists.edit', compact('tenderChecklist'));
    }

    public function update(UpdateTenderChecklistRequest $request, TenderChecklist $tenderChecklist)
    {
        abort_if(
            $tenderChecklist->generalTenderChecklist->tenderChecklistType->isSensitive() &&
            !auth()->user()->can('Read Tender Sensitive Data'), 403
        );

        abort_if(
            $tenderChecklist->assigned_to &&
            (auth()->id() != $tenderChecklist->assigned_to || !auth()->user()->hasRole('System Manager')), 403
        );

        $this->authorize('update', $tenderChecklist->tender);

        $tenderChecklist->update($request->validated());

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

        return back()->with('checklistDeleted', 'Deleted successfully.');
    }
}
