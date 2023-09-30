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

        if (!$product->exists) {
            return false;
        }

        if ($product->isTypeService()) {
            return false;
        }

        if (!$warehouse->exists) {
            $availableQuantity = Merchandise::where('product_id', $product->id)->sum('available');

        } else {
            $availableQuantity = Merchandise::where('product_id', $product->id)->where('warehouse_id', $warehouse->id)->first()->available ?? 0;
        }

        return str(number_format($availableQuantity, 2))->append(' ', $product->unit_of_measurement)->toString();
    }
}
