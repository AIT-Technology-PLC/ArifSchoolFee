<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\ReservationDetail;
use Illuminate\Support\Str;

class ReservationDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse, $product, $history;

    private function get()
    {
        $this->history = (new ReservationDetail())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );
    }

    private function format()
    {
        $this->history->transform(function ($reservationDetail) {
            return [
                'type' => 'RESERVED',
                'code' => $reservationDetail->reservation->code,
                'date' => $reservationDetail->reservation->issued_on,
                'quantity' => $reservationDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $this->product->unit_of_measurement,
                'details' => Str::of($reservationDetail->reservation->customer->company_name ?? 'Unknown')->prepend('Reserved for '),
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
