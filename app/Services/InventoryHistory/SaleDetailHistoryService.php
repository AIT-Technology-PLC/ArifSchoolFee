<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\SaleDetail;
use Illuminate\Support\Str;

class SaleDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse;

    private $product;

    private $history;

    private function get()
    {
        $this->history = (new SaleDetail())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );
    }

    private function format()
    {
        $this->history->transform(function ($saleDetail) {
            return [
                'type' => 'INVOICE',
                'url' => '/sales/' . $saleDetail->sale_id,
                'code' => $saleDetail->sale->code,
                'date' => $saleDetail->sale->issued_on,
                'quantity' => $saleDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $this->product->unit_of_measurement,
                'details' => Str::of($saleDetail->sale->customer->company_name ?? 'Unknown')->prepend('Submitted to '),
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
