<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeCompensationImport implements WithHeadingRow, ToModel
{
    use Importable;

    public function model(array $row)
    {}
}
