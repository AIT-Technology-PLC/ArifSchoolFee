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

        $this->format(
            $grnDetails,
            $grnDetails->first()->grn,
            Str::of('Received from ')->append($grnDetails->first()->grn->supplier->company_name ?? 'Unknown'),
            'add'
        );

        $this->format(
            $gdnDetails,
            $gdnDetails->first()->gdn,
            Str::of('Submitted to ')->append($gdnDetails->first()->gdn->customer->company_name ?? 'Unknown'),
            'subtract'
        );

        $this->format(
            $transferDetails,
            $transferDetails->first()->transfer,
            null,
            null
        );

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

    private function format($details, $parent, $message = null, $function = null)
    {
        $details->map(function ($detail) use ($parent, $message, $function) {
            $this->history->push([
                'type' => Str::of($parent->getTable())->replaceLast('s', '')->upper(),
                'code' => $parent->code,
                'date' => $parent->issued_on,
                'quantity' => $detail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $detail->product->unit_of_measurement,

                'details' => $message ??
                ($detail->warehouse_id == $this->warehouse->id
                    ? Str::of('Transferred')->append(...[' to ', $detail->toWarehouse->name])
                    : Str::of('Transferred')->append(...[' from ', $detail->warehouse->name])),

                'function' => $function ?? ($detail->warehouse_id == $this->warehouse->id ? 'subtract' : 'add'),
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
