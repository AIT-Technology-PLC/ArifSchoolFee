<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\GdnDetail;
use Illuminate\Support\Str;

class GdnDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse, $product, $history;

    private function get()
    {
        $this->history = (new GdnDetail())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );
    }

    private function format()
    {
        $this->history->transform(function ($gdnDetail) {
            return [
                'type' => 'DO',
                'url' => '/gdns/' . $gdnDetail->gdn_id,
                'code' => $gdnDetail->gdn->code,
                'date' => $gdnDetail->gdn->issued_on,
                'quantity' => $gdnDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $this->product->unit_of_measurement,
                'details' => Str::of($gdnDetail->gdn->customer->company_name ?? 'Unknown')->prepend('Submitted to '),
                'function' => 'subtract',
            ];
        });
    }

    public function retrieve($warehouse, $product)
    {
        $this->product = $product;

        $this->warehouse = $warehouse;

        $this->get();

        $this->format();

        return $this->history;
    }
}