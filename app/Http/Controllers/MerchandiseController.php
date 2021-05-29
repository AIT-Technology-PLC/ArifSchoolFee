<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class MerchandiseController extends Controller
{
    public function __invoke(Merchandise $merchandise, Product $product, Warehouse $warehouse)
    {
        $this->authorize('viewAny', $merchandise);

        $onHandMerchandises = $merchandise->getAll()->load('product.productCategory');

        $onHandMerchandiseProducts = $onHandMerchandises->pluck('product')->unique();

        $totalDistinctOnHandMerchandises = $onHandMerchandiseProducts->count();

        $totalDistinctLimitedMerchandises = $merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        $outOfStockMerchandiseProducts = $product->getOutOfStockMerchandiseProducts($onHandMerchandiseProducts)->load('productCategory');

        $totalOutOfStockMerchandises = $outOfStockMerchandiseProducts->count();

        $warehouses = $warehouse->getAllWithoutRelations();

        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($onHandMerchandises);

        return view('merchandises.index', compact('merchandise', 'onHandMerchandises', 'onHandMerchandiseProducts', 'outOfStockMerchandiseProducts', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'totalWarehouseInUse', 'warehouses'));
    }
}
