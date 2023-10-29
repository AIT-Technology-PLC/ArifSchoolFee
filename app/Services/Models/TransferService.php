<?php

namespace App\Services\Models;

use App\Actions\ConvertToSivAction;
use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class TransferService
{
    public function subtract($transfer, $user)
    {
        if (!$user->hasWarehousePermission('subtract', $transfer->transferred_from)) {
            return [false, 'You do not have permission to subtract from one or more of the warehouses.'];
        }

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
            InventoryOperationService::subtract($transfer->transferDetails, $transfer);

            $transfer->subtract();
        });

        return [true, ''];
    }

    public function add($transfer, $user)
    {
        if (!$user->hasWarehousePermission('add', $transfer->transferred_to)) {
            return [false, 'You do not have permission to add to one or more of the warehouses.'];
        }

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
            InventoryOperationService::add($transfer->transferDetails, $transfer);

            $transfer->add();
        });

        return [true, ''];
    }

    public function convertToSiv($transfer, $user)
    {
        if (!$user->hasWarehousePermission('siv', $transfer->transferred_from)) {
            return [false, 'You do not have permission to convert to one or more of the warehouses.', ''];
        }

        if ($transfer->sivs()->exists()) {
            return [false, 'Siv for this transfer was already created.', ''];
        }

        if (!$transfer->isSubtracted()) {
            return [false, 'This transfer is not subtracted yet.', ''];
        }

        if ($transfer->isClosed()) {
            return [false, 'This transfer is already closed.', ''];
        }

        $transferDetails = $transfer->transferDetails()->get(['product_id', 'merchandise_batch_id', 'quantity'])->toArray();

        data_fill($transferDetails, '*.warehouse_id', $transfer->transferred_from);

        $siv = DB::transaction(function () use ($transfer, $transferDetails) {
            $siv = (new ConvertToSivAction)->execute(
                $transfer,
                $transfer->transferredTo->name,
                collect($transferDetails),
            );

            $siv->storeConvertedCustomFields($transfer, 'siv');

            return $siv;
        });

        return [true, '', $siv];
    }

    public function close($transfer)
    {
        if (!$transfer->isAdded()) {
            return [false, 'This transfer is not added to destination yet.'];
        }

        if ($transfer->isClosed()) {
            return [false, 'This transfer is already closed.'];
        }

        $transfer->close();

        return [true, ''];
    }
}
