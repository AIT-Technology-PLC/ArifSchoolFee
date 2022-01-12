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
        return $user->employee->company_id == $company->id && $user->can('Update Company');
    }
}
