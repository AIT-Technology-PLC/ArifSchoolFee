<?php

namespace App\Imports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BrandImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    public function __construct()
    {
        $this->brands = Brand::all();
    }

    public function model(array $row)
    {
        if ($this->brands->where('name', $row['brand_name'])->count()) {
            return null;
        }

        $brand = new Brand([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'name' => $row['brand_name'],
            'description' => $row['description'] ?? null,
        ]);

        $this->brands->push($brand);

        return $brand;
    }

    public function rules(): array
    {
        return [
            'brand_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['brand_name'] = str()->squish($data['brand_name'] ?? '');

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
