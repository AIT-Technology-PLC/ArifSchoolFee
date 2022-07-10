<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\GrnDetail;
use Illuminate\Support\Str;

class GrnDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse;

    private $product;

    private $history;

    private function get()
    {
        $this->history = (new GrnDetail())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );
    }

    private function format()
    {
        $this->history->transform(function ($grnDetail) {
            return [
                'type' => 'GRN',
                'url' => '/grns/'.$grnDetail->grn_id,
                'code' => $grnDetail->grn->code,
                'date' => $grnDetail->grn->issued_on,
                'quantity' => $grnDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $this->product->unit_of_measurement,
                'details' => Str::of($grnDetail->grn->supplier->company_name ?? 'Unknown')->prepend('Purchased from '),
                'function' => 'add',
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
