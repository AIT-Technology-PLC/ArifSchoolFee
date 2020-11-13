<?php

namespace App\Policies;

use App\Models\Employee;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return Str::contains($user->employee->permission->settings, 'r');
    }

    public function view(User $user, Employee $employee)
    {
        return Str::contains($user->employee->permission->settings, 'r');
    }

    public function create(User $user)
    {
        return Str::contains($user->employee->permission->settings, 'c');
    }

    public function update(User $user, Employee $employee)
    {
        return Str::contains($user->employee->permission->settings, 'u');
    }

    public function delete(User $user, Employee $employee)
    {
        return Str::contains($user->employee->permission->settings, 'd');
    }

}
