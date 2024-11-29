<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Message;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Message');
    }

    public function view(User $user, Message $message)
    {
        return $user->can('Read Message');
    }

    public function create(User $user)
    {
        return $user->can('Create Message');
    }
}
