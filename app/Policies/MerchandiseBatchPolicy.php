<?php

namespace App\Policies;

use App\Models\MerchandiseBatch;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MerchandiseBatchPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Merchandise Batch');
    }

    public function view(User $user, MerchandiseBatch $merchandiseBatch)
    {
        return $user->can('Read Merchandise Batch');
    }

    public function update(User $user, MerchandiseBatch $merchandiseBatch)
    {
        return $user->can('Update Merchandise Batch');
    }

    public function damage(User $user, MerchandiseBatch $merchandiseBatch)
    {
        return $user->can('Damage Merchandise Batch');
    }
}
