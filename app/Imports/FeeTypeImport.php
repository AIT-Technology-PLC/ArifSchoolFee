<?php

namespace App\Imports;

use App\Models\FeeGroup;
use App\Models\FeeType;
use App\Rules\MustBelongToCompany;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class FeeTypeImport implements WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts, OnEachRow
{
    use Importable;

    private $feeTypes;

    private $feeGroup;

    public function __construct()
    {
        $this->feeTypes = FeeType::all();
    }

    public function onRow(Row $row)
    {
        $this->feeGroup = FeeGroup::firstWhere('name', $row['fee_group_name']);

        if ($this->feeTypes->where('name', $row['name'])->where('fee_group_id', $this->feeGroup->id)->where('company_id', userCompany()->id)->count()) {
            return null;
        }

        return FeeType::create([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'fee_group_id' => $this->feeGroup->id,
            'name' => $row['name'],
            'description' => $row['description'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'fee_group_name' => ['required', 'string', new MustBelongToCompany('fee_groups', 'name')],           
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
