<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class NextReferenceNumService
{
    public static function table($table, $column = 'code')
    {
        return DB::table($table)
            ->where('company_id', userCompany()->id)
            ->where('warehouse_id', auth()->user()->warehouse_id)
            ->max($column) + 1;
    }
}
