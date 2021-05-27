<?php

namespace App\Services;

use App\Models\GdnDetail;
use App\Models\GrnDetail;
use App\Models\Product;
use App\Models\TransferDetail;
use App\Models\Warehouse;
use Illuminate\Support\Str;

class ProductMovementHistoryInWarehouseService
{
    public function __construct(Warehouse $warehouse, Product $product)
    {
        $this->warehouse = $warehouse;

        $this->product = $product;

        $this->history = collect();
    }

    public function history()
    {
        $grnDetails = (new GrnDetail())->getByWarehouseAndProduct($this->warehouse, $this->product);

        $transferDetails = (new TransferDetail())->getByWarehouseAndProduct($this->warehouse, $this->product);

        $gdnDetails = (new GdnDetail())->getByWarehouseAndProduct($this->warehouse, $this->product);

        $this->formatGrn($grnDetails);

        $this->formatTransfer($transferDetails);

        $this->formatGdn($gdnDetails);

        $this->history = $this->history->sortBy('date')->values()->all();

        for ($i = 0; $i < count($this->history); $i++) {
            $method = $this->history[$i]['function'];

            if ($i == 0) {
                $this->history[$i] = $this->$method($this->history[$i]);
                continue;
            }

            $this->history[$i] = $this->$method($this->history[$i], $this->history[$i - 1]['balance']);
        }

        return $this->history;
    }

    private function formatGrn($grnDetails)
    {
        $grnDetails->map(function ($grnDetail) {
            $this->history->push([
                'type' => 'GRN',
                'code' => $grnDetail->grn->code,
                'date' => $grnDetail->grn->issued_on,
                'quantity' => $grnDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $grnDetail->product->unit_of_measurement,
                'details' => Str::of($grnDetail->grn->supplier->company_name ?? 'Unknown')->prepend('Purchased from '),
                'function' => 'add',
            ]);
        });
    }

    private function formatTransfer($transferDetails)
    {
        $transferDetails->map(function ($transferDetail) {
            $this->history->push([
                'type' => 'TRANSFER',
                'code' => $transferDetail->transfer->code,
                'date' => $transferDetail->transfer->issued_on,
                'quantity' => $transferDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $transferDetail->product->unit_of_measurement,

                'details' => $transferDetail->warehouse_id == $this->warehouse->id ?
                Str::of('Transferred')->append(...[' to ', $transferDetail->toWarehouse->name]) :
                Str::of('Transferred')->append(...[' from ', $transferDetail->warehouse->name]),

                'function' => $transferDetail->warehouse_id == $this->warehouse->id ? 'subtract' : 'add',
            ]);
        });
    }

    private function formatGdn($gdnDetails)
    {
        $gdnDetails->map(function ($gdnDetail) {
            $this->history->push([
                'type' => 'GDN',
                'code' => $gdnDetail->gdn->code,
                'date' => $gdnDetail->gdn->issued_on,
                'quantity' => $gdnDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $gdnDetail->product->unit_of_measurement,
                'details' => Str::of($gdnDetail->gdn->customer->company_name ?? 'Unknown')->prepend('Submitted to '),
                'function' => 'subtract',
            ]);
        });
    }

    private function subtract($item, $previousBalance = 0)
    {
        $item['balance'] = $previousBalance - $item['quantity'];

        return $item;
    }

    private function add($item, $previousBalance = 0)
    {
        $item['balance'] = $previousBalance + $item['quantity'];

        return $item;
    }
}
