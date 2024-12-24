<?php

namespace App\Policies;

use App\Models\StudentPromote;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPromotePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Student Promote');
    }

    public function view(User $user, StudentPromote $studentPromote)
    {
        return $user->can('Read Student Promote');
    }

    public function create(User $user)
    {
        return $user->can('Create Student Promote');
    }

    public function update(User $user, StudentPromote $studentPromote)
    {
        return $user->can('Update Student Promote');
    }

    public function delete(User $user, StudentPromote $studentPromote)
    {
        return $user->can('Delete Student Promote');
    }
}
