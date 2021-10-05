<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class SyncWarehousePermissionsAction
{
    private $warehousePermissions = [
        'read', 'subtract', 'add', 'sales', 'adjustment', 'siv',
    ];

    public function execute($user, $permissions = [])
    {
        if (count($permissions) == 0) {
            return;
        }

        $user->warehouses()->detach();

        foreach ($this->warehousePermissions as $permission) {
            if (!isset($permissions[$permission])) {
                continue;
            }

            foreach ($permissions[$permission] as $warehouseId) {
                DB::table('user_warehouse')->updateOrInsert([
                    'user_id' => $user->id,
                    'warehouse_id' => $warehouseId,
                    'type' => $permission,
                ]);
            }
        }
    }
}
