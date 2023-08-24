<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Tax;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements WithHeadingRow, ToModel, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $products;

    private $productCategories;

    private $brands;

    private $productTax;

    public function __construct()
    {
        $this->products = Product::all();

        $this->productCategories = ProductCategory::all();

        $this->brands = Brand::all();

        $this->taxes = Tax::get(['id', 'type']);
    }

    public function model(array $row)
    {
        $productName = $row['product_name'];
        $code = $row['product_code'] ?? null;
        $productCategory = $this->productCategories->firstWhere('name', $row['product_category_name']);
        $productBrand = $this->brands->firstWhere('name', $row['product_brand']) ?? null;
        $productTax = $this->taxes->firstWhere('type', $row['tax_type']);

        if ($this->products->where('name', $productName)->where('code', $code)->where('product_category_id', $productCategory->id)->count()) {
            return null;
        }

        $mergedDescription = '';

        foreach ($row as $key => $value) {
            $description = '';

            if (str($key)->startsWith('description_')) {
                $description = str($description)->append($key, ': ', $value, '<br/>')->remove('description_')->toString();
            }

            if (str($key)->exactly('description')) {
                $description = str($description)->append($value, '<br/>')->remove('description_')->toString();
            }

            $mergedDescription .= $description;
        }

        $product = new Product([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'product_category_id' => $productCategory->id,
            'name' => $row['product_name'],
            'code' => $row['product_code'] ?? null,
            'type' => str()->title($row['product_type']),
            'unit_of_measurement' => str()->title($row['product_unit_of_measurement']),
            'min_on_hand' => $row['product_min_on_hand'] ?? 0.00,
            'description' => strlen($mergedDescription) ? $mergedDescription : null,
            'brand_id' => $productBrand->id ?? null,
            'tax_id' => $productTax->id,
            'is_batchable' => $row['is_batchable'],
            'batch_priority' => $row['batch_priority'] ?? null,
            'is_active' => $row['is_active'],
            'is_active_for_sale' => $row['used_for_sale'],
            'is_active_for_purchase' => $row['used_for_purchase'],
            'is_active_for_job' => $row['used_for_job'],
        ]);

        $this->products->push($product);

        return $product;
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255'],
            'product_type' => ['required', 'string', 'max:255', Rule::in(['Finished Goods', 'Raw Material', 'Services'])],
            'product_code' => ['nullable', 'string', 'max:255'],
            'product_unit_of_measurement' => ['required', 'string', 'max:255'],
            'product_min_on_hand' => ['nullable', 'numeric'],
            'product_category_name' => ['required', 'string', 'max:255', Rule::in($this->productCategories->pluck('name'))],
            'product_brand' => ['nullable', 'string', 'max:255', Rule::in($this->brands->pluck('name'))],
            'tax_type' => ['required', 'string', 'max:255', Rule::in($this->taxes->pluck('type'))],
            'is_batchable' => ['required', 'boolean'],
            'batch_priority' => ['nullable', 'string', Rule::in(['fifo', 'lifo']), 'required_if:is_batchable,1'],
            'is_active' => ['required', 'boolean'],
            'used_for_sale' => ['required', 'boolean'],
            'used_for_purchase' => ['required', 'boolean'],
            'used_for_job' => ['required', 'boolean'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['product_category_name'] = str()->squish($data['product_category_name'] ?? '');
        $data['product_name'] = str()->squish($data['product_name'] ?? '');
        $data['product_code'] = str()->squish($data['product_code'] ?? '');
        $data['product_type'] = str($data['product_type'] ?? '')->squish()->title()->toString();
        $data['product_brand'] = str()->squish($data['product_brand'] ?? '');
        $data['tax_type'] = str($data['tax_type'] ?? 'VAT')->upper()->squish()->toString();
        $data['is_batchable'] = str()->lower($data['is_batchable'] ?? '') == 'yes' ? 1 : 0;
        $data['batch_priority'] = $data['is_batchable'] == 1 ? str($data['batch_priority'] ?? '')->lower()->squish()->toString() : null;
        $data['is_active'] = str()->lower($data['is_active'] ?? '') == 'no' ? 0 : 1;
        $data['used_for_sale'] = str()->lower($data['used_for_sale'] ?? '') == 'no' ? 0 : 1;
        $data['used_for_purchase'] = str()->lower($data['used_for_purchase'] ?? '') == 'no' ? 0 : 1;
        $data['used_for_job'] = str()->lower($data['used_for_job'] ?? '') == 'no' ? 0 : 1;

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
