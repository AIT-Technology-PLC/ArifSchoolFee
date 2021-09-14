<?php

namespace App\Policies;

use App\Models\TenderStatus;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenderStatusPolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read Tender');
    }

    public function view(User $user, TenderStatus $tenderStatus)
    {
        return $this->doesModelBelongToMyCompany($user, $tenderStatus) && $user->can('Read Tender');
    }

    public function create(User $user)
    {
        return $user->can('Create Tender');
    }

    public function update(User $user, TenderStatus $tenderStatus)
    {
        return $this->doesModelBelongToMyCompany($user, $tenderStatus) && $user->can('Update Tender');
    }

    public function delete(User $user, TenderStatus $tenderStatus)
    {
        return $this->doesModelBelongToMyCompany($user, $tenderStatus) && $user->can('Delete Tender');
    }
}
