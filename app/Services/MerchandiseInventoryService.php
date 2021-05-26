<?php

namespace App\Services;

use App\Models\Merchandise;
use Illuminate\Support\Facades\DB;

class MerchandiseInventoryService
{
    private $merchandise;

    public function __construct()
    {
        $this->merchandise = new Merchandise();
    }

    public function add($detail)
    {
        DB::transaction(function () use ($detail) {
            $merchandise = $this->merchandise->firstOrCreate(
                [
                    'product_id' => $detail->product_id,
                    'warehouse_id' => $detail->warehouse_id,
                ],
                [
                    'on_hand' => 0.00,
                ]
            );

            $merchandise->on_hand = $merchandise->on_hand + $detail->quantity;

            $merchandise->save();
        });
    }

    public function isAvailable($detail)
    {
        return $this->merchandise->where([
            ['product_id', $detail->product_id],
            ['warehouse_id', $detail->warehouse_id],
            ['on_hand', '>=', $detail->quantity],
        ])->exists();
    }

    public function subtract($detail)
    {
        $merchandise = $this->merchandise->where([
            ['product_id', $detail->product_id],
            ['warehouse_id', $detail->warehouse_id],
            ['on_hand', '>=', $detail->quantity],
        ])->first();

        $merchandise->on_hand = $merchandise->on_hand - $detail->quantity;

        $merchandise->save();
    }

    public function transfer($detail)
    {
        DB::transaction(function () use ($detail) {
            $this->subtract($detail);

            $this->add($detail);
        });
    }
}
