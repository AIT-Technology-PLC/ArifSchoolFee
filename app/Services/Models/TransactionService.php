<?php

namespace App\Services\Models;

use App\Models\GdnDetail;
use App\Models\Transaction;
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
        $padFields = $transaction->pad->padFields()->detailFields()->get();

        return $transaction
            ->transactionFields()
            ->detailFields()
            ->get()
            ->groupBy('line')
            ->map(function ($transactionFields) use ($padFields) {
                $data['quantity'] = $transactionFields->firstWhere('pad_field_id', $padFields->firstWhere('label', 'Quantity')->id)->value;

                $data['product_id'] = $transactionFields->firstWhere('pad_field_id', $padFields->firstWhere('label', 'Product')->id)->value;

                $data['warehouse_id'] = $transactionFields->firstWhere('pad_field_id', $padFields->firstWhere('label', 'Warehouse')->id)->value;

                return new GdnDetail($data);
            });
    }
}
