<?php

namespace App\Factory;

use Illuminate\Support\Str;

class InventoryDatatableFactory
{
    public static function make($type)
    {
        $datatable = (string) Str::of($type)
            ->studly()
            ->remove('-')
            ->prepend('App\\DataTables\\')
            ->append('InventoryDatatable')
            ->studly();

        return new $datatable;
    }
}
