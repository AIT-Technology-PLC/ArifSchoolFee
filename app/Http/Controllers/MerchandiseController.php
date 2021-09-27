<?php

namespace App\Http\Controllers;

use App\DataTables\AvailableInventoryDatatable;
use App\DataTables\OnHandInventoryDatatable;
use App\DataTables\OutOfStockInventoryDatatable;
use App\DataTables\ReservedInventoryDatatable;

class MerchandiseController extends Controller
{
    public function index(OnHandInventoryDatatable $datatable)
    {
        $view = 'merchandises.on-hand';

        return $datatable->render('merchandises.index', compact('view'));
    }

    public function available(AvailableInventoryDatatable $datatable)
    {
        $view = 'merchandises.on-hand';

        return $datatable->render('merchandises.index', compact('view'));
    }

    public function reserved(ReservedInventoryDatatable $datatable)
    {
        $view = 'merchandises.reserved';

        return $datatable->render('merchandises.index', compact('view'));
    }

    public function outOfStock(OutOfStockInventoryDatatable $datatable)
    {
        $view = 'merchandises.out-of';

        return $datatable->render('merchandises.index', compact('view'));
    }
}
