<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class MerchandiseController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Merchandise Inventory');
    }

    public function show(Product $product, Warehouse $warehouse)
    {
        if (!userCompany()->isInventoryCheckerEnabled()) {
            return false;
        }

        if ($product->isTypeService()) {
            return false;
        }

        if (!$product->isProductSingle()) {
            $merchandise = Merchandise::query()
                ->whereIn('product_id', $product->productBundles()->pluck('component_id'))
                ->when($warehouse->exists, fn($q) => $q->where('warehouse_id', $warehouse->id))
                ->orderBy('available', 'ASC')
                ->first();

            $availableQuantity = $merchandise->available / $product->productBundles()->where('component_id', $merchandise->product_id)->first()->quantity;
        }

        if ($product->isProductSingle()) {
            $availableQuantity = Merchandise::query()
                ->where('product_id', $product->id)
                ->when($warehouse->exists, fn($q) => $q->where('warehouse_id', $warehouse->id))
                ->sum('available');
        }

        return str(number_format($availableQuantity, 2))->append(' ', $product->unit_of_measurement)->toString();
    }
}
