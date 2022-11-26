<?php

namespace App\Services\Models;

use App\Models\Damage;
use App\Models\MerchandiseBatch;

class MerchandiseBatchService
{
    public function convertToDamage($merchandiseBatch)
    {
        if ($merchandiseBatch->isConvertedToDamage()) {
            return [false, 'This Batch is already converted to damage .', ''];
        }

        $merchandiseBatches = MerchandiseBatch::whereRelation('merchandise', 'id', $merchandiseBatch->merchandise_id)
            ->join('merchandises', 'merchandise_batches.merchandise_id', '=', 'merchandises.id')
            ->where('merchandise_batches.quantity', '>', 0)
            ->whereDate('expiry_date', '<', now())
            ->groupBy(['merchandise_id', 'product_id', 'warehouse_id'])
            ->selectRaw('warehouse_id,product_id,SUM(quantity) AS quantity')
            ->get();

        $damage = Damage::create([
            'code' => nextReferenceNumber('damages'),
            'issued_on' => now(),
        ]);

        $damage->damageDetails()->createMany($merchandiseBatches->toArray());

        $merchandiseBatchesId = MerchandiseBatch::whereRelation('merchandise', 'id', $merchandiseBatch->merchandise_id)
            ->where('merchandise_batches.quantity', '>', 0)
            ->whereDate('expiry_date', '<', now())
            ->get();

        foreach ($merchandiseBatchesId as $merchandiseBatch) {
            $merchandiseBatch->convert();
        }

        return [true, '', $damage];
    }
}