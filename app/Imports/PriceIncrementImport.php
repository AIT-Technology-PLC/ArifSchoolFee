<?php

namespace App\Imports;

use App\Models\PriceIncrementDetail;
use App\Models\Product;
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

    public function __construct($priceIncrement)
    {
        $this->priceIncrement = $priceIncrement;

        $this->products = Product::whereHas('price')->get(['id', 'name', 'code']);
    }

    public function model(array $row)
    {
        return new PriceIncrementDetail([
            'price_increment_id' => $this->priceIncrement->id,
            'product_id' => $this->products->firstWhere('name', $row['product_name'])->firstWhere('code', $row['product_code'])->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', Rule::in($this->products->pluck('name'))],
            'product_code' => ['nullable', 'string', 'max:255', Rule::notIn('no')],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['product_name'] = str()->squish($data['product_name'] ?? '');
        $data['product_code'] = $this->products->where('name', $data['product_name'])->where('code', $data['product_code'] ?? null)->count() ? str()->squish($data['product_code'] ?? '') : 'no';

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
