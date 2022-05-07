<?php

namespace App\Services\Models;

use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function store($pad, $data)
    {
        $line = 0;

        return DB::transaction(function () use ($pad, $data, $line) {
            $transaction = $pad->create(Arr::only($data, ['code', 'issued_on']));

            foreach ($data['master'] as $key => $value) {
                $transaction->transactionFields()->create([
                    'pad_field_id' => $key,
                    'value' => $value,
                ]);
            }

            foreach ($data['details'] as $detail) {
                foreach ($detail as $key => $value) {
                    $transaction->transactionFields()->create([
                        'pad_field_id' => $key,
                        'value' => $value,
                        'line' => 0,
                    ]);
                }

                $line++;
            }

            return $transaction;
        });
    }
}
