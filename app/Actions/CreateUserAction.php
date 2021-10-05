<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    private $action;

    public function __construct(SyncWarehousePermissionsAction $action)
    {
        $this->action = $action;
    }

    private function createNewUser($request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'warehouse_id' => $request->warehouse_id,
        ]);
    }

    public function execute($request)
    {
        return DB::transaction(function () use ($request) {
            $user = $this->createNewUser($request);

            $user->employee()->create($request->only(['position', 'enabled']));

            $this->action->execute(
                $user,
                $request->only('read', 'subtract', 'add', 'sales', 'adjustment', 'siv')
            );

            $user->assignRole($request->role);

            return $user;
        });
    }
}
