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
        $this->authorizeResource(TenderStatus::class);

        $this->tenderStatus = $tenderStatus;
    }

    public function index()
    {
        $tenderStatuses = $this->tenderStatus->getAll()->load(['createdBy', 'updatedBy']);

        return view('tender_statuses.index', compact('tenderStatuses'));
    }

    public function create()
    {
        return view('tender_statuses.create');
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
        return view('tender_statuses.edit', compact('tenderStatus'));
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
