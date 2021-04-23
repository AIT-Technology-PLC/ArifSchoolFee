<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class MerchandiseLevelController extends Controller
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

        $onHandMerchandiseProducts = $onHandMerchandises->pluck('product')->unique();

        $outOfStockMerchandiseProducts = $product->getOutOfStockMerchandiseProducts($onHandMerchandiseProducts)->load('productCategory');

        $totalDistinctOnHandMerchandises = $this->merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $totalDistinctLimitedMerchandises = $this->merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        $totalOutOfStockMerchandises = $outOfStockMerchandiseProducts->count();

        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($onHandMerchandises);

        return view('merchandises.levels.index', compact('onHandMerchandises', 'onHandMerchandiseProducts', 'outOfStockMerchandiseProducts', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'totalWarehouseInUse', 'warehouses'));
    }
}
