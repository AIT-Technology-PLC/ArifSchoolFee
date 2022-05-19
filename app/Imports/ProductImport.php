<?php

namespace App\Imports;

use App\Models\ProductCategory;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class ProductImport implements WithHeadingRow, OnEachRow, WithValidation
{
    use Importable;

    public function onRow(Row $row)
    {
        $productCategory = ProductCategory::firstOrCreate([
            'name' => $row['product_category_name'],
        ]);

        $productCategory->products()->firstOrCreate(
            [
                'name' => $row['name'],
                'product_category_id' => $productCategory->id,
                'company_id' => userCompany()->id,
            ],
            [
                'type' => $row['type'],
                'code' => $row['code'] ?? '',
                'unit_of_measurement' => $row['unit_of_measurement'],
                'min_on_hand' => $row['min_on_hand'] ?? 0.00,
            ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255'],
            'unit_of_measurement' => ['required', 'string', 'max:255'],
            'min_on_hand' => ['nullable', 'numeric'],
            'product_category_name' => ['required', 'string', 'max:255'],
        ];
    }
}