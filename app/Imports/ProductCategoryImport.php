<?php

namespace App\Imports;

use App\Models\ProductCategory;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductCategoryImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    public function __construct()
    {
        $this->productCategories = ProductCategory::all();
    }

    public function model(array $row)
    {
        if ($this->productCategories->where('name', $row['product_category_name'])->count()) {
            return null;
        }

        $productCategory = new ProductCategory([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'name' => $row['product_category_name'],
        ]);

        $this->productCategories->push($productCategory);

        return $productCategory;
    }

    public function rules(): array
    {
        return [
            'product_category_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['product_category_name'] = str()->squish($data['product_category_name'] ?? '');

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
