<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\SivDetail;
use Illuminate\Support\Str;

class SivDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse;

    private $product;

    private $history;

    private function get()
    {
        $this->history = (new SivDetail())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );
    }

    private function format()
    {
        $this->history->transform(function ($sivDetail) {
            return [
                'type' => 'SIV',
                'url' => '/sivs/' . $sivDetail->siv_id,
                'code' => $sivDetail->siv->code,
                'date' => $sivDetail->siv->issued_on,
                'quantity' => $sivDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $this->product->unit_of_measurement,
                'details' => Str::of($sivDetail->siv->issued_to ?? 'Unknown')->prepend('Submitted to '),
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
