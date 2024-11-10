<?php

namespace App\Policies;

use App\Models\StudentDirectory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentDirectoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Student Directory');
    }

    public function view(User $user, StudentDirectory $studentDirectory)
    {
        return $user->can('Read StudentDirectory');
    }

    public function create(User $user)
    {
        return $user->can('Create StudentDirectory');
    }

    public function update(User $user, StudentDirectory $studentDirectory)
    {
        return $user->can('Update StudentDirectory');
    }

    public function delete(User $user, StudentDirectory $studentDirectory)
    {
        return $user->can('Delete StudentDirectory');
    }

    public function import(User $user)
    {
        return $user->can('Import StudentDirectory');
    }
}
