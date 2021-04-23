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

        $onHandStockMerchandises = $product->getAllOnHandStockMerchandises()->load('productCategory');

        $onHandMerchandises = $this->merchandise->getCurrentMerchandiseLevelByProduct()->load('product.productCategory');

        $totalDistinctOnHandMerchandises = $this->merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $outOfStockMerchandises = $product->getAllOutOfStockMerchandises()->load('productCategory');

        $totalOutOfStockMerchandises = $product->getTotalOutOfStockMerchandises($outOfStockMerchandises);

        $totalDistinctLimitedMerchandises = $this->merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($this->merchandise->getAllOnHandMerchandises());

        return view('merchandises.levels.index', compact('onHandMerchandises', 'onHandStockMerchandises', 'outOfStockMerchandises', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'totalWarehouseInUse', 'warehouses'));
    }
}
