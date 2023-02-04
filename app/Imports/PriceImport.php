<?php

namespace App\Imports;

use App\Models\Price;
use App\Models\Product;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PriceImport implements WithHeadingRow, ToModel, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $products;

    private $prices;

    public function __construct()
    {
        $this->products = Product::all();

        $this->prices = Price::all();
    }

    public function model(array $row)
    {
        $product = $this->products
            ->where('name', $row['product_name'])
            ->when(!is_null($row['product_code']) && $row['product_code'] != '', fn($q) => $q->where('code', $row['product_code']))
            ->first();

        if ($this->prices->where('product_id', $product->id)->where('fixed_price', $row['price'])->count()) {
            return null;
        }

        $price = new Price([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'product_id' => $product->id,
            'fixed_price' => $row['price'],
            'name' => $row['name'],
            'is_active' => 1,
        ]);

        $this->prices->push($price);

        return $price;
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', Rule::in($this->products->pluck('name'))],
            'product_code' => ['nullable', 'string', 'max:255', Rule::in($this->products->pluck('code'))],
            'price' => ['required', 'numeric', 'gt:0', 'max:99999999999999999999.99'],
            'name' => ['nullable', 'string'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['product_name'] = str()->squish($data['product_name'] ?? '');
        $data['product_code'] = str()->squish($data['product_code'] ?? '');
        $data['name'] = str()->squish($data['name'] ?? '');

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
