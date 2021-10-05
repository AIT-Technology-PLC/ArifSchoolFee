<?php

namespace App\Actions;

use App\Actions\SyncWarehousePermissionsAction;
use Illuminate\Support\Facades\DB;

class UpdateUserAction
{
    private $action;

    public function __construct(SyncWarehousePermissionsAction $action)
    {
        $this->action = $action;
    }

    private function UpdateUser($employee, $request)
    {
        $employee->user->update($request->only(['name', 'email', 'warehouse_id']));

        return $employee->user;
    }

    public function execute($employee, $request)
    {
        DB::transaction(function () use ($employee, $request) {
            $user = $this->UpdateUser($employee, $request);

            $employee->update($request->only(['position', 'enabled']));

            $this->action->execute(
                $user,
                $request->only('read', 'subtract', 'add', 'sales', 'transfer_from', 'transfer_to', 'adjustment', 'siv')
            );

            $user->syncRoles($request->has('role') ? $request->role : $user->roles[0]->name);
        });
    }
}
