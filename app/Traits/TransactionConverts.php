<?php

namespace App\Traits;

use App\Models\Pad;
use App\Models\PadField;
use App\Models\Product;
use App\Models\ProformaInvoice;
use App\Models\Sale;
use App\Models\Warehouse;

trait TransactionConverts
{
    public function convertTo($target, $isRouteForPad)
    {
        $method = str($target)->singular()->studly()->prepend('convertTo')->toString();

        if ($isRouteForPad) {
            $method = 'convertToPad';
        }

        return $this->$method($target);
    }

    public function convertFrom($target, $id)
    {
        $method = str($target)->singular()->studly()->prepend('convertFrom')->toString();

        return $this->$method($this, $id);
    }

    private function convertToGrn($target)
    {
        $data['grn'] = $this
            ->transactionDetails
            ->map(function ($detail) {
                return [
                    'product_id' => Product::firstWhere('id', $detail['product_id'])->id ?? null,
                    'warehouse_id' => Warehouse::firstWhere('id', $detail['warehouse_id'] ?? null)->id ?? null,
                    'quantity' => $detail['quantity'] ?? null,
                    'description' => $detail['description'] ?? null,
                ];
            })->values()->all();

        $data += $this->transactionMasters->toArray();

        return array_filter($data);
    }

    private function convertToSiv($target)
    {
        $data['siv'] = $this
            ->transactionDetails
            ->map(function ($detail) {
                return [
                    'product_id' => Product::firstWhere('id', $detail['product_id'])->id ?? null,
                    'warehouse_id' => Warehouse::firstWhere('id', $detail['warehouse_id'] ?? null)->id ?? null,
                    'quantity' => $detail['quantity'] ?? null,
                    'description' => $detail['description'] ?? null,
                ];
            })->values()->all();

        $data += $this->transactionMasters->toArray();
        $data['received_by'] = $data['received_by'] ?? null;
        $data['delivered_by'] = $data['delivered_by'] ?? null;
        $data['issued_to'] = $data['customer'] ?? null;
        $data['description'] = $data['description'] ?? null;

        return array_filter($data);
    }

    private function convertToSale($target)
    {
        $data['sale'] = $this
            ->transactionDetails
            ->map(function ($detail) {
                return
                    [
                    'product_id' => Product::firstWhere('id', $detail['product_id'])->id ?? null,
                    'unit_price' => $detail['unit_price'] ?? null,
                    'quantity' => $detail['quantity'] ?? null,
                    'description' => $detail['description'] ?? null,
                ];
            })->values()->all();

        $data += $this->transactionMasters->toArray();
        $data['payment_type'] = $data['payment_method'] ?? null;
        $data['cash_received_type'] = 'percent';
        $data['cash_received'] = $data['cash_received'] ?? null;
        $data['due_date'] = $data['credit_due_date'] ?? null;
        $data['description'] = $data['description'] ?? null;

        return array_filter($data);
    }

    private function convertToGdn($target)
    {
        $data['gdn'] = $this
            ->transactionDetails
            ->map(function ($detail) {
                return [
                    'product_id' => Product::firstWhere('id', $detail['product_id'])->id ?? null,
                    'warehouse_id' => Warehouse::firstWhere('id', $detail['warehouse_id'] ?? null)->id ?? null,
                    'unit_price' => $detail['unit_price'] ?? null,
                    'quantity' => $detail['quantity'] ?? null,
                    'description' => $detail['description'] ?? null,
                ];
            })->values()->all();

        $data += $this->transactionMasters->toArray();
        $data['payment_type'] = isset($data['payment_method']) ? ($data['payment_method'] . ' Payment') : null;
        $data['cash_received_type'] = 'percent';
        $data['cash_received'] = $data['cash_received'] ?? null;
        $data['due_date'] = $data['credit_due_date'] ?? null;
        $data['description'] = $data['description'] ?? null;

        return array_filter($data);
    }

    private function convertToProformaInvoice($target)
    {
        $data['proformaInvoice'] = $this
            ->transactionDetails
            ->map(function ($detail) {
                return [
                    'product_id' => Product::firstWhere('id', $detail['product_id'])->id ?? null,
                    'quantity' => $detail['quantity'] ?? null,
                    'unit_price' => $detail['unit_price'] ?? null,
                    'specification' => $detail['specification'] ?? null,
                ];
            })->values()->all();

        $data += $this->transactionMasters->toArray();
        $data['terms'] = $data['terms'] ?? null;

        return array_filter($data);
    }

