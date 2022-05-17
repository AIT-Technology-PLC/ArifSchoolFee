<?php

namespace App\Imports;

use App\Models\TenderStatus;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TenderStatusImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new TenderStatus([
            'status' => $row['status'],
        ]);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string'],
        ];
    }
}