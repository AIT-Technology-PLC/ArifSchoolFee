<?php

namespace App\Imports;

use App\Models\TenderStatus;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TenderStatusImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    use Importable;

    public function model(array $row)
    {
        if (TenderStatus::where('status', $row['status'])->exists()) {
            return null;
        }

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

    public function chunkSize(): int
    {
        return 50;
    }
}