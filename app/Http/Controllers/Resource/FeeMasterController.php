<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\FeeMasterDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeeMasterRequest;
use App\Http\Requests\UpdateFeeMasterRequest;
use App\Models\FeeMaster;
use App\Models\FeeType;

class FeeMasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Fee Master');

        $this->authorizeResource(FeeMaster::class);
    }

    public function index(FeeMasterDatatable $datatable)
    {
        $datatable->builder()->setTableId('fee-masters-datatable')->orderBy(1, 'asc');

        return $datatable->render('fee-masters.index');
    }

    public function create()
    {
        $feeTypes = FeeType::orderBy('name')->get(['id', 'name']);

        return view('fee-masters.create', compact('feeTypes'));
    }

    public function store(StoreFeeMasterRequest $request)
    {
        FeeMaster::firstOrCreate(
            $request->safe()->only(['fee_type_id','due_date'] + ['company_id' => userCompany()->id]),
            $request->safe()->except(['fee_type_id','due_date'] + ['company_id' => userCompany()->id]),
        );

        return redirect()->route('fee-masters.index')->with('successMessage', 'Fee Master Created Successfully.');
    }
  
    public function edit(FeeMaster $feeMaster)
    {
        if ($feeMaster->assignFeeMasters()->exists()) {
            return back()->with(['failedMessage' => 'This Fee Master has already been assigned and cannot be edited.']);
        }

        $feeMaster->load(['feeType']);

        $feeTypes = FeeType::orderBy('name')->get(['id', 'name']);

        return view('fee-masters.edit', compact('feeMaster', 'feeTypes'));
    }

    public function update(UpdateFeeMasterRequest $request, FeeMaster $feeMaster)
    {
        if ($feeMaster->assignFeeMasters()->exists()) {
            return back()->with(['failedMessage' => 'This Fee Master has already been assigned and cannot be edited.']);
        }
        
        $feeMaster->update($request->validated());

        return redirect()->route('fee-masters.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(FeeMaster $feeMaster)
    {
        if ($feeMaster->assignFeeMasters()->exists()) {
            return back()->with(['failedMessage' => 'This Fee Master has already been assigned and cannot be deleted.']);
        }

        $feeMaster->Delete();

        return back()->with('deleted', 'Deleted Successfully.');
    }
}
