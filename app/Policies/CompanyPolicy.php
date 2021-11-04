<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function update(User $user, Company $company)
    {
        return $this->isIssuedByMyCompany($user, $company) && $user->can('Update Company');
    }
}
