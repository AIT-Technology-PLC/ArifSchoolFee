<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Employee;
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
                'gender' => $row['gender'],
                'address' => $row['address'],
                'bank_name' => $row['bank_name'] ?? '',
                'bank_account' => $row['bank_account'] ?? '',
                'tin_number' => $row['tin_number'] ?? '',
                'job_type' => $row['job_type'],
                'phone' => $row['phone'],
                'id_type' => $row['id_type'] ?? '',
                'id_number' => $row['id_number'] ?? '',
                // 'date_of_hiring' => $row['date_of_hiring'] ?? '',
                'gross_salary' => $row['gross_salary'] ?? null,
                // 'date_of_birth' => $row['date_of_birth'] ?? '',
                'emergency_name' => $row['emergency_name'] ?? '',
                'emergency_phone' => $row['emergency_phone'] ?? '',
                'department_id' => Department::firstWhere('name', $row['department_name'] ?? '')->id ?? '',
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
            'gender' => ['required', 'string', 'max:255', Rule::in(['male', 'female'])],
            'address' => ['required', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255', 'required_unless:*.bank_account,null'],
            'bank_account' => ['nullable', 'string', 'max:255', 'required_unless:*.bank_name,null'],
            'tin_number' => ['nullable', 'string', 'max:255'],
            'job_type' => ['required', 'string', 'max:255', Rule::in(['full time', 'part time', 'contractual', 'remote', 'internship'])],
            'phone' => ['required', 'string', 'max:255'],
            'id_type' => ['nullable', 'string', 'max:255', Rule::in(['passport', 'drivers license', 'employee id', 'kebele id', 'student id'])],
            'id_number' => ['nullable', 'string', 'max:255'],
            'date_of_hiring' => ['nullable', 'date'],
            'gross_salary' => ['nullable', 'numeric'],
            'date_of_birth' => ['nullable', 'date', 'before:' . now()],
            'emergency_name' => ['nullable', 'string', 'max:255', 'required_unless:*.emergency_phone,null'],
            'emergency_phone' => ['nullable', 'string', 'max:255', 'required_unless:*.emergency_name,null'],
            'department_name' => ['nullable', 'string', Rule::when(!isFeatureEnabled('Department Management'), 'prohibited'), new MustBelongToCompany('departments', 'name')],
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
