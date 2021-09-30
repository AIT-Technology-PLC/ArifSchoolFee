<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenderStatusRequest;
use App\Http\Requests\UpdateTenderStatusRequest;
use App\Models\TenderStatus;

class TenderStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');

        $this->authorizeResource(TenderStatus::class);
    }

    public function index()
    {
        $tenderStatuses = TenderStatus::orderBy('status')
            ->with(['createdBy', 'updatedBy'])
            ->get();

        $totalTenderStatuses = TenderStatus::count();

        return view('tender-statuses.index', compact('tenderStatuses', 'totalTenderStatuses'));
    }

    public function create()
    {
        return view('tender-statuses.create');
    }

    public function store(StoreTenderStatusRequest $request)
    {
        TenderStatus::firstOrCreate(
            $request->only(['status'] + ['company_id' => userCompany()->id]),
            $request->except(['status'] + ['company_id' => userCompany()->id]),
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
