<?php

namespace App\Policies;

use App\Models\Returnn;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReturnPolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read Return');
    }

    public function view(User $user, Returnn $returnn)
    {
        return $this->doesModelBelongToMyCompany($user, $returnn) && $user->can('Read Return');
    }

    public function create(User $user)
    {
        return $user->can('Create Return');
    }

    public function update(User $user, Returnn $returnn)
    {
        return $this->doesModelBelongToMyCompany($user, $returnn) && $user->can('Update Return');
    }

    public function delete(User $user, Returnn $returnn)
    {
        return $this->doesModelBelongToMyCompany($user, $returnn) && $user->can('Delete Return');
    }

    public function approve(User $user, Returnn $returnn)
    {
        return $this->doesModelBelongToMyCompany($user, $returnn) && $user->can('Approve Return');
    }

    public function add(User $user, Returnn $returnn)
    {
        return $this->doesModelBelongToMyCompany($user, $returnn) && $user->can('Make Return');
    }
}
