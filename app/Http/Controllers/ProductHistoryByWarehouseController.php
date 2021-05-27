<?php

namespace App\Http\Controllers;

use App\Models\GdnDetail;
use App\Models\GrnDetail;
use App\Models\Product;
use App\Models\TransferDetail;
use App\Models\Warehouse;
use Illuminate\Support\Str;

class ProductHistoryByWarehouseController extends Controller
{
    public function __invoke(Warehouse $warehouse, Product $product)
    {
        $this->authorize('view', $warehouse);

        $this->authorize('view', $product);

        $collection = collect();

        $grnDetails = GrnDetail::where([
            ['warehouse_id', $warehouse->id],
            ['product_id', $product->id],
        ])
            ->whereIn('grn_id', function ($query) {
                $query->select('id')
                    ->from('grns')
                    ->where([
                        ['company_id', userCompany()->id],
                        ['status', 'Added To Inventory'],
                    ]);
            })
            ->get()
            ->load(['grn.supplier', 'product']);

        $transferDetails = TransferDetail::where('product_id', $product->id)
            ->where(function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse->id)
                    ->orWhere('to_warehouse_id', $warehouse->id);
            })
            ->whereIn('transfer_id', function ($query) {
                $query->select('id')
                    ->from('transfers')
                    ->where([
                        ['company_id', userCompany()->id],
                        ['status', 'Transferred'],
                    ]);
            })
            ->get()
            ->load(['transfer', 'product', 'toWarehouse', 'warehouse']);

        $gdnDetails = GdnDetail::where([
            ['warehouse_id', $warehouse->id],
            ['product_id', $product->id],
        ])
            ->whereIn('gdn_id', function ($query) {
                $query->select('id')
                    ->from('gdns')
                    ->where([
                        ['company_id', userCompany()->id],
                        ['status', 'Subtracted From Inventory'],
                    ]);
            })
            ->get()
            ->load(['gdn.customer', 'product']);

        $grnDetails->map(function ($grnDetail) use ($collection) {
            $collection->push([
                'type' => 'Grn',
                'code' => $grnDetail->grn->code,
                'date' => $grnDetail->grn->updated_at,
                'quantity' => $grnDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $grnDetail->product->unit_of_measurement,
                'details' => Str::of($grnDetail->grn->supplier->name ?? 'Unknown')->prepend('Purchased from '),
                'function' => 'add',
            ]);
        });

        $gdnDetails->map(function ($gdnDetail) use ($collection) {
            $collection->push([
                'type' => 'Gdn',
                'code' => $gdnDetail->gdn->code,
                'date' => $gdnDetail->gdn->updated_at,
                'quantity' => $gdnDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $gdnDetail->product->unit_of_measurement,
                'details' => Str::of($gdnDetail->gdn->customer->name ?? 'Unknown')->prepend('Submitted to '),
                'function' => 'subtract',
            ]);
        });

        $transferDetails->map(function ($transferDetail) use ($collection, $warehouse) {
            $collection->push([
                'type' => 'Transfer',
                'code' => $transferDetail->transfer->code,
                'date' => $transferDetail->transfer->updated_at,
                'quantity' => $transferDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $transferDetail->product->unit_of_measurement,
                'function' => $transferDetail->warehouse_id == $warehouse->id ? 'subtract' : 'add',

                'details' => $transferDetail->warehouse_id == $warehouse->id
                ? Str::of('Transferred')->append(...[' to ', $transferDetail->toWarehouse->name])
                : Str::of('Transferred')->append(...[' from ', $transferDetail->warehouse->name]),
            ]);
        });

        for ($i = 0; $i < count($collection); $i++) {
            $method = $collection[$i]['function'];

            if ($i == 0) {
                $collection[$i] = $this->$method($collection[$i]);
                continue;
            }

            $collection[$i] = $this->$method($collection[$i], $collection[$i - 1]['balance']);
        }

        echo $collection;
    }

    public function subtract($item, $previousBalance = 0)
    {
        $item['balance'] = $previousBalance - $item['quantity'];

        return $item;
    }

    public function add($item, $previousBalance = 0)
    {
        $item['balance'] = $previousBalance + $item['quantity'];

        return $item;
    }
}
