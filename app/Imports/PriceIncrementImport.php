<?php

namespace App\Imports;

use App\Models\PriceIncrementDetail;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PriceIncrementImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $products;

    private $priceIncrement;

    private $productCategories;

    public function __construct($priceIncrement)
    {
        $this->priceIncrement = $priceIncrement;

        $this->products = Product::inventoryType()->get(['id', 'name', 'code', 'product_category_id']);

        $this->productCategories = ProductCategory::all(['id', 'name']);
    }

    public function model(array $row)
    {
        $product = Product::ByNameCodeAndCategory($row['product_name'], $row['product_code'], $row['product_category_name']);

        return new PriceIncrementDetail([
            'price_increment_id' => $this->priceIncrement->id,
            'product_id' => $product->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', Rule::in($this->products->pluck('name'))],
            'product_category_name' => ['nullable', 'string', 'max:255', Rule::in($this->productCategories->pluck('name'))],
            'product_code' => ['nullable', 'string', 'max:255', Rule::in($this->products->pluck('code'))],
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
