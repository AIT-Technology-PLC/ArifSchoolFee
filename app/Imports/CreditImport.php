<?php

namespace App\Imports;

use App\Models\Credit;
use App\Models\Customer;
use App\Rules\MustBelongToCompany;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CreditImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $credits;

    public function __construct()
    {
        $this->credits = collect();
    }

    public function model(array $row)
    {
        $credit = new Credit([
            'company_id' => userCompany()->id,
            'warehouse_id' => authUser()->warehouse_id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'code' => $this->credits->isEmpty() ? nextReferenceNumber('credits') : ($this->credits->last()->code + 1),
            'customer_id' => Customer::where('company_name', $row['customer_name'])->first()->id,
            'credit_amount' => $row['credit_amount'],
            'issued_on' => $row['issued_on'] ?? now(),
            'due_date' => $row['due_date'] ?? (isset($row['issued_on']) ? carbon($row['issued_on'])->addDays(10) : now()->addDays(10)),
            'description' => $row['description'] ?? null,
        ]);

        $this->credits->push($credit);

        return $credit;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255', new MustBelongToCompany('customers', 'company_name')],
            'credit_amount' => ['required', 'numeric', 'gt:0'],
            'issued_on' => ['nullable', 'required_unless:*.due_date,null', 'date', 'before_or_equal:now'],
            'due_date' => ['nullable', 'date', 'after:*.issued_on'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['customer_name'] = str()->squish($data['customer_name'] ?? '');

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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($validator->getData() as $key => $row) {
                $customer = Customer::where('company_name', $row['customer_name'])->first();

                if ($customer && $customer->hasReachedCreditLimit($row['credit_amount'])) {
                    $validator->errors()->add($key, 'The customer has exceeded the credit amount limit.');
                }
            }
        });
    }
}
