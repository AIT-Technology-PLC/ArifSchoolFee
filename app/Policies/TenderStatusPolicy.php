<?php

namespace App\Policies;

use App\Models\TenderStatus;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenderStatusPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Tender');
    }

    public function view(User $user, TenderStatus $tenderStatus)
    {
        return $user->can('Read Tender');
    }

    public function create(User $user)
    {
        return $user->can('Create Tender');
    }

    public function update(User $user, TenderStatus $tenderStatus)
    {
        return $user->can('Update Tender');
    }

    public function delete(User $user, TenderStatus $tenderStatus)
    {
        return $user->can('Delete Tender');
    }
}
