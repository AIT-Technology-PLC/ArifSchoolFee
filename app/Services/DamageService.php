<?php

namespace App\Services;

use App\Notifications\DamageSubtracted;
use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class DamageService
{
    private const SUBTRACT_FAILED_MESSAGE = 'This transaction is already subtracted from inventory';

    private const APPROVE_FAILED_MESSAGE = 'This transaction is not approved yet.';

    public function subtract($damage)
    {
        if (!$damage->isApproved()) {
            return [false, static::APPROVE_FAILED_MESSAGE];
        }

        if ($damage->isSubtracted()) {
            return [false, static::SUBTRACT_FAILED_MESSAGE];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($damage->damageDetails);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($damage) {
            InventoryOperationService::subtract($damage->damageDetails);

            $damage->subtract();

            Notification::send(notifiables('Approve Damage', $damage->createdBy), new DamageSubtracted($damage));
        });

        return [true, ''];
    }
}
