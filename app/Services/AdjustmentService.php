<?php

namespace App\Services;

use App\Notifications\AdjustmentMade;
use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdjustmentService
{
    public function adjust($adjustment)
    {
        if (!$adjustment->isApproved()) {
            return [false, 'This Adjustment is not approved.'];
        }

        if ($adjustment->isAdjusted()) {
            return [false, 'This Adjustment is already executed.'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($this->loadOnlySubtracts($adjustment));

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($adjustment) {
            InventoryOperationService::subtract($this->loadOnlySubtracts($adjustment));

            InventoryOperationService::add($this->loadOnlyAdds($adjustment));

            $adjustment->adjust();

            Notification::send(notifiables('Approve Adjustment', $adjustment->createdBy), new AdjustmentMade($adjustment));
        });

        return [true, ''];
    }

    public function loadOnlySubtracts($adjustment)
    {
        return $adjustment->adjustmentDetails()->where('is_subtract', 1)->get();
    }

    public function loadOnlyAdds($adjustment)
    {
        return $adjustment->adjustmentDetails()->where('is_subtract', 0)->get();
    }
}
