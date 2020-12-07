<?php

namespace App\Policies;

use App\Models\Merchandise;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MerchandisePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Merchandise $merchandise)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Merchandise $merchandise)
    {
        //
    }

    public function delete(User $user, Merchandise $merchandise)
    {
        //
    }
}
