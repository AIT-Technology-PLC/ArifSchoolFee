<?php

namespace App\Services\Models;

use App\Models\Transaction;
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
}
