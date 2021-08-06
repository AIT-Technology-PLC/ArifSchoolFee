<?php

namespace App\Http\Controllers;

use App\DataTables\InventoryLevelDatatable;
use App\Models\Merchandise;
use App\Models\Warehouse;

class MerchandiseController extends Controller
{
    public function index(InventoryLevelDatatable $datatable, Warehouse $warehouse)
    {
        $this->authorize('viewAny', Merchandise::class);

        $warehouses = $warehouse->getAllWithoutRelations();

        $view = 'merchandises.on-hand';

        return $datatable->render('merchandises.index', compact('warehouses', 'view'));
    }
}
