<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class MerchandiseController extends Controller
{
    public function index(Merchandise $merchandise, Product $product, Warehouse $warehouse)
    {
        $this->authorize('viewAny', $merchandise);

        // On Hand Merchandise Prooducts
        $onHandMerchandises = $merchandise->getAllOnHand()->load('product.productCategory');
        $onHandMerchandiseProducts = $onHandMerchandises->pluck('product')->unique();

        // Available Merchandise Products
        $availableMerchandises = $merchandise->getAllAvailable()->load('product.productCategory');
        $availableMerchandiseProducts = $availableMerchandises->pluck('product')->unique();

        // Reserved Merchandise Products
        $reservedMerchandises = $merchandise->getAllReserved()->load('product.productCategory');
        $reservedMerchandiseProducts = $reservedMerchandises->pluck('product')->unique();

        // Out Of Stock Merchandise Products
        $outOfStockMerchandiseProducts = $product->getOutOfStockMerchandiseProducts($onHandMerchandiseProducts)->load('productCategory');

        // Warehouses
        $warehouses = $warehouse->getAllWithoutRelations();

        // Merchandise Inventory Insights
        $totalDistinctOnHandMerchandises = $onHandMerchandiseProducts->count();
        $totalDistinctLimitedMerchandises = $merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);
        $totalOutOfStockMerchandises = $outOfStockMerchandiseProducts->count();
        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($onHandMerchandises);

        return view('merchandises.index', compact(
            'merchandise',
            'onHandMerchandises',
            'onHandMerchandiseProducts',
            'availableMerchandises',
            'availableMerchandiseProducts',
            'reservedMerchandises',
            'reservedMerchandiseProducts',
            'outOfStockMerchandiseProducts',
            'warehouses',
            'totalDistinctOnHandMerchandises',
            'totalDistinctLimitedMerchandises',
            'totalOutOfStockMerchandises',
            'totalWarehouseInUse',
        ));
    }
}
