<?php

namespace App\Services\Models;

use App\Models\Damage;
use App\Models\MerchandiseBatch;
use Illuminate\Support\Facades\DB;

class MerchandiseBatchService
{
    public function convertToDamage($merchandiseBatch)
    {
        $merchandiseBatches = MerchandiseBatch::whereRelation('merchandise', 'id', $merchandiseBatch)
            ->notConverted()
            ->whereDate('expiry_date', '<', now())
            ->get();

        if ($merchandiseBatches->isEmpty()) {
            return [false, 'This Batch is already converted to damage .', ''];
        }

        $merchandiseBatches = MerchandiseBatch::whereRelation('merchandise', 'id', $merchandiseBatch)
            ->notConverted()
            ->join('merchandises', 'merchandise_batches.merchandise_id', '=', 'merchandises.id')
            ->where('merchandise_batches.quantity', '>', 0)
            ->whereDate('expiry_date', '<', now())
            ->groupBy(['merchandise_id', 'product_id', 'warehouse_id'])
            ->selectRaw('warehouse_id, product_id, SUM(quantity) AS quantity')
            ->get();

        return DB::transaction(function () use ($merchandiseBatches, $merchandiseBatch) {
            $damage = Damage::create([
                'code' => nextReferenceNumber('damages'),
                'issued_on' => now(),
            ]);

            $damage->damageDetails()->createMany($merchandiseBatches->toArray());

            $convertedMerchandiseBatches = MerchandiseBatch::whereRelation('merchandise', 'id', $merchandiseBatch)
                ->where('merchandise_batches.quantity', '>', 0)
                ->where('merchandise_batches.damage_id', '=', null)
                ->whereDate('expiry_date', '<', now())
                ->get();

            foreach ($convertedMerchandiseBatches as $merchandiseBatch) {
                $merchandiseBatch->damage_id = $damage->id;

                $merchandiseBatch->save();
            }

            return [true, '', $damage];
        });
    }
}