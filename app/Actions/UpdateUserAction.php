<?php

namespace App\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdateUserAction
{
    private $action;

    public function __construct(SyncWarehousePermissionsAction $action)
    {
        $this->action = $action;
    }

    private function UpdateUser($employee, $data)
    {
        $employee->user->update(Arr::only($data, ['name', 'email', 'warehouse_id']));

        return $employee->user;
    }

    public function execute($employee, $data)
    {
        DB::transaction(function () use ($employee, $data) {
            $user = $this->UpdateUser($employee, $data);

            $employee->update(Arr::only($data, ['position', 'enabled', 'gender', 'address', 'bank_name', 'bank_account', 'tin_number', 'job_type', 'phone', 'id_type', 'id_number', 'date_of_hiring', 'date_of_birth', 'emergency_name', 'emergency_phone', 'department_id']));

            $this->action->execute(
                $user,
                Arr::only($data, ['transactions', 'read', 'subtract', 'add', 'sales', 'adjustment', 'siv', 'hr'])
            );

            if (isFeatureEnabled('Compensation Management')) {
                $user->employee->employeeCompensations()->forceDelete();
                $user->employee->employeeCompensations()->createMany($data['employeeCompensation']);
            }

            $user->syncRoles(Arr::has($data, 'role') ? $data['role'] : $user->roles[0]->name);
        });
    }
}