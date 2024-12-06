<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\FeeTypeDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeeTypeRequest;
use App\Http\Requests\UpdateFeeTypeRequest;
use App\Models\FeeGroup;
use App\Models\FeeType;

class FeeTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Fee Type');

        $this->authorizeResource(FeeType::class);
    }

    public function index(FeeTypeDatatable $datatable)
    {
        $datatable->builder()->setTableId('fee-types-datatable')->orderBy(1, 'asc');

        $totalTypes = FeeType::count();

        return $datatable->render('fee-types.index', compact('totalTypes'));
    }

    public function create()
    {
        $feeGroups = FeeGroup::orderBy('name')->get(['id', 'name']);

        return view('fee-types.create', compact('feeGroups'));
    }

    public function store(StoreFeeTypeRequest $request)
    {
        FeeType::firstOrCreate(
            $request->safe()->only(['name'] + ['company_id' => userCompany()->id]),
            $request->safe()->except(['name'] + ['company_id' => userCompany()->id]),
        );

        return redirect()->route('fee-types.index')->with('successMessage', 'New Type Created Successfully.');
    }


    public function edit(FeeType $feeType)
    {
        $feeGroups = FeeGroup::orderBy('name')->get(['id', 'name']);

        return view('fee-types.edit',  compact('feeType', 'feeGroups'));
    }


    public function update(UpdateFeeTypeRequest $request, FeeType $feeType)
    {
        $feeType->update($request->validated());

        return redirect()->route('fee-types.index')->with('successMessage', 'Updated Successfully.');
    }


    public function destroy(FeeType $feeType)
    {
        if ($feeType->feeMasters()->exists()) {
            return back()->with(['failedMessage' => 'This Fee Type data is being used and cannot be deleted.']);
        }

        $feeType->Delete();

        return back()->with('deleted', 'Deleted Successfully.');
    }
}