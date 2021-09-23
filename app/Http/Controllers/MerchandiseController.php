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
    public function index(OnHandInventoryDatatable $datatable)
    {
        $this->authorize('viewAny', Merchandise::class);

        $insights = $this->insights();

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $view = 'merchandises.on-hand';

        return $datatable->render('merchandises.index', compact('insights', 'warehouses', 'view'));
    }

    public function available(AvailableInventoryDatatable $datatable)
    {
        $this->authorize('viewAny', Merchandise::class);

        $insights = $this->insights();

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $view = 'merchandises.on-hand';

        return $datatable->render('merchandises.index', compact('insights', 'warehouses', 'view'));
    }

    public function reserved(ReservedInventoryDatatable $datatable)
    {
        $this->authorize('viewAny', Merchandise::class);

        $insights = $this->insights();

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $view = 'merchandises.reserved';

        return $datatable->render('merchandises.index', compact('insights', 'warehouses', 'view'));
    }

    public function outOfStock(OutOfStockInventoryDatatable $datatable)
    {
        $this->authorize('viewAny', Merchandise::class);

        $insights = $this->insights();

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $view = 'merchandises.out-of';

        return $datatable->render('merchandises.index', compact('insights', 'warehouses', 'view'));
    }

    public function insights()
    {
        $onHandMerchandises = (new Merchandise())->getAllOnHand()->load(['product']);

        $outOfStockMerchandises = (new Product())->getOutOfStockMerchandiseProducts($onHandMerchandises->unique('product_id')->pluck('product'));

        $totalDistinctLimitedMerchandises = (new Merchandise())->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        $totalDistinctOnHandMerchandises = $onHandMerchandises->unique('product_id')->count();

        $totalOutOfStockMerchandises = $outOfStockMerchandises->count();

        $totalWarehousesInUse = (new Warehouse())->getTotalWarehousesUsed($onHandMerchandises);

        return compact('totalDistinctOnHandMerchandises', 'totalDistinctLimitedMerchandises', 'totalOutOfStockMerchandises', 'totalWarehousesInUse');
    }
}
