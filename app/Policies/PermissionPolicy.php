<?php

namespace App\Policies;

use App\Models\Permission;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Permission $permission)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Permission $permission)
    {
        $areTheyFromTheSameCompany = $user->employee->company_id == $permission->employee->company_id;
        
        if ($areTheyFromTheSameCompany) {
            return Str::contains($user->employee->permission->settings, 'crud');
        }

        return false;
    }

    public function delete(User $user, Permission $permission)
    {
        //
    }

    public function restore(User $user, Permission $permission)
    {
        //
    }

    public function forceDelete(User $user, Permission $permission)
    {
        //
    }
}
