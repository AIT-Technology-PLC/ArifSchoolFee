<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Student');
    }

    public function view(User $user, Student $student)
    {
        return $user->can('Read Student');
    }

    public function create(User $user)
    {
        return $user->can('Create Student');
    }

    public function update(User $user, Student $student)
    {
        return $user->can('Update Student');
    }

    public function delete(User $user, Student $student)
    {
        return $user->can('Delete Student');
    }

    public function import(User $user)
    {
        return $user->can('Import Student');
    }
}
