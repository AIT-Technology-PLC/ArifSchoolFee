<?php

namespace App\Imports;

use App\Models\SchoolClass;
use App\Models\Section;
use App\Rules\MustBelongToCompany;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class SchoolClassImport implements WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts, OnEachRow
{
    use Importable;

    private $schoolClasses;

    private $section;

    public function __construct()
    {
        $this->schoolClasses = SchoolClass::all();
    }

    public function onRow(Row $row)
    {
        if ($this->schoolClasses->where('name', $row['name'])->where('company_id', userCompany()->id)->count()) {
            return null;
        }

        $this->section = Section::firstWhere('name', $row['section_name']);

        $schoolClass = SchoolClass::create([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'name' => $row['name'],
        ]);

        $schoolClass->sections()->sync($this->section->id);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:10'],
            'section_name' => ['required', 'integer', new MustBelongToCompany('sections', 'name')],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['name'] = str()->squish($data['name'] ?? '');
        $data['section_name'] = str()->squish($data['section_name'] ?? '');

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
