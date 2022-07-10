<?php

namespace App\Http\Controllers\Invokable;

use App\DataTables\ProductPerWarehouseDatatable;
use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class ProductPerWarehouseHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory History');
    }

    public function __invoke(Product $product, Warehouse $warehouse, ProductPerWarehouseDatatable $datatable)
    {
        $this->authorize('viewAny', Merchandise::class);

        abort_if(! authUser()->hasWarehousePermission('read', $warehouse), 403);

        $datatable->builder()->setTableId('product-per-warehouse-datatable');

        return $datatable->render('warehouses-products.index', compact('warehouse', 'product'));
    }
}
