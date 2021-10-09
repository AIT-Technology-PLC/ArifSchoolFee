<?php

namespace App\Services;

use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class GdnService
{
    private const SUBTRACT_FAILED_MESSAGE = 'This transaction is already subtracted from inventory';

    private const APPROVE_FAILED_MESSAGE = 'This transaction is not approved yet.';

    public function subtract($gdn)
    {
        if (!$gdn->isApproved()) {
            return [false, static::APPROVE_FAILED_MESSAGE];
        }

        if ($gdn->isSubtracted()) {
            return [false, static::SUBTRACT_FAILED_MESSAGE];
        }

        $from = $gdn->reservation()->exists() ? 'reserved' : 'available';

        $unavailableProducts = InventoryOperationService::unavailableProducts($gdn->gdnDetails, $from);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($gdn, $from) {
            InventoryOperationService::subtract($gdn->gdnDetails, $from);

            $gdn->subtract();
        });

        return [true, ''];
    }
}
