<?php

namespace App\Imports;

use App\Models\CostUpdateDetail;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CostUpdateImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $costUpdate;

    private $products;

    private $productCategories;

    public function __construct($costUpdate)
    {
        $this->costUpdate = $costUpdate;

        $this->products = Product::inventoryType()->get(['id', 'name', 'code', 'product_category_id']);

        $this->productCategories = ProductCategory::all(['id', 'name']);
    }

    public function model(array $row)
    {
        return new CostUpdateDetail([
            'cost_update_id' => $this->costUpdate->id,
            'product_id' => $this->products
                ->where('name', $row['product_name'])
                ->when(!is_null($row['product_code']) && $row['product_code'] != '', fn($q) => $q->where('code', $row['product_code']))
                ->when(
                    !is_null($row['product_category_name']) && $row['product_category_name'] != '',
                    fn($q) => $q->where('product_category_id', $this->productCategories->firstWhere('name', $row['product_category_name'])->id)
                )
                ->first()
                ->id,
            'average_unit_cost' => $row['average_unit_cost'],
            'fifo_unit_cost' => $row['fifo_unit_cost'] ?? null,
            'lifo_unit_cost' => $row['lifo_unit_cost'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', Rule::in($this->products->pluck('name'))],
            'product_category_name' => ['nullable', 'string', 'max:255', Rule::in($this->productCategories->pluck('name'))],
            'product_code' => ['nullable', 'string', 'max:255', Rule::in($this->products->pluck('code'))],
            'average_unit_cost' => ['required', 'numeric', 'gte:0'],
            'fifo_unit_cost' => ['nullable', 'numeric', 'gte:0'],
            'lifo_unit_cost' => ['nullable', 'numeric', 'gte:0'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['product_name'] = str()->squish($data['product_name'] ?? '');
        $data['product_category_name'] = str()->squish($data['product_category_name'] ?? '');
        $data['product_code'] = str()->squish($data['product_code'] ?? '');

        return $data;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }
}
