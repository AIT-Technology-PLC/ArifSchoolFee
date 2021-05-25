<?php

namespace App\Traits;

trait HasOptions
{

    public function getInventoryTypes()
    {
        $isCompanyPremiumMember = userCompany()->isCompanyPremiumMember();

        if ($isCompanyPremiumMember) {
            return ['Manufactured Inventory', 'Raw Material Inventory', 'Merchandise Inventory'];
        }

        return ['Merchandise Inventory'];
    }

    public function getMeasurementUnits()
    {
        return ['Metric Ton', 'Quintal', 'Piece', 'Kilogram', 'Box', 'Meter', 'Centimeter'];
    }

    public function getShippingLines()
    {
        return ['DHL', 'MAERSEK'];
    }
}
