<?php

namespace App\Services;

use App\Notifications\TransferMade;
use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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

            Notification::send(notifiables('Approve Transfer', $transfer->createdBy), new TransferMade($transfer));
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

            Notification::send(notifiables('Approve Transfer', $transfer->createdBy), new TransferMade($transfer));
        });

        return [true, ''];
    }
}
