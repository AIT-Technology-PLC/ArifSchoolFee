<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeavePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Leave');
    }

    public function view(User $user, Leave $leave)
    {
        return $user->can('Read Leave') || $leave->employee->is(authUser()->employee);
    }

    public function create(User $user)
    {
        return $user->can('Create Leave');
    }

    public function update(User $user, Leave $leave)
    {
        return $user->can('Update Leave');
    }

    public function delete(User $user, Leave $leave)
    {
        return $user->can('Delete Leave');
    }

    public function approve(User $user, Leave $leave)
    {
        return $user->can('Approve Leave');
    }

    public function cancel(User $user, Leave $leave)
    {
        return $user->can('Cancel Leave');
    }
}
