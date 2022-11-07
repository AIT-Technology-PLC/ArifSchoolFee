<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryReportRequest;
use App\Reports\InventoryLevelReport;

class InventoryLevelReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Daily Inventory Level Report');
    }

    public function index(InventoryReportRequest $request)
    {
        abort_if(authUser()->cannot('Read Daily Inventory Report'), 403);

        $inventoryLevelReport = new InventoryLevelReport($request->validated());

        return view('reports.inventory-level', compact('inventoryLevelReport'));
    }
}
