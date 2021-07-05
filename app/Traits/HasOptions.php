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
        $collection = collect([
            'Metric Ton', 
            'Quintal', 
            'Piece', 
            'Kilogram', 
            'Box', 
            'Meter', 
            'Centimeter',
            'Square Meter',
            'Packet',
            'Liter'
        ]);

        return $collection->sort();
    }
}
