<?php

namespace App\Policies;

use App\Models\Permission;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Permission $permission)
    {
        return $user->employee->id == $permission->employee->id;
    }

    public function update(User $user, Permission $permission)
    {
        $areTheyFromTheSameCompany = $user->employee->company_id == $permission->employee->company_id;

        $isUserEqualsToEmployee = $user->employee->id == $permission->employee->id;

        if (!$isUserEqualsToEmployee && $areTheyFromTheSameCompany) {
            return Str::contains($user->employee->permission->settings, 'crud');
        }

        return false;
    }

    public function settingsMenu(User $user)
    {
        return Str::contains($user->employee->permission->settings, 'crud');
    }
}
