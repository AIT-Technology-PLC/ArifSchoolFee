<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Services\InventoryHistory\ProductPerWarehouseHistoryService;

class ProductPerWarehouseHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory History');
    }

    public function __invoke(Product $product, Warehouse $warehouse, ProductPerWarehouseHistoryService $service)
    {
        $this->authorize('view', $warehouse);

        $this->authorize('view', $product);

        $history = $service->get($warehouse, $product);

        return view('warehouses-products.index', compact('warehouse', 'product', 'history'));
    }
}
