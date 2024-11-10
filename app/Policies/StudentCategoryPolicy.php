<?php

namespace App\Policies;

use App\Models\StudentCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Student Category');
    }

    public function view(User $user, StudentCategory $studentCategory)
    {
        return $user->can('Read Student Category');
    }

    public function create(User $user)
    {
        return $user->can('Create Student Category');
    }

    public function update(User $user, StudentCategory $studentCategory)
    {
        return $user->can('Update Student Category');
    }

    public function delete(User $user, StudentCategory $studentCategory)
    {
        return $user->can('Delete Student Category');
    }

    public function import(User $user)
    {
        return $user->can('Import Student Category');
    }
}
