<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\FeeGroup;
use App\DataTables\FeeGroupDatatable;
use App\Http\Requests\StoreFeeGroupRequest;
use App\Http\Requests\UpdateFeeGroupRequest;

class FeeGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Fee Group');

        $this->authorizeResource(FeeGroup::class);
    }

    public function index(FeeGroupDatatable $datatable)
    {
        $datatable->builder()->setTableId('fee-groups-datatable')->orderBy(1, 'asc');

        $totalGroups = FeeGroup::count();

        return $datatable->render('fee-groups.index', compact('totalGroups'));
    }

    public function create()
    {
        return view('fee-groups.create');
    }

    public function store(StoreFeeGroupRequest $request)
    {
        FeeGroup::firstOrCreate(
            $request->safe()->only(['name'] + ['company_id' => userCompany()->id]),
            $request->safe()->except(['name'] + ['company_id' => userCompany()->id]),
        );

        return redirect()->route('fee-groups.index')->with('successMessage', 'New Group Created Successfully.');
    }
  
    public function edit(FeeGroup $feeGroup)
    {
        return view('fee-groups.edit',  compact('feeGroup'));
    }

    public function update(UpdateFeeGroupRequest $request, FeeGroup $feeGroup)
    {
        $feeGroup->update($request->validated());

        return redirect()->route('fee-groups.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(FeeGroup $feeGroup)
    {
        if ($feeGroup->feeTypes()->exists()) {
            return back()->with(['failedMessage' => 'This Fee Group is being used and cannot be deleted.']);
        }

        $feeGroup->delete();

        return back()->with('deleted', 'Deleted Successfully.');
    }
}