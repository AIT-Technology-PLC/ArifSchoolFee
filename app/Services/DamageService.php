<?php

namespace App\Services;

use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;

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
        });

        return [true, ''];
    }
}
