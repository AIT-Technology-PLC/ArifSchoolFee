<?php

namespace App\Http\Controllers\Invokable;

use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class MerchandiseInventoryLevelByWarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Merchandise Inventory');
    }

    public function __invoke(Warehouse $warehouse, Merchandise $merchandise, Product $product)
    {
        $this->authorize('viewAny', $merchandise);

        $onHandMerchandises = (new Product)->getOnHandMerchandiseProductsQuery()
            ->with('merchandises.product.productCategory')
            ->with('merchandises', function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse->id);
            })
            ->get()
            ->pluck('merchandises')
            ->flatten();

        $outOfStockMerchandises = (new Product)->getOutOfStockMerchandiseProductsQuery($warehouse->id)
            ->with('productCategory')
            ->get();

        $warehouses = auth()->user()->getAllowedWarehouses('read');

        $insights = $this->insights($warehouse);

        return view('warehouses.merchandises.index',
            compact('onHandMerchandises', 'outOfStockMerchandises', 'warehouses', 'warehouse', 'insights'));
    }

    private function insights($warehouse)
    {
        $totalOnHandProducts = (new Product)->getOnHandMerchandiseProductsQuery()
            ->whereHas('merchandises', function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse->id);
            })
            ->count();

        $totalOutOfStockProducts = (new Product)->getOutOfStockMerchandiseProductsQuery($warehouse->id)->count();

        $totalLimitedProducts = (new Product)->getLimitedMerchandiseProductsQuery($warehouse->id)->count();

        return compact('totalOnHandProducts', 'totalOutOfStockProducts', 'totalLimitedProducts');
    }
}
