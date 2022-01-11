<?php

namespace App\Http\Controllers\Invokable;

use App\Factory\InventoryDatatableFactory;
use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use App\Models\Warehouse;
use App\Services\Inventory\MerchandiseProductService;

class MerchandiseInventoryLevelController extends Controller
{
    private $service;

    public function __construct(MerchandiseProductService $service)
    {
        $this->middleware('isFeatureAccessible:Merchandise Inventory');

        $this->service = $service;
    }

    public function __invoke($type)
    {
        $this->authorize('viewAny', Merchandise::class);

        $insights = $this->insights();

        $warehouses = auth()->user()->getAllowedWarehouses('read');

        $datatable = InventoryDatatableFactory::make($type);

        $datatable->builder()->setTableId($type . '-datatable')->orderBy(1, 'asc');

        return $datatable->render('merchandises.index', compact('insights', 'warehouses'));
    }

    private function insights()
    {
        return [
            'totalOnHandProducts' => $this->service->getOnHandMerchandiseProductsQuery(user:auth()->user())->count(),
            'totalOutOfStockProducts' => $this->service->getOutOfStockMerchandiseProductsQuery(user:auth()->user())->count(),
            'totalLimitedProducts' => $this->service->getLimitedMerchandiseProductsQuery(user:auth()->user())->count(),
            'totalWarehousesInUse' => (new Warehouse)->getWarehousesInUseQuery()->count(),
        ];
    }
}
