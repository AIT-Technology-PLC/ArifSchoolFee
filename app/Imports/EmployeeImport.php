<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Warehouse;
use App\Rules\MustBelongToCompany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class EmployeeImport implements WithHeadingRow, OnEachRow, WithValidation, WithChunkReading
{
    use Importable;

    public function onRow(Row $row)
    {
        $user = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'warehouse_id' => Warehouse::firstWhere('name', $row['branch_name'])->id,
        ]);

        $user->employee()->create(
            [
                'enabled' => '1',
                'position' => $row['position'],
                'gender' => str()->lower($row['gender']),
                'phone' => $row['phone'],
                'address' => $row['address'],
            ]);

        $user->assignRole($row['role']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:30', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'postion' => ['required', 'string'],
            'gender' => ['required', 'string', 'max:5', Rule::in(['male', 'female'])],
            'phone' => ['required', 'number', 'max:15'],
            'address' => ['required', 'string', 'max:50'],
            'role' => ['required', 'string', Rule::notIn(['System Manager']), new MustBelongToCompany('roles', 'name')],
            'branch_name' => ['required', 'string', new MustBelongToCompany('warehouses', 'name')],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['name'] = str()->squish($data['name'] ?? '');
        $data['email'] = str()->squish($data['email'] ?? '');

        return $data;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
