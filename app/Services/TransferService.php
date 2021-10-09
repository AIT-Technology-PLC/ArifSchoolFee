<?php

namespace App\Services;

use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class TransferService
{
    public function subtract($transfer)
    {
        if (!$transfer->isApproved()) {
            return [false, 'This Transfer is not approved yet.'];
        }

        if ($transfer->isSubtracted()) {
            return [false, "This Transfer is already subtracted from {$transfer->transferredFrom->name}"];
        }

        data_fill($transfer->transferDetails, '*.warehouse_id', $transfer->transferred_from);

        data_fill($transfer->transferDetails, '*.warehouse', $transfer->transferredFrom);

        $unavailableProducts = InventoryOperationService::unavailableProducts($transfer->transferDetails);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($transfer) {
            InventoryOperationService::subtract($transfer->transferDetails);

            $transfer->subtract();
        });

        return [true, ''];
    }

    public function add($transfer)
    {
        if (!$transfer->isApproved()) {
            return [false, 'This Transfer is not approved yet.'];
        }

        if (!$transfer->isSubtracted()) {
            return [false, "This Transfer is not subtracted from {$transfer->transferredFrom->name} yet."];
        }

        if ($transfer->isAdded()) {
            return [false, "This Transfer is already added to {$transfer->transferredTo->name}."];
        }

        data_fill($transfer->transferDetails, '*.warehouse_id', $transfer->transferred_to);

        DB::transaction(function () use ($transfer) {
            InventoryOperationService::add($transfer->transferDetails);

            $transfer->add();
        });

        return [true, ''];
    }
}
