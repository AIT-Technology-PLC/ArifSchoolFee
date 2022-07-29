<?php

namespace App\Factory;

class InventoryDatatableFactory
{
    public static function make($type)
    {
        $datatable = str($type)
            ->studly()
            ->remove('-')
            ->prepend('App\\DataTables\\')
            ->append('InventoryDatatable')
            ->studly()
            ->toString();

        return new $datatable;
    }
}
