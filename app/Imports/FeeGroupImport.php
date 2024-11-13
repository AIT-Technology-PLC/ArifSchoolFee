<?php

namespace App\Imports;

use App\Models\FeeGroup;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class FeeGroupImport implements WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts, OnEachRow
{
    use Importable;

    private $feeGroups;

    public function __construct()
    {
        $this->feeGroups = FeeGroup::all();
    }

    public function onRow(Row $row)
    {
        if ($this->feeGroups->where('name', $row['name'])->where('company_id', userCompany()->id)->count()) {
            return null;
        }

        return FeeGroup::create([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'name' => $row['name'],
            'description' => $row['description'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['name'] = str()->squish($data['name'] ?? '');

        return $data;
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
