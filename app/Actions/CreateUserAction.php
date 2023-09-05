<?php

namespace App\Actions;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    private $action;

    public function __construct(SyncWarehousePermissionsAction $action)
    {
        $this->action = $action;
    }

    private function createNewUser($data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'warehouse_id' => $data['warehouse_id'],
        ]);
    }

    public function execute($data)
    {
        return DB::transaction(function () use ($data) {
            $user = $this->createNewUser($data);

            $user->employee()->create(Arr::only($data, ['position', 'enabled', 'gender', 'address', 'bank_name', 'bank_account', 'tin_number', 'job_type', 'phone', 'id_type', 'id_number', 'date_of_hiring', 'date_of_birth', 'emergency_name', 'emergency_phone', 'department_id', 'paid_time_off_amount']));

            $this->action->execute(
                $user,
                Arr::only($data, ['transactions', 'read', 'subtract', 'add', 'sales', 'adjustment', 'siv', 'hr', 'transfer_source'])
            );

            if (auth()->check() && isFeatureEnabled('Compensation Management') && isset($data['employeeCompensation'])) {
                $user->employee->employeeCompensations()->createMany($data['employeeCompensation']);
            }

            $user->assignRole($data['role']);

            return $user;
        });
    }
}
