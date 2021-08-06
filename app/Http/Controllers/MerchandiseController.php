<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Merchandise;
use App\DataTables\OnHandInventoryDatatable;
use App\DataTables\ReservedInventoryDatatable;
use App\DataTables\AvailableInventoryDatatable;
use App\DataTables\OutOfStockInventoryDatatable;

class MerchandiseController extends Controller
{
    public function index(OnHandInventoryDatatable $datatable, Warehouse $warehouse)
    {
        $this->authorize('viewAny', Merchandise::class);

        $warehouses = $warehouse->getAllWithoutRelations();

        $view = 'merchandises.on-hand';

        return $datatable->render('merchandises.index', compact('warehouses', 'view'));
    }

    public function available(AvailableInventoryDatatable $datatable, Warehouse $warehouse)
    {
        $this->authorize('viewAny', Merchandise::class);

        $warehouses = $warehouse->getAllWithoutRelations();

        $view = 'merchandises.on-hand';

        return $datatable->render('merchandises.index', compact('warehouses', 'view'));
    }

    public function reserved(ReservedInventoryDatatable $datatable, Warehouse $warehouse)
    {
        $this->authorize('viewAny', Merchandise::class);

        $warehouses = $warehouse->getAllWithoutRelations();

        $view = 'merchandises.reserved';

        return $datatable->render('merchandises.index', compact('warehouses', 'view'));
    }

    public function outOfStock(OutOfStockInventoryDatatable $datatable, Warehouse $warehouse)
    {
        $this->authorize('viewAny', Merchandise::class);

        $warehouses = $warehouse->getAllWithoutRelations();

        $view = 'merchandises.out-of';

        return $datatable->render('merchandises.index', compact('warehouses', 'view'));
    }
}
