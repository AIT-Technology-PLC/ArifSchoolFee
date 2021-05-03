<?php

namespace App\Traits;

trait HasOptions
{

    public function getInventoryTypes()
    {
        $isCompanyPremiumMember = userCompany()->isCompanyPremiumMember();

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
}
