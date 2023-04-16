<?php

namespace App\Imports;

use App\Models\AdjustmentDetail;
use App\Models\Merchandise;
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

class AdjustmentImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $adjustment;

    private $warehouses;

    private $products;

    private $productCategories;

    public function __construct($adjustment)
    {
        $this->adjustment = $adjustment;

        $this->products = Product::inventoryType()->get(['id', 'name', 'code', 'product_category_id']);

        $this->productCategories = ProductCategory::all(['id', 'name']);

        $this->warehouses = Warehouse::all(['id', 'name']);

        $this->merchandises = Merchandise::all();
    }

    public function model(array $row)
    {
        $merchandise = $this->merchandises
            ->where('product_id', $this->products
                    ->where('name', $row['product_name'])
                    ->when(!is_null($row['product_code']) && $row['product_code'] != '', fn($q) => $q->where('code', $row['product_code']))
                    ->when(
                        !is_null($row['product_category_name']) && $row['product_category_name'] != '',
                        fn($q) => $q->where('product_category_id', $this->productCategories->firstWhere('name', $row['product_category_name'])->id)
                    )
                    ->first()
                    ->id
            )
            ->where('warehouse_id', $this->warehouses->firstWhere('name', $row['warehouse_name'])->id)
            ->first();

        $newQuantity = $row['quantity'] ?? 0;

        if (!$merchandise) {
            $isSubtract = '0';
            $quantity = abs($newQuantity);
        }

        if ($merchandise) {
            $isSubtract = $merchandise->available > $newQuantity ? '1' : '0';
            $quantity = abs($merchandise->available - $newQuantity);
        }

        return new AdjustmentDetail([
            'adjustment_id' => $this->adjustment->id,
            'product_id' => $this->products
                ->where('name', $row['product_name'])
                ->when(!is_null($row['product_code']) && $row['product_code'] != '', fn($q) => $q->where('code', $row['product_code']))
                ->when(
                    !is_null($row['product_category_name']) && $row['product_category_name'] != '',
                    fn($q) => $q->where('product_category_id', $this->productCategories->firstWhere('name', $row['product_category_name'])->id)
                )
                ->first()
                ->id,
            'warehouse_id' => $this->warehouses->firstWhere('name', $row['warehouse_name'])->id,
            'is_subtract' => $isSubtract,
            'quantity' => $quantity,
            'reason' => $row['reason'],
        ]);
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', Rule::in($this->products->pluck('name'))],
            'product_category_name' => ['nullable', 'string', 'max:255', Rule::in($this->productCategories->pluck('name'))],
            'product_code' => ['nullable', 'string', 'max:255', Rule::in($this->products->pluck('code'))],
            'warehouse_name' => ['required', 'string', Rule::in($this->warehouses->pluck('name'))],
            'quantity' => ['nullable', 'numeric', 'gte:0'],
            'reason' => ['required', 'string'],
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
