<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class MerchandiseLevelController extends Controller
{
    public function index(Merchandise $merchandise, Product $product, Warehouse $warehouse)
    {
        $warehouses = $warehouse->getAllWithoutRelations();

        $onHandMerchandises = $merchandise->getMerchandiseProductsLevel()->load('product.productCategory');

        $onHandMerchandiseProducts = $onHandMerchandises->pluck('product')->unique();

        $outOfStockMerchandiseProducts = $product->getOutOfStockMerchandiseProducts($onHandMerchandiseProducts)->load('productCategory');

        $totalDistinctOnHandMerchandises = $merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $totalDistinctLimitedMerchandises = $merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        $totalOutOfStockMerchandises = $outOfStockMerchandiseProducts->count();

        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($onHandMerchandises);

        return view('merchandises.levels.index', compact('merchandise', 'onHandMerchandises', 'onHandMerchandiseProducts', 'outOfStockMerchandiseProducts', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'totalWarehouseInUse', 'warehouses'));
    }
}
