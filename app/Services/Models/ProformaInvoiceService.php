<?php

namespace App\Services\Models;

use App\Models\Gdn;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class ProformaInvoiceService
{
    public function convertToGdn($proformaInvoice, $request)
    {
        if (!$proformaInvoice->isConverted()) {
            return [false, 'This Proforma Invoice is not confirmed yet.', ''];
        }

        if (!is_null($proformaInvoice->proformaInvoiceable_id)) {
            return [false, 'This Proforma Invoice is already converted.', ''];
        }

        if ($proformaInvoice->isClosed()) {
            return [false, 'This Proforma Invoice is closed.', ''];
        }

        $gdnDetails = collect($proformaInvoice->proformaInvoiceDetails)
            ->map(function ($item) use ($request) {
                $item['warehouse_id'] = $request['warehouse_id'];
                $item['unit_price'] = $item['unit_price_after_discount'];

                return $item;
            });

        $gdn = DB::transaction(function () use ($request, $proformaInvoice, $gdnDetails) {
            $gdn = Gdn::create([
                'customer_id' => $request['customer_id'] ?? null,
                'contact_id' => $proformaInvoice->contact_id ?? null,
                'code' => nextReferenceNumber('gdns'),
                'payment_type' => $request['payment_type'],
                'cash_received_type' => $request['cash_received_type'],
                'cash_received' => $request['cash_received'],
                'description' => $proformaInvoice->description ?? '',
                'issued_on' => now(),
                'due_date' => $request['due_date'],
            ]);

            $gdn->gdnDetails()->createMany(
                $gdnDetails
                    ->toArray()
            );

            $proformaInvoice->associate($gdn);

            return $gdn;
        });

        return [true, '', $gdn];
    }

    public function close($proformaInvoice)
    {
        if (!$proformaInvoice->isConverted()) {
            return [false, 'This Proforma Invoice is not confirmed yet.'];
        }

        if ($proformaInvoice->isClosed()) {
            return [false, 'This Proforma Invoice is already closed.'];
        }

        $proformaInvoice->close();

        return [true, ''];
    }

    public function convertToSale($proformaInvoice, $request)
    {
        if (!$proformaInvoice->isConverted()) {
            return [false, 'This Proforma Invoice is not confirmed yet.', ''];
        }

        if (!is_null($proformaInvoice->proformaInvoiceable_id)) {
            return [false, 'This Proforma Invoice is already converted.', ''];
        }

        if ($proformaInvoice->isClosed()) {
            return [false, 'This Proforma Invoice is closed.', ''];
        }

        $saleDetails = collect($proformaInvoice->proformaInvoiceDetails)
            ->map(function ($item) use ($request) {
                $item['warehouse_id'] = $request['warehouse_id'];
                $item['unit_price'] = $item['unit_price_after_discount'];
                unset($item['discount']);

                return $item;
            });

        $sale = DB::transaction(function () use ($request, $proformaInvoice, $saleDetails) {
            $sale = Sale::create([
                'customer_id' => $request['customer_id'] ?? null,
                'contact_id' => $proformaInvoice->contact_id ?? null,
                'code' => nextReferenceNumber('sales'),
                'payment_type' => $request['payment_type'],
                'cash_received_type' => $request['cash_received_type'],
                'cash_received' => $request['cash_received'],
                'description' => $proformaInvoice->description ?? '',
                'issued_on' => now(),
                'due_date' => $request['due_date'],
            ]);

            $sale->saleDetails()->createMany(
                $saleDetails
                    ->toArray()
            );

            $proformaInvoice->associate($sale);

            return $sale;
        });

        return [true, '', $sale];
    }
}
