<?php

namespace App\Services\Inventory;

use App\Models\Product;

class MerchandiseProductService
{
    public function getAvailableMerchandiseProductsQuery($warehouseId = null, $user = null)
    {
        if ($user && $user->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return Product::query()
            ->inventoryType()
            ->whereHas('merchandises', function ($query) use ($warehouseId, $user) {
                $query->when($warehouseId, fn($q) => $q->where('warehouse_id', $warehouseId))
                    ->when($user, function ($query) use ($user) {
                        $query->whereIn('warehouse_id', $user->getAllowedWarehouses('read')->pluck('id'));
                    })
                    ->where(function ($query) {
                        $query->where('available', '>', 0);
                    });
            });
    }

    public function getOnHandMerchandiseProductsQuery($warehouseId = null, $user = null)
    {
        if ($user && $user->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return Product::query()
            ->inventoryType()
            ->whereHas('merchandises', function ($query) use ($warehouseId, $user) {
                $query->when($warehouseId, fn($q) => $q->where('warehouse_id', $warehouseId))
                    ->when($user, function ($query) use ($user) {
                        $query->whereIn('warehouse_id', $user->getAllowedWarehouses('read')->pluck('id'));
                    })
                    ->where(function ($query) {
                        $query->where('available', '>', 0)
                            ->orWhere('reserved', '>', 0);
                    });
            });
    }

    public function getOutOfStockMerchandiseProductsQuery($warehouseId = null, $user = null)
    {
        if ($user && $user->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return Product::query()
            ->inventoryType()
            ->whereNotIn('id', function ($query) use ($warehouseId, $user) {
                $query->select('product_id')
                    ->from('merchandises')
                    ->when($user, fn($q) => $q->where('company_id', $user->employee->company_id))
                    ->when($warehouseId, fn($q) => $q->where('warehouse_id', $warehouseId))
                    ->when($user, function ($query) use ($user) {
                        $query->whereIn('warehouse_id', $user->getAllowedWarehouses('read')->pluck('id'));
                    })
                    ->where(function ($query) {
                        $query->where('available', '>', 0)
                            ->orWhere('reserved', '>', 0);
                    });
            });
    }

    public function getLimitedMerchandiseProductsQuery($warehouseId = null, $user = null)
    {
        if ($user && $user->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return Product::query()
            ->inventoryType()
            ->where('min_on_hand', '>', 0)
            ->whereHas('merchandises', function ($query) use ($warehouseId, $user) {
                $query->when($warehouseId, fn($q) => $q->where('warehouse_id', $warehouseId))
                    ->when($user, function ($query) use ($user) {
                        $query->whereIn('warehouse_id', $user->getAllowedWarehouses('read')->pluck('id'));
                    })
                    ->where('merchandises.available', '>', 0)
                    ->whereRaw('merchandises.available <= products.min_on_hand');
            });
    }
}
