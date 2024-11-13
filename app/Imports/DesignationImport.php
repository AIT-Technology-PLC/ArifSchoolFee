<?php

namespace App\Imports;

use App\Models\Designation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class DesignationImport implements WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts, OnEachRow
{
    use Importable;

    private $designations;

    public function __construct()
    {
        $this->designations = Designation::all();
    }

    public function onRow(Row $row)
    {
        if ($this->designations->where('name', $row['name'])->where('company_id', userCompany()->id)->count()) {
            return null;
        }

        return Designation::create([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'name' => $row['name'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:30'],
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
