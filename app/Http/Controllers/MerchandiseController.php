<?php

namespace App\Http\Controllers;

use App\DataTables\AvailableInventoryDatatable;
use App\DataTables\OnHandInventoryDatatable;
use App\DataTables\OutOfStockInventoryDatatable;
use App\DataTables\ReservedInventoryDatatable;
use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class MerchandiseController extends Controller
{
    public function index(OnHandInventoryDatatable $datatable, Warehouse $warehouse)
    {
        $this->authorize('viewAny', Merchandise::class);

        $insights = $this->insights();

        data_set($insights, 'totalWarehousesInUse', $warehouse->getTotalWarehousesUsed($insights['onHandMerchandises']));

        $warehouses = $warehouse->getAllWithoutRelations();

        $view = 'merchandises.on-hand';

        return $datatable->render('merchandises.index', compact('insights', 'warehouses', 'view'));
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

    public function insights()
    {
        $onHandMerchandises = (new Merchandise())->getAllOnHand()->load(['product']);

        $outOfStockMerchandises = (new Product())->getOutOfStockMerchandiseProducts($onHandMerchandises->unique('product_id')->pluck('product'));

        $totalDistinctLimitedMerchandises = (new Merchandise())->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        $totalDistinctOnHandMerchandises = $onHandMerchandises->unique('product_id')->count();

        $totalOutOfStockMerchandises = $outOfStockMerchandises->count();

        return compact('onHandMerchandises', 'totalDistinctOnHandMerchandises', 'totalDistinctLimitedMerchandises', 'totalOutOfStockMerchandises');
    }
}
