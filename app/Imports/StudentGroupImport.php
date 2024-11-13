<?php

namespace App\Imports;

use App\Models\StudentGroup;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class StudentGroupImport implements WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts, OnEachRow
{
    use Importable;

    private $studentGroups;

    public function __construct()
    {
        $this->studentGroups = StudentGroup::all();
    }

    public function onRow(Row $row)
    {
        if ($this->studentGroups->where('name', $row['name'])->where('company_id', userCompany()->id)->count()) {
            return null;
        }

        return StudentGroup::create([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'name' => $row['name'],
            'description' => $row['description'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:100'],
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
