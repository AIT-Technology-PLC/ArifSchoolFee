<?php

namespace App\Policies;

use App\Models\StudentGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentGroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Student Group');
    }

    public function view(User $user, StudentGroup $studentGroup)
    {
        return $user->can('Read Student Group');
    }

    public function create(User $user)
    {
        return $user->can('Create Student Group');
    }

    public function update(User $user, StudentGroup $studentGroup)
    {
        return $user->can('Update Student Group');
    }

    public function delete(User $user, StudentGroup $studentGroup)
    {
        return $user->can('Delete Student Group');
    }

    public function import(User $user)
    {
        return $user->can('Import Student Group');
    }
}
