<?php

namespace App\Imports;

use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WarehouseImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        if (Warehouse::where('name', $row['name'])->exists()) {
            return null;
        }

        return new Warehouse([
            'name' => $row['name'],
            'location' => $row['location'],
            'is_active' => $row['is_active'],
            'is_sales_store' => $row['is_sales_store'],
            'can_be_sold_from' => $row['can_be_sold_from'],
            'email' => $row['email'] ?? '',
            'phone' => $row['phone'] ?? '',
            'description' => $row['description'] ?? '',
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'is_sales_store' => ['required', 'boolean'],
            'can_be_sold_from' => ['required', 'boolean'],
            'email' => ['nullable', 'string', 'email'],
            'phone' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }
}