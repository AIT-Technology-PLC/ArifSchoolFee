<?php

namespace App\Services;

use App\Models\Merchandise;

class AddPurchasedItemsToInventory
{
    public static function addToInventory($purchase)
    {
        if (!self::isAddToInventoryNowChecked($purchase)) {
            return true;
        }

        self::validateWarehouseAndExpirationFields();

        self::addProductsPurchasedToInventory($purchase->purchaseDetails);
    }

    public static function validateWarehouseAndExpirationFields()
    {
        return request()->validate([
            'warehouse_id' => 'sometimes|required|integer',
            'expires_on' => 'sometimes|nullable|date',
        ]);
    }

    public static function isAddToInventoryNowChecked($purchase)
    {
        return $purchase->isAddedToInventory();
    }

    public static function preparePurchaseDetailForMerchandise($detail)
    {
        $detail = [
            'product_id' => $detail->product_id,
            'total_received' => $detail->quantity,
            'total_on_hand' => $detail->quantity,
            'total_sold' => 0,
            'total_broken' => 0,
            'total_returns' => 0,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'company_id' => auth()->user()->employee->company_id,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
            'warehouse_id' => $detail->to_warehouse_id ?? self::validateWarehouseAndExpirationFields()['warehouse_id'],
            'expires_on' => self::validateWarehouseAndExpirationFields()['expires_on'] ?? null,
        ];

        return $detail;
    }

    public static function addProductsPurchasedToInventory($details)
    {
        foreach ($details as $detail) {
            if ($detail->product->isProductMerchandise()) {
                $merchandise = new Merchandise();
                $merchandise->create(self::preparePurchaseDetailForMerchandise($detail));
            }
        }
    }
}
