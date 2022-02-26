<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\TenderStatusDatatable;
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

    public function index(TenderStatusDatatable $datatable)
    {
        $datatable->builder()->setTableId('tender-statuses-datatable')->orderBy('1', 'asc');

        $totalTenderStatuses = TenderStatus::count();

        return $datatable->render('tender-statuses.index', compact('totalTenderStatuses'));
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
        $tenderStatus->update($request->validated());

        return redirect()->route('tender-statuses.index');
    }

    public function destroy(TenderStatus $tenderStatus)
    {
        $tenderStatus->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
