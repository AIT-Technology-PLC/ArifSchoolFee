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
            'product_id' => $this->products
                ->where('name', $row['product_name'])
                ->when(!is_null($row['product_code']) && $row['product_code'] != '', fn($q) => $q->where('code', $row['product_code']))
                ->first()
                ->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', Rule::in($this->products->pluck('name'))],
            'product_code' => ['nullable', 'string', 'max:255', Rule::in($this->products->pluck('code'))],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['product_name'] = str()->squish($data['product_name'] ?? null);
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
