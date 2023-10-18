<?php

namespace App\Http\Controllers\Report;

use App\DataTables\InventoryInTransitReportDatatable;
use App\Http\Controllers\Controller;

class InventoryInTransitReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory In Transit Report');
    }

    public function index(InventoryInTransitReportDatatable $datatable)
    {
        abort_if(authUser()->cannot('Read Inventory In Transit Report'), 403);

        $datatable->builder()->setTableId('inventory-in-transit-reports-datatable')->orderBy(1, 'asc');

        return $datatable->render('reports.inventory-in-transit');
    }
}
