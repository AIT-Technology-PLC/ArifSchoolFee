<?php

namespace App\Policies;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchoolClassPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Class');
    }

    public function view(User $user, SchoolClass $schoolClass)
    {
        return $user->can('Read Class');
    }

    public function create(User $user)
    {
        return $user->can('Create Class');
    }

    public function update(User $user, SchoolClass $schoolClass)
    {
        return $user->can('Update Class');
    }

    public function delete(User $user, SchoolClass $schoolClass)
    {
        return $user->can('Delete Class');
    }

    public function import(User $user)
    {
        return $user->can('Import Class');
    }
}
