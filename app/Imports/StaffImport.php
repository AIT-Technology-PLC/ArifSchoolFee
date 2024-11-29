<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Staff;
use App\Models\StaffCompensation;
use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Illuminate\Validation\Rule;
use App\Rules\MustBelongToCompany;

class StaffImport implements WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts, OnEachRow
{
    use Importable;

    private $staffs;

    public function __construct()
    {
        $this->staffs = Staff::all();
    }

    public function onRow(Row $row)
    {
        if ($this->staffs->where('first_name', $row['first_name'])->where('phone', $row['phone'])->where('email', $row['email'])->count()) {
            return null;
        }

        $staff = Staff::create([
            'company_id' => userCompany()->id,
            'code' => $this->staffs->isEmpty() ? nextReferenceNumber('staff') : ($this->staffs->last()->code + 1),
            'warehouse_id' => Warehouse::firstWhere('name', $row['branch_name'])->id,
            'department_id' => Department::firstWhere('name', $row['department_name'])->id,
            'designation_id' => Designation::firstWhere('name', $row['designation_name'])->id,
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'father_name' => $row['father_name'] ?? null,
            'mother_name' => $row['mother_name'] ?? null,
            'email' => $row['email'],
            'phone' => $row['phone'],
            'gender' => $row['gender'],
            'marital_status' => $row['marital_status'] ?? null,
            'date_of_birth' => $row['date_of_birth'] ?? null,
            'current_address' => $row['current_address'],
            'permanent_address' => $row['permanent_address'] ?? null,
        ]);

        StaffCompensation::create([
            'company_id' => userCompany()->id,
            'staff_id' => $staff->id,
            'date_of_joining' => $row['date_of_joining'],
            'qualifications' => $row['qualifications'] ?? null,
            'experience' => $row['experience'] ?? null,
            'efp_number' => $row['efp_number'] ?? null,
            'basic_salary' => $row['basic_salary'],
            'job_type' => $row['job_type'],
            'location' => $row['location'] ?? null,
            'bank_name' => $row['bank_name'] ?? null,
            'bank_account' => $row['bank_account'] ?? null,
            'branch_name' => $row['branch_name'] ?? null,
            'tin_number' => $row['tin_number'] ?? null,
        ]);

        return $staff;
    }

    public function rules(): array
    {
        return [
            'branch_name' => ['required', 'integer', new MustBelongToCompany('warehouses','name')],
            'department_name' => ['required', 'integer', new MustBelongToCompany('departments','name')],
            'designation_name' => ['required', 'integer', new MustBelongToCompany('designations','name')],
            'first_name' => ['required', 'string', 'max:15'],
            'last_name' => ['required', 'string', 'max:15'],
            'father_name' => ['nullable', 'string', 'max:15'],
            'mother_name' => ['nullable', 'string', 'max:15'],
            'email' => ['required', 'string', 'email', 'max:30', 'unique:staff'],
            'phone' => ['required', 'string', 'max:15', 'unique:staff'],
            'gender' => ['required', 'string', 'max:5', Rule::in(['male', 'female'])],
            'marital_status' => ['nullable', 'string', 'max:15', Rule::in(['married', 'single','divorced','widowed'])],
            'date_of_birth' => ['nullable', 'date', 'before:' . now()],
            'current_address' => ['required', 'string', 'max:50'],
            'permanent_address' => ['nullable', 'string', 'max:50'],
            'date_of_joining' => ['required', 'date', 'before:' . now()],
            'qualifications' => ['nullable', 'string', 'max:100'],
            'experience' => ['nullable', 'string', 'max:100'],
            'efp_number' => ['nullable', 'string', 'max:100'],
            'basic_salary' => ['required', 'numeric', 'gte:0'],
            'job_type' => ['required', 'string', 'max:20', Rule::in(['permanent', 'contract'])],
            'location' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:25', 'required_unless:bank_account,null'],
            'bank_account' => ['nullable', 'string', 'max:30', 'required_unless:bank_name,null'],
            'branch_name' => ['nullable', 'string', 'max:30'],
            'tin_number' => ['nullable', 'string', 'max:30'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['branch_name'] = str()->squish($data['branch_name'] ?? '');
        $data['department_name'] = str()->squish($data['department_name'] ?? '');
        $data['designation_name'] = str()->squish($data['designation_name'] ?? '');
        $data['first_name'] = str()->squish($data['first_name'] ?? '');
        $data['last_name'] = str()->squish($data['last_name'] ?? '');
        $data['father_name'] = str()->squish($data['father_name'] ?? '');
        $data['mother_name'] = str()->squish($data['mother_name'] ?? '');

        return $data;
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
