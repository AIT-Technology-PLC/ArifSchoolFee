<?php

namespace App\Imports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class DepartmentImport implements WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts, OnEachRow
{
    use Importable;

    private $departments;

    public function __construct()
    {
        $this->departments = Department::all();
    }

    public function onRow(Row $row)
    {
        if ($this->departments->where('name', $row['name'])->where('company_id', userCompany()->id)->count()) {
            return null;
        }

        return Department::create([
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
