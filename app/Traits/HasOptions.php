<?php

namespace App\Traits;

trait HasOptions
{

    public function getInventoryTypes()
    {
        return ['Merchandise Inventory'];
    }

    public function getMeasurementUnits()
    {
        return ['Metric Ton', 'Quintal', 'Piece', 'Kilogram', 'Box', 'Meter', 'Centimeter'];
    }
}
