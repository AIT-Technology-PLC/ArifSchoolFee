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

            $employee->update(Arr::only($data, ['enabled', 'gender', 'address', 'phone']));

            $this->action->execute(
                $user,
                Arr::only($data, ['transactions'])
            );

            $user->syncRoles(Arr::has($data, 'role') ? $data['role'] : $user->roles[0]->name);
        });
    }
}
