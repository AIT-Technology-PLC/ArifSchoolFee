<?php

namespace App\Traits;

trait HasOptions
{

    public function getInventoryTypes()
    {
        $isCompanyPremiumMember = auth()->user()->employee->company->isCompanyPremiumMember();

        if ($isCompanyPremiumMember) {
            return ['Manufactured Product', 'Raw Material', 'MRO Item', 'Merchandise Product'];
        }

        return ['Merchandise Product'];
    }

    public function getMeasurementUnits()
    {
        return ['Metric Ton', 'Quintal', 'Piece', 'Kilogram', 'Box'];
    }

    public function getShippingLines()
    {
        return ['DHL', 'MAERSEK'];
    }

    public function getPurchaseStatuses()
    {
        return [
            'New Order',
            'Received Quotation/PI',
            'Negotiating Quote',
            'Approved Quotation',
            'Made Partial Payment',
            'Started Shipping',
            'Delivered',
            'Made Full Payment',

            # Status will change to "Added To Inventory" by button
            // 'Added To Inventory',   
        ];
    }

    public function getSaleStatuses()
    {
        return [
            'New Order',
            'Sent Quotation/PI',
            'Negotiating Quote',
            'Approved Quotation',
            'Received Partial Payment',

            # Status will change to the following statuses if only product is available in stock otherwise 
            # will be advised to create new Purchase Order

            // 'Left Warehouse & Started Shipping',
            // 'Delivered To Customer',
            // 'Received Full Payment',
        ];
    }
}
