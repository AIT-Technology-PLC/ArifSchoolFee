<?php

namespace App\Services\Models;

use App\Models\Gdn;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class ProformaInvoiceService
{
    public function convertToGdn($proformaInvoice, $data)
    {
        if ($proformaInvoice->isCancelled()) {
            return [false, 'This Proforma Invoice is cancelled.', ''];
        }

        if (!$proformaInvoice->isConverted()) {
            return [false, 'This Proforma Invoice is not confirmed yet.', ''];
        }

        if ($proformaInvoice->isAssociated()) {
            return [false, 'This Proforma Invoice is already converted.', ''];
        }

        if ($proformaInvoice->isClosed()) {
            return [false, 'This Proforma Invoice is closed.', ''];
        }

        $details = collect($proformaInvoice->proformaInvoiceDetails)
            ->map(function ($item) use ($data) {
                $item['warehouse_id'] = $data['warehouse_id'];
                $item['unit_price'] = $item['unit_price_after_discount'];

                return $item;
            });

        $gdn = DB::transaction(function () use ($data, $proformaInvoice, $details) {
            $gdn = Gdn::create([
                'customer_id' => $data['customer_id'] ?? null,
                'contact_id' => $proformaInvoice->contact_id ?? null,
                'code' => nextReferenceNumber('gdns'),
                'payment_type' => $data['payment_type'],
                'cash_received_type' => $data['cash_received_type'],
                'cash_received' => $data['cash_received'],
                'description' => $proformaInvoice->description ?? '',
                'issued_on' => now(),
                'due_date' => $data['due_date'],
            ]);

            $gdn->gdnDetails()->createMany($details->toArray());

            $proformaInvoice->proformaInvoiceable()->associate($gdn)->save();

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

    public function convertToSale($proformaInvoice, $data)
    {
        if ($proformaInvoice->isCancelled()) {
            return [false, 'This Proforma Invoice is cancelled.', ''];
        }

        if (!$proformaInvoice->isConverted()) {
            return [false, 'This Proforma Invoice is not confirmed yet.', ''];
        }

        if ($proformaInvoice->isAssociated()) {
            return [false, 'This Proforma Invoice is already converted.', ''];
        }

        if ($proformaInvoice->isClosed()) {
            return [false, 'This Proforma Invoice is closed.', ''];
        }

        $details = collect($proformaInvoice->proformaInvoiceDetails)
            ->map(function ($item) use ($data) {
                $item['warehouse_id'] = $data['warehouse_id'];
                $item['unit_price'] = $item['unit_price_after_discount'];
                unset($item['discount']);

                return $item;
            });

        $sale = DB::transaction(function () use ($data, $proformaInvoice, $details) {
            $sale = Sale::create([
                'customer_id' => $data['customer_id'] ?? null,
                'contact_id' => $proformaInvoice->contact_id ?? null,
                'code' => nextReferenceNumber('sales'),
                'payment_type' => $data['payment_type'],
                'cash_received_type' => $data['cash_received_type'],
                'cash_received' => $data['cash_received'],
                'description' => $proformaInvoice->description ?? '',
                'issued_on' => now(),
                'due_date' => $data['due_date'],
            ]);

            $sale->saleDetails()->createMany($details->toArray());

            $proformaInvoice->proformaInvoiceable()->associate($sale)->save();

            return $sale;
        });

        return [true, '', $sale];
    }
}
