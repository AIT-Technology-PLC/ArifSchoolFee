<?php

namespace App\Imports;

use App\Models\AdjustmentDetail;
use App\Models\Product;
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

    public function __construct($adjustment)
    {
        $this->adjustment = $adjustment;

        $this->products = Product::all(['id', 'name']);

        $this->warehouses = Warehouse::all(['id', 'name']);
    }

    public function model(array $row)
    {
        return new AdjustmentDetail([
            'adjustment_id' => $this->adjustment->id,
            'product_id' => $this->products->firstWhere('name', $row['product_name'])->id,
            'warehouse_id' => $this->warehouses->firstWhere('name', $row['warehouse_name'])->id,
            'is_subtract' => $row['operation'] == 'Subtract' ? '1' : '0',
            'quantity' => $row['quantity'],
            'reason' => $row['reason'],
        ]);
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', Rule::in($this->products->pluck('name'))],
            'warehouse_name' => ['required', 'string', Rule::in($this->warehouses->pluck('name'))],
            'operation' => ['required', 'string'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'reason' => ['required', 'string'],
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