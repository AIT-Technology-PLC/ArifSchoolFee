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
    public function __invoke($type)
    {
        $this->authorize('viewAny', Merchandise::class);

        $insights = $this->insights();

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        return $this->datatable($type)->render('merchandises.index', compact('insights', 'warehouses'));
    }

    private function insights()
    {
        return [
            'totalOnHandProducts' => (new Product)->getOnHandMerchandiseProductsQuery()->count(),
            'totalOutOfStockProducts' => (new Product)->getOutOfOnHandMerchandiseProductsQuery()->count(),
            'totalLimitedMerchandises' => (new Product)->getLimitedMerchandiseProductsQuery()->count(),
            'totalWarehousesInUse' => (new Warehouse)->getWarehousesInUseQuery()->count(),
        ];
    }

    private function datatable($type)
    {
        if ($type == 'on-hand') {
            return new OnHandInventoryDatatable;
        }

        if ($type == 'available') {
            return new AvailableInventoryDatatable;
        }

        if ($type == 'reserved') {
            return new ReservedInventoryDatatable;
        }

        if ($type == 'out-of-stock') {
            return new OutOfStockInventoryDatatable;
        }
    }
}
