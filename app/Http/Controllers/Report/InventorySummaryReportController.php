<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Models\Product;
use App\Reports\InventorySummaryReport;

class InventorySummaryReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory Summary Report');
    }

    public function index(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Inventory Summary Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $products = Product::orderBy('name')->get();

        $inventorySummaryReport = new InventorySummaryReport($request->validated());

        return view('reports.inventory-summary', compact('warehouses', 'products', 'inventorySummaryReport'));
    }
}
