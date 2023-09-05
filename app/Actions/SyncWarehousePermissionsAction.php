<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class SyncWarehousePermissionsAction
{
    private $warehousePermissions = [
        'transactions', 'read', 'subtract', 'add', 'sales', 'adjustment', 'siv', 'hr', 'transfer_source',
    ];

    public function execute($user, $permissions = [])
    {
        $user->warehouses()->detach();

        if (count($permissions) == 0) {
            return;
        }

        foreach ($this->warehousePermissions as $permission) {
            if (! isset($permissions[$permission])) {
                continue;
            }

            foreach ($permissions[$permission] as $warehouseId) {
                DB::table('user_warehouse')
                    ->insert([
                        'user_id' => $user->id,
                        'warehouse_id' => $warehouseId,
                        'type' => $permission,
                    ]);
            }
        }
    }
}
