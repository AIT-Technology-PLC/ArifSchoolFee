<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    private function createNewUser($request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'warehouse_id' => $request->warehouse_id,
        ]);
    }

    private function giveWarehousePermissions($request, $user)
    {
        $user->warehouses()->detach();

        foreach ($request->read ?? [] as $warehouseId) {
            DB::table('user_warehouse')->insert([
                'user_id' => $user->id,
                'warehouse_id' => $warehouseId,
                'type' => 'read',
            ]);
        }

        foreach ($request->add ?? [] as $warehouseId) {
            DB::table('user_warehouse')->insert([
                'user_id' => $user->id,
                'warehouse_id' => $warehouseId,
                'type' => 'add',
            ]);
        }

        foreach ($request->subtract ?? [] as $warehouseId) {
            DB::table('user_warehouse')->insert([
                'user_id' => $user->id,
                'warehouse_id' => $warehouseId,
                'type' => 'subtract',
            ]);
        }
    }
    public function execute($request)
    {
        DB::transaction(function () use ($request) {
            $user = $this->createNewUser($request);

            $user->employee()->create($request->only(['position', 'enabled']));

            $this->giveWarehousePermissions($request, $user);

            $user->assignRole($request->role);
        });
    }
}
