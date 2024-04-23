<?php

namespace App\Actions;

use App\Actions\SyncWarehousePermissionsAction;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    private $action;

    public function __construct(SyncWarehousePermissionsAction $action)
    {
        $this->action = $action;
    }

    private function UpdateUser($employee, $data)
    {
        $keys = ['name', 'email', 'warehouse_id'];

        if (isset($data['password']) && !is_null($data['password'])) {
            $data['password'] = Hash::make($data['password']);
            $keys[] = 'password';
        }

        $employee->user->update(Arr::only($data, $keys));

        return $employee->user;
    }

    public function execute($employee, $data)
    {
        DB::transaction(function () use ($employee, $data) {
            $user = $this->UpdateUser($employee, $data);

            $employee->update(Arr::only($data, ['position', 'enabled', 'gender', 'address', 'bank_name', 'bank_account', 'tin_number', 'job_type', 'phone', 'id_type', 'id_number', 'date_of_hiring', 'date_of_birth', 'emergency_name', 'emergency_phone', 'department_id', 'paid_time_off_amount', 'does_receive_sales_report_email']));

            $this->action->execute(
                $user,
                Arr::only($data, ['transactions', 'read', 'subtract', 'add', 'sales', 'adjustment', 'siv', 'hr', 'transfer_source'])
            );

            $user->employee->employeeCompensations()->forceDelete();

            if (isFeatureEnabled('Compensation Management') && isset($data['employeeCompensation'])) {
                $user->employee->employeeCompensations()->createMany($data['employeeCompensation']);
            }

            $user->syncRoles(Arr::has($data, 'role') ? $data['role'] : $user->roles[0]->name);
        });
    }
}
