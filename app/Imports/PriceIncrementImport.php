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

class PriceIncrementImport implements WithHeadingRow, ToModel, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $products;

    private $priceIncrement;

    public function __construct($priceIncrement)
    {
        $this->products = Product::all();

        $this->priceIncrement = $priceIncrement;
    }

    public function model(array $row)
    {
        $priceIncrementDetails = new PriceIncrementDetail([
            'price_increment_id' => $this->priceIncrement->id,
            'product_id' => $this->products->firstWhere('name', $row['product_name'])->id,
        ]);

        return $priceIncrementDetails;
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', 'distinct', Rule::in($this->products->pluck('name'))],
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
