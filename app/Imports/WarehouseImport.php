<?php

namespace App\Imports;

use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WarehouseImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $warehouses;

    private $totalActiveWarehouses;

    public function __construct()
    {
        $this->warehouses = Warehouse::all();

        $this->totalActiveWarehouses = Warehouse::active()->count();
    }

    public function model(array $row)
    {
        if ($this->warehouses->where('name', $row['name'])->count()) {
            return null;
        }

        if (limitReached('warehouse', $this->totalActiveWarehouses)) {
            session('limitReachedMessage', __('messages.limit_reached', ['limit' => 'branches']));
            return;
        }

        return new Warehouse([
            'company_id' => userCompany()->id,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'name' => $row['name'],
            'location' => $row['location'],
            'is_active' => '1',
            'is_sales_store' => $row['is_sales_store'] == 'Yes' ? '1' : '0',
            'can_be_sold_from' => '1',
            'email' => $row['email'] ?? '',
            'phone' => $row['phone'] ?? '',
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'is_sales_store' => ['required', 'string'],
            'email' => ['nullable', 'string', 'email'],
            'phone' => ['nullable', 'string'],
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