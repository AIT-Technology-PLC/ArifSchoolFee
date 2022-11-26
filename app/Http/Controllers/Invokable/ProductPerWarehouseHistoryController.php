<?php

namespace App\Http\Controllers\Invokable;

use App\DataTables\ProductPerWarehouseDatatable;
use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use App\Models\MerchandiseBatch;
use App\Models\Product;
use App\Models\Warehouse;

class ProductPerWarehouseHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory History');
    }

    public function __invoke(Product $product, Warehouse $warehouse, ProductPerWarehouseDatatable $datatable, MerchandiseBatch $merchandiseBatch, $expired = null)
    {
        $this->authorize('viewAny', Merchandise::class);

        abort_if(!authUser()->hasWarehousePermission('read', $warehouse), 403);

        $merchandiseBatches = MerchandiseBatch::whereRelation('merchandise', 'product_id', $product->id)->whereRelation('merchandise', 'warehouse_id', $warehouse->id)
            ->where('merchandise_batches.quantity', '>', 0)
            ->when(isset($expired), fn($q) => $q->whereDate('expiry_date', '<', now()))
            ->get();

        $datatable->builder()->setTableId('product-per-warehouse-datatable');

        return $datatable->render('warehouses-products.index', compact('expired', 'warehouse', 'product', 'merchandiseBatches'));
    }
}