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

    private $activeWarehouses;

    public function __construct()
    {
        $this->warehouses = Warehouse::all();

        $this->activeWarehouses = Warehouse::active()->get();
    }

    public function model(array $row)
    {
        if ($this->warehouses->where('name', $row['warehouse_name'])->count()) {
            return null;
        }

        if (limitReached('warehouse', $this->activeWarehouses->count())) {
            session('limitReachedMessage', __('messages.limit_reached', ['limit' => 'branches']));

            return null;
        }

        $warehouse = new Warehouse([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'name' => $row['warehouse_name'],
            'location' => $row['warehouse_location'],
            'is_active' => '1',
            'is_sales_store' => str()->lower($row['warehouse_is_sales_store']) == 'yes' ? '1' : '0',
            'can_be_sold_from' => '1',
            'email' => $row['warehouse_email'] ?? '',
            'phone' => $row['warehouse_phone'] ?? '',
        ]);

        $this->activeWarehouses->push($warehouse);

        $this->warehouses->push($warehouse);

        return $warehouse;
    }

    public function rules(): array
    {
        return [
            'warehouse_name' => ['required', 'string', 'max:255'],
            'warehouse_location' => ['required', 'string', 'max:255'],
            'warehouse_is_sales_store' => ['required', 'string'],
            'warehouse_email' => ['nullable', 'string', 'email'],
            'warehouse_phone' => ['nullable', 'string'],
        ];
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
