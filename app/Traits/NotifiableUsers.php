<?php

namespace App\Traits;

use App\Models\Employee;
use App\User;

trait NotifiableUsers
{
    public function notifiableUsers($permission)
    {
        $usersFromEmployees = Employee::with('user')->companyEmployees()
            ->where('id', '<>', auth()->user()->employee->id)
            ->get()
            ->pluck('user');

        $usersId = $usersFromEmployees->pluck('id')->toArray();

        $users = User::permission($permission)->whereIn('id', $usersId)->get();

        return $users;
    }

    public function notifyCreator($resource, $users)
    {
        if (!$resource->createdBy) {
            return [];
        }

        if ($users->contains('id', $resource->createdBy->id)) {
            return [];
        }

        if ($resource->createdBy->id == auth()->id()) {
            return [];
        }

        return $resource->createdBy;
    }
}
