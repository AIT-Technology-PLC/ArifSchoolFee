<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SupplierImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $suppliers;

    public function __construct()
    {
        $this->suppliers = Supplier::all();
    }

    public function model(array $row)
    {
        if ($this->suppliers->where('company_name', $row['company_name'])->count()) {
            return null;
        }

        return new Supplier([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'company_name' => $row['company_name'],
            'tin' => $row['tin'] ?? '',
            'address' => $row['address'] ?? '',
            'contact_name' => $row['contact_name'] ?? '',
            'email' => $row['email'] ?? '',
            'phone' => $row['phone'] ?? '',
            'country' => $row['country'] ?? '',
        ]);
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'tin' => ['nullable', 'numeric'],
            'address' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
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