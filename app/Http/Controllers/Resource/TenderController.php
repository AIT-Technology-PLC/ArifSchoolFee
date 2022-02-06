<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\TenderDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenderRequest;
use App\Http\Requests\UpdateTenderRequest;
use App\Models\Tender;
use App\Models\TenderStatus;
use App\Notifications\TenderStatusChanged;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TenderController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');

        $this->authorizeResource(Tender::class, 'tender');
    }

    public function index(TenderDatatable $datatable)
    {
        $datatable->builder()->setTableId('tenders-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalTenders = Tender::count();

        return $datatable->render('tenders.index', compact('totalTenders'));
    }

    public function create()
    {
        $tenderStatuses = TenderStatus::orderBy('status')->get();

        return view('tenders.create', compact('tenderStatuses'));
    }

    public function store(StoreTenderRequest $request)
    {
        $tender = DB::transaction(function () use ($request) {
            $tender = Tender::create($request->except('tender'));

            foreach ($request->lot as $lot) {
                $tenderLot = $tender->tenderLots()->create();
                $tenderLot->tenderLotDetails()->createMany($lot['lotDetails']);
            }

            return $tender;
        });

        return redirect()->route('tenders.show', $tender);
    }

    public function show(Tender $tender)
    {
        $tender->load([
            'customer',
            'tenderLots.tenderLotDetails.product',
            'tenderChecklists.assignedTo',
            'tenderChecklists.generalTenderChecklist.tenderChecklistType',
        ]);

        return view('tenders.show', compact('tender'));
    }

    public function edit(Tender $tender)
    {
        $tender->load(['tenderLots.tenderLotDetails.product']);

        $tenderStatuses = TenderStatus::orderBy('status')->get();

        return view('tenders.edit', compact('tender', 'tenderStatuses'));
    }

    public function update(UpdateTenderRequest $request, Tender $tender)
    {
        DB::transaction(function () use ($request, $tender) {
            $originalStatus = $tender->status;

            $tender->update($request->except('tender'));

            for ($i = 0; $i < count($request->lot); $i++) {
                for ($j = 0; $j < count($request->lot[$i]['lotDetails']); $j++) {
                    $tender->tenderLots[$i]->tenderLotDetails[$j]->update($request->lot[$i]['lotDetails'][$j]);
                }
            }

            if ($tender->wasChanged('status')) {
                Notification::send(Notifiables::byNextActionPermission('Read Tender'), new TenderStatusChanged($originalStatus, $tender));
            }
        });

        return redirect()->route('tenders.show', $tender->id);
    }

    public function destroy(Tender $tender)
    {
        $tender->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
