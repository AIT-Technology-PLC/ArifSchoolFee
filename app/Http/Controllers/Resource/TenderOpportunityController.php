<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\TenderOpportunityDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenderOpportunityRequest;
use App\Http\Requests\UpdateTenderOpportunityRequest;
use App\Models\TenderOpportunity;
use App\Models\TenderStatus;
use App\Notifications\TenderOpportunityStatusUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TenderOpportunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');

        $this->authorizeResource(TenderOpportunity::class);
    }

    public function index(TenderOpportunityDatatable $datatable)
    {
        $datatable->builder()->setTableId('tender-opportunities-datatable')->orderBy(4, 'desc');

        $totalTenderOpportunities = TenderOpportunity::count();

        return $datatable->render('tender-opportunities.index', compact('totalTenderOpportunities'));
    }

    public function create()
    {
        $tenderStatuses = TenderStatus::orderBy('status')->get();

        return view('tender-opportunities.create', compact('tenderStatuses'));
    }

    public function store(StoreTenderOpportunityRequest $request)
    {
        $tenderOpportunity = TenderOpportunity::create($request->validated());

        return redirect()->route('tender-opportunities.show', $tenderOpportunity->id);
    }

    public function show(TenderOpportunity $tenderOpportunity)
    {
        $tenderOpportunity->load(['customer', 'tenderStatus']);

        return view('tender-opportunities.show', compact('tenderOpportunity'));
    }

    public function edit(TenderOpportunity $tenderOpportunity)
    {
        $tenderOpportunity->load(['customer', 'tenderStatus']);

        $tenderStatuses = TenderStatus::orderBy('status')->get();

        return view('tender-opportunities.edit', compact('tenderOpportunity', 'tenderStatuses'));
    }

    public function update(UpdateTenderOpportunityRequest $request, TenderOpportunity $tenderOpportunity)
    {
        DB::transaction(function () use ($request, $tenderOpportunity) {
            $originalStatus = $tenderOpportunity->tender_status_id;

            $tenderOpportunity->update($request->validated());

            if ($tenderOpportunity->wasChanged('tender_status_id')) {
                Notification::send(notifiables('Read Tender'), new TenderOpportunityStatusUpdated($originalStatus, $tenderOpportunity));
            }
        });

        return redirect()->route('tender-opportunities.show', $tenderOpportunity->id);
    }

    public function destroy(TenderOpportunity $tenderOpportunity)
    {
        $tenderOpportunity->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
