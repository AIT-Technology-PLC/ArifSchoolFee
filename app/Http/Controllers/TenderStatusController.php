<?php

namespace App\Http\Controllers;

use App\Models\TenderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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

    public function store(Request $request)
    {
        $tenderStatus = $request->validate([
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $tenderStatus['company_id'] = auth()->user()->employee->company_id;
        $tenderStatus['created_by'] = auth()->user()->id;
        $tenderStatus['updated_by'] = auth()->user()->id;

        $this->tenderStatus->firstOrCreate(
            Arr::only($tenderStatus, ['status', 'company_id']),
            Arr::except($tenderStatus, ['status', 'company_id'])
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

        $tenderStatusData['updated_by'] = auth()->user()->id;

        $tenderStatus->update($tenderStatusData);

        return redirect()->route('tender-statuses.index');
    }

    public function destroy(TenderStatus $tenderStatus)
    {
        $tenderStatus->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
