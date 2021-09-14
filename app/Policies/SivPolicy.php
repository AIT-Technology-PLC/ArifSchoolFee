<?php

namespace App\Policies;

use App\Models\Siv;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SivPolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read SIV');
    }

    public function view(User $user, Siv $siv)
    {
        return $this->doesModelBelongToMyCompany($user, $siv) && $user->can('Read SIV');
    }

    public function create(User $user)
    {
        return $user->can('Create SIV');
    }

    public function update(User $user, Siv $siv)
    {
        return $this->doesModelBelongToMyCompany($user, $siv) && $user->can('Update SIV');
    }

    public function delete(User $user, Siv $siv)
    {
        return $this->doesModelBelongToMyCompany($user, $siv) && $user->can('Delete SIV');
    }

    public function approve(User $user, Siv $siv)
    {
        return $this->doesModelBelongToMyCompany($user, $siv) && $user->can('Approve SIV');
    }
}
