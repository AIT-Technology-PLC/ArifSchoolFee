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
                $item['discount'] = $item['discount'] * 100;

                return $item;
            });

        $data = [
            'customer_id' => $proformaInvoice->customer_id ?? '',
            'discount' => $proformaInvoice->discount * 100,
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
}
