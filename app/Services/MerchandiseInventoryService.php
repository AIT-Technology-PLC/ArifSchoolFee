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

    public function add($detail, $to = 'available')
    {
        DB::transaction(function () use ($detail, $to) {
            $merchandise = $this->merchandise->firstOrCreate(
                [
                    'product_id' => $detail->product_id,
                    'warehouse_id' => $detail->warehouse_id,
                ],
                [
                    $to => 0.00,
                    'company_id' => SetDataOwnerService::forNonTransaction()['company_id'],
                ]
            );

            $merchandise->$to = $merchandise->$to + $detail->quantity;

            $merchandise->save();
        });
    }

    public function isAvailable($detail, $in = 'available')
    {
        return $this->merchandise->where([
            ['product_id', $detail->product_id],
            ['warehouse_id', $detail->warehouse_id],
            [$in, '>=', $detail->quantity],
        ])->exists();
    }

    public function subtract($detail, $from = 'available')
    {
        $merchandise = $this->merchandise->where([
            ['product_id', $detail->product_id],
            ['warehouse_id', $detail->warehouse_id],
            [$from, '>=', $detail->quantity],
        ])->first();

        $merchandise->$from = $merchandise->$from - $detail->quantity;

        $merchandise->save();
    }

    public function transfer($detail)
    {
        DB::transaction(function () use ($detail) {
            $this->subtract($detail);

            $detail->warehouse_id = $detail->to_warehouse_id;

            $this->add($detail);
        });
    }

    public function adjust($detail)
    {
        DB::transaction(function () use ($detail) {
            if ($detail->is_subtract) {
                $this->subtract($detail);
            }

            if (!$detail->is_subtract) {
                $this->add($detail);
            }
        });
    }

    public function reserve($detail)
    {
        DB::transaction(function () use ($detail) {
            $this->subtract($detail);
            $this->add($detail, 'reserve');
        });
    }
}
