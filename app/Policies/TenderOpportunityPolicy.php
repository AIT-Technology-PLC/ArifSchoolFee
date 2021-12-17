<?php

namespace App\Policies;

use App\Models\TenderOpportunity;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenderOpportunityPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Tender');
    }

    public function view(User $user, TenderOpportunity $tenderOpportunity)
    {
        return $this->isIssuedByMyCompany($user, $tenderOpportunity) && $user->can('Read Tender');
    }

    public function create(User $user)
    {
        return $user->can('Create Tender');
    }

    public function update(User $user, TenderOpportunity $tenderOpportunity)
    {
        return $this->isIssuedByMyCompany($user, $tenderOpportunity, true) && $user->can('Update Tender');
    }

    public function delete(User $user, TenderOpportunity $tenderOpportunity)
    {
        return $this->isIssuedByMyCompany($user, $tenderOpportunity, true) && $user->can('Delete Tender');
    }
}
