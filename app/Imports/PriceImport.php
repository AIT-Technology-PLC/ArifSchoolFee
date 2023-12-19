<?php

namespace App\Imports;

use App\Models\Price;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PriceImport implements WithHeadingRow, ToModel, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $products;

    private $prices;

    private $productCategories;

    public function __construct()
    {
        $this->products = Product::all(['id', 'name', 'code', 'product_category_id']);

        $this->productCategories = ProductCategory::all(['id', 'name']);

        $this->prices = Price::all();
    }

    public function model(array $row)
    {
        $productCategory = $this->productCategories->where('name', $row['product_category_name'])->first();

        $product = $this->products
            ->where('name', $row['product_name'])
            ->when(!empty($row['product_code']), fn($q) => $q->where('code', $row['product_code']))
            ->when(!empty($productCategory), fn($q) => $q->where('product_category_id', $productCategory->id))
            ->first();

        if ($this->prices->where('product_id', $product->id)->where('fixed_price', $row['price'])->count()) {
            return null;
        }

        $price = new Price([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'product_id' => $product->id,
            'fixed_price' => $row['price'],
            'name' => $row['name'],
            'is_active' => 1,
        ]);

        $this->prices->push($price);

        return $price;
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', Rule::in($this->products->pluck('name'))],
            'product_category_name' => ['nullable', 'string', 'max:255', Rule::in($this->productCategories->pluck('name'))],
            'product_code' => ['nullable', 'string', 'max:255', Rule::in($this->products->pluck('code'))],
            'price' => ['required', 'numeric', 'gt:0', 'max:99999999999999999999.99'],
            'name' => ['nullable', 'string'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['product_name'] = str()->squish($data['product_name'] ?? '');
        $data['product_category_name'] = str()->squish($data['product_category_name'] ?? '');
        $data['product_code'] = str()->squish($data['product_code'] ?? '');
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            collect($validator->getData())
                ->filter(function ($row) {
                    $productCategory = $this->productCategories->where('name', $row['product_category_name'])->first();

                    return $this->products
                        ->where('name', $row['product_name'])
                        ->when(!empty($row['product_code']), fn($q) => $q->where('code', $row['product_code']))
                        ->when(!empty($productCategory), fn($q) => $q->where('product_category_id', $productCategory->id))
                        ->isEmpty();
                })
                ->keys()
                ->chunk(50)
                ->each
                ->each(fn($key) => $validator->errors()->add($key, 'Product name by product code or vice versa is not registered.'));
        });
    }
}
