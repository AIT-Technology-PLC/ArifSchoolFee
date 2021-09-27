<?php

namespace App\Http\Controllers;

use App\Factory\InventoryDatatableFactory;
use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class MerchandiseController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Merchandise Inventory');
    }

    public function __invoke($type)
    {
        $this->authorize('viewAny', Merchandise::class);

        $insights = $this->insights();

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $datatable = InventoryDatatableFactory::make($type);

        return $datatable->render('merchandises.index', compact('insights', 'warehouses'));
    }

    private function insights()
    {
        return [
            'totalOnHandProducts' => (new Product)->getOnHandMerchandiseProductsQuery()->count(),
            'totalOutOfStockProducts' => (new Product)->getOutOfOnHandMerchandiseProductsQuery()->count(),
            'totalLimitedProducts' => (new Product)->getLimitedMerchandiseProductsQuery()->count(),
            'totalWarehousesInUse' => (new Warehouse)->getWarehousesInUseQuery()->count(),
        ];
    }
}
