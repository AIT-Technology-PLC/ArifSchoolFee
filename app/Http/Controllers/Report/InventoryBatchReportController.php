<?php

namespace App\Http\Controllers\Report;

use App\DataTables\InventoryBatchReportDatatable;
use App\Http\Controllers\Controller;
use App\Models\Product;

class InventoryBatchReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory Batch Report');
    }

    public function index(InventoryBatchReportDatatable $datatable)
    {
        abort_if(authUser()->cannot('Read Inventory Batch Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $products = Product::orderBy('name')->get();

        $datatable->builder()->setTableId('inventory-batch-reports-datatable')->orderBy(1, 'asc');

        return $datatable->render('reports.inventory-batch', compact('warehouses', 'products'));
    }
}
