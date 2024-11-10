<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function update(User $user, Company $school)
    {
        return $user->employee->company_id == $school->id && $user->can('Update Company');
    }
}
