<?php

namespace App\Imports;

use App\Models\Price;
use App\Models\Product;
use App\Rules\MustBelongToCompany;
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
        $product = $this->products->firstWhere('name', $row['product_name']);

        if ($this->prices->where('product_id', $product->id)->count()) {
            return null;
        }

        $price = new Price([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'product_id' => $product->id,
            'type' => $row['type'],
            'min_price' => $row['min_price'] ?? null,
            'max_price' => $row['max_price'] ?? null,
            'fixed_price' => $row['fixed_price'] ?? null,
        ]);

        $this->prices->push($price);

        return $price;
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', 'distinct', new MustBelongToCompany('products', 'name')],
            'type' => ['required', 'string', 'max:255', Rule::in(['fixed', 'range'])],
            'min_price' => ['nullable', 'numeric', 'required_if:type,range', 'prohibited_if:type,fixed', 'gt:0',
                'lt:*.max_price', 'max:99999999999999999999.99',
            ],
            'max_price' => ['nullable', 'numeric', 'required_if:type,range', 'prohibited_if:type,fixed', 'gt:0',
                'gt:*.min_price', 'max:99999999999999999999.99',
            ],
            'fixed_price' => [
                'nullable', 'numeric', 'required_if:type,fixed', 'prohibited_if:type,range', 'gt:0', 'max:99999999999999999999.99',
            ],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['product_name'] = str()->squish($data['product_name']);

        $data['type'] = str()->lower($data['type']);

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
