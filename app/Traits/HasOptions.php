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
            'Sales Completed',
        ];
    }

    public function getSaleStatusForMovedProducts()
    {
        return [
            'Left Warehouse & Started Shipping',
            'Delivered To Customer',
            'Received Full Payment',
            'Sales Completed',
        ];
    }
}