    private function convertToPad($target)
    {
        $transaction = request()->route('transaction');
        $data = [];
        $master = $this->transactionFields()->masterFields()->pluck('value', 'pad_field_id')->toArray();
        $details = $this->transactionFields()->detailFields()->get()->groupBy('line')->map->pluck('value', 'pad_field_id')->toArray();

        foreach ($master as $key => $value) {
            $padFieldId = Pad::firstWhere('name', $target)->padFields()->masterFields()->firstWhere('label', PadField::find($key)->label)->id ?? null;

            if ($padFieldId != null) {
                $data['master'][$padFieldId] = $value;
            }
        }

        foreach ($details as $index => $detail) {
            foreach ($detail as $key => $value) {
                $padFieldId = Pad::firstWhere('name', $target)->padFields()->detailFields()->firstWhere('label', PadField::find($key)->label)->id ?? null;

                if ($padFieldId != null) {
                    $data['details'][$index][$padFieldId] = $value;
                }
            }
        }

        $data['master'][Pad::firstWhere('name', $target)->padFields()->masterFields()->where('label', 'like', '%' . $transaction->pad->name . '%')->orWhere('label', 'like', '%' . $transaction->pad->abbreviation . '%')->first()->id ?? null] = (string) $transaction->code;
        data_set($data['details'], '*.' . (Pad::firstWhere('name', $target)->padFields()->detailFields()->where('label', 'like', '%' . $transaction->pad->name . '%')->orWhere('label', 'like', '%' . $transaction->pad->abbreviation . '%')->first()->id ?? null), (string) $transaction->code);

        $data['details'] = array_values($data['details']);

        return count($data) ? $data : null;
    }

    private function convertFromProformaInvoice($transaction, $id)
    {
        $data = [];
        $pad = $transaction->pad;
        $proformaInvoice = ProformaInvoice::find($id);

        $data['master'][$pad->padFields()->masterFields()->firstWhere('label', 'Customer')?->id] = $proformaInvoice->customer_id ?? null;
        $data['master'][$pad->padFields()->masterFields()->firstWhere('label', 'Terms')?->id] = $proformaInvoice->terms ?? null;

        $data['details'] = $proformaInvoice->proformaInvoiceDetails->map(function ($detail) use ($pad) {
            return [
                $pad->padFields()->detailFields()->firstWhere('label', 'Product')?->id => $detail['product_id'],
                $pad->padFields()->detailFields()->firstWhere('label', 'Quantity')?->id => $detail['quantity'] ?? null,
                $pad->padFields()->detailFields()->firstWhere('label', 'Unit Price')?->id => $detail['unit_price'] ?? null,
                $pad->padFields()->detailFields()->firstWhere('label', 'Specification')?->id => $detail['specification'] ?? null,
            ];
        })->values()->all();

        return count($data) ? $data : null;
    }

    private function convertFromSale($transaction, $id)
    {
        $data = [];
        $pad = $transaction->pad;
        $sale = Sale::find($id);

        $data['master'][$pad->padFields()->masterFields()->firstWhere('label', 'Customer')?->id] = $sale->customer_id ?? null;
        $data['master'][$pad->padFields()->masterFields()->firstWhere('label', 'Invoice No')?->id] = $sale->code;

        $data['details'] = $sale->saleDetails->map(function ($detail) use ($pad) {
            return [
                $pad->padFields()->detailFields()->firstWhere('label', 'Description')?->id => $detail['product_id'],
                $pad->padFields()->detailFields()->firstWhere('label', 'Item')?->id => $detail['product_id'],
                $pad->padFields()->detailFields()->firstWhere('label', 'Product')?->id => $detail['product_id'],
                $pad->padFields()->detailFields()->firstWhere('label', 'From')?->id => $detail['warehouse_id'] ?? null,
                $pad->padFields()->detailFields()->firstWhere('label', 'Warehouse')?->id => $detail['warehouse_id'] ?? null,
                $pad->padFields()->detailFields()->firstWhere('label', 'Quantity')?->id => $detail['quantity'] ?? null,
                $pad->padFields()->detailFields()->firstWhere('label', 'Unit Price')?->id => $detail['unit_price'] ?? null,
                $pad->padFields()->detailFields()->firstWhere('label', 'Quantity')?->id => $detail['quantity'] ?? null,
            ];
        })->values()->all();

        return count($data) ? $data : null;
    }
}
