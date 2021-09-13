<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenderStatusRequest;
use App\Http\Requests\UpdateTenderStatusRequest;
use App\Models\TenderStatus;

class TenderStatusController extends Controller
{
    private $tenderStatus;

    public function __construct(TenderStatus $tenderStatus)
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Tender Management');

        $this->authorizeResource(TenderStatus::class);

        $this->tenderStatus = $tenderStatus;
    }

    public function index()
    {
        $tenderStatuses = $this->tenderStatus->getAll()->load(['createdBy', 'updatedBy']);

        $totalTenderStatuses = $tenderStatuses->count();

        return view('tender-statuses.index', compact('tenderStatuses', 'totalTenderStatuses'));
    }

    public function create()
    {
        return view('tender-statuses.create');
    }

    public function store(StoreTenderStatusRequest $request)
    {
        $this->tenderStatus->firstOrCreate(
            $request->only(['status', 'company_id']),
            $request->except(['status', 'company_id']),
        );

        return redirect()->route('tender-statuses.index');
    }

    public function edit(TenderStatus $tenderStatus)
    {
        return view('tender-statuses.edit', compact('tenderStatus'));
    }

    public function update(UpdateTenderStatusRequest $request, TenderStatus $tenderStatus)
    {
        $tenderStatus->update($request->all());

        return redirect()->route('tender-statuses.index');
    }

    public function destroy(TenderStatus $tenderStatus)
    {
        $tenderStatus->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
