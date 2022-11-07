<?php

namespace App\Http\Controllers\Report;

use App\DataTables\InventoryLevelReportDatatable;
use App\Http\Controllers\Controller;

class InventoryLevelReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Daily Inventory Level Report');
    }

    public function index(InventoryLevelReportDatatable $datatable)
    {
        abort_if(authUser()->cannot('Read Daily Inventory Report'), 403);

        $datatable->builder()->setTableId('inventory-level-reports-datatable')->orderBy(1, 'asc');

        return $datatable->render('reports.inventory-level');
    }
}