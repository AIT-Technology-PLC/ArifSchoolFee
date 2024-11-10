<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserLogPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read User Login Log');
    }

    public function view(User $user, UserLog $userLog)
    {
        return $user->can('Read User Login Log');
    }
}
