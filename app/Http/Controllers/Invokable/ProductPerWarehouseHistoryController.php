<?php

namespace App\Http\Controllers\Invokable;

use App\Http\Controllers\Controller;
use App\Models\Merchandise;
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
        $this->authorize('viewAny', Merchandise::class);

        abort_if(!auth()->user()->hasWarehousePermission('read', $warehouse), 403);

        $history = $service->get($warehouse, $product);

        return view('warehouses-products.index', compact('warehouse', 'product', 'history'));
    }
}
