<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class SyncWarehousePermissionsAction
{
    public function execute($user, $permissions = [])
    {
        if (count($permissions) == 0) {
            return;
        }

        $user->warehouses()->detach();

        foreach ($permissions['read'] ?? [] as $warehouseId) {
            DB::table('user_warehouse')->updateOrInsert([
                'user_id' => $user->id,
                'warehouse_id' => $warehouseId,
                'type' => 'read',
            ]);
        }

        foreach ($permissions['add'] ?? [] as $warehouseId) {
            DB::table('user_warehouse')->updateOrInsert([
                'user_id' => $user->id,
                'warehouse_id' => $warehouseId,
                'type' => 'add',
            ]);
        }

        foreach ($permissions['subtract'] ?? [] as $warehouseId) {
            DB::table('user_warehouse')->updateOrInsert([
                'user_id' => $user->id,
                'warehouse_id' => $warehouseId,
                'type' => 'subtract',
            ]);
        }
    }
}
