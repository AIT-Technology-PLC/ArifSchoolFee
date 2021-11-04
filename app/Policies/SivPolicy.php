<?php

namespace App\Policies;

use App\Models\Siv;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class SivPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read SIV');
    }

    public function view(User $user, Siv $siv)
    {
        return $this->isIssuedByMyCompany($user, $siv) && $user->can('Read SIV');
    }

    public function create(User $user)
    {
        return $user->can('Create SIV');
    }

    public function update(User $user, Siv $siv)
    {
        return $this->isIssuedByMyCompany($user, $siv) && $user->can('Update SIV');
    }

    public function delete(User $user, Siv $siv)
    {
        return $this->isIssuedByMyCompany($user, $siv) && $user->can('Delete SIV');
    }

    public function approve(User $user, Siv $siv)
    {
        return $this->isIssuedByMyCompany($user, $siv) && $user->can('Approve SIV');
    }
}
