<?php

namespace App\Http\Controllers\Invokable;

use App\Factory\InventoryDatatableFactory;
use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use App\Models\Product;
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
        $this->authorize(str()->camel($type), Merchandise::class);

        $insights = $this->insights();

        $warehouses = authUser()->getAllowedWarehouses('read');

        $datatable = InventoryDatatableFactory::make($type);

        $datatable->builder()->setTableId($type.'-datatable')->orderBy(1, 'asc');

        $hasExpiredInventory = Product::batchable()->exists();

        return $datatable->render('merchandises.index', compact('insights', 'warehouses', 'hasExpiredInventory'));
    }

    private function insights()
    {
        return [
            'totalOnHandProducts' => $this->service->getOnHandMerchandiseProductsQuery(user:authUser())->count(),
            'totalAvailableProducts' => $this->service->getAvailableMerchandiseProductsQuery(user:authUser())->count(),
            'totalOutOfStockProducts' => $this->service->getOutOfStockMerchandiseProductsQuery(user:authUser())->count(),
            'totalLimitedProducts' => $this->service->getLimitedMerchandiseProductsQuery(user:authUser())->count(),
            'totalWarehousesInUse' => (new Warehouse)->getWarehousesInUseQuery()->count(),
        ];
    }
}
