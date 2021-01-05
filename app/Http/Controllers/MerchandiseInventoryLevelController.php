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
        $onHandMerchandises = $this->merchandise->getCurrentMerchandiseLevelByProduct()->load('product.productCategory');

        $totalDistinctOnHandMerchandises = $this->merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $outOfStockMerchandises = $product->getAllOutOfStockMerchandises()->load('productCategory');

        $totalOutOfStockMerchandises = $product->getTotalOutOfStockMerchandises($outOfStockMerchandises);

        $totalDistinctLimitedMerchandises = $this->merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($this->merchandise->getAllOnHandMerchandises());

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('merchandises.levels.index', compact('onHandMerchandises', 'outOfStockMerchandises', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'totalWarehouseInUse', 'warehouses'));
    }

    public function getCurrentMerchandiseLevelByWarehouse(Product $product, Warehouse $warehouse)
    {
        $this->authorize('view', $warehouse);
        
        $onHandMerchandises = $this->merchandise->getCurrentMerchandiseLevelByProductAndWarehouse($warehouse->id)->load('product.productCategory');

        $totalDistinctOnHandMerchandises = $this->merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $outOfStockMerchandises = $product->getAllOutOfStockMerchandisesByWarehouse($onHandMerchandises)->load('productCategory');

        $totalOutOfStockMerchandises = $product->getTotalOutOfStockMerchandises($outOfStockMerchandises);

        $totalDistinctLimitedMerchandises = $this->merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('merchandises.levels.index', compact('onHandMerchandises', 'outOfStockMerchandises', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'warehouses', 'warehouse'));
    }

}
