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

        if ($product->isProductSingle()) {
            $availableQuantity = Merchandise::query()
                ->where('product_id', $product->id)
                ->when($warehouse->exists, fn($q) => $q->where('warehouse_id', $warehouse->id))
                ->sum('available');

            return str(number_format($availableQuantity, 2))->append(' ', $product->unit_of_measurement)->toString();
        }

        $merchandises = Merchandise::query()
            ->whereIn('product_id', $product->productBundles()->pluck('component_id'))
            ->when($warehouse->exists, fn($q) => $q->where('warehouse_id', $warehouse->id))
            ->orderBy('available', 'ASC')
            ->get();

        if ($merchandises->count() != $product->productBundles()->count()) {
            $availableQuantity = 0;
        }

        if ($merchandises->count() == $product->productBundles()->count()) {
            $availableQuantity = $merchandises->first()->available / $product->productBundles()->where('component_id', $merchandises->first()->product_id)->first()->quantity;
        }

        return str(number_format($availableQuantity, 2))->append(' ', $product->unit_of_measurement)->toString();
    }
}
