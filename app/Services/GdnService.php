<?php

namespace App\Services;

use App\Notifications\GdnSubtracted;
use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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

        $unavailableProducts = InventoryOperationService::unavailableProducts($gdn->details(), $from);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($gdn, $from) {
            InventoryOperationService::subtract($gdn->details(), $from);

            $gdn->subtract();

            Notification::send(notifiables('Approve GDN', $gdn->createdBy), new GdnSubtracted($gdn));
        });

        return [true, ''];
    }
}
