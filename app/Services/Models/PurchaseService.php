<?php

namespace App\Services\Models;

class PurchaseService
{
    public function convertToGrn($purchase)
    {
        if (! $purchase->isPurchased()) {
            return [false, 'This purchase is not yet purchased.', ''];
        }

        if ($purchase->isClosed()) {
            return [false, 'This purchase is closed.', ''];
        }

        $data = [
            'purchase_id' => $purchase->id,
            'supplier_id' => $purchase->supplier_id,
            'grn' => $purchase->purchaseDetails->toArray(),
        ];

        return [true, '', $data];
    }
}
