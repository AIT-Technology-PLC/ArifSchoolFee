<?php

namespace App\Services\Models;

use App\Actions\PadAutoBatchStoringAction;
use App\Models\MerchandiseBatch;
use App\Models\Pad;
use App\Models\Product;
use App\Models\TransactionField;
use App\Models\Warehouse;
use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function store($pad, $data)
    {
        return DB::transaction(function () use ($pad, $data) {
            $transaction = $pad->transactions()->create(Arr::only($data, ['code', 'status', 'issued_on']));

            $this->storeTransactionFields($transaction, $data);

            PadAutoBatchStoringAction::execute($transaction);

            return $transaction;
        });
    }

    public function update($transaction, $data)
    {
        return DB::transaction(function () use ($transaction, $data) {
            $transaction->transactionFields()->forceDelete();

            $transaction->update(Arr::only($data, ['code', 'status', 'issued_on']));

            $this->storeTransactionFields($transaction, $data);

            PadAutoBatchStoringAction::execute($transaction);

            return $transaction;
        });
    }

    public function approve($transaction)
    {
        if (!$transaction->pad->isApprovable()) {
            return [false, 'This feature is not approvable.'];
        }

        if ($transaction->isApproved()) {
            return [false, 'This transaction is already approved.'];
        }

        $transaction->approve();

        return [true, ''];
    }

    public function subtract($transaction, $user, $line = null)
    {
        if (!$transaction->pad->isInventoryOperationSubtract()) {
            return [false, 'This transaction can not be subtracted from inventory.'];
        }

        $subtractedLines = $transaction->transactionFields()->where('key', 'subtracted_by')->whereNotNull('line')->pluck('line')->unique();

        $transactionDetails = $this->formatTransactionDetails($transaction, $line)->whereNotIn('line', $subtractedLines);

        if (!$user->hasWarehousePermission('subtract',
            $transactionDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to subtract from one or more of the warehouses.'];
        }

        if ($transaction->pad->isApprovable() && !$transaction->isApproved()) {
            return [false, 'This transaction is not approved yet.'];
        }

        if ($transaction->isSubtracted()) {
            return [false, 'This transaction is already subtracted from inventory.'];
        }

        if (!is_null($line) && TransactionField::isSubtracted($transaction, $line)) {
            return [false, 'This product is already subtracted from inventory.'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($transactionDetails);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($transaction, $transactionDetails, $line) {
            InventoryOperationService::subtract($transactionDetails, $transaction);

            if (!is_null($line)) {
                TransactionField::subtract($transaction, $line);
            }

            if (is_null($line)) {
                $transaction->subtract();
            }
        });

        return [true, ''];
    }

    public function add($transaction, $user, $line = null)
    {
        if (!$transaction->pad->isInventoryOperationAdd()) {
            return [false, 'This transaction can not be added to inventory.'];
        }

        $addedLines = $transaction->transactionFields()->where('key', 'added_by')->whereNotNull('line')->pluck('line')->unique();

        $transactionDetails = $this->formatTransactionDetails($transaction, $line)->whereNotIn('line', $addedLines);

        if (!$user->hasWarehousePermission('add',
            $transactionDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to add to one or more of the warehouses.'];
        }

        if ($transaction->pad->isApprovable() && !$transaction->isApproved()) {
            return [false, 'This transaction is not approved yet.'];
        }

        if ($transaction->isAdded()) {
            return [false, 'This transaction is already added to inventory.'];
        }

        if (!is_null($line) && TransactionField::isAdded($transaction, $line)) {
            return [false, 'This product is already added to inventory.'];
        }

        DB::transaction(function () use ($transaction, $transactionDetails, $line) {
            InventoryOperationService::add($transactionDetails, $transaction);

            if (!is_null($line)) {
                TransactionField::add($transaction, $line);
            }

            if (is_null($line)) {
                $transaction->add();
            }
        });

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

    public function updateFileUploads($transaction, $data)
    {
        $line = 0;

        $padFields = $transaction->pad->padFields()->get();

        if (array_key_exists('master', $data)) {
            foreach ($data['master'] as $key => $value) {
                $transactionField = $transaction->transactionFields()->create([
                    'pad_field_id' => $key,
                    'value' => $value,
                ]);

                if (is_object($value) && $padFields->firstWhere('id', $key)->isInputTypeFile()) {
                    $transactionField->update([
                        'value' => $value->store('pad_transaction_files', 'public'),
                    ]);
                }
            }
        }

        if (array_key_exists('details', $data)) {
            foreach ($data['details'] as $detail) {
                foreach ($detail as $key => $value) {
                    $transactionField = $transaction->transactionFields()->create([
                        'pad_field_id' => $key,
                        'value' => $value,
                        'line' => $line,
                    ]);

                    if (is_object($value) && $padFields->firstWhere('id', $key)->isInputTypeFile()) {
                        $transactionField->update([
                            'value' => $value->store('pad_transaction_files', 'public'),
                        ]);
                    }
                }

                $line++;
            }
        }
    }

    private function formatTransactionDetails($transaction, $line)
    {
        $transactionDetails = $transaction
            ->transactionDetails
            ->map(function ($detail) {
                return [
                    'product_id' => Product::firstWhere('id', $detail['product_id'])->id,
                    'warehouse_id' => Warehouse::firstWhere('id', $detail['warehouse_id'])->id,
                    'merchandise_batch_id' => MerchandiseBatch::whereHas('merchandise')->firstWhere('id', $detail['merchandise_batch_id'] ?? null)?->id,
                    'quantity' => $detail['quantity'],
                    'batch_no' => $detail['batch_no'] ?? null,
                    'expires_on' => $detail['expires_on'] ?? null,
                    'line' => $detail['line'],
                ];
            });

        return is_null($line) ? $transactionDetails : $transactionDetails->where('line', $line);
    }
}
