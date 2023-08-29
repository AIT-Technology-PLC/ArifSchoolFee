<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $customers;

    public function __construct()
    {
        $this->customers = Customer::all();
    }

    public function model(array $row)
    {
        if ($this->customers->where('company_name', $row['company_name'])->count()) {
            return null;
        }

        $customer = new Customer([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'company_name' => $row['company_name'],
            'tin' => $row['tin'] ?? null,
            'address' => $row['address'] ?? '',
            'contact_name' => $row['contact_name'] ?? '',
            'email' => $row['email'] ?? '',
            'phone' => $row['phone'] ?? '',
            'country' => $row['country'] ?? '',
            'credit_amount_limit' => $row['credit_amount_limit'] ?? 0.00,
        ]);

        $this->customers->push($customer);

        return $customer;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255', Rule::unique('customers')->where('company_id', userCompany()->id)->withoutTrashed()],
            'tin' => ['nullable', 'numeric', 'distinct', Rule::unique('customers')->where('company_id', userCompany()->id)->withoutTrashed()],
            'address' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'credit_amount_limit' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['company_name'] = str()->squish($data['company_name'] ?? '');

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
