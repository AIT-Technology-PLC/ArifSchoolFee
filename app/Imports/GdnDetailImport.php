<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GdnDetailImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        //
    }
}