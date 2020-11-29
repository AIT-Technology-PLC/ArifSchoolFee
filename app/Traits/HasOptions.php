<?php

namespace App\Traits;

trait HasOptions
{

    public function getInventoryTypes()
    {
        return ['Manufactured Product', 'Merchandise Product', 'Raw Material', 'MRO Item'];
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
            'Added To Inventory',
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
            'Left Warehouse & Started Shipping',
            'Delivered To Customer',
            'Received Full Payment',
        ];
    }
}
