<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class GrnImport implements WithHeadingRow, OnEachRow, WithValidation
{
    use Importable;

    public function onRow(Row $row)
    {

    }

    public function rules(): array
    {
        return [

        ];
    }
}
