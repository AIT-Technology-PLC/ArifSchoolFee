<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Gdn;
use App\Models\Product;
use App\Models\Warehouse;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidatePrice;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class GdnImport implements WithHeadingRow, OnEachRow, WithValidation, WithChunkReading
{
    use Importable;

    private $gdn;

    private $warehouses;

    private $customers;

    private $products;

    public function __construct($gdn)
    {
        $this->gdn = $gdn;

        $this->products = Product::all(['id', 'name']);

        $this->warehouses = Warehouse::all(['id', 'name']);

        $this->customers = Customer::all(['id', 'company_name']);
    }

    public function onRow(Row $row)
    {
        $gdn = Gdn::create([
            'code' => nextReferenceNumber('gdns'),
            'customer_id' => $this->products->firstWhere('company_name', $row['customer_name'])->id,
            'issued_on' => $row['issued_on'],
            'description' => $row['description'],
            'payment_type' => $row['payment_type'],
            'cash_received_type' => $row['cash_received_type'],
            'cash_received' => $row['cash_received'],
            'due_date' => $row['due_date'],
            'discount' => $row['discount'],
        ]);

        $gdn->gdnDetail()->create([
            'product_id' => $this->products->firstWhere('name', $row['product_name'])->id,
            'warehouse_id' => $this->warehouses->firstWhere('name', $row['warehouse_name'])->id,
            'quantity' => $row['quantity'],
            'unit_price' => $row['unit_price'],
            'description' => $row['description'],
            'discount' => $row['discount'],

        ]);
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('gdns')],
            'product_name' => ['required', 'string', 'max:255', Rule::in($this->products->pluck('name'))],
            'warehouse_name' => ['required', 'string', Rule::in($this->warehouses->pluck('name'))],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'unit_price' => ['nullable', 'numeric', new ValidatePrice],
            'description' => ['nullable', 'string'],
            'discount' => ['nullable', 'numeric', 'gt:0'],
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