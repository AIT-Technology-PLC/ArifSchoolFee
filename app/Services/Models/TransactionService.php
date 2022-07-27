<?php

namespace App\Services\Models;

use App\Models\Pad;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function store($pad, $data)
    {
        return DB::transaction(function () use ($pad, $data) {
            $transaction = $pad->transactions()->create(Arr::only($data, ['code', 'issued_on']));

            $this->storeTransactionFields($transaction, $data);

            return $transaction;
        });
    }

    public function update($transaction, $data)
    {
        return DB::transaction(function () use ($transaction, $data) {
            $transaction->transactionFields()->forceDelete();

            $transaction->update(Arr::only($data, ['code', 'issued_on']));

            $this->storeTransactionFields($transaction, $data);

            return $transaction;
        });
    }

    public function approve($transaction)
    {
        if ($transaction->pad->isCancellable() && $transaction->isCancelled()) {
            return [false, 'This transaction is cancelled.'];
        }

        if ($transaction->pad->isClosable() && $transaction->isClosed()) {
            return [false, 'This transaction is closed.'];
        }

        if ($transaction->pad->isApprovable() && $transaction->isApproved()) {
            return [false, 'This transaction is already approved.'];
        }

        $transaction->approve();

        return [true, ''];
    }

    public function subtract($transaction, $user)
    {
        if (!$transaction->pad->isInventoryOperationSubtract()) {
            return [false, 'This transaction can not be subtracted from inventory.'];
        }

        $transactionDetails = $this->formatTransactionDetails($transaction);

        if (!$user->hasWarehousePermission('subtract',
            $transactionDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to subtract from one or more of the warehouses.'];
        }

        if ($transaction->pad->isCancellable() && $transaction->isCancelled()) {
            return [false, 'This transaction is cancelled.'];
        }

        if ($transaction->pad->isApprovable() && !$transaction->isApproved()) {
            return [false, 'This transaction is not approved yet.'];
        }

        if ($transaction->isSubtracted()) {
            return [false, 'This transaction is already subtracted from inventory'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($transactionDetails);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($transaction, $transactionDetails) {
            InventoryOperationService::subtract($transactionDetails);

            $transaction->subtract();
        });

        return [true, ''];
    }

    public function add($transaction, $user)
    {
        if (!$transaction->pad->isInventoryOperationAdd()) {
            return [false, 'This transaction can not be added to inventory.'];
        }

        $transactionDetails = $this->formatTransactionDetails($transaction);

        if (!$user->hasWarehousePermission('add',
            $transactionDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to add to one or more of the warehouses.'];
        }

        if ($transaction->pad->isCancellable() && $transaction->isCancelled()) {
            return [false, 'This transaction is cancelled.'];
        }

        if ($transaction->pad->isApprovable() && !$transaction->isApproved()) {
            return [false, 'This transaction is not approved yet.'];
        }

        if ($transaction->isAdded()) {
            return [false, 'This transaction is already add to inventory'];
        }

        DB::transaction(function () use ($transaction, $transactionDetails) {
            InventoryOperationService::add($transactionDetails);

            $transaction->add();
        });

        return [true, ''];
    }

    public function close($transaction)
    {
        if ($transaction->pad->isInventoryOperationSubtract() && !$transaction->isSubtracted()) {
            return [false, 'This transaction is not subtracted yet.'];
        }

        if ($transaction->pad->isInventoryOperationAdd() && !$transaction->isAdded()) {
            return [false, 'This transaction is not added yet.'];
        }

        if ($transaction->pad->isCancellable() && $transaction->isCancelled()) {
            return [false, 'This transaction is cancelled.'];
        }

        if ($transaction->pad->isClosable() && $transaction->isClosed()) {
            return [false, 'This transaction is already closed.'];
        }

        $transaction->close();

        return [true, ''];
    }

    public function cancel($transaction)
    {
        if ($transaction->pad->isClosable() && $transaction->isClosed()) {
            return [false, 'This transaction is closed.'];
        }

        if ($transaction->pad->isCancellable() && $transaction->isCancelled()) {
            return [false, 'This transaction is already cancelled.'];
        }

        $transaction->cancel();

        return [true, ''];
    }

    public function convertTo($transaction, $target)
    {
        $isRouteForPad = Pad::enabled()->where('name', $target)->exists();

        $route = $isRouteForPad
        ? route('pads.transactions.create', Pad::enabled()->firstWhere('name', $target)->id)
        : route($target . '.create');

        $data = $transaction->convertTo($target, $isRouteForPad);

        return [$route, $data];
    }

    public function convertFrom($transaction, $target, $id)
    {
        $route = route('pads.transactions.create', $transaction->pad_id);

        $data = $transaction->convertFrom($target, $id);

        return [$route, $data];
    }

    private function storeTransactionFields($transaction, $data)
    {
        $line = 0;

        if (array_key_exists('master', $data)) {
            foreach ($data['master'] as $key => $value) {
                $transaction->transactionFields()->create([
                    'pad_field_id' => $key,
                    'value' => $value,
                ]);
            }
        }

        if (array_key_exists('details', $data)) {
            foreach ($data['details'] as $detail) {
                foreach ($detail as $key => $value) {
                    $transaction->transactionFields()->create([
                        'pad_field_id' => $key,
                        'value' => $value,
                        'line' => $line,
                    ]);
                }

                $line++;
            }
        }
    }

    private function formatTransactionDetails($transaction)
    {
        return $transaction
            ->transactionDetails
            ->map(function ($detail) {
                return [
                    'product_id' => Product::firstWhere('name', $detail['product'])->id,
                    'warehouse_id' => Warehouse::firstWhere('name', $detail['warehouse'])->id,
                    'quantity' => $detail['quantity'],
                ];
            });
    }
}
