<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryValuationReportRequest;
use App\Reports\InventoryValuationReport;

class InventoryValuationReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory Valuation Report');
    }

    public function index(InventoryValuationReportRequest $request)
    {
        abort_if(authUser()->cannot('Read Inventory Valuation Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $totalNumberOfBranches = $warehouses->count();

        $inventoryValuationReports = new InventoryValuationReport($request->validated());

        return view('reports.inventory-valuation', compact('warehouses', 'inventoryValuationReports', 'totalNumberOfBranches'));
    }
}
