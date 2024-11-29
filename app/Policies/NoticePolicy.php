<?php

namespace App\Policies;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NoticePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Staff');
    }

    public function view(User $user, Notice $notice)
    {
        return $user->can('Read Notice');
    }

    public function create(User $user)
    {
        return $user->can('Create Notice');
    }

    public function update(User $user)
    {
        return $user->can('Update Notice');
    }

    public function delete(User $user, Notice $notice)
    {
        return $user->can('Delete Notice');
    }
}
