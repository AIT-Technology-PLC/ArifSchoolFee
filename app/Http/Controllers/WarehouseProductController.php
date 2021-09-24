<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Services\InventoryHistory\InventoryHistoryService;

class WarehouseProductController extends Controller
{
    public function __invoke(Warehouse $warehouse, Product $product, InventoryHistoryService $service)
    {
        $this->authorize('view', $warehouse);

        $this->authorize('view', $product);

        $history = $service->get($warehouse, $product);

        return view('warehouses-products.index', compact('warehouse', 'product', 'history'));
    }
}
