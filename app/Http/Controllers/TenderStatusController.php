<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenderStatusRequest;
use App\Models\TenderStatus;
use Illuminate\Http\Request;

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

    public function update(Request $request, TenderStatus $tenderStatus)
    {
        $tenderStatusData = $request->validate([
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $tenderStatusData['updated_by'] = auth()->id();

        $tenderStatus->update($tenderStatusData);

        return redirect()->route('tender-statuses.index');
    }

    public function destroy(TenderStatus $tenderStatus)
    {
        $tenderStatus->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
