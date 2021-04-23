<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class MerchandiseInventoryLevelController extends Controller
{
    private $merchandise;

    public function __construct(Merchandise $merchandise)
    {
        $this->merchandise = $merchandise;
    }

    public function index(Product $product, Warehouse $warehouse)
    {
        $warehouses = $warehouse->getAllWithoutRelations();

        $onHandMerchandises = $this->merchandise->getMerchandiseProductsLevel()->load('product.productCategory');

        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($onHandMerchandises);

        $onHandMerchandiseProducts = $onHandMerchandises->pluck('product')->unique();

        $outOfStockMerchandiseProducts = $product->getOutOfStockMerchandiseProducts($onHandMerchandiseProducts)->load('productCategory');

        $totalDistinctOnHandMerchandises = $this->merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $totalOutOfStockMerchandises = $outOfStockMerchandiseProducts->count();

        $totalDistinctLimitedMerchandises = $this->merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        return view('merchandises.levels.index', compact('onHandMerchandises', 'onHandMerchandiseProducts', 'outOfStockMerchandiseProducts', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'totalWarehouseInUse', 'warehouses'));
    }
}
