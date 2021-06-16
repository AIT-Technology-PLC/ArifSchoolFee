<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class MerchandiseLevelByWarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Merchandise Inventory');
    }

    public function index(Warehouse $warehouse, Merchandise $merchandise, Product $product)
    {
        $this->authorize('view', $warehouse);

        $onHandMerchandises = $merchandise->getAll()->where('warehouse_id', $warehouse->id)->load('product.productCategory');

        $onHandMerchandiseProducts = $onHandMerchandises->pluck('product')->unique();

        $totalDistinctOnHandMerchandises = $onHandMerchandiseProducts->count();

        $totalDistinctLimitedMerchandises = $merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        $outOfStockMerchandises = $product->getOutOfStockMerchandiseProducts($onHandMerchandiseProducts)->load('productCategory');

        $totalOutOfStockMerchandises = $outOfStockMerchandises->count();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('warehouses.merchandises.index', compact('onHandMerchandises', 'outOfStockMerchandises', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'warehouses', 'warehouse'));
    }
}
