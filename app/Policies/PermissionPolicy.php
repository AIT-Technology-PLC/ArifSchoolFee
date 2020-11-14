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
        $doesAdminAndEmployeeBelongToSameCompany = $user->employee->company_id == $permission->employee->company_id;

        $isUserAdmin = Str::contains($user->employee->permission->settings, 'crud');

        $isItMyPermission = $user->employee->id == $permission->employee->id;

        $canSeePermission = $isItMyPermission || ($isUserAdmin && $doesAdminAndEmployeeBelongToSameCompany);

        if ($canSeePermission) {
            return true;
        }

        return false;
    }

    public function update(User $user, Permission $permission)
    {
        $doesAdminAndEmployeeBelongToSameCompany = $user->employee->company_id == $permission->employee->company_id;

        $isUserAdmin = Str::contains($user->employee->permission->settings, 'crud');

        $isItMyPermission = $user->employee->id == $permission->employee->id;

        $canEditPermission = !$isItMyPermission && ($isUserAdmin && $doesAdminAndEmployeeBelongToSameCompany);

        if ($canEditPermission) {
            return true;
        }

        return false;
    }

    public function settingsMenu(User $user)
    {
        return Str::contains($user->employee->permission->settings, 'crud');
    }
}
