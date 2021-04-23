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

        $totalDistinctOnHandMerchandises = $merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $outOfStockMerchandises = $product->getAllOutOfStockMerchandisesByWarehouse($onHandMerchandises)->load('productCategory');

        $totalOutOfStockMerchandises = $product->getTotalOutOfStockMerchandises($outOfStockMerchandises);

        $totalDistinctLimitedMerchandises = $merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('warehouses.merchandises.index', compact('onHandMerchandises', 'outOfStockMerchandises', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'warehouses', 'warehouse'));
    }
}
