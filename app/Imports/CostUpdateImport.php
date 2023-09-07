<?php

namespace App\Imports;

use App\Models\CostUpdateDetail;
use App\Models\InventoryValuationHistory;
use App\Models\Merchandise;
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
            'average_unit_cost' => ['required', 'numeric', 'gt:0'],
            'fifo_unit_cost' => ['nullable', 'numeric', 'gt:0'],
            'lifo_unit_cost' => ['nullable', 'numeric', 'gt:0'],
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
        $validator
            ->after(function ($validator) {
                collect($validator->getData())
                    ->filter(fn($row) => Product::where('name', $row['product_name'])->when(!empty($row['product_code']), fn($q) => $q->where('code', $row['product_code']))->doesntExist())
                    ->keys()
                    ->chunk(50)
                    ->each
                    ->each(fn($key) => $validator->errors()->add($key, 'Product name by product code or vice versa is not registered.'));
            })
            ->after(function ($validator) {
                collect($validator->getData())
                    ->chunk(50)
                    ->each
                    ->each(function ($detail, $key) use ($validator) {
                        $product = Product::where('name', $detail['product_name'])->when(!empty($detail['product_code']), fn($q) => $q->where('code', $detail['product_code']))->first();

                        if ($product) {
                            $quantity = Merchandise::where('product_id', $product->id)->sum('available');

                            if ($quantity == 0) {
                                $validator->errors()->add($key, 'Products that have no quantity can not have cost.');
                            }

                            if (InventoryValuationHistory::where('product_id', $product->id)->exists()) {
                                $validator->errors()->add($key, 'This product has cost histories which can not be overridden.');
                            }
                        }
                    });
            });
    }
}
