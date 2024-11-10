<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Department');
    }

    public function view(User $user, Department $department)
    {
        return $user->can('Read Department');
    }

    public function create(User $user)
    {
        return $user->can('Create Department');
    }

    public function update(User $user, Department $department)
    {
        return $user->can('Update Department');
    }

    public function delete(User $user, Department $department)
    {
        return $user->can('Delete Department');
    }
}