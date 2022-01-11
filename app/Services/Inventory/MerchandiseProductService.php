<?php

namespace App\Services\Inventory;

use App\Models\Product;

class MerchandiseProductService
{
    public function getOnHandMerchandiseProductsQuery($warehouseId = null, $user = null)
    {
        if ($user && $user->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return Product::query()
            ->whereHas('merchandises', function ($query) use ($warehouseId, $user) {
                $query->when($warehouseId, fn($query) => $query->where('warehouse_id', $warehouseId))
                    ->when($user, function ($query) use ($user) {
                        $query->whereIn('warehouse_id', $user->getAllowedWarehouses('read')->pluck('id'));
                    })
                    ->where(function ($query) {
                        $query->where('available', '>', 0)
                            ->orWhere('reserved', '>', 0);
                    });
            });
    }

    public function getOutOfStockMerchandiseProductsQuery($warehouseId = null)
    {
        if (auth()->check() && auth()->user()->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return Product::query()
            ->whereNotIn('id', function ($query) use ($warehouseId) {
                $query->select('product_id')
                    ->from('merchandises')
                    ->where('company_id', userCompany()->id)
                    ->when($warehouseId, fn($query) => $query->where('warehouse_id', $warehouseId))
                    ->when(auth()->check(), function ($query) {
                        $query->whereIn('warehouse_id', auth()->user()->getAllowedWarehouses('read')->pluck('id'));
                    })
                    ->where(function ($query) {
                        $query->where('available', '>', 0)
                            ->orWhere('reserved', '>', 0);
                    });
            });
    }

    public function getLimitedMerchandiseProductsQuery($warehouseId = null)
    {
        if (auth()->check() && auth()->user()->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return Product::query()
            ->where('min_on_hand', '>', 0)
            ->whereHas('merchandises', function ($query) use ($warehouseId) {
                $query->when($warehouseId, fn($query) => $query->where('warehouse_id', $warehouseId))
                    ->when(auth()->check(), function ($query) {
                        $query->whereIn('warehouse_id', auth()->user()->getAllowedWarehouses('read')->pluck('id'));
                    })
                    ->where('merchandises.available', '>', 0)
                    ->whereRaw('merchandises.available <= products.min_on_hand');
            });
    }
}
