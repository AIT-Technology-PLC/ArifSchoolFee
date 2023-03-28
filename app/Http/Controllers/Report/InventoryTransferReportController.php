<?php

namespace App\Http\Controllers\Report;

use App\DataTables\InventoryTransferReportDatatable;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Product;

class InventoryTransferReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory Transfer Report');
    }

    public function index(InventoryTransferReportDatatable $datatable)
    {
        abort_if(authUser()->cannot('Read Inventory Transfer Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $users = Employee::with('user:id,name')->get()->pluck('user')->sortBy('name');

        $products = Product::orderBy('name')->get();

        return $datatable->render('reports.inventory-transfer', compact('warehouses', 'users', 'products'));
    }
}
