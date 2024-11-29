<?php

namespace App\Actions;

use App\Models\Staff;
use App\Models\StaffCompensation;

class CreateStaffDetail
{
   public function execute($data)
    {
        if (Staff::where('first_name', $data['first_name'])->where('phone', $data['phone'])->where('email', $data['email'])->count()) {
            return null;
        }

        $staff = Staff::create([
            'company_id' => userCompany()->id,
            'code' => $data['code'],
            'warehouse_id' => $data['warehouse_id'],
            'department_id' => $data['department_id'],
            'designation_id' => $data['designation_id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'father_name' => $data['father_name'] ?? null,
            'mother_name' => $data['mother_name'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'],
            'gender' => $data['gender'],
            'marital_status' => $data['marital_status'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'current_address' => $data['current_address'],
            'permanent_address' => $data['permanent_address'] ?? null,
        ]);

        StaffCompensation::create([
            'company_id' => userCompany()->id,
            'staff_id' => $staff->id,
            'date_of_joining' => $data['date_of_joining'],
            'qualifications' => $data['qualifications'] ?? null,
            'experience' => $data['experience'] ?? null,
            'efp_number' => $data['efp_number'] ?? null,
            'basic_salary' => $data['basic_salary'],
            'job_type' => $data['job_type'],
            'location' => $data['location'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'bank_account' => $data['bank_account'] ?? null,
            'branch_name' => $data['branch_name'] ?? null,
            'tin_number' => $data['tin_number'] ?? null,
        ]);

        return $staff;
    }
}
