<?php

namespace App\Imports;

use App\Models\TenderStatus;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TenderStatusImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $tenderStatuses;

    public function __construct()
    {
        $this->tenderStatuses = TenderStatus::all();
    }

    public function model(array $row)
    {
        if ($this->tenderStatuses->where('status', $row['status'])->count()) {
            return null;
        }

        return new TenderStatus([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
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

    public function batchSize(): int
    {
        return 50;
    }
}
