<?php

namespace App\Imports;

use App\Models\BillOfMaterial;
use App\Models\BillOfMaterialDetail;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BillOfMaterialImport implements WithHeadingRow, ToModel, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $products;

    private $billOfMaterial;

    private $productCategories;

    public function __construct($billOfMaterial)
    {
        $this->products = Product::rawMaterial()->get();

        $this->productCategories = ProductCategory::all(['id', 'name']);

        $this->billOfMaterial = $billOfMaterial;
    }

    public function model(array $row)
    {
        $product = Product::ByNameCodeAndCategory($row['product_name'], $row['product_code'], $row['product_category_name']);

        if ($this->billOfMaterial->product_id == $product->id) {
            return null;
        }

        return new BillOfMaterialDetail([
            'bill_of_material_id' => $this->billOfMaterial->id,
            'product_id' => $product->id,
            'quantity' => $row['quantity'],
        ]);
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', 'distinct', Rule::in($this->products->pluck('name'))],
            'product_category_name' => ['nullable', 'string', 'max:255', Rule::in($this->productCategories->pluck('name'))],
            'product_code' => ['nullable', 'string', 'max:255', Rule::in($this->products->pluck('code'))],
            'quantity' => ['required', 'numeric', 'gt:0'],
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
