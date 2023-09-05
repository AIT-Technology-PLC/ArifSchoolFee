<?php

namespace App\Imports;

use App\Models\GrnDetail;
use App\Models\Product;
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

    public function __construct($grn)
    {
        $this->grn = $grn;

        $this->products = Product::inventoryType()->get(['id', 'name', 'code']);

        $this->warehouses = Warehouse::all(['id', 'name']);
    }

    public function model(array $row)
    {
        return new GrnDetail([
            'grn_id' => $this->grn->id,
            'product_id' => $this->products
                ->where('name', $row['product_name'])
                ->when(!is_null($row['product_code']) && $row['product_code'] != '', fn($q) => $q->where('code', $row['product_code']))
                ->first()
                ->id,
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
}
