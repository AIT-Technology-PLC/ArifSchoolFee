<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Attendance');
    }

    public function view(User $user, Attendance $attendance)
    {
        return $user->can('Read Attendance');
    }

    public function create(User $user)
    {
        return $user->can('Create Attendance');
    }

    public function update(User $user, Attendance $attendance)
    {
        return $user->can('Update Attendance');
    }

    public function delete(User $user, Attendance $attendance)
    {
        return $user->can('Delete Attendance');
    }

    public function approve(User $user, Attendance $attendance)
    {
        return $user->can('Approve Attendance');
    }

    public function cancel(User $user, Attendance $attendance)
    {
        return $user->can('Cancel Attendance');
    }

    public function import(User $user)
    {
        return $user->can('Import Attendance');
    }
}