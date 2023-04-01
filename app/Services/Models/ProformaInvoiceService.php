<?php

namespace App\Services\Models;

class ProformaInvoiceService
{
    public function convertToGdn($proformaInvoice)
    {
        if (!$proformaInvoice->isConverted()) {
            return [false, 'This Proforma Invoice is not confirmed yet.', ''];
        }

        if ($proformaInvoice->isClosed()) {
            return [false, 'This Proforma Invoice is closed.', ''];
        }

        $proformaInvoiceDetails = collect($proformaInvoice->proformaInvoiceDetails->toArray())
            ->map(function ($item) {
                $item['unit_price'] = $item['originalUnitPrice'];

                return $item;
            });

        $data = [
            'customer_id' => $proformaInvoice->customer_id ?? '',
            'contact_id' => $proformaInvoice->contact_id ?? '',
            'discount' => $proformaInvoice->discount,
            'gdn' => $proformaInvoiceDetails,
        ];

        return [true, '', $data];
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

    public function convertToSale($proformaInvoice)
    {
        if (!$proformaInvoice->isConverted()) {
            return [false, 'This Proforma Invoice is not confirmed yet.', ''];
        }

        if ($proformaInvoice->isClosed()) {
            return [false, 'This Proforma Invoice is closed.', ''];
        }

        $proformaInvoiceDetails = collect($proformaInvoice->proformaInvoiceDetails)
            ->map(function ($item) {
                $item['unit_price'] = $item['unit_price_after_discount'];
                unset($item['discount']);

                return $item;
            });

        $data = [
            'customer_id' => $proformaInvoice->customer_id ?? '',
            'contact_id' => $proformaInvoice->contact_id ?? '',
            'sale' => $proformaInvoiceDetails,
        ];

        return [true, '', $data];
    }
}