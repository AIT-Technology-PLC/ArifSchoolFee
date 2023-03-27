<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryTransferReportRequest;
use App\Models\Employee;
use App\Models\Product;
use App\Reports\InventoryTransferReport;

class InventoryTransferReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory Transfer Report');
    }

    public function index(InventoryTransferReportRequest $request)
    {
        abort_if(authUser()->cannot('Read Inventory Transfer Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $users = Employee::with('user:id,name')->get()->pluck('user')->sortBy('name');

        $products = Product::orderBy('name')->get();

        $inventoryTransferReport = new InventoryTransferReport($request->validated());

        return view('reports.inventory-transfer', compact('warehouses', 'inventoryTransferReport', 'users', 'products'));
    }
}
