<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
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

    public function __construct()
    {
        $this->products = Product::all();

        $this->productCategories = ProductCategory::all();
    }

    public function model(array $row)
    {
        $productName = $row['name'];
        $code = $row['code'] ?? '';
        $productCategory = $this->productCategories->firstWhere('name', $row['product_category_name']);

        if ($this->products->where('name', $productName)->where('code', $code)->where('product_category_id', $productCategory->id)->count()) {
            return null;
        }

        $product = new Product([
            'company_id' => userCompany()->id,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'product_category_id' => $productCategory->id,
            'name' => $row['name'],
            'code' => $row['code'] ?? '',
            'type' => $row['type'],
            'unit_of_measurement' => $row['unit_of_measurement'],
            'min_on_hand' => $row['min_on_hand'] ?? 0.00,
        ]);

        $this->products->push($product);

        return $product;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255', Rule::in(['Merchandise Inventory'])],
            'code' => ['nullable', 'string', 'max:255'],
            'unit_of_measurement' => ['required', 'string', 'max:255'],
            'min_on_hand' => ['nullable', 'numeric'],
            'product_category_name' => ['required', 'string', 'max:255', Rule::in($this->productCategories->pluck('name'))],
        ];
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
