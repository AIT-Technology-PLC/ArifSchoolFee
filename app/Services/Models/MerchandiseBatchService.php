<?php

namespace App\Services\Models;

use App\Models\Damage;
use App\Models\MerchandiseBatch;

class MerchandiseBatchService
{
    public function convertToDamage($merchandiseBatch)
    {
        $merchandiseBatches = MerchandiseBatch::whereRelation('merchandise', 'id', $merchandiseBatch->merchandise_id)
            ->join('merchandises', 'merchandise_batches.merchandise_id', '=', 'merchandises.id')
            ->whereDate('expiry_date', '<', now())
            ->groupBy(['merchandise_id', 'product_id', 'warehouse_id'])
            ->selectRaw('warehouse_id,product_id,SUM(quantity) AS quantity')
            ->get();

        $damage = Damage::create([
            'code' => nextReferenceNumber('damages'),
            'issued_on' => now(),
        ]);

        $damage->damageDetails()->createMany($merchandiseBatches->toArray());

        return [true, '', $damage];
    }
}