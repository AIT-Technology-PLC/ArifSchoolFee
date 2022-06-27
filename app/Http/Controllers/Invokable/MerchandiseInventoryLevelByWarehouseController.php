<?php

namespace App\Http\Controllers\Invokable;

use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\Inventory\MerchandiseProductService;

class MerchandiseInventoryLevelByWarehouseController extends Controller
{
    private $service;

    public function __construct(MerchandiseProductService $service)
    {
        $this->middleware('isFeatureAccessible:Merchandise Inventory');

        $this->service = $service;
    }

    public function __invoke(Warehouse $warehouse, Merchandise $merchandise, Product $product)
    {
        $this->authorize('viewAny', $merchandise);

        $onHandMerchandises = Merchandise::with('product.productCategory')
            ->whereIn('warehouse_id', authUser()->getAllowedWarehouses('read')->pluck('id'))
            ->where('warehouse_id', $warehouse->id)
            ->where(function ($query) {
                $query->where('available', '>', 0)
                    ->orWhere('reserved', '>', 0);
            })
            ->get();

        $outOfStockMerchandises = $this->service
            ->getOutOfStockMerchandiseProductsQuery($warehouse->id, authUser())
            ->with('productCategory')
            ->get();

        $warehouses = authUser()->getAllowedWarehouses('read');

        $insights = $this->insights($warehouse);

        return view(
            'warehouses.merchandises.index',
            compact('onHandMerchandises', 'outOfStockMerchandises', 'warehouses', 'warehouse', 'insights')
        );
    }

    private function insights($warehouse)
    {
        return [
            'totalOnHandProducts' => $this->service->getOnHandMerchandiseProductsQuery($warehouse->id, authUser())->count(),
            'totalOutOfStockProducts' => $this->service->getOutOfStockMerchandiseProductsQuery($warehouse->id, authUser())->count(),
            'totalLimitedProducts' => $this->service->getLimitedMerchandiseProductsQuery($warehouse->id, authUser())->count(),
        ];
    }
}
