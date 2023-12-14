<?php

namespace App\Imports;

use App\Models\GrnDetail;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Warehouse;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GrnImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $grn;

    private $warehouses;

    private $products;

    private $productCategories;

    public function __construct($grn)
    {
        $this->grn = $grn;

        $this->products = Product::inventoryType()->get(['id', 'name', 'code', 'product_category_id']);

        $this->productCategories = ProductCategory::all(['id', 'name']);

        $this->warehouses = Warehouse::all(['id', 'name']);
    }

    public function model(array $row)
    {
        $product = Product::ByNameCodeAndCategory($row['product_name'], $row['product_code'], $row['product_category_name']);

        return new GrnDetail([
            'grn_id' => $this->grn->id,
            'product_id' => $product->id,
            'warehouse_id' => $this->warehouses->firstWhere('name', $row['warehouse_name'])->id,
            'quantity' => $row['quantity'] ?? 0.00,
            'unit_cost' => $row['unit_cost'] ?? 0.00,
            'description' => $row['description'] ?? '',
            'batch_no' => $row['batch_no'] ?? null,
            'expires_on' => $row['expires_on'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', Rule::in($this->products->pluck('name'))],
            'product_category_name' => ['nullable', 'string', 'max:255', Rule::in($this->productCategories->pluck('name'))],
            'product_code' => ['nullable', 'string', 'max:255', Rule::in($this->products->pluck('code'))],
            'warehouse_name' => ['required', 'string', Rule::in($this->warehouses->pluck('name'))],
            'quantity' => ['nullable', 'numeric', 'gt:0'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'batch_no' => ['nullable', 'string'],
            'expires_on' => ['nullable', 'date'],
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
        return 50;
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            collect($validator->getData())
                ->filter(fn($row) => is_null(Product::ByNameCodeAndCategory($row['product_name'], $row['product_code'], $row['product_category_name'])))
                ->keys()
                ->chunk(50)
                ->each
                ->each(fn($key) => $validator->errors()->add($key, 'Product name by product code or vice versa is not registered.'));
        });
    }
}
