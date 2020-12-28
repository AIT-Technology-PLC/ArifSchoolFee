<?php

namespace App\Services;

use App\Models\Merchandise;

class AddPurchasedItemsToInventory
{
    public static function addToInventory($purchase)
    {
        if (!self::isAddToInventoryNowChecked($purchase->status)) {
            return true;
        }

        self::addProductsPurchasedToInventory($purchase);
    }

    protected static function isAddToInventoryNowChecked($purchaseStatus)
    {
        return $purchaseStatus == "Added To Inventory";
    }

    protected static function preparePurchaseDetailForMerchandise($purchase, $purchaseDetail)
    {
        $purchaseDetail = [
            'product_id' => $purchaseDetail->product_id,
            'total_received' => $purchaseDetail->quantity,
            'total_on_hand' => $purchaseDetail->quantity,
            'total_sold' => 0,
            'total_broken' => 0,
            'total_returns' => 0,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'company_id' => auth()->user()->employee->company_id,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
            'warehouse_id' => $purchase->warehouse_id,
            'expires_on' => $purchase->expires_on,
        ];

        return $purchaseDetail;
    }

    protected static function addProductsPurchasedToInventory($purchase)
    {
        foreach ($purchase->purchaseDetails as $purchaseDetail) {
            if ($purchaseDetail->product->isProductMerchandise()) {
                $merchandise = new Merchandise();
                $merchandise->create(self::preparePurchaseDetailForMerchandise($purchase, $purchaseDetail));
            }
        }
    }
}
