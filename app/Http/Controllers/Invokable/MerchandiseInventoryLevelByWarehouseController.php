<?php

namespace App\Http\Controllers\Invokable;

use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\MerchandiseProductService;

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

        $onHandMerchandises = $this->service->getOnHandMerchandiseProductsQuery()
            ->with('merchandises.product.productCategory')
            ->with('merchandises', function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse->id);
            })
            ->get()
            ->pluck('merchandises')
            ->flatten();

        $outOfStockMerchandises = $this->service->getOutOfStockMerchandiseProductsQuery($warehouse->id)
            ->with('productCategory')
            ->get();

        $warehouses = auth()->user()->getAllowedWarehouses('read');

        $insights = $this->insights($warehouse);

        return view('warehouses.merchandises.index',
            compact('onHandMerchandises', 'outOfStockMerchandises', 'warehouses', 'warehouse', 'insights'));
    }

    private function insights($warehouse)
    {
        $totalOnHandProducts = $this->service->getOnHandMerchandiseProductsQuery()
            ->whereHas('merchandises', function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse->id);
            })
            ->count();

        $totalOutOfStockProducts = $this->service->getOutOfStockMerchandiseProductsQuery($warehouse->id)->count();

        $totalLimitedProducts = $this->service->getLimitedMerchandiseProductsQuery($warehouse->id)->count();

        return compact('totalOnHandProducts', 'totalOutOfStockProducts', 'totalLimitedProducts');
    }
}
