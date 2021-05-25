<?php

namespace App\Services;

use Illuminate\Support\Arr;

class MerchandiseInventoryService
{
    private $merchandise;

    public function __construct(Merchandise $merchandise)
    {
        $this->merchandise = $merchandise;
    }

    public function add($detail)
    {
        DB::transaction(function () use ($detail) {
            $merchandise = $this->merchandise->firstOrCreate(
                [
                    'product_id' => $detail->product_id,
                    'warehouse_id' => $detail->warehouse_id,
                ],
                Arr::add(SetDataOwnerService::forNonTransaction(),
                    'on_hand', 0.00)
            );
    
            $merchandise->on_hand = $merchandise->on_hand + $detail->quantity;
    
            $merchandise->updated_by = SetDataOwnerService::forUpdate()['updated_by'];
    
            $merchandise->save();
        });
    }
}
