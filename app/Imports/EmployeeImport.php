<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\User;
use App\Models\Warehouse;
use App\Rules\MustBelongToCompany;
use Google\Service\Spanner\Session;
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
        if (limitReached('user', Employee::enabled()->count())) {
            session('limitReachedMessage', __('messages.limit_reached', ['limit' => 'users']));
            return;
        }

        $user = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'warehouse_id' => Warehouse::firstWhere('name', $row['warehouse_name'])->id,
        ]);

        $user->employee()->create(
            [
                'position' => $row['job_title'],
                'enabled' => '1',
            ]);

        $user->assignRole($row['role']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'job_title' => ['required', 'string'],
            'role' => ['required', 'string', Rule::notIn(['System Manager']), new MustBelongToCompany('roles', 'name')],
            'warehouse_name' => ['required', 'string', new MustBelongToCompany('warehouses', 'name')],
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
