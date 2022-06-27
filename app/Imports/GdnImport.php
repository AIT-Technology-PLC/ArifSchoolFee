<?php

namespace App\Imports;

use App\Imports\GdnDetailImport;
use App\Imports\GdnMasterImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GdnImport implements WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            'master' => new GdnMasterImport(),
            'detail' => new GdnDetailImport(),
        ];
    }
}