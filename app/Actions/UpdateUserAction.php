<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class UpdateUserAction
{
    private function UpdateUser($employee, $request)
    {
        $employee->user->update($request->only(['name', 'email', 'warehouse_id']));

        return $employee->user;
    }

    private function giveWarehousePermissions($request, $user)
    {
        $user->warehouses()->detach();

        foreach ($request->read ?? [] as $warehouseId) {
            DB::table('user_warehouse')->updateOrInsert([
                'user_id' => $user->id,
                'warehouse_id' => $warehouseId,
                'type' => 'read',
            ]);
        }

        foreach ($request->add ?? [] as $warehouseId) {
            DB::table('user_warehouse')->updateOrInsert([
                'user_id' => $user->id,
                'warehouse_id' => $warehouseId,
                'type' => 'add',
            ]);
        }

        foreach ($request->subtract ?? [] as $warehouseId) {
            DB::table('user_warehouse')->updateOrInsert([
                'user_id' => $user->id,
                'warehouse_id' => $warehouseId,
                'type' => 'subtract',
            ]);
        }
    }

    public function execute($employee, $request)
    {
        DB::transaction(function () use ($employee, $request) {
            $user = $this->UpdateUser($employee, $request);

            $employee->update($request->only(['position', 'enabled']));

            $this->giveWarehousePermissions($request, $user);

            $user->syncRoles($request->has('role') ? $request->role : $user->roles[0]->name);
        });
    }
}
