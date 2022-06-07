<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPlannerPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->can('Create Job Planner');
    }
}