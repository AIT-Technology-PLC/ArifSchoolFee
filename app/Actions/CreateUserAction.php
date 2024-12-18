<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    private $action;

    public function __construct(SyncWarehousePermissionsAction $action)
    {
        $this->action = $action;
    }

    private function createNewUser($data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'warehouse_id' => $data['warehouse_id'],
            'is_admin' => 0,
            'user_type' => 'school',
        ]);
    }

    public function execute($data)
    {
        return DB::transaction(function () use ($data) {
            $user = $this->createNewUser($data);

            $user->employee()->create(Arr::only($data, ['position','enabled', 'gender', 'address', 'phone']));

            $this->action->execute(
                $user,
                Arr::only($data, ['transactions'])
            );

            $user->assignRole($data['role']);

            return $user;
        });
    }
}
