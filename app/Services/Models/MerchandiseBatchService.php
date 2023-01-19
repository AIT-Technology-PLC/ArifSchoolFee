<?php

namespace App\Services\Models;

use App\Models\Damage;
use Illuminate\Support\Facades\DB;

class MerchandiseBatchService
{
    public function convertToDamage($merchandiseBatch)
    {
        if ($merchandiseBatch->isDamaged()) {
            return [false, 'This Batch is already damage.', ''];
        }

        if (!$merchandiseBatch->isExpired()) {
            return [false, 'This Batch is not expired yet.', ''];
        }

        return DB::transaction(function () use ($merchandiseBatch) {
            $damage = Damage::create([
                'code' => nextReferenceNumber('damages'),
                'issued_on' => now(),
            ]);

            $damage->damageDetails()->create([
                'merchandise_batch_id' => $merchandiseBatch->id,
                'product_id' => $merchandiseBatch->merchandise->product_id,
                'warehouse_id' => $merchandiseBatch->merchandise->warehouse_id,
                'quantity' => $merchandiseBatch->quantity,
            ]);

            $damage->approve();

            return [true, '', $damage];
        });
    }
}
