<?php

namespace App\Policies;

use App\Models\CostUpdate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CostUpdatePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Cost Update');
    }

    public function view(User $user, CostUpdate $costUpdate)
    {
        return $user->can('Read Cost Update');
    }

    public function create(User $user)
    {
        return $user->can('Create Cost Update');
    }

    public function update(User $user, CostUpdate $costUpdate)
    {
        return $user->can('Update Cost Update');
    }

    public function delete(User $user, CostUpdate $costUpdate)
    {
        return $user->can('Delete Cost Update');
    }

    public function approve(User $user, CostUpdate $costUpdate)
    {
        return $user->can('Approve Cost Update');
    }

    public function reject(User $user, CostUpdate $costUpdate)
    {
        return $user->can('Reject Cost Update');
    }

    public function import(User $user)
    {
        return $user->can('Import Cost Update');
    }
}
