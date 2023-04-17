<?php

namespace App\Imports;

use App\Models\BillOfMaterial;
use App\Models\BillOfMaterialDetail;
use App\Models\Product;
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

    public function __construct($billOfMaterial)
    {
        $this->products = Product::rawMaterial()->get();

        $this->billOfMaterial = $billOfMaterial;
    }

    public function model(array $row)
    {
        $product = $this->products->firstWhere('name', $row['product_name']);

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
            'quantity' => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['product_name'] = str()->squish($data['product_name'] ?? '');

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
