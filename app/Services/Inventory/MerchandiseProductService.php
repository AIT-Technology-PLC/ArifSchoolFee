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
            ->when(!is_null($user) && !$user->hasRole('System Manager'), fn($q) => $q->active())
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
            ->when(!is_null($user) && !$user->hasRole('System Manager'), fn($q) => $q->active())
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
            ->when(!is_null($user) && !$user->hasRole('System Manager'), fn($q) => $q->active())
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
            ->when(!is_null($user) && !$user->hasRole('System Manager'), fn($q) => $q->active())
            ->inventoryType()
            ->whereHas('merchandises', function ($query) use ($warehouseId, $user) {
                $query
                    ->when($warehouseId, fn($q) => $q->where('merchandises.warehouse_id', $warehouseId))
                    ->when($user, fn($q) => $q->whereIn('merchandises.warehouse_id', $user->getAllowedWarehouses('read')->pluck('id')))
                    ->where('merchandises.available', '>', 0)
                    ->leftJoin('product_reorders', function ($join) {
                        $join
                            ->on('merchandises.product_id', '=', 'product_reorders.product_id')
                            ->on('merchandises.warehouse_id', '=', 'product_reorders.warehouse_id');
                    })
                    ->whereRaw('merchandises.available <= COALESCE(product_reorders.quantity, products.min_on_hand)');
            });
    }
}
