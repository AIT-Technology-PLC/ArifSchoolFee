<?php

namespace App\Policies;

use App\Models\Employee;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class EmployeePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models employees.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return Str::contains(auth()->user()->employee->permission->settings, 'r');
    }

    /**
     * Determine whether the user can view the models employee.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function view(User $user, Employee $employee)
    {
        return Str::contains(auth()->user()->employee->permission->settings, 'r');
    }

    /**
     * Determine whether the user can create models employees.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Str::contains(auth()->user()->employee->permission->settings, 'c');
    }

    /**
     * Determine whether the user can update the models employee.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function update(User $user, Employee $employee)
    {
        return Str::contains(auth()->user()->employee->permission->settings, 'u');
    }

    /**
     * Determine whether the user can delete the models employee.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function delete(User $user, Employee $employee)
    {
        return Str::contains(auth()->user()->employee->permission->settings, 'd');
    }

    /**
     * Determine whether the user can restore the models employee.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function restore(User $user, Employee $employee)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the models employee.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function forceDelete(User $user, Employee $employee)
    {
        //
    }
}
