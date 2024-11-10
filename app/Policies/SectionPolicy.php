<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Section;
use Illuminate\Auth\Access\HandlesAuthorization;

class SectionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Section');
    }

    public function view(User $user, Section $section)
    {
        return $user->can('Read Section');
    }

    public function create(User $user)
    {
        return $user->can('Create Section');
    }

    public function update(User $user, Section $section)
    {
        return $user->can('Update Section');
    }

    public function delete(User $user, Section $section)
    {
        return $user->can('Delete Section');
    }

    public function import(User $user)
    {
        return $user->can('Import Section');
    }
}
