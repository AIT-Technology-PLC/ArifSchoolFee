<?php

namespace App\Imports;

use App\Models\Debt;
use App\Models\Supplier;
use App\Rules\MustBelongToCompany;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DebtImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $debts;

    public function __construct()
    {
        $this->debts = collect();
    }

    public function model(array $row)
    {
        $debt = new Debt([
            'company_id' => userCompany()->id,
            'warehouse_id' => authUser()->warehouse_id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'code' => $this->debts->isEmpty() ? nextReferenceNumber('debts') : ($this->debts->last()->code + 1),
            'supplier_id' => Supplier::where('company_name', $row['supplier_name'])->first()->id,
            'debt_amount' => $row['debt_amount'],
            'issued_on' => $row['issued_on'] ?? now(),
            'due_date' => $row['due_date'] ?? (isset($row['issued_on']) ? carbon($row['issued_on'])->addDays(10) : now()->addDays(10)),
            'description' => $row['description'] ?? null,
        ]);

        $this->debts->push($debt);

        return $debt;
    }

    public function rules(): array
    {
        return [
            'supplier_name' => ['required', 'string', 'max:255', new MustBelongToCompany('suppliers', 'company_name')],
            'debt_amount' => ['required', 'numeric', 'gt:0'],
            'issued_on' => ['nullable', 'required_unless:*.due_date,null', 'date', 'before_or_equal:now'],
            'due_date' => ['nullable', 'date', 'after:*.issued_on'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['supplier_name'] = str()->squish($data['supplier_name'] ?? '');

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
            $rows = collect($validator->getData())->unique('supplier_name');

            foreach ($rows as $key => $row) {
                $supplier = Supplier::where('company_name', $row['supplier_name'])->first();

                $debtAmount = collect($validator->getData())->where('supplier_name', $row['supplier_name'])->sum('debt_amount');

                if ($supplier && $supplier->hasReachedDebtLimit($debtAmount)) {
                    $validator->errors()->add($key, 'The supplier "' . $supplier->company_name . '" has exceeded the debt amount limit.');
                }
            }
        });
    }
}
