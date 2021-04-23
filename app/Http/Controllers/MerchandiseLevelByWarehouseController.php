<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class MerchandiseLevelByWarehouseController extends Controller
{
    public function index(Warehouse $warehouse, Merchandise $merchandise, Product $product)
    {
        $this->authorize('view', $warehouse);

        $onHandMerchandises = $merchandise->getCurrentMerchandiseLevelByProductAndWarehouse($warehouse->id)->load('product.productCategory');

        $outOfStockMerchandises = $product->getOutOfStockMerchandiseProductsByWarehouse($onHandMerchandises)->load('productCategory');

        $warehouses = $warehouse->getAllWithoutRelations();

        $totalDistinctOnHandMerchandises = $merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $totalOutOfStockMerchandises = $outOfStockMerchandises->count();

        $totalDistinctLimitedMerchandises = $merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        return view('warehouses.merchandises.index', compact('onHandMerchandises', 'outOfStockMerchandises', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'warehouses', 'warehouse'));
    }
}
