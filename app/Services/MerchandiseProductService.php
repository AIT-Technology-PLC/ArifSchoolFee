<?php

namespace App\Services;

class MerchandiseProductService
{
    public function getOnHandMerchandiseProductsQuery()
    {
        if (auth()->user()->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return $this->whereHas('merchandises', function ($query) {
            $query->whereIn('warehouse_id', auth()->user()->getAllowedWarehouses('read')->pluck('id'))
                ->where('available', '>', 0)
                ->orWhere('reserved', '>', 0);
        });
    }

    public function getOutOfStockMerchandiseProductsQuery($warehouseId = null)
    {
        if (auth()->user()->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return $this->whereNotIn('id', function ($query) use ($warehouseId) {
            $query->select('product_id')
                ->from('merchandises')
                ->where('company_id', userCompany()->id)
                ->whereIn('warehouse_id', auth()->user()->getAllowedWarehouses('read')->pluck('id'))
                ->when($warehouseId, fn($query) => $query->where('warehouse_id', $warehouseId))
                ->where('available', '>', 0)
                ->orWhere('reserved', '>', 0);
        });
    }

    public function getLimitedMerchandiseProductsQuery($warehouseId = null)
    {
        if (auth()->user()->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return $this->whereHas('merchandises', function ($query) use ($warehouseId) {
            $query->whereIn('warehouse_id', auth()->user()->getAllowedWarehouses('read')->pluck('id'))
                ->when($warehouseId, fn($query) => $query->where('warehouse_id', $warehouseId))
                ->whereRaw('products.min_on_hand != 0')
                ->whereRaw('merchandises.available <= products.min_on_hand');
        });
    }
}
