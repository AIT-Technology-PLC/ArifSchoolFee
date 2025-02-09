<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\FeeDiscountDatatable;
use App\Http\Controllers\Controller;
use App\Models\FeeDiscount;
use App\Http\Requests\StoreFeeDiscountRequest;
use App\Http\Requests\UpdateFeeDiscountRequest;

class FeeDiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Fee Discount');

        $this->authorizeResource(FeeDiscount::class);
    }

    public function index(FeeDiscountDatatable $datatable)
    {
        $datatable->builder()->setTableId('fee-discounts-datatable')->orderBy(1, 'asc');

        $totalDiscounts = FeeDiscount::count();

        return $datatable->render('fee-discounts.index', compact('totalDiscounts'));
    }
  
    public function create()
    {
        return view('fee-discounts.create');
    }

    public function store(StoreFeeDiscountRequest $request)
    {
        FeeDiscount::firstOrCreate(
            $request->safe()->only(['name','discount_code'] + ['company_id' => userCompany()->id]),
            $request->safe()->except(['name','discount_code'] + ['company_id' => userCompany()->id]),
        );

        return redirect()->route('fee-discounts.index')->with('successMessage', 'New Group Created Successfully.');
    }
  
    public function edit(FeeDiscount $feeDiscount)
    {
        if ($feeDiscount->assignFeeDiscounts()->exists() || $feeDiscount->feePayments()->exists()) {
            return back()->with(['failedMessage' => 'This Fee Discount has already been assigned and cannot be edited.']);
        }

        return view('fee-discounts.edit',  compact('feeDiscount'));
    }
  
    public function update(UpdateFeeDiscountRequest $request, FeeDiscount $feeDiscount)
    {
        if ($feeDiscount->assignFeeDiscounts()->exists() || $feeDiscount->feePayments()->exists()) {
            return back()->with(['failedMessage' => 'This Fee Discount has already been assigned and cannot be edited.']);
        }

        $feeDiscount->update($request->validated());

        return redirect()->route('fee-discounts.index')->with('successMessage', 'Updated Successfully.');
    }

 
    public function destroy(FeeDiscount $feeDiscount)
    {
        if ($feeDiscount->assignFeeDiscounts()->exists() || $feeDiscount->feePayments()->exists()) {
            return back()->with(['failedMessage' => 'This Fee Discount has already been assigned and cannot be deleted.']);
        }

        $feeDiscount->delete();

        return back()->with('deleted', 'Deleted Successfully.');
    }
}