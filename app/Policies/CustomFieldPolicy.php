<?php

namespace App\Policies;

use App\Models\CustomField;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomFieldPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Custom Field');
    }

    public function view(User $user, CustomField $customField)
    {
        return $user->can('Read Custom Field');
    }

    public function create(User $user)
    {
        return $user->can('Create Custom Field');
    }

    public function update(User $user, CustomField $customField)
    {
        return $user->can('Update Custom Field');
    }

    public function delete(User $user, CustomField $customField)
    {
        return $user->can('Delete Custom Field');
    }
}
