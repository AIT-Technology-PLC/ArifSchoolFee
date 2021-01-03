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
        $onHandMerchandises = $this->merchandise->getCurrentMerchandiseLevelByProduct();

        $totalDistinctOnHandMerchandises = $this->merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $outOfStockMerchandises = $product->getAllOutOfStockMerchandises();

        $totalOutOfStockMerchandises = $product->getTotalOutOfStockMerchandises($outOfStockMerchandises);

        $totalDistinctLimitedMerchandises = $this->merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($this->merchandise->getAllOnHandMerchandises());

        return view('merchandises.levels.index', compact('onHandMerchandises', 'outOfStockMerchandises', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'totalWarehouseInUse'));
    }

}
